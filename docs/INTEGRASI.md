# Integrasi ke absensi-hutamakarya

Untuk urutan lengkap pemasangan, restore DBeaver, pengujian staging, cutover, dan rollback, ikuti **[Panduan Merge Step by Step untuk Vendor](../PANDUAN_MERGE_ABSENSI_HUTAMAKARYA.md)**. Dokumen ini merupakan ringkasan teknis pendamping.

Paket berada di `packages/sugihart-device-manager`. Aplikasi pembungkus di root memakai Laravel 13; paket dapat dipasang pada Laravel 12 atau 13 dengan PHP 8.5.12 atau patch 8.5 yang lebih baru. Periksa versi proyek dari penyedia jasa dengan `composer show laravel/framework` sebelum memasangnya. Integrasi pada source absensi yang baru belum bisa diuji karena source tersebut belum tersedia.

## Hasil yang dituju

URL ini tetap dilayani langsung, tanpa redirect:

```text
https://absensi.hutamakarya.com/sugihart_device_manager/public/api/get_render_kios/{token-perangkat-lama}
```

Empat endpoint lama, token vendor/perangkat, nama tabel, dan password hash lama dipertahankan. Nama route internal memakai `sdm.*`, namespace PHP memakai `Sugihart\DeviceManager`, dan view memakai `sdm::`. Login modul memakai guard `sdm` dan koneksi database `sdm`; default login dan tabel `users` milik aplikasi absensi tidak diganti. Logout modul mempertahankan sesi aplikasi induk.

## 1. Salin paket ke source induk

Struktur akhir:

```text
absensi-hutamakarya/
  app/
  bootstrap/
  composer.json
  packages/
    sugihart-device-manager/
      composer.json
      src/
      config/
      routes/
      resources/
      database/
      public/
  public/
    index.php                        # front controller aplikasi absensi
    vendor/
      sugihart-device-manager/        # hanya aset yang dipublikasikan
```

Salin **seluruh** folder `packages/sugihart-device-manager` dari repo ini ke lokasi di atas. Aplikasi Laravel pembungkus di root repo ini tidak perlu disalin. Pertahankan `composer.json`, `bootstrap/app.php`, `APP_KEY`, dan konfigurasi login milik aplikasi absensi.

Tambahkan satu entri ke array `repositories` di `composer.json` induk, dengan mempertahankan entri yang sudah ada:

```json
{
    "type": "path",
    "url": "packages/sugihart-device-manager",
    "options": { "symlink": false }
}
```

Lalu jalankan dari root aplikasi induk:

```sh
composer require sugihart/device-manager:^1.0 --with-all-dependencies
```

Provider akan terdaftar otomatis melalui Composer package discovery. Jika aplikasi induk menonaktifkan discovery untuk semua paket, tambahkan `Sugihart\DeviceManager\DeviceManagerServiceProvider::class` ke daftar provider aplikasi tersebut.

## 2. Hubungkan database PostgreSQL device manager

Tambahkan variabel berikut ke `.env` induk. Gunakan database PostgreSQL khusus device manager yang sudah berisi hasil pemindahan data lama, bukan database absensi:

```dotenv
SDM_ROUTE_PREFIX=sugihart_device_manager/public
SDM_ASSET_PREFIX=vendor/sugihart-device-manager
SDM_DB_CONNECTION=pgsql
SDM_DB_HOST=127.0.0.1
SDM_DB_PORT=5432
SDM_DB_DATABASE=sugihart_device_manager
SDM_DB_USERNAME=postgres
SDM_DB_PASSWORD="isi_password_postgresql"
SDM_DB_SEARCH_PATH=public
SDM_DB_SSLMODE=prefer
SDM_ATTENDANCE_URL=https://absensi.hutamakarya.com/
SDM_KIOSK_LOCAL_URL=http://localhost/hutamakarya-kios-live2/
SDM_KIOSK_ASSET_URL=http://localhost/hutamakarya-kios-live2/
SDM_ENFORCE_KIOSK_IP=false
SDM_REGISTRATION_ENABLED=false
```

Gunakan nama database sebenarnya bila berbeda. Jangan arahkan koneksi `sdm` ke database absensi: kedua aplikasi mempunyai tabel bernama `users`. Satu server PostgreSQL boleh dipakai bersama dengan database terpisah. Ikuti [panduan pemindahan MySQL ke PostgreSQL](POSTGRESQL.md) agar ID, token, dan hash password tetap sama. Dump SQL MySQL tidak dapat langsung di-restore sebagai dump PostgreSQL. Konfigurasi database aplikasi absensi tetap mengikuti kebutuhan aplikasi induk; paket hanya mengatur koneksi `sdm`.

`APP_URL` induk tetap `https://absensi.hutamakarya.com`, tanpa akhiran device manager. `SDM_*_URL` adalah konfigurasi server; client tidak perlu mengganti URL.

Setelah membuat database PostgreSQL dan backup database lama:

```sh
php artisan config:clear
php artisan sdm:install --force
php artisan vendor:publish --tag=sdm-assets --force
php artisan route:list --path=sugihart_device_manager
```

`sdm:install` menggunakan koneksi `sdm`, membuat tabel yang belum ada, dan melewati tabel lama tanpa mengubah barisnya. Tabel tambahan `password_reset_tokens` dipakai reset password. Perintah `migrate` biasa milik aplikasi induk tidak menjalankan migrasi modul secara otomatis. Jangan memakai `migrate:fresh`, `db:wipe`, atau menjalankan seeder lama untuk migrasi data.

Akun admin lama dapat dipakai setelah data tabel `users` dipindahkan tanpa mengubah hash password. Untuk instalasi kosong saja, buat akun melalui `php artisan sdm:admin email@perusahaan.com`; password diminta secara interaktif. Tidak ada password admin bawaan.

## 3. Alihkan request lama ke aplikasi induk

Document root web server harus mengarah ke `absensi-hutamakarya/public`, menggunakan front controller `public/index.php` induk. Prefix `/sugihart_device_manager/public` sekarang merupakan route Laravel.

Saat cutover, arsipkan aplikasi nested lama **di luar document root** sebelum mempublikasikan aset. Jangan menyisakan `public/sugihart_device_manager/public/index.php`, `.htaccess`, `vendor`, `.env`, atau kode aplikasi Laravel lama di jalur tersebut. File `index.php` atau aturan web server lama dapat mengambil alih request sebelum route baru dijalankan. Aset admin dipublikasikan ke `public/vendor/sugihart-device-manager`. Jangan membuat folder fisik `public/sugihart_device_manager/public` kembali: benturan folder dengan route utama admin dapat menyebabkan 403/404 sebelum Laravel menerima request.

Gunakan aturan front controller aplikasi induk yang sudah berjalan. Pada Nginx, pastikan request yang bukan file aset diteruskan ke `index.php`, misalnya aturan Laravel standar:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Jika ada `location`, `Alias`, atau rewrite khusus untuk aplikasi device manager lama, sesuaikan agar tidak mendahului route induk. Pemilihan PHP 8.5.12 dilakukan di PHP-FPM/panel hosting; `.htaccess` lama yang memaksa `ea-php72` sudah tidak dipakai.

Setelah memeriksa endpoint pada staging:

```sh
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika induk memakai worker persisten/Octane, restart worker mengikuti prosedur deployment induk.

## 4. Pemeriksaan sebelum mengganti produksi

1. Bandingkan respons tiga API JSON lama dan staging menggunakan token vendor yang sama; periksa jumlah perangkat, urutan, URL CCTV, serta timestamp.
2. Buka URL kios menggunakan token perangkat yang sama. Respons valid harus HTML dengan HTTP 200, tanpa redirect login.
3. Periksa login admin lama, daftar vendor/perangkat, dan aktivitas admin. Login serta logout modul harus tidak mengganggu login absensi.
4. Jalankan kios fisik untuk memeriksa kamera lokal, wajah karyawan, running text, jam kerja, leaderboard, dan materi iklan.
5. Periksa log aplikasi dan respons API setelah cache route/config diaktifkan.

## Layanan yang tetap dibutuhkan kios

Tampilan kios masih menggunakan kontrak layanan yang sudah ada:

| Penyedia | Endpoint/aset |
| --- | --- |
| Aplikasi absensi | `get_running_text/{token}`, `get_display_now/{token}/{time}`, `api/office_hour`, `api/get_face_album_employee/{employee_id}`, `api/get_five_ontime_employee`, `uploads/*` |
| Aplikasi lokal di komputer kios | `api/face_data/data.php`, `api/api_cross.php`, `public/css/*`, `public/js/*`, `public/image/*` |

Endpoint tersebut berasal dari proyek lain dan tidak diimplementasikan ulang di paket ini. Minta penyedia jasa mempertahankan endpoint absensi tersebut pada rewrite mereka. Pengujian backend di repo ini tidak membuktikan kamera, Odoo, CORS/akses localhost pada browser kios, atau endpoint absensi baru sudah berfungsi. Konfigurasi browser kios yang sebelumnya dipakai tetap diperlukan; uji pada browser/perangkat sebenarnya.

Pengecekan IP tetap nonaktif sesuai working copy lama. Jika diaktifkan, gunakan konfigurasi trusted proxies aplikasi induk yang benar karena pemeriksaan memakai `$request->ip()`.

## Pembaruan dan rollback

Untuk memperbarui paket path yang dipasang dengan `symlink: false`, salin source paket terbaru, jalankan `composer update sugihart/device-manager` dan `composer reinstall sugihart/device-manager --prefer-dist`, publikasikan aset, lalu bangun ulang cache. Composer menyalin paket ke `vendor`; mengedit source saja belum memperbarui salinan terpasang. Perintah reinstall memastikan perubahan source dengan nomor versi yang sama ikut disalin ulang.

Untuk rollback, kembalikan source/lock file, aset, dan konfigurasi deployment sebelumnya. Migrasi paket sengaja menolak `down()` karena tabel dapat berasal dari aplikasi lama; pemulihan database dilakukan melalui backup secara eksplisit jika diperlukan. Jangan menghapus atau membuat ulang token saat rollback.

Jika versi Laravel induk di luar 12/13, jangan memaksa constraint Composer. Aplikasi root repo ini bisa dijalankan terpisah di PHP 8.5.12, dengan reverse proxy untuk prefix URL lama sampai integrasi ke versi induk dapat disiapkan dan diuji. Source induk yang sebenarnya dibutuhkan untuk menentukan konfigurasi akhir.

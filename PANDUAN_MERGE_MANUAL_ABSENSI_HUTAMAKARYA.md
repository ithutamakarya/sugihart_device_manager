# Merge Manual Sugihart Device Manager ke Absensi Hutamakarya

Panduan ini untuk branch **`feature/native-sugihart-integration`**, menggunakan PHP 8.5.12 dan Laravel 13. Metode ini menyalin source native Laravel; tidak memasang paket `sugihart/device-manager` melalui Composer. Alternatif paket tetap tersedia pada branch `main`.

Source absensi hasil rewrite belum tersedia di repo ini. Vendor tetap harus menguji perubahan pada source induk, konfigurasi web server, dan kios sebenarnya sebelum produksi.

## 1. Siapkan source dan backup

Gunakan source terbaru dari branch `feature/native-sugihart-integration`. Simpan backup source/release induk, database absensi, database device manager, `.env`, dan konfigurasi server sebelum mengubah lingkungan yang berjalan.

Periksa PHP CLI dan PHP web server, Laravel, serta extension `pdo_pgsql`. Di root aplikasi induk:

```sh
php -v
php artisan --version
composer check-platform-reqs
```

Targetnya PHP **8.5.12** dan Laravel **13**. Pertahankan Composer/lock file, `APP_KEY`, default login, database, dan bootstrap aplikasi absensi. Jangan menimpa file-file tersebut dengan pembungkus standalone repo device manager.

## 2. Siapkan satu ZIP serah-terima

Di root repo device manager, jalankan Python 3:

```sh
python tools/build_native_handoff.py
```

Hasilnya **`integration/sugihart-native.zip`**. ZIP memuat sembilan file aplikasi berikut, folder aset statis, panduan ini sebagai `docs/SUGIHART_NATIVE_MERGE.md`, dan manifest SHA-256 `sugihart-native-manifest.json`.

| # | File yang disalin ke path sama pada induk |
| --- | --- |
| 1 | `app/Http/Controllers/Sugihart/DeviceManagerController.php` |
| 2 | `app/Models/Sugihart/User.php` |
| 3 | `app/Http/Middleware/Sugihart/Authenticate.php` |
| 4 | `app/Providers/SugihartServiceProvider.php` |
| 5 | `config/sugihart-device-manager.php` |
| 6 | `routes/sugihart.php` |
| 7 | `database/migrations/sugihart/2026_11_06_000000_create_device_manager_tables.php` |
| 8 | `resources/views/sugihart/admin.blade.php` |
| 9 | `resources/views/sugihart/kios.blade.php` |

Aset berada di `public/vendor/sugihart-device-manager/`. Aset tetap berupa banyak file CSS/JavaScript/font di server, tetapi pemindahannya cukup melalui satu ZIP. ZIP tidak berisi `.env`, dump database, file Composer induk, `bootstrap/providers.php`, atau `public/index.php`.

ZIP hasil build dan dump database tidak masuk Git. Kirim ZIP dan salah satu dump PostgreSQL secara terpisah jika vendor membutuhkannya. Python hanya diperlukan untuk membuat ZIP, bukan untuk menjalankan aplikasi hasil merge.

## 3. Ekstrak source ke proyek induk

Periksa manifest dan isi ZIP terlebih dahulu. Ekstrak dengan mempertahankan path relatifnya ke root `absensi-hutamakarya`. Jika file native Sugihart sudah ada di induk, bandingkan/backup sebelum menggantinya.

Contoh PowerShell pada salinan development yang belum mempunyai file Sugihart:

```powershell
Expand-Archive -LiteralPath 'C:\handoff\sugihart-native.zip' -DestinationPath 'C:\proyek\absensi-hutamakarya'
```

Atau salin sembilan file di tabel secara manual beserta seluruh folder aset. Tidak perlu menyalin direktori `packages`, `vendor` dependency Composer, test, atau wrapper standalone. Ini adalah penggabungan file, bukan `git merge` seluruh repo device manager ke repo absensi.

## 4. Daftarkan satu provider

Di `bootstrap/providers.php` **milik induk**, tambahkan provider berikut pada array yang sudah ada:

```php
App\Providers\SugihartServiceProvider::class,
```

Contoh jika sebelumnya hanya ada `AppServiceProvider`:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\SugihartServiceProvider::class,
];
```

Pertahankan semua provider induk lainnya. Provider Sugihart mengatur route, koneksi `sdm`, guard/broker login `sdm`, dan perintah instalasi. Tidak perlu menambah route yang sama lagi di `routes/web.php`, `routes/api.php`, atau bootstrap routing induk.

Tidak perlu menambah repository Composer, `composer require sugihart/device-manager`, atau autoload khusus; namespace `App` mengikuti autoload standar Laravel.

**Jika induk sebelumnya sudah memasang versi paket:** pada salinan development, hapus dependency `sugihart/device-manager` melalui Composer dan entri repository path-nya sebelum mengaktifkan provider native. Jangan memuat provider paket dan native secara bersamaan karena nama route/guard-nya sama. Periksa perubahan lock file dan uji ulang sebelum deployment.

## 5. Hubungkan PostgreSQL khusus modul

Tambahkan konfigurasi berikut pada `.env` **induk**, dengan kredensial database device manager yang sebenarnya:

```dotenv
SDM_ROUTE_PREFIX=sugihart_device_manager/public
SDM_ASSET_PREFIX=vendor/sugihart-device-manager
SDM_DB_CONNECTION=pgsql
SDM_DB_HOST=127.0.0.1
SDM_DB_PORT=5432
SDM_DB_DATABASE=sugihart_device_manager
SDM_DB_USERNAME=postgres
SDM_DB_PASSWORD="password_database_device_manager"
SDM_DB_SEARCH_PATH=public
SDM_DB_SSLMODE=prefer
SDM_ATTENDANCE_URL=https://absensi.hutamakarya.com/
SDM_KIOSK_LOCAL_URL=http://localhost/hutamakarya-kios-live2/
SDM_KIOSK_ASSET_URL=http://localhost/hutamakarya-kios-live2/
SDM_ENFORCE_KIOSK_IP=false
SDM_REGISTRATION_ENABLED=false
```

Nama database boleh berbeda, misalnya `device` atau `sugihart`, asalkan memang khusus device manager. Database absensi dan modul jangan dicampur karena keduanya mempunyai tabel `users` dan `migrations`.

Jangan mengubah `DB_*` dan `APP_KEY` induk untuk memasang modul ini. Isi `SDM_DB_*` secara eksplisit; jangan menyalin referensi `${DB_DATABASE}` atau `${DB_PASSWORD}` dari `.env` standalone ke induk karena dapat mengarah ke database absensi.

Gunakan role PostgreSQL dan pengaturan SSL sesuai server tujuan. Pada lokal/staging, arahkan `SDM_ATTENDANCE_URL` ke aplikasi absensi yang sedang diuji jika endpointnya tersedia. `localhost` pada `SDM_KIOSK_*` berarti komputer yang membuka browser kios, bukan server hosting.

## 6. Restore data lama bila diperlukan

Jika database modul sudah berhasil di-restore, gunakan database tersebut dan lewati restore ulang.

Untuk instalasi dari snapshot, buat database PostgreSQL kosong terlebih dahulu dan restore **sebelum** menjalankan `sdm:install`. Pilih satu cara berikut.

### DBeaver / file Custom

Pilih database modul kosong > **Tools > Restore**:

- Format: **Custom**.
- Backup file: **`device.postgresql.backup`**.
- Centang **Discard objects owner**.
- Matikan **Clean** dan **Create database**.
- Extra command args: `--no-privileges --exit-on-error --single-transaction`.

Alternatif terminal:

```sh
pg_restore -h 127.0.0.1 -p 5432 -U postgres -d sugihart_device_manager --format=custom --no-owner --no-privileges --exit-on-error --single-transaction device.postgresql.backup
```

### File SQL teks

```sh
psql -X -h 127.0.0.1 -p 5432 -U postgres -d sugihart_device_manager --set=ON_ERROR_STOP=1 --file=device.postgresql.sql
```

Di DBeaver, `.sql` memakai Format **Plain**, bukan Custom. Error `did not find magic string in file header` berarti format file tidak cocok dengan alat restore. Jangan menjalankan dua alternatif restore pada database yang sama.

Snapshot yang diberikan berisi 33 perangkat, 203 log, 2 akun, 1 vendor, dan 4 catatan migrasi lama. ID, token, hash password, timestamp, dan sequence sudah dipertahankan. Untuk cutover produksi, gunakan data akhir terbaru jika sumber berubah setelah snapshot tersebut.

## 7. Jalankan instalasi dan periksa route

Dari root induk:

```sh
composer dump-autoload
php artisan config:clear
php artisan db:show --database=sdm
php artisan sdm:install --force
php artisan route:clear
php artisan view:clear
php artisan route:list --path=sugihart_device_manager
```

`sdm:install` hanya menjalankan migrasi dalam `database/migrations/sugihart` dengan koneksi/repository migrasi `sdm`. Tabel hasil restore dilewati dan tabel yang belum ada dibuat. Basename migrasi tetap sama dengan versi paket agar instalasi yang sudah tercatat tidak diulang.

Jangan menjalankan semua migrasi induk menggunakan `--database=sdm`, `migrate:fresh`, atau `db:wipe`. Migrasi absensi tetap memakai prosedur vendor. Aset sudah disalin dari ZIP; pada metode native tidak ada langkah `vendor:publish --tag=sdm-assets`.

Periksa file aset ini tersedia secara HTTP:

```text
/vendor/sugihart-device-manager/elmer/dist/css/style.css
```

## 8. Uji lokal dan fungsi admin

```sh
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000/sugihart_device_manager/public/login`. Login memakai email/password admin pada tabel `users` device manager, bukan kredensial PostgreSQL atau akun absensi.

Jika perlu akun tambahan:

```sh
php artisan sdm:admin admin@perusahaan.com
```

Password diminta secara interaktif. Tidak ada password bawaan. Reset password memakai mailer induk; pastikan konfigurasi emailnya berfungsi jika fitur ini diperlukan.

Uji daftar vendor, perangkat, pengguna, log, tambah/edit/hapus, dan **Create Sub** CCTV. Uji login absensi dan modul secara bersamaan, lalu logout modul: sesi absensi harus tetap aktif dan data akun kedua aplikasi tetap terpisah.

## 9. Uji URL API lama dan kios fisik

| Path setelah origin | Token | Hasil |
| --- | --- | --- |
| `/sugihart_device_manager/public/api/get_data_cctv/{token}` | Vendor | JSON CCTV aktif; slash URL diganti underscore |
| `/sugihart_device_manager/public/api/get_kios/{token}` | Vendor | JSON kios aktif |
| `/sugihart_device_manager/public/api/get_device/{token}` | Vendor | JSON semua perangkat vendor, termasuk nonaktif |
| `/sugihart_device_manager/public/api/get_render_kios/{token}` | Perangkat kios aktif | HTML kios tanpa redirect login |

Ketiga API JSON harus mempertahankan field/timestamp/urutan lama. Token vendor salah menghasilkan HTTP 200 dengan `{"response":"failed"}`. Token kios salah/nonaktif menghasilkan body `404` dengan HTTP 200 sesuai kontrak lama.

Kios masih membutuhkan endpoint absensi `get_running_text/{token}`, `get_display_now/{token}/{time}`, `api/office_hour`, `api/get_face_album_employee/{employee_id}`, worker `api/get_five_ontime_employee` bila digunakan, dan `uploads/*`. Komputer kios masih menyediakan `api/face_data/data.php`, `api/api_cross.php`, CSS/JS lokal, dan gambar kamera `public/image/capture.jpg`.

Vendor harus mempertahankan kontrak layanan tersebut pada rewrite absensi. Periksa network/console browser, CSP/CORS, worker, kamera, data karyawan, running text, leaderboard, dan materi iklan pada perangkat sebenarnya. Tes HTML backend saja belum membuktikan seluruh perangkat berfungsi.

## 10. Cutover server dan cache

Document root domain harus mengarah ke `absensi-hutamakarya/public`, dengan PHP 8.5.12 dan front controller **induk**. Prefix `/sugihart_device_manager/public` sekarang merupakan route Laravel.

Pada jadwal deployment, arsipkan aplikasi nested lama di `public/sugihart_device_manager/` ke luar document root setelah backup dan verifikasi path. Pastikan `index.php`, `.htaccess`, `.env`, `vendor`, atau Alias/rewrite lama tidak mengambil alih request. Jangan membuat kembali folder fisik `public/sugihart_device_manager/public` untuk aset; gunakan `public/vendor/sugihart-device-manager`.

Gunakan aturan front controller Laravel induk. Contoh bagian routing Nginx:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Sesudah source/config/data siap pada release induk:

```sh
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan config:clear
php artisan sdm:install --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan izin `storage`/`bootstrap/cache` sesuai server, lalu reload worker persisten/Octane jika dipakai. Jika ada middleware autentikasi global pada induk, pastikan empat API token lama tetap dilayani sesuai kontraknya.

Uji kembali URL produksi berikut tanpa mengubah client:

```text
https://absensi.hutamakarya.com/sugihart_device_manager/public/api/get_render_kios/{token-perangkat-lama}
```

## 11. Pemeliharaan, pergantian metode, dan rollback

Setelah merge, perubahan device manager dilakukan langsung di source absensi. Tidak perlu memperbarui paket Composer modul. Jika mengirim pembaruan lewat ZIP, bandingkan sembilan file native dan aset dengan versi vendor; jangan menimpa perubahan vendor tanpa review.

Untuk berpindah alternatif pada repo standalone ini, pastikan working tree bersih, lalu jalankan dari root repo:

```sh
git switch main
composer install
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Ganti `main` dengan `feature/native-sugihart-integration` untuk kembali ke metode native, kemudian jalankan ulang perintah dependency/cache yang sama. Pertahankan `.env` dan `APP_KEY`. Folder `vendor`, cache, dan file ZIP yang diabaikan Git tidak otomatis berganti bersama branch.

Untuk rollback deployment, kembalikan release source, konfigurasi provider/`.env`, aset, dan aturan web server yang sebelumnya teruji. Jangan menjatuhkan tabel modul untuk membatalkan integrasi; `down()` migrasi sengaja menolak penghapusan data lama. Jika pemulihan database diperlukan, tentukan nasib data baru setelah cutover sebelum memakai backup.

Jangan menjalankan kode Laravel 5.8 lama pada PHP 8.5 sebagai fallback. Siapkan release/runtime rollback yang kompatibel dan tetap mempertahankan URL lama.

## Checklist serah terima

- [ ] Sembilan file aplikasi dan aset tersedia; provider native terdaftar sekali.
- [ ] Tidak ada dependency/provider paket lama yang ikut aktif.
- [ ] Database modul benar, terpisah dari absensi, dan hasil restore sesuai.
- [ ] Login/CRUD/Create Sub/reset password dan log diuji.
- [ ] Login/logout modul tidak mengganggu sesi induk.
- [ ] Empat API lama berfungsi sebelum dan sesudah cache.
- [ ] Kios fisik dan layanan absensi/lokal diuji.
- [ ] Source, lock induk, backup, konfigurasi, dan hasil pengujian tercatat pada release vendor.

Referensi: [Laravel service providers](https://laravel.com/framework/docs/13.x/providers), [PostgreSQL pg_restore](https://www.postgresql.org/docs/14/app-pgrestore.html), dan [DBeaver restore](https://dbeaver.com/docs/dbeaver/Backup-Restore/).

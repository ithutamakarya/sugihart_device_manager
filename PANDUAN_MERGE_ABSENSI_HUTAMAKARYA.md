# Panduan Merge Sugihart Device Manager ke Absensi Hutamakarya

Dokumen ini ditujukan untuk pemilik aplikasi dan vendor yang menerima source `absensi-hutamakarya` hasil rewrite. Jalankan integrasi di komputer development atau staging terlebih dahulu, kemudian lakukan cutover produksi setelah pemeriksaan selesai.

**Target:** satu aplikasi Laravel yang melayani absensi dan device manager, menggunakan PHP 8.5.12, dengan URL API device manager lama tetap sama. Database device manager tetap PostgreSQL tersendiri; merge source tidak berarti menggabungkan tabel atau akun kedua aplikasi.

Source absensi hasil rewrite belum tersedia di workspace ini. Nama file konfigurasi khusus vendor, middleware tambahan, dan konfigurasi web server harus dicocokkan dengan source serta server yang benar-benar diterima.

## Ringkasan urutan

1. Siapkan source terbaru dan backup.
2. Periksa versi PHP/Laravel proyek induk.
3. Salin paket device manager ke luar direktori publik induk.
4. Pasang paket melalui Composer proyek induk.
5. Siapkan database PostgreSQL khusus modul dan restore data.
6. Tambahkan konfigurasi `SDM_*` pada `.env` induk.
7. Jalankan instalasi tabel tambahan dan publikasi aset.
8. Periksa endpoint absensi yang dipanggil kios.
9. Uji aplikasi gabungan di lokal/staging.
10. Siapkan web server dan lakukan cutover produksi.
11. Bangun cache, uji kembali, dan serahkan hasilnya.

Semua perintah `composer` dan `php artisan` di bawah dijalankan dari **root proyek induk `absensi-hutamakarya`**, kecuali jika disebutkan lain. Perintah database dijalankan melalui terminal PostgreSQL/DBeaver. Ganti contoh nama database, role, host, dan path sesuai lingkungan tujuan.

## 1. Siapkan source yang akan diserahkan

Dari proyek `sugihart_device_manager`, siapkan:

| Yang diserahkan | Kegunaan |
| --- | --- |
| Seluruh folder `packages/sugihart-device-manager/` | Source paket yang dipasang ke aplikasi induk, termasuk aset di dalamnya |
| Dokumen ini | Langkah integrasi vendor |
| Folder `docs/` | Kontrak API, PostgreSQL, dan penjelasan integrasi |
| `device.postgresql.backup` | Snapshot PostgreSQL format Custom untuk Restore DBeaver/`pg_restore` |
| `device.postgresql.sql` | Alternatif snapshot SQL teks untuk `psql`/SQL editor |

Gunakan hanya **salah satu** file PostgreSQL untuk restore; isinya snapshot yang sama. Dump yang tersedia berasal dari `device.sql` tanggal **6 November 2026, 16:42:17** menurut header dump. Jika data sumber berubah sesudah waktu tersebut, siapkan dump akhir yang lebih baru sebelum cutover.

File dump dan `.env` diabaikan oleh Git. Dump harus disertakan secara terpisah melalui sarana transfer yang disepakati. Simpan kredensial deployment pada konfigurasi lingkungan tujuan, bukan pada source yang dibagikan.

Jika source dikirim melalui Git, periksa `git status --short` dan pastikan perubahan rewrite serta seluruh folder paket sudah masuk ke revisi yang diterima vendor. Clone/arsip dari commit lama tidak menyertakan perubahan working copy yang belum di-commit.

## 2. Siapkan backup dan periksa versi induk

Simpan backup release absensi yang berjalan, database absensi, database device manager lama, konfigurasi web server, serta `.env` dan `APP_KEY` masing-masing aplikasi. Tentukan lokasi arsip di luar document root.

Di root source absensi baru, jalankan:

```sh
php -v
php artisan --version
composer show laravel/framework
composer check-platform-reqs
```

Target integrasi ini adalah PHP **8.5.12** dan Laravel **13**. Paket juga menerima Laravel 12 melalui constraint Composer, tetapi jika source induk menggunakan versi lain, vendor perlu menyelesaikan kompatibilitasnya terlebih dahulu. Jangan menggunakan `--ignore-platform-reqs` untuk memaksa pemasangan.

Pastikan `pdo_pgsql` aktif. PHP CLI dan PHP yang dipakai Apache/PHP-FPM/panel hosting harus menggunakan runtime yang sesuai; hasil `php -v` hanya membuktikan versi CLI.

## 3. Salin paket ke source induk

Salin folder:

```text
sugihart_device_manager/packages/sugihart-device-manager/
```

ke:

```text
absensi-hutamakarya/packages/sugihart-device-manager/
```

Struktur akhir yang diharapkan:

```text
absensi-hutamakarya/
  app/
  bootstrap/
  config/
  composer.json
  composer.lock
  packages/
    sugihart-device-manager/
      composer.json
      config/
      database/migrations/
      public/
      resources/views/
      routes/
      src/
  public/
    index.php
    vendor/
      sugihart-device-manager/       # hasil publikasi aset pada langkah 7
```

Pertahankan `app/`, `bootstrap/app.php`, `public/index.php`, route, file Composer, `.env`, dan `APP_KEY` milik aplikasi **absensi**. Aplikasi Laravel standalone di root repo device manager digunakan untuk pengujian lokal; file pembungkus tersebut tidak perlu menimpa file induk.

Tidak perlu menyalin controller/model/view modul satu per satu ke namespace `App` induk. Paket menggunakan namespace `Sugihart\DeviceManager`, view `sdm::`, dan nama route `sdm.*`.

## 4. Pasang melalui Composer induk

Tambahkan entri berikut ke array `repositories` pada `composer.json` induk. Pertahankan repository dan dependency yang sudah ada; contoh ini adalah potongan konfigurasi, bukan pengganti seluruh file:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/sugihart-device-manager",
            "options": {
                "symlink": false
            }
        }
    ]
}
```

Jika `repositories` sudah ada, tambahkan objek repository paket ke daftar tersebut. `symlink: false` membuat Composer menyalin paket ke `vendor`, sehingga deployment tidak bergantung pada symlink workstation.

Jalankan di development/staging:

```sh
composer require "sugihart/device-manager:^1.0" --with-all-dependencies
composer show sugihart/device-manager
php artisan package:discover
```

Periksa perubahan `composer.json` dan `composer.lock` induk, termasuk dependency lain yang ikut berubah. Simpan hasil resolusi dependency yang telah diuji untuk deployment; jangan mengganti lock file induk dengan lock file aplikasi standalone device manager.

Provider paket terdaftar otomatis. Jika induk menonaktifkan package discovery melalui `extra.laravel.dont-discover`, tambahkan provider berikut ke daftar yang sudah ada di `bootstrap/providers.php` Laravel 13:

```php
Sugihart\DeviceManager\DeviceManagerServiceProvider::class,
```

Jangan mendaftarkan provider dua kali atau menyalin route modul ke `routes/api.php` induk. Paket sudah mendaftarkan prefix dan endpoint lengkapnya.

## 5. Siapkan database PostgreSQL dan restore data

Gunakan database PostgreSQL khusus device manager. Database absensi boleh tetap memakai engine yang dibutuhkan aplikasi induk.

Kedua aplikasi memiliki tabel bernama `users` dan `migrations`. Jangan restore snapshot device manager ke database absensi yang sudah berisi data tersebut.

### 5A. Jika database modul sudah di-restore

Jika sebelumnya sudah berhasil restore melalui DBeaver, gunakan database tersebut selama memang khusus device manager. Nama database boleh `sugihart`, `device`, atau nama lain; nantinya isi `SDM_DB_DATABASE` dengan nama yang sebenarnya.

Jangan restore ulang hanya untuk mengikuti contoh nama dalam dokumen. Lanjutkan ke pemeriksaan data dan langkah 6.

### 5B. Jika membutuhkan database baru

Melalui administrator PostgreSQL/DBeaver, buat database kosong dengan encoding UTF-8, misalnya:

```sql
CREATE DATABASE sugihart_device_manager ENCODING 'UTF8' TEMPLATE template0;
```

Siapkan role aplikasi yang dapat mengakses database tersebut dan menjalankan migrasi awal. Sesuaikan kepemilikan/izin schema dengan kebijakan server. Contoh perintah restore di bawah memakai `postgres`; ganti dengan role yang sesuai bila berbeda.

Restore dilakukan **sebelum** menjalankan `php artisan sdm:install` pada database kosong ini.

### 5C. Restore melalui DBeaver — pilih Format Custom

1. Pilih database modul yang kosong, lalu **Tools > Restore**.
2. Pilih **Format: Custom**.
3. Pada **Backup file**, pilih `device.postgresql.backup`.
4. Centang **Discard objects owner**.
5. Hilangkan centang **Create database** dan **Clean (drop) database objects before recreating them**.
6. Isi **Extra command args** dengan `--no-privileges --exit-on-error --single-transaction`.
7. Jalankan restore dan pastikan selesai tanpa error.

`Clean` menghapus objek tujuan sebelum restore. Database kosong tidak memerlukan opsi tersebut.

Alternatif terminal, jalankan satu baris berikut dari lokasi file backup:

```sh
pg_restore --host=127.0.0.1 --port=5432 --username=postgres --dbname=sugihart_device_manager --format=custom --no-owner --no-privileges --exit-on-error --single-transaction --verbose device.postgresql.backup
```

### 5D. Alternatif menggunakan file SQL teks

Jika memilih `device.postgresql.sql`, gunakan `psql`, bukan `pg_restore --format=c`:

```sh
psql -X --host=127.0.0.1 --port=5432 --username=postgres --dbname=sugihart_device_manager --set=ON_ERROR_STOP=1 --file=device.postgresql.sql
```

Di DBeaver, pilih **Format: Plain** untuk file `.sql`. File yang sama dapat dijalankan sebagai satu SQL script melalui SQL editor pada database kosong.

Jika muncul `did not find magic string in file header`, format file dan alat restore tidak cocok. Mengganti ekstensi nama file saja tidak mengonversi formatnya.

### 5E. Periksa hasil restore

Pada database modul, jalankan:

```sql
SELECT 'device' AS table_name, COUNT(*) AS total FROM device
UNION ALL SELECT 'log_activity', COUNT(*) FROM log_activity
UNION ALL SELECT 'migrations', COUNT(*) FROM migrations
UNION ALL SELECT 'users', COUNT(*) FROM users
UNION ALL SELECT 'vendor', COUNT(*) FROM vendor;
```

Untuk snapshot yang diberikan, hasil sebelum instalasi modul adalah:

| Tabel | Baris |
| --- | ---: |
| device | 33 |
| log_activity | 203 |
| migrations | 4 |
| users | 2 |
| vendor | 1 |

Totalnya 243 baris. Dump yang lebih baru tentu dapat memiliki jumlah berbeda. Sesudah `sdm:install`, riwayat migrasi paket bertambah dan tabel reset password dibuat; itu tidak berarti data lama berubah.

File hasil konversi sudah mempertahankan ID, token, hash password, timestamp, nilai `NULL`, serta sequence/ID berikutnya. Jangan membuat ulang token atau mengubah `is_active` menjadi boolean. Detail konversi dan sequence terdapat pada [panduan PostgreSQL](docs/POSTGRESQL.md).

## 6. Tambahkan konfigurasi modul pada `.env` induk

Tambahkan variabel berikut dan sesuaikan kredensial dengan database modul hasil langkah 5:

```dotenv
SDM_ROUTE_PREFIX=sugihart_device_manager/public
SDM_ASSET_PREFIX=vendor/sugihart-device-manager

SDM_DB_CONNECTION=pgsql
SDM_DB_HOST=127.0.0.1
SDM_DB_PORT=5432
SDM_DB_DATABASE=sugihart_device_manager
SDM_DB_USERNAME=postgres
SDM_DB_PASSWORD="isi_password_database_modul"
SDM_DB_SEARCH_PATH=public
SDM_DB_SSLMODE=prefer

SDM_ATTENDANCE_URL=https://absensi.hutamakarya.com/
SDM_KIOSK_LOCAL_URL=http://localhost/hutamakarya-kios-live2/
SDM_KIOSK_ASSET_URL=http://localhost/hutamakarya-kios-live2/
SDM_ENFORCE_KIOSK_IP=false
SDM_REGISTRATION_ENABLED=false
```

Ketentuan konfigurasi:

- `SDM_DB_*` harus menuju database **modul**. Untuk integrasi, isi kredensialnya secara eksplisit; referensi `${DB_DATABASE}`/`${DB_PASSWORD}` dari `.env` standalone dapat mengarah ke database absensi jika disalin ke induk.
- `DB_*`, default guard, cookie sesi, dan `APP_KEY` induk tetap mengikuti aplikasi absensi. Akun admin modul memakai guard `sdm`, bukan SSO dengan akun absensi.
- `APP_URL` induk pada produksi tetap `https://absensi.hutamakarya.com`, tanpa akhiran device manager.
- `SDM_ROUTE_PREFIX` mempertahankan underscore `sugihart_device_manager` dan segmen `/public` pada URL lama. Nama folder paket yang memakai tanda minus tidak menjadi URL API.
- `SDM_ASSET_PREFIX` berbeda dari prefix route. Ini mencegah folder aset mengambil alih route halaman utama modul.
- Pada local/staging, sesuaikan `SDM_ATTENDANCE_URL` ke origin aplikasi absensi yang sedang diuji jika endpointnya tersedia di sana. Nilai `localhost` pada `SDM_KIOSK_*` berarti **komputer yang membuka browser kios**, bukan server hosting.
- Sesuaikan `SDM_DB_SSLMODE` dengan konfigurasi TLS database tujuan. Pengecekan IP perangkat tetap nonaktif seperti working copy lama; jika diaktifkan, vendor perlu memeriksa trusted proxies induk.

Setelah menyimpan `.env`:

```sh
php artisan config:clear
php artisan db:show --database=sdm
```

Pastikan perintah terakhir menampilkan koneksi PostgreSQL modul yang benar. Jika password ditolak, gunakan kredensial database yang berhasil pada DBeaver; akun database ini berbeda dari akun login admin di tabel `users`.

## 7. Instal tabel tambahan dan publikasikan aset

Setelah data berhasil di-restore dan konfigurasi koneksi benar, jalankan:

```sh
php artisan sdm:install --force
php artisan vendor:publish --tag=sdm-assets --force
php artisan route:clear
php artisan view:clear
php artisan route:list --path=sugihart_device_manager
```

`sdm:install` menggunakan koneksi `sdm`, melewati tabel lama yang sudah ada, dan membuat tabel yang belum ada seperti `password_reset_tokens`. Pada versi paket ini, empat catatan migrasi dump tetap ada dan satu catatan migrasi paket ditambahkan.

Migrasi modul tidak didaftarkan ke perintah `migrate` biasa induk. Gunakan `sdm:install` untuk modul; proses migrasi absensi tetap mengikuti prosedur vendor. Jangan memakai `migrate:fresh`, `db:wipe`, atau seeder lama untuk memindahkan data.

Aset harus tersedia di:

```text
absensi-hutamakarya/public/vendor/sugihart-device-manager/
```

Salah satu file yang dapat diperiksa adalah `elmer/dist/css/style.css` di bawah folder tersebut. Tidak diperlukan build npm baru untuk aset modul yang sudah disertakan; kebutuhan build frontend induk tetap mengikuti proyek absensi.

## 8. Pastikan layanan absensi dan kios tetap tersedia

Paket ini menyediakan API device manager dan HTML tampilan kios. Tampilan tersebut masih memanggil layanan berikut yang berasal dari aplikasi lain:

| Penyedia | Endpoint/aset yang harus dipertahankan |
| --- | --- |
| Absensi | `get_running_text/{token}` |
| Absensi | `get_display_now/{token}/{time}` |
| Absensi | `api/office_hour` |
| Absensi | `api/get_face_album_employee/{employee_id}` |
| Absensi | `api/get_five_ontime_employee` — terdapat pada worker kios; periksa apakah alurnya diaktifkan |
| Absensi | `uploads/*` untuk materi iklan |
| Aplikasi lokal kios | `api/face_data/data.php`, `api/api_cross.php` |
| Aplikasi lokal kios | `public/css/*`, `public/js/*`, `public/image/*` termasuk kamera `capture.jpg` |

Vendor perlu mencocokkan path, parameter, dan format respons endpoint absensi dengan kode kios pada `packages/sugihart-device-manager/resources/views/render_kios/js/`. Menjaga empat API modul saja belum membuktikan kamera, data wajah, jam kerja, running text, dan iklan akan tampil lengkap.

Jika induk menerapkan autentikasi global, redirect login, CSP, CORS, atau kebijakan akses lain, uji dampaknya pada route modul, worker JavaScript, dan koneksi ke aplikasi lokal kios. Empat API token perangkat harus tetap dapat diakses client dengan kontrak lama. Penyesuaian tepatnya mengikuti implementasi induk yang diterima vendor.

## 9. Uji aplikasi gabungan di lokal/staging

Untuk development lokal, jalankan dari root absensi:

```sh
php artisan serve --host=127.0.0.1 --port=8000
```

Buka:

```text
http://127.0.0.1:8000/sugihart_device_manager/public/login
```

Login dengan email/password admin device manager lama setelah data `users` dipulihkan. Jika password admin memang tidak diketahui, gunakan alur reset password dengan mailer yang berfungsi, atau buat akun baru melalui `php artisan sdm:admin email@perusahaan.com`. Password diminta secara interaktif. Hindari menjalankan seeder lama dengan password bawaan.

Periksa empat endpoint ini menggunakan token dari database yang sedang diuji:

| Method/path setelah origin | Jenis token | Hasil yang diharapkan |
| --- | --- | --- |
| `GET /sugihart_device_manager/public/api/get_data_cctv/{token}` | Vendor | JSON CCTV aktif; urut token lalu nama; `/` pada URL CCTV diganti `_` |
| `GET /sugihart_device_manager/public/api/get_kios/{token}` | Vendor | JSON kios aktif; urut token lalu nama |
| `GET /sugihart_device_manager/public/api/get_device/{token}` | Vendor | JSON semua perangkat vendor termasuk nonaktif |
| `GET /sugihart_device_manager/public/api/get_render_kios/{token}` | Perangkat kios | HTML dengan HTTP 200, tanpa redirect login |

Contoh pemeriksaan header, ganti placeholder dengan token perangkat kios aktif:

```sh
curl -i "http://127.0.0.1:8000/sugihart_device_manager/public/api/get_render_kios/TOKEN_PERANGKAT_AKTIF"
```

Pada Windows PowerShell yang mempunyai alias `curl`, gunakan `curl.exe`. Pilih token perangkat, bukan token vendor, untuk endpoint render kios.

Checklist pengujian:

- [ ] Login, dashboard, vendor, perangkat, pengguna, dan log aktivitas modul dapat dibuka.
- [ ] CRUD memakai database modul; data akun absensi tidak berubah.
- [ ] Login/logout modul tidak mengakhiri login absensi.
- [ ] Endpoint token perangkat tidak mendapat redirect, kewajiban login, atau pembungkus JSON tambahan dari induk.
- [ ] Token vendor yang tidak ditemukan tetap menghasilkan HTTP 200 dengan `{"response":"failed"}`.
- [ ] Token kios tidak valid/nonaktif menghasilkan body `404` dengan HTTP 200 sesuai kontrak modul.
- [ ] Data perangkat, urutan, timestamp, dan URL CCTV dibandingkan dengan keluaran sumber.
- [ ] Semua aset admin dapat dimuat tanpa 404.
- [ ] Kios fisik berhasil memuat kamera lokal, data karyawan, leaderboard, running text, dan iklan.
- [ ] Pemeriksaan diulang sesudah cache config/route diaktifkan.

Jalankan juga test suite absensi milik vendor dan test suite repo standalone device manager pada lingkungan pengujian. Jangan menunjuk tes ke database produksi. Detail tes PostgreSQL terpisah ada pada [panduan PostgreSQL](docs/POSTGRESQL.md); kontrak API lengkap ada pada [KONTRAK-API.md](docs/KONTRAK-API.md).

## 10. Siapkan web server dan cutover produksi

Document root domain harus mengarah ke:

```text
absensi-hutamakarya/public
```

Request ke URL lama harus diteruskan ke `public/index.php` milik aplikasi absensi baru. Paket melayani prefix `/sugihart_device_manager/public` sebagai route Laravel.

Pada jadwal cutover yang disepakati:

1. Hentikan perubahan data pada modul lama dan ambil backup/data akhir jika berbeda dari snapshot pengujian. Pertahankan ID, token, dan hash password saat memindahkan data akhir.
2. Deploy release induk yang sudah berisi paket, file Composer/lock hasil pengujian, dan konfigurasi `SDM_*` produksi.
3. Arsipkan aplikasi nested lama dari `absensi-hutamakarya/public/sugihart_device_manager/` ke lokasi backup **di luar document root**, setelah memastikan path sumber/tujuan dan backupnya benar.
4. Pastikan tidak ada lagi `public/sugihart_device_manager/public/index.php`, `.htaccess`, `.env`, atau `vendor` Laravel lama yang mengambil alih request.
5. Hapus/ubah pemetaan `Alias`, `location`, atau rewrite khusus aplikasi nested lama melalui konfigurasi vendor. Jangan membuat kembali folder fisik `public/sugihart_device_manager/public` untuk aset; aset baru berada di `public/vendor/sugihart-device-manager`.
6. Pilih PHP 8.5.12 pada runtime web server dan hapus penggunaan handler PHP 7.2 lama dari jalur request tersebut.
7. Jalankan langkah instalasi/cache di bawah, lalu uji domain produksi.

Contoh aturan front controller Laravel pada Nginx:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Pada Apache, gunakan aturan front controller Laravel induk dan pastikan rewrite aktif. Contoh Nginx di atas bukan pengganti seluruh konfigurasi virtual host, TLS, atau PHP-FPM yang sudah disiapkan vendor.

Saat deployment, folder `packages/sugihart-device-manager` harus sudah tersedia **sebelum** menjalankan Composer karena repository bertipe `path`. Di release baru:

```sh
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan config:clear
php artisan sdm:install --force
php artisan vendor:publish --tag=sdm-assets --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Gunakan `composer install` dengan lock file hasil pengujian, bukan resolusi ulang seluruh dependency di server produksi. Pastikan proses PHP mempunyai akses tulis ke `storage` dan `bootstrap/cache`. Jika induk memakai Octane/worker persisten, restart/reload sesuai prosedur deployment vendor.

URL produksi yang harus tetap berfungsi adalah:

```text
https://absensi.hutamakarya.com/sugihart_device_manager/public/api/get_render_kios/{token-perangkat-lama}
```

Client tidak perlu mengganti alamat API, parameter token, atau parser respons modul. Pengujian tetap diperlukan untuk memastikan rewrite endpoint absensi dari vendor dan layanan lokal kios mempertahankan kontraknya.

## 11. Pemeriksaan akhir dan serah terima

- [ ] Empat API lama berfungsi di domain produksi tanpa perubahan URL.
- [ ] Login dan API absensi tetap berfungsi.
- [ ] Database modul dan database absensi tidak bercampur.
- [ ] Counter ID hasil restore tidak berbenturan saat membuat data baru.
- [ ] Kios nyata sudah diuji, bukan hanya respons HTML dari server.
- [ ] Source paket, `composer.json`, dan `composer.lock` yang diuji tersedia dalam release/revisi vendor.
- [ ] Konfigurasi `SDM_*`, lokasi aset, PHP-FPM, dan aturan web server terdokumentasi.
- [ ] Backup sebelum cutover dan prosedur rollback tersedia.
- [ ] Vendor mencatat versi release, hasil pengujian, lokasi database, dan penanggung jawab deployment.

## Pembaruan paket berikutnya

Dengan `symlink: false`, mengedit `packages/` tidak langsung memperbarui salinan di `vendor`. Pada development/staging, salin paket terbaru lalu jalankan:

```sh
composer update sugihart/device-manager
composer reinstall sugihart/device-manager --prefer-dist
php artisan vendor:publish --tag=sdm-assets --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

`reinstall` memastikan source paket disalin ulang, termasuk jika perubahan source tidak mengubah nomor versi. Uji kembali sebelum deployment. Deploy release/artifact yang sudah diperbarui secara utuh; jika melakukan deployment di direktori yang sama dengan vendor lama, pastikan salinan paket ikut diperbarui, jangan hanya menyalin folder source.

## Rollback

1. Hentikan penulisan ke modul selama pemulihan.
2. Kembalikan source release, Composer/lock, konfigurasi, aset, dan pemetaan web server yang sebelumnya telah terbukti berjalan.
3. Jangan menjalankan rollback/drop pada tabel modul untuk membatalkan pemasangan paket. Migrasi `down()` paket sengaja menolak penghapusan tabel yang mungkin berasal dari data lama.
4. Pulihkan database dari backup hanya jika memang dibutuhkan; jika ada data baru setelah cutover, tentukan cara menyelamatkan data tersebut sebelum pemulihan.
5. Bangun ulang cache/restart worker sesuai release yang dipulihkan, lalu uji login absensi dan URL API lama.

Source Laravel 5.8/PHP 7 lama tidak otomatis menjadi opsi rollback yang dapat dijalankan pada PHP 8.5.12. Vendor perlu menyiapkan release/runtime rollback yang kompatibel; bila menggunakan aplikasi standalone Laravel 13 sebagai fallback, pemetaan URL lama ke aplikasi tersebut harus diuji sebelum cutover.

## Troubleshooting

| Gejala | Pemeriksaan/tindakan |
| --- | --- |
| Composer tidak menemukan `sugihart/device-manager` | Periksa folder paket dan entri `repositories` di Composer induk; pastikan perintah dijalankan dari root absensi |
| Perintah `sdm:install` tidak tersedia | Jalankan `composer dump-autoload` dan `php artisan package:discover`; periksa `dont-discover` dan registrasi provider |
| `did not find magic string in file header` | `.backup` menggunakan Custom/`pg_restore`; `.sql` menggunakan Plain/`psql` |
| `password authentication failed` | Cocokkan `SDM_DB_*` dengan database modul, lalu `php artisan config:clear`; pastikan password tidak masih mereferensikan database absensi |
| Tabel sudah ada saat restore | Gunakan database baru yang kosong, atau gunakan database modul yang sudah berhasil dipulihkan tanpa restore ulang |
| URL API 403/404 meskipun route terdaftar | Periksa document root, folder fisik nested lama, rewrite/Alias/location, lalu cache route |
| URL API mengarah ke halaman login | Periksa middleware autentikasi/redirect global induk; route API modul harus mempertahankan akses token lama |
| Admin tanpa CSS atau aset 404 | Jalankan publikasi `sdm-assets` setelah config benar; periksa path `public/vendor/sugihart-device-manager` |
| Perubahan source paket belum muncul | Perbarui salinan `vendor` melalui Composer/reinstall dan bangun ulang cache |
| HTML kios tampil tetapi kamera/data/iklan kosong | Periksa endpoint absensi dan aplikasi lokal pada langkah 8 serta konsol/network browser kios |

## Referensi

- [Laravel Package Development](https://laravel.com/framework/docs/13.x/packages)
- [Composer Path Repositories](https://getcomposer.org/doc/05-repositories.md#path)
- [Composer CLI: install, update, reinstall](https://getcomposer.org/doc/03-cli.md)
- [PostgreSQL pg_restore](https://www.postgresql.org/docs/14/app-pgrestore.html)
- [DBeaver Backup and Restore](https://dbeaver.com/docs/dbeaver/Backup-Restore/)

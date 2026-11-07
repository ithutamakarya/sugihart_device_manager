# Sugihart Device Manager

Rewrite Laravel 5.8 ke **Laravel 13.40.0**, untuk **PHP 8.5.12**. Source fungsi device manager dikemas menjadi paket Composer agar dapat dipasang ke aplikasi absensi tanpa menyalin controller, route, model, atau view ke namespace aplikasi induk.

- [Panduan merge step by step untuk vendor](PANDUAN_MERGE_ABSENSI_HUTAMAKARYA.md)
- [Ringkasan integrasi ke absensi-hutamakarya](docs/INTEGRASI.md)
- [Kontrak API dan perilaku yang dipertahankan](docs/KONTRAK-API.md)
- [Konfigurasi dan migrasi PostgreSQL](docs/POSTGRESQL.md)
- Paket yang dipindahkan ke aplikasi induk: `packages/sugihart-device-manager`

## Menjalankan lokal

Gunakan PHP CLI 8.5.12 dan Composer 2 terbaru. Extension: PDO PostgreSQL (`pdo_pgsql`) untuk aplikasi; PDO SQLite untuk tes cepat; extension standar Laravel seperti mbstring, openssl, tokenizer, XML, ctype, curl, dan fileinfo.

1. Jalankan `composer install`.
2. Jika belum ada `.env`, salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`. Pertahankan APP_KEY instalasi lama bila memakai `.env` yang sudah ada.
3. Buat database PostgreSQL khusus device manager, lalu isi `DB_*` dan `SDM_DB_*` pada `.env` dengan kredensial PostgreSQL. Driver default keduanya `pgsql`, port `5432`. Konfigurasi lokal sebelum perubahan disimpan di `.local/before-postgresql/.env`.
4. Jika memakai dump `device.sql`, restore `device.postgresql.sql` ke database kosong terlebih dahulu sesuai [panduan PostgreSQL](docs/POSTGRESQL.md), lalu jalankan `php artisan sdm:install`. Untuk instalasi kosong tanpa data lama, cukup jalankan `php artisan sdm:install`.
5. Jalankan `php artisan vendor:publish --tag=sdm-assets --force`.
6. Jalankan `php artisan serve`.

Admin: `http://localhost:8000/sugihart_device_manager/public/login`.

API kios: `http://localhost:8000/sugihart_device_manager/public/api/get_render_kios/{token-perangkat}`.

Proyek root ini sudah merupakan aplikasi standalone Laravel 13; tidak perlu menunggu source absensi dari vendor untuk menjalankan halaman admin. Jalankan perintah dari folder yang berisi `artisan`, bukan dari `packages/sugihart-device-manager`.

Untuk PostgreSQL lokal, gunakan nama database yang benar-benar dipilih saat restore DBeaver (misalnya `sugihart`). Sesuaikan `DB_DATABASE` dan `SDM_DB_DATABASE`, serta `DB_PASSWORD` dan `SDM_DB_PASSWORD`. Pada `.env` working copy ini, variabel `SDM_DB_DATABASE` dan `SDM_DB_PASSWORD` mereferensikan `DB_DATABASE` dan `DB_PASSWORD`, sehingga cukup mengubah nilai `DB_*` tersebut. Setelah mengubah `.env`, jalankan:

```sh
php artisan config:clear
php artisan sdm:install
php artisan serve --host=127.0.0.1 --port=8000
```

Jika login/API menampilkan `password authentication failed`, sesuaikan password PostgreSQL dengan koneksi DBeaver. Akun PostgreSQL berbeda dari akun login admin aplikasi; halaman login memakai email/password pada tabel `users` hasil restore.

Setelah data lama dipindahkan, akun admin lama tetap digunakan. Untuk database kosong, `php artisan sdm:admin admin@perusahaan.com` meminta password secara interaktif. Tidak ada seeder dengan password bawaan. Reset password memerlukan konfigurasi email Laravel yang berfungsi; default lokal hanya menulis email ke log.

Untuk hosting standalone yang masih dipasang secara fisik di subfolder URL lama, set `SDM_ROUTE_PREFIX=` dan `SDM_ASSET_PREFIX=` kosong serta `APP_URL` ke URL publik subfolder tersebut. Untuk integrasi ke aplikasi induk, gunakan prefix default sesuai panduan integrasi.

## Verifikasi

```sh
composer test
composer lint
composer validate --strict --no-check-publish
composer check-platform-reqs
```

Tes cepat menggunakan SQLite in-memory; suite yang sama juga dapat dijalankan pada PostgreSQL terpisah (lihat panduan PostgreSQL), mencakup kontrak API lama, HTML kios, pemeriksaan token/IP, login/password reset, CRUD dan log, instalasi berulang, CSRF, serta pemisahan database/guard dari aplikasi induk. Tes tidak mengakses database produksi atau layanan kios/absensi eksternal.

## Struktur

`bootstrap`, `config`, `public/index.php`, dan `artisan` adalah aplikasi Laravel pembungkus untuk development/standalone. Seluruh logika aplikasi dan aset berada di `packages/sugihart-device-manager` dengan namespace `Sugihart\DeviceManager`. Paket memakai koneksi dan guard `sdm`, route `sdm.*`, serta view `sdm::`.

Source absensi hasil rewrite belum tersedia, sehingga penggabungan dan pengujian pada server serta kios sebenarnya masih perlu dilakukan setelah source tersebut diterima. Empat URL API lama sudah didaftarkan dan diuji pada prefix yang sama di repo ini.

Referensi target versi: [rilis Laravel 13](https://github.com/laravel/docs/blob/13.x/releases.md), [rilis PHP 8.5.12](https://www.php.net/releases/8_5_12.php).

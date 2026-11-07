# Sugihart Device Manager ? Native Laravel

Branch `feature/native-sugihart-integration`: implementasi native **Laravel 13 / PHP 8.5.12** untuk disalin ke proyek absensi. Alternatif paket Composer tetap tersedia pada branch `main`.

- [Panduan merge manual untuk vendor](PANDUAN_MERGE_MANUAL_ABSENSI_HUTAMAKARYA.md)
- [Kontrak API](docs/KONTRAK-API.md)
- [Database PostgreSQL dan restore DBeaver](docs/POSTGRESQL.md)
- [Desain native](docs/superpowers/specs/2026-11-07-native-sugihart-integration-design.md)

## Menjalankan lokal

1. Jalankan `composer install` dengan PHP 8.5.12 dan extension `pdo_pgsql`.
2. Jika belum ada `.env`, salin `.env.example` lalu jalankan `php artisan key:generate`. Pertahankan APP_KEY bila memakai lingkungan lama.
3. Isi koneksi PostgreSQL `DB_*` dan `SDM_DB_*`. Jangan arahkan modul ke database absensi yang berisi tabel users milik induk.
4. Bila memakai snapshot lama, restore file PostgreSQL ke database kosong dahulu. Setelah itu jalankan `php artisan config:clear` dan `php artisan sdm:install`.
5. Jalankan `php artisan serve --host=127.0.0.1 --port=8000`.

Admin: http://127.0.0.1:8000/sugihart_device_manager/public/login

Login memakai email/password admin dari tabel users device manager, bukan password PostgreSQL. Akun baru dapat dibuat dengan `php artisan sdm:admin admin@perusahaan.com`. Tidak ada password admin bawaan.

## Serah terima ke vendor

Jalankan `python tools/build_native_handoff.py` untuk menghasilkan `integration/sugihart-native.zip`. ZIP memuat 9 file aplikasi, aset statis, panduan, dan manifest SHA-256. File ZIP dan dump database tidak masuk Git; buat ulang dan kirim terpisah bila diperlukan.

Pada induk cukup ekstrak file native, tambahkan `App\Providers\SugihartServiceProvider::class` ke provider yang sudah ada, dan isi variabel `SDM_*`. Jangan menimpa file Composer, bootstrap, public/index.php, APP_KEY, atau default login induk. Panduan manual menjelaskan seluruh langkah, termasuk restore dan cutover URL lama.

## Verifikasi

```sh
composer test
composer lint
composer check-platform-reqs
python -m unittest discover -s tools -p "test_*.py"
```

Tes cepat memakai SQLite in-memory. Suite modul juga dapat diuji menggunakan PostgreSQL terpisah dengan variabel `SDM_TEST_PGSQL_*`; lihat panduan PostgreSQL. Pengujian tidak mengubah database produksi.

Regresi browser opsional (Windows/Edge): instal `playwright-core` di direktori terpisah, isi `SDM_PLAYWRIGHT_MODULE` dengan path absolut modul itu dan `SDM_BROWSER_PATH` dengan path `msedge.exe`, lalu jalankan `php artisan config:clear` dan `python tools/test_native_admin_browser.py`. Pastikan port 8766 kosong. Runner membuat SQLite sementara untuk menguji token vendor dan aksi tabel pada halaman kedua tanpa memakai database aplikasi.

Untuk berganti branch, pastikan perubahan tersimpan, jalankan `git switch main` atau `git switch feature/native-sugihart-integration`, kemudian `composer install`, `php artisan config:clear`, `php artisan route:clear`, dan `php artisan view:clear`. Dependency vendor/cache tidak otomatis mengikuti pergantian branch. Jangan menghapus .env atau membuat ulang APP_KEY hanya untuk berganti branch.

Setelah berpindah ke `main`, jalankan juga `php artisan vendor:publish --tag=sdm-assets --force` untuk mengembalikan aset paket.

Panduan paket `PANDUAN_MERGE_ABSENSI_HUTAMAKARYA.md` berlaku untuk versi paket pada `main`; gunakan panduan manual pada branch ini. Source absensi dari vendor dan perangkat kios sebenarnya tetap perlu diuji pada saat integrasi akhir.

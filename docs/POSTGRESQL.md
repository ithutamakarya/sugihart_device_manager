# PostgreSQL

Database aplikasi standalone dan koneksi paket `sdm` sekarang memakai PostgreSQL secara default. Nama koneksi paket tetap `sdm`, sehingga integrasi tidak mengubah konfigurasi database induk. URL API tetap sama.

## Instalasi

Aktifkan extension PHP `pdo_pgsql`. Buat database PostgreSQL dengan encoding UTF-8 melalui administrator database, misalnya dari `psql`:

```sql
CREATE DATABASE sugihart_device_manager ENCODING 'UTF8' TEMPLATE template0;
```

Isi `.env` standalone:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sugihart_device_manager
DB_USERNAME=postgres
DB_PASSWORD="password_postgresql"
DB_SEARCH_PATH=public
DB_SSLMODE=prefer

SDM_DB_CONNECTION=pgsql
SDM_DB_HOST=127.0.0.1
SDM_DB_PORT=5432
SDM_DB_DATABASE=sugihart_device_manager
SDM_DB_USERNAME=postgres
SDM_DB_PASSWORD="password_postgresql"
SDM_DB_SEARCH_PATH=public
SDM_DB_SSLMODE=prefer
```

Sesuaikan nama database, role, password, dan SSL dengan server PostgreSQL yang digunakan. Untuk standalone, kedua koneksi boleh menuju database device manager yang sama. Pada aplikasi induk, tambahkan hanya `SDM_DB_*` sesuai [panduan integrasi](INTEGRASI.md). Gunakan database terpisah dari absensi agar tabel `users` tidak bercampur.

```sh
php artisan config:clear
php artisan sdm:install
```

Migrasi memakai Laravel Schema Builder dan membuat tabel yang belum ada. Kolom `is_active` tetap integer untuk mempertahankan format JSON lama. Perangkat dengan token `NULL` tetap diurutkan sebelum token berisi, seperti pada API MySQL sebelumnya.

## Data dari MySQL lama

Mengubah driver dan menjalankan `sdm:install` hanya menyiapkan tabel PostgreSQL. Data MySQL tidak disalin otomatis. Backup SQL MySQL juga bukan file yang dapat langsung dijalankan oleh `psql`.

### Restore dump `device.sql` yang sudah dikonversi

Dump yang diberikan sudah dikonversi menjadi **`device.postgresql.sql`** di root proyek. Isinya 243 baris: 33 perangkat, 203 log aktivitas, 4 riwayat migrasi, 2 akun, dan 1 vendor. Seluruh nilai, termasuk ID, token, URL perangkat, hash password, timestamp, dan `NULL`, dipertahankan. Counter ID berikutnya juga mengikuti `AUTO_INCREMENT` dump asal.

1. Buat database PostgreSQL **baru dan kosong**, khusus device manager. Jangan menjalankan `sdm:install` pada database ini sebelum restore.
2. Jalankan dari root proyek (ganti host, user, dan nama database sesuai server):

```sh
psql -X -h 127.0.0.1 -p 5432 -U postgres -d sugihart_device_manager --set=ON_ERROR_STOP=1 --file=device.postgresql.sql
```

3. Arahkan `SDM_DB_*` pada `.env` ke database tersebut, lalu jalankan:

```sh
php artisan config:clear
php artisan sdm:install --force
```

Langkah terakhir menambahkan tabel reset password dan catatan migrasi paket baru. File konversi mempertahankan keempat catatan migrasi historis dari snapshot; nama migrasinya berbeda dari migrasi paket baru. Riwayat ini hanya dipulihkan pada database modul yang terpisah, bukan tabel migrasi aplikasi absensi.

File SQL menggunakan satu transaksi dan tidak berisi `DROP TABLE` atau `TRUNCATE`. Bila tabel tujuan sudah ada, restore berhenti dengan error. Gunakan database baru untuk restore, bukan menimpa tabel yang sudah diisi. File ini dapat dijalankan melalui **Query Tool** pgAdmin pada database kosong; formatnya SQL biasa dengan `INSERT`, bukan arsip untuk menu Restore pgAdmin.

### Restore melalui DBeaver

Tersedia pula **`device.postgresql.backup`** di root proyek, arsip format **Custom** yang dibuat dengan PostgreSQL 14. Arsip ini berisi snapshot 5 tabel/243 baris yang sama dengan file SQL, beserta counter ID. Restore ke database kosong dan restore ulang memakai `--clean --if-exists` sudah diuji pada database sementara; seluruh nilai dan counter ID cocok dengan dump MySQL asal.

Di DBeaver:

1. Pilih database PostgreSQL khusus device manager yang kosong, lalu **Tools → Restore**.
2. Pilih **Format: Custom**.
3. Pada **Backup file**, pilih `device.postgresql.backup` (bukan file `.sql`).
4. Centang **Discard objects owner**. Biarkan **Create database** dan **Clean (drop) database objects before recreating them** tidak dicentang untuk database yang sudah dibuat dan masih kosong.
5. Pada **Extra command args**, gunakan `--no-privileges --exit-on-error --single-transaction`.
6. Jalankan restore, refresh daftar tabel, sesuaikan `.env`, lalu jalankan `php artisan config:clear` dan `php artisan sdm:install --force`.

Error `pg_restore: did not find magic string in file header` berarti file yang diberikan tidak sesuai dengan format arsip yang diminta. `device.postgresql.sql` adalah SQL teks biasa, sehingga tidak dapat dipakai dengan `pg_restore --format=c`. Jika tetap menggunakan `.sql`, pilih **Format: Plain** agar proses dijalankan melalui `psql`, atau jalankan perintah `psql` di atas. Mengganti ekstensi `.sql` menjadi `.backup` saja tidak mengubah formatnya.

Opsi **Clean** menghapus objek yang akan dipulihkan; arsip ini juga memuat tabel `users` dan `migrations`, sehingga gunakan database modul yang terpisah dari database absensi. Referensi format dan opsi tersedia pada [dokumentasi DBeaver](https://dbeaver.com/docs/dbeaver/Backup-Restore/) dan [pg_restore PostgreSQL](https://www.postgresql.org/docs/14/app-pgrestore.html).

### Menjalankan ulang konversi

`device.sql` asli tidak diubah. Dump SQL dan arsip backup diabaikan oleh Git karena memuat data dan kredensial aplikasi lama; simpan/pindahkan file hasil konversi secara terpisah saat deployment. Script konversi dapat dilacak di repo:

```sh
python tools/convert_mysql_dump.py device.sql --output device.postgresql.sql
python -m unittest discover -s tools -p test_convert_mysql_dump.py
```

Script hanya mendukung bentuk dump dan tipe kolom yang dipakai proyek ini. SQL, tipe data, atau nilai yang tidak didukung akan ditolak, bukan dikonversi secara diam-diam. Restore telah diuji pada PostgreSQL sementara dengan perbandingan seluruh kolom/baris dan pemeriksaan integrasi Laravel setelah restore.

### Jika memakai dump lain

Saat memindahkan data, pertahankan semua kolom pada tabel `users`, `vendor`, `device`, dan `log_activity`, terutama ID, relasi `vendor_id`, token, `is_active`, hash password, serta timestamp. Gunakan alat konversi MySQL ke PostgreSQL atau ekspor/impor data dengan pemetaan kolom eksplisit. Import ke database PostgreSQL khusus modul, lalu bandingkan jumlah baris dan respons API sebelum cutover.

Gunakan struktur tabel dari migrasi paket sebagai acuan. Jangan mengubah `is_active` menjadi boolean atau mengganti token/password. Tabel `password_reset_tokens` dapat dibuat kosong; tautan reset password yang sedang berjalan tidak perlu dipindahkan. Jangan menyalin tabel `migrations` MySQL untuk menghindari konflik riwayat migrasi.

Untuk impor manual dari dump lain, setelah mengimpor ID secara eksplisit, selaraskan sequence PostgreSQL sebelum membuat data baru. Langkah ini tidak diperlukan untuk `device.postgresql.sql`: file tersebut sudah mengatur sequence dan mempertahankan counter `AUTO_INCREMENT` asli, termasuk celah ID yang terhapus.

```sql
SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE(MAX(id), 1), MAX(id) IS NOT NULL) FROM users;
SELECT setval(pg_get_serial_sequence('vendor', 'id'), COALESCE(MAX(id), 1), MAX(id) IS NOT NULL) FROM vendor;
SELECT setval(pg_get_serial_sequence('device', 'id'), COALESCE(MAX(id), 1), MAX(id) IS NOT NULL) FROM device;
SELECT setval(pg_get_serial_sequence('log_activity', 'id'), COALESCE(MAX(id), 1), MAX(id) IS NOT NULL) FROM log_activity;
```

Jalankan SQL tersebut pada database/schema modul saat proses impor selesai dan aplikasi belum menerima penulisan. Sesuaikan `search_path` jika schema bukan `public`. Fungsi sequence dijelaskan pada [dokumentasi PostgreSQL](https://www.postgresql.org/docs/14/functions-sequence.html).

## Pengujian PostgreSQL

`composer test` tetap menggunakan SQLite in-memory agar tes cepat tidak membutuhkan server database. Suite yang sama bisa menguji koneksi modul pada PostgreSQL sungguhan, sementara database simulasi aplikasi induk tetap memakai SQLite terpisah.

Buat database pengujian khusus dengan nama berakhiran `_test`. Role pengujian harus dapat membuat schema di database tersebut. Tes membuat schema acak `sdm_test_*` untuk setiap kasus dan menghapus schema itu setelah selesai; tes tidak mengambil kredensial dari `.env` aplikasi.

PowerShell:

```powershell
$env:SDM_TEST_PGSQL_DATABASE = 'sugihart_device_manager_test'
$env:SDM_TEST_PGSQL_HOST = '127.0.0.1'
$env:SDM_TEST_PGSQL_PORT = '5432'
$env:SDM_TEST_PGSQL_USERNAME = 'postgres'
$env:SDM_TEST_PGSQL_PASSWORD = 'password_database_test'
php artisan test
```

Linux/macOS:

```sh
SDM_TEST_PGSQL_DATABASE=sugihart_device_manager_test \
SDM_TEST_PGSQL_HOST=127.0.0.1 \
SDM_TEST_PGSQL_PORT=5432 \
SDM_TEST_PGSQL_USERNAME=postgres \
SDM_TEST_PGSQL_PASSWORD=password_database_test \
php artisan test
```

Hapus variabel `SDM_TEST_PGSQL_*` dari shell setelah pengujian jika ingin kembali ke tes SQLite. Informasi koneksi PostgreSQL Laravel tersedia di [dokumentasi database Laravel](https://laravel.com/framework/docs/13.x/database).

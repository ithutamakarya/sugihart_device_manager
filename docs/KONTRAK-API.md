# Kontrak API lama

Prefix default: `/sugihart_device_manager/public`. Semua endpoint memakai `GET`, tanpa login admin. Token vendor/perangkat lama tetap digunakan.

| Path setelah prefix | Token | Respons |
| --- | --- | --- |
| `/api/get_data_cctv/{token}` | Vendor | Array perangkat `cctv` aktif milik vendor; urut `token`, lalu `name`; `/` pada `url_device` diganti `_` |
| `/api/get_kios/{token}` | Vendor | Array perangkat `kios-k` aktif milik vendor; urut `token`, lalu `name`; URL tidak diubah |
| `/api/get_device/{token}` | Vendor | Semua perangkat vendor termasuk nonaktif; tanpa penambahan urutan baru |
| `/api/get_render_kios/{token}` | Perangkat | HTML kios aktif bertipe `kios-k` |

API vendor mengembalikan HTTP 200 dan `{"response":"failed"}` jika token tidak ditemukan. Vendor tanpa perangkat menghasilkan `[]`. Status aktif vendor tidak ditambahkan sebagai filter karena kode lama tidak memakainya.

Field perangkat dipertahankan: `id`, `name`, `url_device`, `type`, `vendor_id`, `ip_access`, `is_active`, `token`, `created_at`, `updated_at`, serta kolom tambahan bila ada pada database lama. Respons memakai query builder agar timestamp tetap dalam format database, bukan format serialisasi tanggal Eloquent yang baru.

Token kios tidak valid/nonaktif/bertipe lain menghasilkan body `404` dengan HTTP 200, mengikuti cabang kegagalan kode lama. Bug query yang sebelumnya membuat semua token lolos telah diperbaiki. URL CCTV `null` menghasilkan string kosong agar kompatibel dengan perilaku `str_replace` PHP lama tanpa deprecation PHP 8.5.

API tidak membuka sesi atau mengirim cookie login. Tidak ada tambahan prefix `/api`, redirect ke URL baru, pagination, pembungkus `data`, atau kewajiban bearer token.

Perubahan halaman administrasi: hapus data kini memakai POST + CSRF dengan URL path yang sama, validasi input ditambahkan, akun nonaktif ditolak, percobaan login dibatasi, dan registrasi publik dinonaktifkan secara default. UI admin sudah menyesuaikan; perubahan tersebut tidak mengubah empat API perangkat di atas.

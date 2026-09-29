# LaporBoss

Aplikasi portal aspirasi siswa berbasis Laravel 13, disesuaikan dengan ERD dan referensi tampilan yang diberikan.

## Komponen pembelajaran yang diterapkan

- **View (Home Page)**: halaman `/` dengan tampilan portal aspirasi.
- **Register**: `/register`, menyimpan NIS, nama, dan rombel siswa.
- **Authentication/Login**: `/login` untuk siswa dan `/admin/login` untuk admin.
- **Authorization/Middleware**: route admin dilindungi middleware `admin` dan tidak dapat dibuka sebelum login admin.
- **CRUD Data Master tanpa relasi**: CRUD `kategori` pada `/admin/kategori`.

## Akun demo

Admin:
- Email: `admin@laporboss.test`
- Password: `admin123`

Siswa:
- NIS: `123456789` (login hanya menggunakan NIS, sesuai ERD)

## Menjalankan project

1. Pastikan PHP 8.3+, Composer, Node.js/NPM, dan SQLite PDO aktif.
2. Jalankan `composer install` jika dependency belum tersedia.
3. Salin `.env.example` menjadi `.env` jika belum ada, lalu jalankan `php artisan key:generate`.
4. Pastikan `DB_CONNECTION=sqlite` dan file `database/database.sqlite` tersedia.
5. Jalankan `php artisan migrate:fresh --seed`.
6. Jalankan `npm install`.
7. Jalankan `npm run build` atau gunakan `npm run dev` saat development.
8. Jalankan `php artisan serve`.

## Catatan implementasi

ERD yang diberikan memiliki tabel users, tb_aspirasi, tb_riwayat, kategori, dan admin. Untuk memenuhi komponen pembelajaran yang diminta, implementasi ini memprioritaskan fondasi yang dapat langsung didemonstrasikan: home page, registrasi/login, middleware admin, serta CRUD data master kategori. Struktur admin dan kategori sudah disiapkan untuk dikembangkan ke modul aspirasi/riwayat berikutnya.

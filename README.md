# Kopi Senja - User Frontend

Aplikasi frontend untuk pelanggan Kopi Senja. Melalui aplikasi ini, pelanggan dapat melihat menu, melakukan reservasi meja, memesan secara online, dan memberikan ulasan. Dibangun menggunakan CodeIgniter 4.

## 🚀 Fitur Utama
- **Lihat Menu**: Menampilkan menu kopi dan makanan beserta kategori dan harga.
- **Reservasi Meja**: Booking meja untuk waktu tertentu.
- **Pemesanan Online**: Memesan menu secara langsung.
- **Ulasan Pelanggan**: Memberikan rating dan komentar setelah kunjungan.

## 📋 Cara Install

1. **Clone repository ini**
   Pastikan Anda telah menginstal Git, kemudian clone ke folder web server Anda (contoh: `htdocs` untuk XAMPP atau `www` untuk WAMP).

2. **Install Dependensi dengan Composer**
   Buka terminal/command prompt di direktori project, lalu jalankan:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   - Copy file `env` menjadi `.env`.
   - Buka file `.env` dan ubah environment menjadi development:
     ```env
     CI_ENVIRONMENT = development
     ```
   - Konfigurasi koneksi database Anda di bagian `database.default` agar sama dengan database Admin:
     ```env
     database.default.hostname = localhost
     database.default.database = nama_database_kopi_senja
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```

4. **Jalankan Server Lokal**
   Gunakan server bawaan CodeIgniter untuk menjalankan aplikasi:
   ```bash
   php spark serve --port 8081
   ```
   *Catatan: Port diubah ke 8081 jika port 8080 sedang digunakan oleh aplikasi Admin.*
   
   Aplikasi dapat diakses melalui browser di alamat: `http://localhost:8081`

---
*Dibuat menggunakan CodeIgniter 4*

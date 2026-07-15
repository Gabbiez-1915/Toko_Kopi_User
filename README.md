# Toko Kopi - Aplikasi Pelanggan

Ini adalah aplikasi untuk pengguna/pelanggan Toko Kopi. Aplikasi ini digunakan pelanggan untuk melihat menu dan melakukan pemesanan/reservasi tempat.

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstall dan menjalankan aplikasi:

1. **Clone Repository**
   ```bash
   git clone https://github.com/Nardo4577/toko_kopi_user.git
   cd toko_kopi_user
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

## Konfigurasi `.env`

File `.env` tidak disertakan di GitHub karena berisi API Key dan konfigurasi database. Anda harus membuatnya secara manual.

- Salin file `.env.example` dan ubah namanya menjadi `.env`:
  ```bash
  cp .env.example .env
  ```
- Buka file `.env` dan atur konfigurasi database serta API yang digunakan (Midtrans & Google Calendar):
  ```env
  database.tests.database = toko_kopi
  database.tests.username = root
  database.tests.password = 
  
  MIDTRANS_SERVER_KEY="server_key_anda"
  MIDTRANS_CLIENT_KEY="client_key_anda"
  GOOGLE_CLIENT_ID="google_client_id_anda"
  GOOGLE_CLIENT_SECRET="google_client_secret_anda"
  ```

## Migrations dan Seeder

Pastikan Anda sudah memiliki database `toko_kopi` (bisa berbagi dengan admin). Jika Anda belum menjalankan seeder untuk user, Anda bisa menjalankannya:

```bash
php spark migrate --all
php spark db:seed App\\Database\\Seeds\\UserSeeder
```

## Akun Demo Pelanggan

Gunakan akun berikut untuk login sebagai pelanggan:
- **Username**: `budi_santoso` / **Email**: `budi@gmail.com`
- **Password**: `user123`

---

## Screenshot Fitur Utama

*(Ganti teks dan path gambar di bawah ini dengan screenshot aplikasi Anda yang sebenarnya)*

### Halaman Home / Menu
![Halaman Utama](user-home.png)

### Halaman Pemesanan / Reservasi
![Pemesanan](reservasi.png)

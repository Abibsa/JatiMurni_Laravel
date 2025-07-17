## Nama Proyek

**Jatimurni Database Laravel**

## Fitur Utama

- Manajemen Produk (CRUD produk, kategori)
- Manajemen Pengguna (CRUD user, status user)
- Manajemen Pesanan (order, order item, pembayaran)
- Ekspor & Impor data pengguna (Excel)
- Dashboard admin & pengguna
- Autentikasi dan otorisasi pengguna
- Riwayat transaksi dan detail pesanan

## Instruksi Instalasi

1. **Clone repository**
   ```
   git clone <url-repo-anda>
   cd jatimurni_database_laravel
   ```

2. **Install dependencies**
   ```
   composer install
   npm install
   ```

3. **Copy file environment**
   ```
   cp .env.example .env
   ```

4. **Generate application key**
   ```
   php artisan key:generate
   ```

5. **Konfigurasi database**  
   Edit file `.env` dan sesuaikan konfigurasi database Anda.

6. **Jalankan migrasi dan seeder**
   ```
   php artisan migrate --seed
   ```

7. **Jalankan aplikasi**
   ```
   php artisan serve
   ```

---

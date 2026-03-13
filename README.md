# 🪑 Jati Murni - E-Commerce Furniture & Home Decor

Jati Murni adalah platform e-commerce berbasis web modern yang dirancang khusus untuk toko furnitur dan dekorasi rumah. Sistem ini mencakup alur kerja lengkap mulai dari penjelajahan produk, sistem keranjang belanja multi-item, manajemen pesanan, hingga analitik mendalam bagi administrator. Mengutamakan estetika premium dan keandalan sistem melalui pengujian otomatis.

---

## 🚀 Fitur Utama

### 🛍️ Pengalaman Pelanggan (Customer)
- **Registrasi & Login Mandiri**: Sistem autentikasi lengkap dengan validasi keamanan.
- **Katalog Produk Premium**: Tampilan katalog elegan dengan filter kategori dan fitur pencarian.
- **Galeri Multi-Foto**: Lihat detail furnitur dari berbagai sudut dengan galeri foto interaktif.
- **Keranjang Belanja (Shopping Cart)**: Tambahkan banyak barang ke keranjang sebelum melakukan checkout final.
- **Multi-Item Checkout**: Pesan beberapa jenis produk sekaligus dalam satu transaksi.
- **Portal Unggah Bukti Bayar**: Pelanggan dapat mengunggah bukti transfer mandiri untuk diproses oleh admin.
- **Invoice Mandiri**: Cetak atau simpan invoice resmi pesanan langsung dari halaman transaksi.
- **Sistem Review & Rating**: Berikan ulasan bintang 1-5 dan komentar untuk produk yang telah dibeli (status Selesai).

### 🔧 Manajemen (Admin)
- **Dasbor Analitik Real-time**: Visualisasi total penjualan bulanan, statistik pelanggan, dan grafik tren penjualan 7 hari terakhir.
- **Manajemen Produk & Galeri**: Pengelolaan produk lengkap (CRUD) termasuk fitur upload hingga 5 foto galeri tambahan per produk.
- **Manajemen Pesanan & Sinkronisasi**: Lihat daftar pesanan masuk, ubah status (Pending -> Processing -> Shipped -> Completed), dan validasi pembayaran pelanggan.
- **Manajemen Kategori & Stok**: Kontrol stok otomatis yang berkurang setiap terjadi transaksi sukses.
- **Manajemen Pengguna**: Fitur ekspor/impor data pelanggan via Excel dan manajemen reset password.

### 🧪 Kualitas & Keamanan
- **Automated Testing**: Seluruh alur kritis (Cart, Checkout, Payment, Review) terlindungi oleh unit testing.
- **Input Validation**: Validasi data ketat pada setiap form input.
- **Access Control**: Pemisahan hak akses yang jelas antara Admin dan Customer menggunakan Middleware kustom.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend**: Blade Templating + Bootstrap 5 + Vanilla CSS (Aesthetics focused)
- **Database**: SQLite (Default Development) / MySQL (Compatible)
- **Icons**: FontAwesome 6
- **Exports**: Laravel Excel
- **Server**: PHP 8.2+

---

## 📦 Instalasi & Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan Jati Murni di mesin lokal Anda:

1. **Clone Repository**
   ```bash
   git clone https://github.com/Abibsa/JatiMurni_Laravel.git
   cd JatiMurni_Laravel
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   Pastikan folder `database/` memiliki file `database.sqlite` jika menggunakan driver sqlite.
   ```bash
   # Linux/macOS
   touch database/database.sqlite
   # Windows (PowerShell)
   New-Item database/database.sqlite
   
   php artisan migrate --seed
   ```

5. **Symlink Storage** (Penting untuk gambar produk)
   ```bash
   php artisan storage:link
   ```

6. **Running Automated Tests** (Opsional - Untuk Verifikasi)
   ```bash
   php artisan test tests/Feature/VerificationTest.php
   ```

7. **Jalankan Server**
   ```bash
   php artisan serve
   ```
   Akses di `http://127.0.0.1:8000`

---

## 🔐 Akun Demo (Default)

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@jatimurni.com | admin123 |
| **Customer** | customer@gmail.com | customer123 |

---

## 📂 Struktur Folder Penting

- `app/Http/Controllers`: Logika bisnis (Cart, Order, Product, Review).
- `app/Models`: Definisi model database dan relasinya.
- `resources/views/admin`: Antarmuka manajemen administrator.
- `resources/views/pengguna`: Antarmuka belanja untuk pelanggan.
- `database/migrations`: Skema tabel database (Orders, Reviews, ProductImages, dll).

---

## 📸 Preview Screenshot
*(Silakan ganti dengan screenshot aplikasi Anda untuk nilai maksimal)*

- **Dashboard Admin**: Analitas visual yang intuitif.
- **Katalog Produk**: Layout bersih dengan indikator rating.
- **Detail Transaksi**: Fitur unggah bukti bayar yang user-friendly.

---

## 📜 Lisensi
Proyek ini dikembangkan untuk keperluan tugas kuliah dan pembelajaran framework Laravel.

**Developed with ❤️ by Abibsa & Jati Murni Team**

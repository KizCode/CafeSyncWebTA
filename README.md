# CafeSync POS

Sistem Point of Sale (POS) untuk kafe/restoran berbasis web. Dibangun dengan Laravel 12, mendukung tiga peran pengguna (Administrator, Kasir, Gudang), antrian produksi, manajemen stok bahan baku, laporan pendapatan, dan antarmuka bilingual Indonesia / Inggris.

---

## Daftar Isi

1. [Persyaratan](#persyaratan)
2. [Instalasi](#instalasi)
3. [Akun Demo](#akun-demo)
4. [Login & Navigasi](#login--navigasi)
5. [Panduan per Role](#panduan-per-role)
   - [Kasir](#kasir)
   - [Administrator](#administrator)
   - [Gudang](#gudang)
6. [Fitur Umum](#fitur-umum)
7. [Perhitungan Transaksi](#perhitungan-transaksi)
8. [Deploy ke Hosting](#deploy-ke-hosting)
9. [Pengembangan](#pengembangan)
10. [Teknologi](#teknologi)
11. [Lisensi](#lisensi)

---

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer
- Node.js & npm (untuk asset frontend)
- Ekstensi PHP: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`
- Database: SQLite (default) atau MySQL/MariaDB

---

## Instalasi

### 1. Clone repository

```bash
git clone <url-repository>
cd CafeSync-WEB
```

### 2. Install dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Siapkan database

**SQLite (default):**

```bash
# Windows (PowerShell)
New-Item -Path "database\database.sqlite" -ItemType File -Force

# Linux / macOS
touch database/database.sqlite
```

**MySQL:** ubah pengaturan `DB_*` di file `.env`, lalu buat database kosong.

### 5. Migrasi & data awal

```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan aplikasi

```bash
php artisan serve
```

Buka browser: **http://127.0.0.1:8000** — Anda akan diarahkan ke halaman login.

Untuk development dengan hot-reload CSS/JS:

```bash
npm run dev
```

---

## Akun Demo

Setelah menjalankan seeder, gunakan akun berikut (password semua: **`password`**):

| Username  | Role          | Akses utama                          |
|-----------|---------------|--------------------------------------|
| `admin`   | Administrator | Dashboard admin, produk, laporan     |
| `kasir01` | Kasir         | POS, antrian, riwayat transaksi      |
| `gudang01`| Gudang        | Stok bahan baku & pergerakan stok    |

---

## Login & Navigasi

1. Buka alamat website → halaman **Login** muncul otomatis.
2. Masukkan **username** dan **password**.
3. Setelah login, Anda diarahkan ke halaman utama sesuai role.
4. Gunakan **sidebar** di kiri untuk berpindah menu.
5. Tombol **bahasa** (ID/EN) di header mengganti bahasa antarmuka.
6. Tombol **tema** (ikon bulan/matahari) mengganti mode terang/gelap.

---

## Panduan per Role

### Kasir

Menu utama Kasir: **Kasir**, **Antrian**, **Produk**, **Transaksi**, dan **Profil**.

#### Membuat transaksi

1. Buka menu **Kasir**.
2. Klik kategori di atas untuk memfilter produk, atau gunakan kotak **Cari produk**.
3. Klik produk untuk menambahkannya ke keranjang.
4. Atur jumlah item di keranjang (+ / −) atau hapus item yang tidak diperlukan.
5. *(Opsional)* Aktifkan **Diskon** — pilih persen (%) atau nominal (Rp).
6. *(Opsional)* Aktifkan **PPN 11%**.
7. Isi **nama pelanggan** untuk antrian (bisa pakai tombol acak).
8. Klik **Bayar** → modal pembayaran terbuka.
9. Pilih metode: **Tunai**, **QRIS**, atau **Debit**.
   - **Tunai:** masukkan uang diterima; gunakan tombol cepat (20rb, 50rb, 100rb, Uang Pas) jika perlu.
   - **QRIS / Debit:** nominal otomatis sesuai total.
10. Klik **Konfirmasi Pembayaran**.
11. **Popup struk** muncul — klik **Cetak** untuk mencetak, **Buka Antrian** untuk melihat antrian produksi, atau **Lanjut Transaksi** untuk kembali ke POS.

#### Antrian produksi

1. Buka menu **Antrian**.
2. Lihat pesanan yang sedang menunggu / diproses / selesai.
3. Seret kartu pesanan untuk mengubah urutan.
4. Ubah status pesanan (misalnya: Menunggu → Sedang dibuat → Selesai).
5. Klik nama pelanggan untuk mengedit jika perlu.

#### Riwayat transaksi & cetak ulang struk

1. Buka menu **Transaksi**.
2. Filter berdasarkan **tanggal mulai** dan **tanggal akhir** jika diperlukan.
3. Klik nomor invoice, tombol **Struk**, atau ikon **Cetak** → struk muncul di **popup**.
4. Klik **Cetak** di dalam popup untuk mencetak struk.

#### Kelola produk (Kasir)

Menu **Produk** memungkinkan Kasir melihat dan mengelola daftar produk serta resep bahan baku yang dipakai per produk.

---

### Administrator

Menu utama Admin: **Dashboard**, **Produk & Resep**, **Transaksi**, **Laporan**, **Pengaturan Antrian**, dan **Profil**.

#### Dashboard

Ringkasan aktivitas bisnis: pendapatan, transaksi, dan statistik penting.

#### Produk & Resep

1. Buka **Produk & Resep**.
2. Tambah, ubah, atau hapus produk.
3. Atur harga, kategori, stok, dan gambar produk.
4. Kelola **resep** — tentukan bahan baku dan jumlah per produk.

#### Riwayat transaksi

Sama seperti Kasir, plus tombol **Export PDF** saat filter tanggal aktif untuk mengunduh daftar transaksi.

#### Laporan pendapatan

1. Buka menu **Laporan**.
2. Pilih rentang tanggal.
3. Lihat ringkasan: total pendapatan, pengeluaran, laba kotor, jumlah transaksi.
4. Grafik pendapatan harian dan tabel produk terlaris ditampilkan di halaman yang sama.
5. Unduh atau pratinjau laporan dalam format PDF.

#### Pengaturan antrian

Atur status produksi (nama, warna, ikon) dan daftar nama acak pelanggan di antrian.

---

### Gudang

Menu utama Gudang: **Dashboard**, **Bahan Baku**, dan **Profil**.

#### Kelola bahan baku

1. Buka **Bahan Baku**.
2. Tambah bahan baru (nama, satuan, stok minimum).
3. Klik bahan untuk mengubah datanya.

#### Stok masuk & penyesuaian

- **Stok masuk:** catat penerimaan bahan dari supplier.
- **Sesuaikan stok:** koreksi stok fisik (rusak, hilang, selisih opname).
- **Riwayat:** lihat semua pergerakan stok per bahan.

Stok bahan otomatis berkurang saat produk terjual (sesuai resep produk).

---

## Fitur Umum

| Fitur | Keterangan |
|-------|------------|
| Multi-role | Administrator, Kasir, Gudang dengan akses terpisah |
| POS realtime | Keranjang, diskon, PPN, dan pembayaran tanpa reload halaman |
| Popup struk | Struk muncul di modal setelah bayar & dari riwayat transaksi |
| Antrian produksi | Drag-and-drop, ubah status & nama pelanggan |
| Resep produk | Kaitkan bahan baku ke produk untuk stok otomatis |
| Laporan & PDF | Laporan pendapatan dan export struk/transaksi ke PDF |
| Bilingual | Bahasa Indonesia & Inggris |
| Tema gelap | Mode terang / gelap disimpan di browser |
| Profil | Ubah nama, email, telepon; keamanan akun & ganti password |

---

## Perhitungan Transaksi

```
Subtotal      = jumlah (harga × qty) semua item
Diskon        = subtotal × (persen/100)  ATAU  nilai nominal (maks = subtotal)
Setelah diskon = subtotal − diskon
PPN           = setelah diskon × 11%  (jika diaktifkan)
Grand Total   = setelah diskon + PPN
```

**Format invoice:** `INV-YYYYMMDD-XXXX`  
- `YYYYMMDD` = tanggal transaksi  
- `XXXX` = nomor urut harian (otomatis)

---

## Deploy ke Hosting

Aplikasi ini membutuhkan **hosting PHP** (bukan GitHub Pages).

### Document root = folder `public/` (disarankan)

Arahkan document root hosting ke folder `public/`. Tidak perlu file tambahan.

### Document root = folder proyek

Jika hosting tidak bisa mengubah document root, gunakan file yang sudah disediakan di root repo:

- `index.php` — meneruskan request ke Laravel
- `.htaccess` — mengarahkan request ke folder `public/` (Apache)

Langkah deploy umum:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

Pastikan folder `storage/` dan `bootstrap/cache/` bisa ditulis web server.

---

## Pengembangan

```bash
# Server lokal
php artisan serve

# Asset hot-reload
npm run dev

# Build production
npm run build

# Reset database + data demo
php artisan migrate:fresh --seed
```

---

## Teknologi

| Lapisan | Stack |
|---------|-------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Bootstrap 5, jQuery |
| Database | SQLite (default) / MySQL |
| PDF | DomPDF |
| Grafik | Chart.js |
| Ikon | Font Awesome 6 |

---

## Lisensi

MIT License

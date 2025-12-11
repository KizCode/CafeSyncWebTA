# Sistem Kasir Laravel

Aplikasi kasir sederhana menggunakan Laravel 12 dengan Blade + jQuery.

## Fitur Utama

### 1. Halaman Kasir
- Tampilan produk berdasarkan kategori
- Klik produk untuk menambah ke keranjang
- Perhitungan realtime dengan jQuery
- Diskon (persen atau nominal) yang bisa di-toggle
- PPN 11% yang bisa diaktifkan/nonaktifkan
- Edit quantity dan hapus item di keranjang

### 2. Halaman Pembayaran
- 3 metode pembayaran: Tunai, QRIS, Debit
- Input uang tunai dengan perhitungan kembalian otomatis
- Tombol quick amount (20rb, 50rb, 100rb, Uang Pas)
- Validasi pembayaran

### 3. Struk Pembayaran
- Tampilan struk setelah pembayaran berhasil
- Detail lengkap transaksi
- Fitur cetak struk
- Download PDF
- Tombol transaksi baru

### 4. Riwayat Transaksi
- List semua transaksi dengan pagination
- Filter berdasarkan tanggal
- Detail transaksi
- Status pembayaran (Lunas/Belum Lunas)
- Cetak ulang struk PDF

### 5. Laporan Pendapatan
- Filter berdasarkan rentang tanggal
- Summary cards:
  - Total Pendapatan
  - Total Pengeluaran
  - Laba Kotor
  - Total Transaksi & Item Terjual
- Grafik pendapatan harian (Chart.js)
- Tabel produk terlaris

## Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd cafesync-web
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Buat database SQLite
```bash
New-Item -Path "database\database.sqlite" -ItemType File -Force
```

5. Jalankan migrasi dan seeder
```bash
php artisan migrate:fresh --seed
```

6. Jalankan server
```bash
php artisan serve
```

7. Akses aplikasi di browser
```
http://127.0.0.1:8000
```

## Database Structure

### Categories
- id
- name
- description
- timestamps

### Products
- id
- name
- price
- category_id
- stock
- description
- image
- timestamps

### Transactions
- id
- invoice_number (unique)
- subtotal
- discount_amount
- discount_type (percent/nominal)
- discount_value
- tax_amount
- is_tax_enabled
- grand_total
- payment_method (tunai/qris/debit)
- paid_amount
- change_amount
- status (lunas/belum_lunas)
- timestamps

### Transaction Items
- id
- transaction_id
- product_id
- quantity
- unit_price
- total_price
- timestamps

### Expenses
- id
- description
- amount
- expense_date
- category
- notes
- timestamps

## Data Seeder

Aplikasi sudah dilengkapi dengan data dummy:
- 4 Kategori (Makanan, Minuman, Snack, Dessert)
- 17 Produk
- 3 Data pengeluaran

## Teknologi

- **Backend**: Laravel 12
- **Frontend**: Blade Templates, jQuery, Bootstrap 5
- **Database**: SQLite
- **Charts**: Chart.js
- **Icons**: Font Awesome

## Fitur Teknis

### Perhitungan Transaksi
1. Subtotal = Sum(harga × qty)
2. Diskon = Subtotal × (diskon_persen/100) atau nilai_nominal
3. Setelah Diskon = Subtotal - Diskon
4. PPN = Setelah Diskon × 11%
5. Grand Total = Setelah Diskon + PPN

### Nomor Invoice
Format: `INV-YYYYMMDD-XXXX`
- YYYYMMDD = Tanggal transaksi
- XXXX = Nomor urut harian (auto increment)

### Stok Produk
- Stok otomatis berkurang saat transaksi berhasil
- Menggunakan database transaction untuk data consistency

## Routes

```php
GET  /                          -> Redirect ke kasir
GET  /cashier                   -> Halaman kasir utama
GET  /cashier/products          -> Get data produk (AJAX)
GET  /payment                   -> Halaman pembayaran
POST /payment/process           -> Proses pembayaran
GET  /receipt/{id}              -> Halaman struk
GET  /transactions              -> Riwayat transaksi
GET  /transactions/{id}         -> Detail transaksi
GET  /transactions/{id}/pdf     -> Struk PDF
GET  /reports                   -> Laporan pendapatan
```

## Tips Penggunaan

1. **Diskon**: Toggle switch untuk mengaktifkan, bisa pilih persen atau nominal
2. **PPN**: Toggle switch untuk mengaktifkan pajak 11%
3. **Tunai**: Gunakan tombol quick amount untuk input cepat
4. **PDF**: Akan otomatis trigger print dialog saat dibuka
5. **Filter Laporan**: Gunakan rentang tanggal untuk melihat performa

## Development

Untuk development dengan hot reload:
```bash
npm run dev
```

## License

MIT License

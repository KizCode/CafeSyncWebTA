# Panduan Testing Sistem Kasir

## 1. Testing Halaman Kasir

### URL: http://127.0.0.1:8000/cashier

**Test Case:**
1. ✅ Halaman menampilkan produk dari semua kategori
2. ✅ Klik tab kategori untuk filter produk
3. ✅ Klik produk → item masuk keranjang
4. ✅ Klik produk yang sama → quantity bertambah
5. ✅ Edit quantity di keranjang (+ / -)
6. ✅ Hapus item dari keranjang (icon X)
7. ✅ Aktifkan diskon → input nilai (coba persen dan nominal)
8. ✅ Aktifkan PPN 11% → total bertambah 11%
9. ✅ Total dihitung realtime saat ada perubahan
10. ✅ Tombol "Bayar" disabled saat keranjang kosong

**Hasil yang Diharapkan:**
- Semua perhitungan benar
- UI responsif dan interaktif
- Tidak ada error di console

---

## 2. Testing Halaman Pembayaran

### URL: http://127.0.0.1:8000/payment (via tombol Bayar)

**Test Case - Metode Tunai:**
1. ✅ Pilih metode "Tunai"
2. ✅ Input nominal uang (coba kurang dari total → tombol disabled)
3. ✅ Input nominal pas/lebih → kembalian dihitung otomatis
4. ✅ Klik tombol quick amount (20rb, 50rb, 100rb)
5. ✅ Klik "Uang Pas" → nominal = total
6. ✅ Submit pembayaran

**Test Case - Metode QRIS/Debit:**
1. ✅ Pilih metode "QRIS" atau "Debit"
2. ✅ Section tunai tersembunyi
3. ✅ Tombol konfirmasi langsung aktif
4. ✅ Submit pembayaran

**Hasil yang Diharapkan:**
- Validasi bekerja dengan baik
- Kembalian dihitung dengan benar
- Redirect ke halaman struk setelah berhasil

---

## 3. Testing Halaman Struk

### URL: Otomatis setelah pembayaran berhasil

**Test Case:**
1. ✅ Menampilkan nomor invoice (format: INV-YYYYMMDD-XXXX)
2. ✅ Menampilkan tanggal & waktu transaksi
3. ✅ Detail item lengkap dengan qty dan harga
4. ✅ Subtotal, diskon, pajak, grand total sesuai
5. ✅ Metode pembayaran ditampilkan
6. ✅ Dibayar dan kembalian (untuk tunai)
7. ✅ Klik "Cetak Struk" → trigger print dialog
8. ✅ Klik "Download PDF" → buka struk di tab baru
9. ✅ Klik "Transaksi Baru" → kembali ke kasir
10. ✅ Klik "Lihat Riwayat" → ke halaman riwayat

**Hasil yang Diharapkan:**
- Semua data akurat
- Print berfungsi
- PDF dapat dibuka dan di-print

---

## 4. Testing Halaman Riwayat

### URL: http://127.0.0.1:8000/transactions

**Test Case:**
1. ✅ Menampilkan semua transaksi (terbaru di atas)
2. ✅ Filter berdasarkan tanggal mulai & akhir
3. ✅ Klik "Filter" → data terfilter
4. ✅ Klik "Reset" → kembali ke semua data
5. ✅ Klik icon mata → detail transaksi
6. ✅ Klik icon PDF → buka struk PDF
7. ✅ Pagination berfungsi (jika data > 20)

**Hasil yang Diharapkan:**
- Data tersortir dengan benar
- Filter bekerja akurat
- Tombol aksi berfungsi semua

---

## 5. Testing Halaman Detail Transaksi

### URL: http://127.0.0.1:8000/transactions/{id}

**Test Case:**
1. ✅ Informasi transaksi lengkap
2. ✅ Tabel item detail dengan qty dan harga
3. ✅ Summary perhitungan (subtotal, diskon, pajak, total)
4. ✅ Dibayar dan kembalian ditampilkan
5. ✅ Klik "Kembali" → ke riwayat
6. ✅ Klik "Cetak PDF" → buka PDF

**Hasil yang Diharapkan:**
- Semua data sesuai dengan transaksi
- Navigasi lancar

---

## 6. Testing Halaman Laporan

### URL: http://127.0.0.1:8000/reports

**Test Case:**
1. ✅ Default menampilkan bulan berjalan
2. ✅ Input tanggal mulai & akhir → klik "Tampilkan Laporan"
3. ✅ Summary Cards menampilkan:
   - Total Pendapatan (dari transaksi)
   - Total Pengeluaran (dari tabel expenses)
   - Laba Kotor (pendapatan - pengeluaran)
   - Total Transaksi & Item Terjual
4. ✅ Grafik pendapatan harian ditampilkan
5. ✅ Hover grafik → tooltip muncul dengan format Rupiah
6. ✅ Tabel produk terlaris menampilkan top 10

**Hasil yang Diharapkan:**
- Perhitungan akurat
- Grafik render dengan baik
- Data produk terlaris benar

---

## 7. Testing Stok Produk

**Test Case:**
1. ✅ Cek stok awal produk di database
2. ✅ Lakukan transaksi beberapa item
3. ✅ Cek database → stok berkurang sesuai qty
4. ✅ Lakukan transaksi lagi → stok terus berkurang

**Hasil yang Diharapkan:**
- Stok otomatis ter-update
- Tidak ada duplikasi pengurangan stok

---

## 8. Testing PDF Struk

### URL: http://127.0.0.1:8000/transactions/{id}/pdf

**Test Case:**
1. ✅ Halaman PDF terbuka di tab baru
2. ✅ Auto trigger print dialog
3. ✅ Format struk rapi (mirip struk kasir)
4. ✅ Semua informasi lengkap dan akurat
5. ✅ Print preview bagus
6. ✅ Bisa di-print atau save as PDF

**Hasil yang Diharapkan:**
- PDF format struk kasir sederhana
- Semua data terbaca jelas
- Print-ready

---

## 9. Testing Responsivitas

**Test Case:**
1. ✅ Buka di desktop (1920x1080)
2. ✅ Buka di tablet (768x1024)
3. ✅ Buka di mobile (375x667)
4. ✅ Test semua halaman di berbagai ukuran

**Hasil yang Diharapkan:**
- UI tetap rapi di semua ukuran
- Tidak ada element terpotong
- Fungsi tetap bekerja

---

## 10. Testing Edge Cases

**Test Case:**
1. ✅ Keranjang kosong → tombol bayar disabled
2. ✅ Uang kurang → validasi muncul
3. ✅ Diskon 100% → total jadi 0 (masih bisa transaksi)
4. ✅ Diskon lebih dari subtotal → handled
5. ✅ Tanggal filter terbalik → tetap menampilkan data
6. ✅ Akses /payment langsung (tanpa cart) → redirect/error gracefully

**Hasil yang Diharapkan:**
- Tidak ada crash/error
- Validasi bekerja dengan baik
- Error handling tepat

---

## Quick Test Scenario

### Skenario Lengkap (5 menit):

1. **Buat Transaksi**
   - Buka kasir
   - Tambah 3 produk berbeda
   - Aktifkan diskon 10%
   - Aktifkan PPN
   - Klik Bayar

2. **Pembayaran**
   - Pilih Tunai
   - Input 100.000
   - Submit

3. **Struk**
   - Cek detail benar
   - Klik Download PDF
   - Tutup PDF

4. **Transaksi Baru**
   - Klik "Transaksi Baru"
   - Tambah produk
   - Pilih QRIS
   - Submit

5. **Riwayat**
   - Buka riwayat
   - Filter hari ini
   - Buka detail transaksi pertama

6. **Laporan**
   - Buka laporan
   - Cek summary cards
   - Lihat grafik
   - Cek produk terlaris

**Expected Time:** 5-7 menit
**Expected Result:** Semua fitur berjalan lancar tanpa error

---

## Database Verification

Gunakan SQLite viewer atau Artisan tinker untuk verifikasi:

```bash
php artisan tinker
```

```php
// Cek total transaksi
\App\Models\Transaction::count();

// Cek transaksi terakhir
\App\Models\Transaction::latest()->first();

// Cek stok produk ID 1
\App\Models\Product::find(1)->stock;

// Cek total pendapatan hari ini
\App\Models\Transaction::whereDate('created_at', today())->sum('grand_total');
```

---

## Troubleshooting

### Issue: Keranjang kosong setelah redirect payment
**Solution:** Pastikan browser support sessionStorage, coba clear cache

### Issue: PDF tidak auto print
**Solution:** Pastikan popup blocker tidak aktif

### Issue: Grafik tidak muncul
**Solution:** Cek console error, pastikan Chart.js loaded

### Issue: Stok tidak berkurang
**Solution:** Cek database transaction di TransactionController

---

## Checklist Final

- [ ] Semua halaman bisa diakses
- [ ] Tidak ada error di console browser
- [ ] Tidak ada error di Laravel log
- [ ] Database ter-update dengan benar
- [ ] PDF bisa dibuka dan di-print
- [ ] Grafik render dengan baik
- [ ] Responsif di mobile
- [ ] Perhitungan matematika akurat
- [ ] Form validation bekerja
- [ ] Navigation flow lancar

---

**Happy Testing! 🎉**

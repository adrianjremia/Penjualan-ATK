# PENJELASAN DETAIL FITUR BARU - BRD v2.0

---

## FITUR #10: RIWAYAT TRANSAKSI - ADMIN

### Apa itu?
Halaman untuk melihat dan mengelola semua transaksi penjualan yang pernah dilakukan. Admin bisa melihat detail transaksi dan melakukan koreksi jika ada kesalahan pencatatan.

### Untuk siapa?
**Admin** - User yang mengelola transaksi dan data barang

### Lokasi
- URL: `/admin/riwayat-transaksi`
- Controller: `TransaksiController.php`
- View: `resources/views/admin/riwayatTransaksi/index.blade.php`
- Model: `Transaksi.php`, `DetailTransaksi.php`

### Fitur yang bisa dilakukan:

#### 1. **VIEW / LIHAT DAFTAR TRANSAKSI**
- Tampilan tabel berisi semua transaksi
- Kolom: ID Transaksi, Tanggal, Jumlah Item, Total Harga, Aksi
- Filter berdasarkan:
  - Tanggal mulai & tanggal akhir (range picker)
  - Bisa lihat transaksi dalam periode tertentu
- Pagination otomatis

#### 2. **EDIT TRANSAKSI**
- Admin klik tombol "Edit" di salah satu transaksi
- Halaman edit membuka form dengan detail transaksi
- Admin bisa ubah **qty (jumlah item)** untuk setiap barang
- Sistem **otomatis hitung**:
  - Delta qty (selisih qty lama vs baru) → untuk adjust stok
  - Sesuaikan stok barang (increment/decrement)
  - Hitung total harga baru
  - Update data transaksi di database
  - Log perubahan ke Activity Log
- Redirect ke halaman transaksi dengan pesan "Update sukses"
- **Fitur keamanan**:
  - Validate stok agar tidak deficit
  - Database transaction agar atomicity (semua berhasil atau semua batal)
  - Activity log untuk audit trail

#### 3. **LIHAT DETAIL & INVOICE**
- Admin klik tombol "Detail"
- Tampilkan detail lengkap transaksi (item, qty, harga satuan, subtotal)
- Bisa lihat/unduh invoice

### PERBEDAAN ADMIN vs PEMILIK:
**Admin TIDAK BISA DELETE** - Hanya bisa view & edit untuk koreksi

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `index()`, `editInvoice()`, `update()`
- View: index.blade.php, edit.blade.php
- Routes: GET/PUT riwayat-transaksi

---

## FITUR #11: RIWAYAT TRANSAKSI - PEMILIK

### Apa itu?
Fitur yang sama seperti Admin, tapi untuk Pemilik dengan tambahan fungsi **DELETE** untuk menghapus transaksi.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin monitoring dan mengelola transaksi lengkap

### Lokasi
- URL: `/pemilik/riwayat-transaksi`
- Controller: `RiwayatTransaksiController.php`
- View: `resources/views/owner/riwayat-transaksi/index.blade.php`
- Model: `Transaksi.php`, `DetailTransaksi.php`

### Fitur yang bisa dilakukan:

#### 1. **VIEW / LIHAT DAFTAR TRANSAKSI**
- Sama seperti admin
- Tabel semua transaksi dengan filter tanggal
- Pagination

#### 2. **EDIT TRANSAKSI**
- Sama seperti admin
- Bisa ubah qty
- Auto-adjust stok & hitung total baru
- Log perubahan

#### 3. **DELETE / HAPUS TRANSAKSI**
- Pemilik klik tombol "Delete"
- Sistem akan:
  1. Load detail transaksi
  2. **Loop setiap item** → increment stok barang (refund)
  3. Hapus detail_transaksi
  4. Hapus transaksi
  5. Log penghapusan ke Activity Log
  6. Commit transaction
  7. Redirect dengan pesan "Hapus sukses"
- **Fitur keamanan**:
  - Database transaction untuk atomicity
  - Automatic stok refund (tidak perlu manual)
  - Activity log untuk audit

#### 4. **LIHAT DETAIL & INVOICE**
- Sama seperti admin

### PERBEDAAN ADMIN vs PEMILIK:
**Pemilik BISA DELETE** - View, edit, dan hapus transaksi

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `index()`, `edit()`, `update()`, `destroy()`
- View: index.blade.php, edit.blade.php
- Routes: GET/PUT/DELETE riwayat-transaksi

---

## FITUR #12: RECORD AKTIVITAS

### Apa itu?
Sistem pencatatan otomatis semua aktivitas yang terjadi di sistem. Untuk keperluan audit trail dan transparansi. Setiap action penting (create, update, delete barang/transaksi) akan dicatat otomatis.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin monitoring aktivitas sistem

### Lokasi
- URL: `/pemilik/record-aktivitas`
- Controller: `ActivityLogController.php`
- Service: `ActivityLogService.php`
- View: `resources/views/owner/record-aktivitas/index.blade.php`
- Model: `ActivityLog.php`

### Fitur yang bisa dilakukan:

#### 1. **VIEW / LIHAT DAFTAR AKTIVITAS**
- Tampilan tabel berisi semua record aktivitas
- Kolom: No, User, Action, Description, Tanggal, Jam
- Pagination (50 per halaman)

#### 2. **FILTER AKTIVITAS**
Pemilik bisa filter berdasarkan:
- **User**: Pilih user mana yang aktivitasnya mau dilihat
- **Action**: Tipe action (Create, Update, Delete, Login, dll)
- **Tanggal**: Range picker untuk periode tertentu
- **Search**: Cari dalam description (bebas keyword)
- Kombinasi filter sekaligus untuk analisis lebih detail

#### 3. **RECORD OTOMATIS**
Sistem akan otomatis catat:
- Kapan user login
- Kapan tambah barang
- Kapan ubah barang
- Kapan hapus barang
- Kapan catat transaksi
- Kapan edit transaksi
- Kapan hapus transaksi
- Dan aktivitas penting lainnya

Format record:
```
User: Admin
Action: Update
Description: Memperbarui Transaksi ID T870 (qty: 2 → 3, total: Rp 2.275.000 → Rp 3.412.500)
Tanggal: 2026-07-04 20:18:39
```

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `index()`
- Service: ActivityLogService (logCreate, logUpdate, logDelete, logLogin)
- View: index.blade.php dengan filter form
- Database: activity_logs table dengan migration

### Keuntungan:
- Transparansi penuh → siapa yang ubah data
- Audit trail → untuk compliance & keamanan
- Debugging easier → bisa track error dari activity log
- Investigasi fraud → bisa lihat siapa yang delete/ubah data

---

## FITUR #13: LAPORAN PENJUALAN (ENHANCED)

### Apa itu?
Laporan penjualan yang menampilkan data penjualan dalam periode tertentu dengan visualisasi grafik dan statistik detail.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin monitor penjualan harian

### Lokasi
- URL: `/pemilik/laporan-penjualan`
- Controller: `LaporanController.php` method `laporanPenjualan()`
- View: `resources/views/owner/laporan/penjualan.blade.php`

### Fitur yang bisa dilakukan:

#### 1. **FILTER PERIODE TANGGAL**
- Input "Tanggal Mulai" dan "Tanggal Akhir"
- Bisa lihat penjualan dalam range periode tertentu
- Default: bulan ini

#### 2. **TAMPILAN GRAFIK**
- Grafik garis/kolom menampilkan penjualan harian
- X-axis: Tanggal
- Y-axis: Total penjualan (Rp)
- Bisa lihat trend penjualan naik/turun

#### 3. **STATISTIK SUMMARY**
Ditampilkan di atas halaman:
- **Total Transaksi**: Berapa banyak transaksi dalam periode
- **Total Penjualan**: Total Rp dari semua transaksi
- **Rata-rata per Hari**: Total penjualan ÷ jumlah hari

#### 4. **TABEL DETAIL HARIAN**
- Breakdown penjualan per hari
- Kolom: Tanggal, Jumlah Transaksi, Jumlah Item Terjual, Total Penjualan
- Bisa lihat detail dari hari mana yang paling ramai

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `laporanPenjualan()`
- Query: Get transaksi dengan filter tanggal, aggregate data per hari
- View: Chart display + statistik + tabel detail

---

## FITUR #14: LAPORAN LABA RUGI (ENHANCED)

### Apa itu?
Laporan keuangan yang menunjukkan pendapatan, modal (cost of goods), dan profit (laba). Membantu pemilik memahami seberapa profitable bisnis.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin analisis keuangan & profitabilitas

### Lokasi
- URL: `/pemilik/laporan-laba-rugi`
- Controller: `LaporanController.php` method `laporanLabaRugi()`
- View: `resources/views/owner/laporan/laba-rugi.blade.php`

### Fitur yang bisa dilakukan:

#### 1. **FILTER PERIODE TANGGAL**
- Input "Tanggal Mulai" dan "Tanggal Akhir"
- Lihat profit dalam periode tertentu

#### 2. **BREAKDOWN HARIAN**
Tabel dengan kolom per hari:
- **Tanggal**: Hari yang dilaporkan
- **Pendapatan**: Total harga jual semua item terjual
- **Modal/Cost**: Total harga beli semua item terjual
- **Laba**: Pendapatan - Modal = Profit
- **Margin %**: (Laba / Pendapatan) × 100%

#### 3. **SUMMARY TOTAL**
Di bawah tabel:
- **Total Pendapatan**: Jumlah semua pendapatan periode
- **Total Modal**: Jumlah semua cost periode
- **Total Laba**: Jumlah semua profit
- **Margin Keuntungan %**: Rata-rata profit margin periode

#### 4. **ANALISIS PROFIT**
Pemilik bisa:
- Lihat hari mana yang paling profitable
- Lihat trend margin profit (naik/turun)
- Identifikasi produk dengan margin tinggi/rendah

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `laporanLabaRugi()`
- Query: Get detail_transaksi, join dengan barang (ambil harga_beli & harga_jual)
- Aggregate: Sum per tanggal, hitung profit & margin
- View: Tabel breakdown + summary total

---

## FITUR #15: LAPORAN INVENTARIS (NEW)

### Apa itu?
Laporan stok barang yang menampilkan daftar semua barang dengan informasi stok terkini, best-seller products, dan revenue per barang.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin monitor stok & performa penjualan per barang

### Lokasi
- URL: `/pemilik/informasi-stok`
- Controller: `LaporanController.php` method `informasiStok()`
- View: `resources/views/owner/laporan/stok.blade.php`

### Fitur yang bisa dilakukan:

#### 1. **FILTER BARANG & TANGGAL**
- Search input untuk cari barang berdasarkan nama
- Filter periode tanggal (untuk analisis penjualan periode tertentu)
- Bisa kombinasi keduanya

#### 2. **DAFTAR SEMUA BARANG**
Tabel dengan kolom:
- **No. Barang**: ID/Code
- **Nama Barang**: Nama produk
- **Kategori**: Jenis kategori
- **Stok Terkini**: Qty stok sekarang
- **Harga Satuan**: Harga jual
- **Total Nilai**: Stok × Harga satuan
- **Status**: Critical / Medium / Safe (berdasarkan stok)

#### 3. **TOP 5 PRODUK TERLARIS**
Di atas tabel, display top 5 produk:
- **Nama Produk**: Nama barang
- **Qty Terjual**: Berapa unit terjual dalam periode
- **Revenue**: Total penjualan barang
- **Margin**: Profit per unit × qty terjual

Contoh:
| Produk | Qty Terjual | Revenue | Margin |
|--------|-----------|---------|--------|
| Pensil HB | 150 | Rp 450.000 | Rp 100.000 |
| Buku Tulis | 120 | Rp 2.400.000 | Rp 600.000 |
| Pena Ballpoint | 200 | Rp 1.000.000 | Rp 200.000 |

#### 4. **SUMMARY STATISTIK**
- Total Produk: Berapa jenis barang
- Total Stok: Total unit barang yang ada
- Total Nilai Stok: Total Rp nilai stok
- Total Revenue (periode): Total penjualan

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `informasiStok()`
- Query: Get semua barang, join dengan detail_transaksi (hitung qty terjual & revenue)
- Top 5: Order by qty_terjual DESC limit 5
- View: Summary + Top 5 table + Full inventory table

---

## FITUR #16: PREDIKSI PENJUALAN / FORECASTING (NEW)

### Apa itu?
Sistem prediksi penjualan menggunakan algoritma statistik untuk forecast (ramalan) penjualan minggu depan. Membantu pemilik planning stok lebih baik.

### Untuk siapa?
**Pemilik / Owner** - User yang ingin planning stok berdasarkan prediksi penjualan

### Lokasi
- URL: `/pemilik/forecasting`
- Controller: `LaporanController.php` method `forecasting()`
- Service: `ForecastingService.php`
- View: `resources/views/owner/laporan/forecasting.blade.php`

### Fitur yang bisa dilakukan:

#### 1. **ALGORITMA FORECASTING**
- Metode: **Single Exponential Smoothing (SES)**
- Alpha (smoothing factor): 0.3
- Input: Sales data per minggu tahun ini
- Output: Forecast minggu depan

Cara kerja simple:
```
Forecast = Alpha × (Penjualan Terakhir) + (1 - Alpha) × (Forecast Terakhir)
```

#### 2. **FORECAST PER PRODUK**
Untuk setiap barang, tampilkan:
- **Nama Produk**: Produk apa
- **Penjualan Minggu Ini**: Actual qty minggu lalu
- **Forecast Minggu Depan**: Prediksi qty minggu depan
- **Trend %**: Persentase perubahan (naik/turun berapa %)
- **Status Stok**:
  - 🔴 **Critical**: Stok rendah, harus reorder urgent
  - 🟡 **Medium**: Stok sedang, monitoring
  - 🟢 **Safe**: Stok aman, tidak perlu urgent

#### 3. **FILTER PRODUK**
- Dropdown untuk pilih produk spesifik
- Lihat detail forecast per produk
- Historical data breakdown per minggu

#### 4. **SUMMARY OVERVIEW**
Di atas:
- Total produk critical → reorder prioritas
- Total produk medium → monitor
- Total produk safe → OK
- Overall demand trend (naik/turun)

#### 5. **VISUALISASI**
- Grafik trend penjualan mingguan per produk
- Garis forecast minggu depan
- Bisa lihat pola/seasonal pattern

### Contoh Penggunaan:
```
Produk: Pensil HB
- Minggu 1: 50 unit
- Minggu 2: 55 unit
- Minggu 3: 60 unit
- Minggu 4: 62 unit
→ Forecast Minggu 5: 63 unit (trend naik)
→ Status: Safe (stok 100 > forecast 63)

Produk: Pena Tinta
- Minggu 1: 100 unit
- Minggu 2: 80 unit
- Minggu 3: 70 unit
- Minggu 4: 60 unit
→ Forecast Minggu 5: 54 unit (trend turun)
→ Status: Critical (stok 30 < forecast 54, kurang 24 unit)
→ ACTION: Reorder urgent!
```

### Implementasi Status
✅ **SUDAH SELESAI & BERJALAN**
- Controller method: `forecasting()`
- Service method: `calculateForecast()`, `getHistoricalData()`
- Algorithm: SES dengan Alpha 0.3
- View: Overview + Per-product forecast + Historical chart

---

## PERBANDINGAN FITUR LAMA vs BARU

### FITUR ORIGINAL (9):
1. Login
2. Kelola Barang
3. Transaksi Penjualan
4. Update Stok Otomatis
5. Invoice
6. Laporan Penjualan (Basic)
7. Laporan Laba Rugi (Basic)
8. Informasi Stok (Basic)
9. Dashboard

### FITUR TAMBAHAN (6):
10. Riwayat Transaksi - Admin (Edit only)
11. Riwayat Transaksi - Pemilik (Edit + Delete)
12. Record Aktivitas (Audit Trail)
13. Laporan Penjualan (Enhanced - Grafik, Stats, Detail Harian)
14. Laporan Laba Rugi (Enhanced - Breakdown Profit, Margin Analysis)
15. Laporan Inventaris (Top 5 Best-Seller, Revenue Analysis)
16. Prediksi Penjualan (Forecasting - Planning Stok)

### YANG ENHANCED:
- Laporan Penjualan: Dari basic → ada grafik + statistik
- Laporan Laba Rugi: Dari basic → ada breakdown profit margin
- Informasi Stok: Dari basic → ada top 5 + revenue per barang

---

## RINGKASAN

| No | Fitur | Untuk | Status | Kemampuan |
|----|-------|-------|--------|-----------|
| 10 | Riwayat Transaksi (Admin) | Admin | ✅ | View, Edit |
| 11 | Riwayat Transaksi (Pemilik) | Pemilik | ✅ | View, Edit, Delete |
| 12 | Record Aktivitas | Pemilik | ✅ | View + Filter + Audit Trail |
| 13 | Laporan Penjualan | Pemilik | ✅ | Grafik + Stats + Detail Harian |
| 14 | Laporan Laba Rugi | Pemilik | ✅ | Profit Analysis + Margin % |
| 15 | Laporan Inventaris | Pemilik | ✅ | Top 5 + Revenue + Status Stok |
| 16 | Prediksi Penjualan | Pemilik | ✅ | Forecast + Trend + Status Stok |

**Total Fitur Sekarang: 16** (dari 9 original)


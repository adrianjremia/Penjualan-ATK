# Dokumentasi Dashboard Pemilik Toko (Updated)

## Penjelasan Halaman Dashboard Pemilik Toko

Halaman Dashboard Pemilik Toko adalah halaman overview utama yang memberikan ringkasan kondisi bisnis bulan berjalan sebagai bahan evaluasi dan pengambilan keputusan strategis. Dashboard difokuskan pada metrics yang actionable untuk periode bulan ini, bukan all-time metrics, sehingga pemilik toko dapat dengan cepat melihat performa bisnis terkini.

Pada bagian atas halaman terdapat header dengan judul "Dashboard Pemilik Toko" dan subtitle "Ringkasan laporan bisnis Anda bulan ini". Informasi utama ditampilkan melalui empat kartu KPI (Key Performance Indicators) yang mencakup **Penjualan Bulan Ini**, **Laba Bulan Ini**, **Penjualan Hari Ini**, dan **Stok Kritis**. Setiap KPI card dilengkapi dengan icon visual untuk memudahkan identifikasi dan perbandingan cepat antar metrics.

Selanjutnya, dashboard menampilkan **Penjualan 7 Hari Terakhir** dalam format daily breakdown yang memungkinkan pemilik toko melihat trend penjualan harian selama seminggu terakhir. Ini memberikan insight tentang pola penjualan dan fluktuasi revenue harian.

Dashboard juga menyediakan **Status Inventaris** dalam tiga kartu dengan color-coding untuk kemudahan identifikasi: **Stok Kritis** (merah, stok < 5 unit), **Stok Menengah** (orange, stok 5-10 unit), dan **Stok Aman** (hijau, stok > 10 unit). Sistem color-coding ini membantu pemilik toko dengan cepat mengidentifikasi produk yang perlu restock urgent.

Bagian **Top 5 Produk Terlaris (Bulan Ini)** menampilkan lima produk dengan penjualan tertinggi selama bulan berjalan, disertai informasi quantity terjual dan total revenue per produk. Ini membantu pemilik toko mengidentifikasi produk bestseller untuk perencanaan inventory dan strategi merchandising.

Terakhir, dashboard menyediakan **Alerts Section** yang menampilkan notifikasi dinamis berdasarkan kondisi stok dan performa bisnis (hanya tampil jika ada warning/alert). Sidebar menu di sebelah kiri berfungsi sebagai navigasi utama ke seluruh fitur aplikasi yang dapat diakses oleh pemilik toko.

---

## Tabel 4.1 Komponen pada Halaman Dashboard Pemilik (Updated)

| No | Komponen | Fungsi | Tipe Input | Source Data |
|----|----------|--------|-----------|------------|
| 1 | Page Header | Judul dan subtitle halaman | Static text | "Dashboard Pemilik Toko" & "Ringkasan laporan bisnis Anda bulan ini" |
| 2 | Penjualan Bulan Ini Card | Total revenue bulan sekarang | Calculated metric | SUM(transaksi.total_harga) WHERE MONTH(created_at) = current month |
| 3 | Laba Bulan Ini Card | Total profit bulan sekarang | Calculated metric | SUM(harga_jual - harga_beli) × qty untuk bulan sekarang |
| 4 | Penjualan Hari Ini Card | Revenue hari sekarang | Calculated metric | SUM(transaksi.total_harga) WHERE DATE(created_at) = today |
| 5 | Stok Kritis Card | Jumlah produk dengan stok kritis | Counter metric | COUNT(barang) WHERE stok < 5 |
| 6 | Penjualan 7 Hari Terakhir | Daily breakdown penjualan seminggu | Array of daily totals | Array[7] dengan SUM per hari untuk 7 hari terakhir |
| 7 | Status Inventaris - Kritis | Produk dengan stok < 5 unit | Counter with label | COUNT(barang) WHERE stok < 5 |
| 8 | Status Inventaris - Menengah | Produk dengan stok 5-10 unit | Counter with label | COUNT(barang) WHERE stok BETWEEN 5 AND 10 |
| 9 | Status Inventaris - Aman | Produk dengan stok > 10 unit | Counter with label | COUNT(barang) WHERE stok > 10 |
| 10 | Top 5 Produk Terlaris | 5 produk terlaris bulan ini | Table with details | JOIN detail_transaksi, transaksi, barang; GROUP BY barang; ORDER BY SUM(qty) DESC LIMIT 5; WHERE MONTH(transaksi.created_at) = current month |
| 11 | Margin Keuntungan | Profit margin percentage | Calculated metric | (Laba Bulan Ini / Penjualan Bulan Ini) × 100% |
| 12 | Alerts Section | Dynamic notifications | Conditional display | IF stok < 5 OR forecast issue THEN show; ELSE hide |

---

## Fitur Utama Dashboard

### 1. KPI Grid (4 Cards)
Empat metrik utama yang menunjukkan performa bisnis bulan ini:
- **Penjualan Bulan Ini**: Total revenue dari semua transaksi bulan sekarang
- **Laba Bulan Ini**: Profit calculation (selling price - cost price) × quantity untuk bulan sekarang
- **Penjualan Hari Ini**: Real-time daily revenue tracking
- **Stok Kritis**: Quick indicator untuk inventory alert

### 2. Weekly Sales Trend
Menampilkan breakdown penjualan untuk 7 hari terakhir dalam format daily cards, membantu pemilik toko melihat:
- Pola penjualan harian
- Fluktuasi revenue
- Hari dengan performa terbaik/terburuk

### 3. Inventory Health Status
Tiga kartu dengan color-coding untuk inventory management:
- Stok Kritis (Red): < 5 unit - Urgent restock needed
- Stok Menengah (Orange): 5-10 unit - Plan restock soon
- Stok Aman (Green): > 10 unit - Healthy inventory levels

### 4. Top Performers
Table menampilkan 5 produk terlaris bulan ini dengan:
- Nama produk
- Quantity terjual
- Total revenue per produk

### 5. Dynamic Alerts
Notifikasi yang muncul hanya jika ada kondisi yang memerlukan perhatian:
- Stock warning alerts
- Performance alerts
- Forecasting alerts (jika terintegrasi)

---

## Data Refresh & Update

Dashboard dihitung real-time setiap kali page di-load. Semua metrics menggunakan data current dari database:
- Metrics bulan ini: Diperbarui setiap kali ada transaksi baru
- Metrics harian: Direset setiap tengah malam (UTC)
- Inventory status: Diperbarui real-time setiap kali ada transaksi atau manual stock adjustment
- Top 5 products: Diupdate setiap kali ada transaksi baru

---

## Navigation & Quick Access

Sidebar menu menyediakan navigasi ke:
- Dashboard (current page)
- Laporan Penjualan (detailed sales analysis)
- Laporan Laba Rugi (profit/loss detailed report)
- Laporan Penjualan & Inventaris (inventory with sales data)
- Prediksi Penjualan (forecasting & stock prediction)

Tidak ada quick access buttons di dashboard karena sidebar sudah selalu terbuka untuk navigasi yang lebih efisien.

---

## Design & Consistency

Dashboard menggunakan design pattern yang konsisten dengan semua halaman lain di aplikasi:
- Same color palette (Blue #3b82f6, Gray #1f2937)
- Same card styling (white background, #e5e7eb borders, 8px radius)
- Same typography hierarchy
- Same responsive breakpoints (768px mobile)
- Responsive grid layout untuk semua sections

Status colors:
- Red (#ef4444): Kritis/Alert state
- Orange (#f59e0b): Menengah/Warning state
- Green (#10b981): Aman/Healthy state
- Blue (#3b82f6): Neutral/Info state

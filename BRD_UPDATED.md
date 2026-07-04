# Business Requirements Document (BRD) - UPDATED
## Aplikasi Sistem Informasi Berbasis Web untuk Toko Alat Tulis Berkat Kasih

---

## Table of Contents
1. Executive Summary
2. Latar Belakang & Tujuan
3. Scope (Updated)
4. Business Requirements (Updated)
5. Stakeholder Analysis
6. Project Constraints and Assumptions
7. Risk Management
8. Requirements Traceability Matrix (RTM) - Updated
9. Acceptance Criteria
10. Glossary of Terms

---

## Executive Summary

### Project Overview:
Aplikasi Sistem Informasi Penjualan dan Inventori Berbasis Web pada Toko Alat Tulis Berkat Kasih telah dikembangkan dengan fitur yang lebih lengkap untuk mendukung operasional toko secara menyeluruh. Aplikasi ini membantu mengelola data barang, mencatat transaksi penjualan, mengelola stok otomatis, menyajikan laporan terstruktur, serta menyediakan audit trail untuk transparansi dan keamanan sistem.

Aplikasi digunakan oleh dua jenis pengguna (Admin dan Pemilik Toko) dengan hak akses berbeda. Sistem berbasis web memungkinkan akses mudah dan pengelolaan data terpusat.

### Business Objectives:
- Meningkatkan efisiensi dan akurasi dalam pencatatan penjualan dan pengelolaan inventori
- Mengurangi risiko kesalahan pencatatan data barang, stok, dan transaksi manual
- Mempermudah admin dalam mengelola data barang dan melakukan transaksi penjualan
- Menyediakan informasi penjualan dan laporan laba rugi yang jelas dan terstruktur
- Mendukung pemilik toko dalam memantau kinerja usaha dengan laporan komprehensif
- **[NEW]** Menyediakan audit trail lengkap melalui record aktivitas untuk transparansi operasional
- **[NEW]** Memungkinkan analisis penjualan dengan prediksi penjualan berbasis data historis

---

## Latar Belakang & Tujuan

### Background:
Toko Alat Tulis Berkat Kasih melakukan pencatatan manual yang rentan kesalahan. Sistem informasi berbasis web dipilih untuk pengelolaan data terpusat yang konsisten dan real-time.

### Business Problems/Opportunities:
Pengembangan sistem ini menawarkan peluang untuk meningkatkan efisiensi kerja, meminimalkan kesalahan pencatatan, menyediakan informasi akurat, dan membantu pemilik toko memantau kinerja bisnis serta merencanakan strategi berbasis data.

---

## Scope

### In-Scope: ✅

#### Fitur Existing (Original):
1. Sistem autentikasi pengguna melalui fitur login
2. Pengelolaan data barang (CRUD + pencarian)
3. Pencatatan transaksi penjualan terstruktur
4. Pembaruan stok barang otomatis
5. Validasi stok agar tidak negatif
6. Penampilan invoice setelah transaksi
7. Laporan penjualan berdasarkan periode waktu
8. Laporan laba rugi (akses pemilik toko)
9. Informasi stok barang untuk pemilik toko

#### Fitur Baru (Added in Development):
10. **[NEW] Riwayat Transaksi - Admin**
    - View: Filter dan tampilkan semua transaksi dengan detail
    - Edit: Ubah jumlah item, auto-adjust stok, hitung total baru
    - Detail/Invoice: Lihat rincian transaksi dan unduh invoice
    - Tidak ada delete (hanya edit untuk koreksi)

11. **[NEW] Riwayat Transaksi - Pemilik**
    - View: Filter transaksi berdasarkan tanggal dan user
    - Edit: Ubah jumlah item, auto-adjust stok, hitung total baru
    - Delete: Hapus transaksi dengan refund stok otomatis
    - Detail/Invoice: Lihat rincian dan unduh invoice

12. **[NEW] Record Aktivitas**
    - View semua aktivitas sistem dengan filter (user, action, tanggal, search)
    - Pagination untuk menampilkan aktivitas
    - Audit trail untuk transparansi operasional
    - Read-only untuk integritas audit

13. **[NEW] Laporan Penjualan (Enhanced)**
    - Grafik penjualan harian
    - Filter berdasarkan periode tanggal
    - Statistik: Total transaksi, total penjualan, rata-rata per hari
    - Tabel detail transaksi per hari

14. **[NEW] Laporan Laba Rugi (Enhanced)**
    - Analisis profit margin per produk
    - Breakdown: Pendapatan, Modal, Laba per hari
    - Total keseluruhan dan margin keuntungan (%)
    - Filter periode waktu

15. **[NEW] Laporan Inventaris/Stok**
    - Daftar semua barang dengan stok terkini
    - Filter: Nama barang, periode tanggal
    - Top 5 produk terlaris (qty + revenue)
    - Summary: Total produk, total revenue

16. **[NEW] Prediksi Penjualan (Forecasting)**
    - Algoritma: Single Exponential Smoothing (alpha=0.3)
    - Query sales data per minggu (tahun ini)
    - Forecast minggu depan per produk
    - Trend persentase perubahan
    - Status stok: Critical / Medium / Safe
    - Filter produk spesifik

---

## Business Requirements

### High-Level Requirements:
Sistem harus mendukung:
- Manajemen pengguna dan autentikasi
- Pengelolaan data barang dan transaksi
- Pengelolaan stok otomatis
- Laporan penjualan dan analisis keuangan
- **[NEW]** Tracking aktivitas untuk audit trail
- **[NEW]** Analisis prediktif untuk perencanaan stok

### Detailed Requirements:

#### Manajemen Pengguna
- Sistem menyediakan fitur login
- Sistem membedakan hak akses berdasarkan role (Admin / Pemilik)

#### Manajemen Data Barang
- Admin dapat menambahkan, mengubah, menghapus data barang
- Admin dapat mencari data barang berdasarkan nama/kategori

#### Transaksi Penjualan
- Admin dapat mencatat transaksi penjualan
- Sistem menghitung total harga otomatis
- Sistem mengurangi stok barang otomatis
- Sistem menolak transaksi jika stok tidak mencukupi
- Sistem menampilkan invoice setelah transaksi

#### Riwayat Transaksi (New)
- **Admin**: View + Edit (tanpa delete)
- **Pemilik**: View + Edit + Delete dengan refund stok
- Kedua role dapat melihat detail & invoice

#### Record Aktivitas (New)
- Pemilik dapat melihat semua aktivitas sistem
- Filter: user, action, tanggal, search dalam description
- Pagination untuk usability
- Read-only untuk audit integrity

#### Laporan & Informasi
- Sistem menyediakan laporan penjualan dengan grafik & statistik
- Sistem menyediakan laporan laba rugi dengan analisis margin
- Sistem menyediakan laporan inventaris dengan top 5 produk
- Sistem menyediakan prediksi penjualan per produk
- Semua laporan bisa difilter berdasarkan periode

### Non-Functional Requirements:
- Sistem berbasis web dan responsif
- Antarmuka user-friendly
- Password tersimpan aman dengan enkripsi
- Data terpusat dalam basis data relasional
- Performa stabil untuk concurrent user (Admin + Pemilik)
- **[NEW]** Database transactions untuk data consistency (terutama edit/delete)
- **[NEW]** Automatic activity logging untuk setiap operasi

---

## Stakeholder Analysis

### Stakeholder Identification:

| Stakeholder | Peran |
|---|---|
| Admin | Mengelola data barang, mencatat transaksi, edit riwayat transaksi |
| Pemilik Toko | Monitoring laporan, stok, aktivitas, edit/delete riwayat transaksi |
| Mahasiswa/Peneliti | Merancang, mengembangkan, menguji sistem |
| Dosen Pembimbing | Memberikan arahan, evaluasi, masukan penelitian |

### Stakeholder Requirements:
- Admin: Sistem mudah untuk mengelola data dan transaksi cepat & akurat
- Pemilik: Laporan komprehensif, audit trail, prediksi untuk keputusan bisnis
- Mahasiswa/Peneliti: Implementasi sesuai penelitian
- Dosen: Dokumentasi lengkap & hasil sesuai akademik

---

## Project Constraints and Assumptions

### Constraints:
- Satu studi kasus (tidak mendukung multi-toko)
- Jangka waktu terbatas sesuai jadwal penelitian
- Teknologi: Laravel, MySQL, Web-based
- Dua peran pengguna: Admin & Pemilik
- Tidak ada integrasi dengan layanan eksternal

### Assumptions:
- Pengguna memiliki pemahaman dasar aplikasi web
- Data barang & pengguna awal tersedia
- Akses melalui perangkat terhubung internet/jaringan lokal
- Kebutuhan bisnis tidak berubah signifikan

---

## Risk Management

### Identified Risks:
- Kesalahan perancangan/implementasi
- Keterbatasan waktu pengembangan
- Kesalahan input data pengguna
- Kurangnya penerimaan pengguna terhadap sistem baru

### Mitigation Strategies:
- Black Box Testing menyeluruh
- Jadwal pengembangan terstruktur & realistis
- Validasi input dan pesan error
- Antarmuka sederhana & mudah digunakan
- **[NEW]** Comprehensive activity logging untuk debugging
- **[NEW]** Database transactions untuk mencegah data corruption

---

## Requirements Traceability Matrix (RTM)

| ID | Requirement | Tujuan Bisnis |
|---|---|---|
| BR-01 | Login system | Keamanan akses pengguna |
| BR-02 | Kelola data barang | Efisiensi inventori |
| BR-03 | Catat transaksi penjualan | Akurasi pencatatan |
| BR-04 | Kurangi stok otomatis | Cegah kesalahan stok |
| BR-05 | Cegah stok negatif | Jaga akurasi inventori |
| BR-06 | Tampilkan invoice | Transparansi transaksi |
| BR-07 | Laporan penjualan | Monitor kinerja penjualan |
| BR-08 | Laporan laba rugi | Dukung keputusan pemilik |
| BR-09 | Informasi stok | Pantau ketersediaan barang |
| **BR-10** | **Riwayat Transaksi Admin** | **Kontrol & koreksi transaksi** |
| **BR-11** | **Riwayat Transaksi Pemilik** | **Kelola riwayat lengkap** |
| **BR-12** | **Record Aktivitas** | **Audit trail & transparansi** |
| **BR-13** | **Laporan Inventaris** | **Analisis stok & penjualan** |
| **BR-14** | **Prediksi Penjualan** | **Perencanaan stok berbasis data** |

---

## Acceptance Criteria

### Success Criteria:
- Seluruh fitur utama berjalan sesuai kebutuhan
- Data barang, transaksi, laporan tersimpan akurat
- Stok diperbarui otomatis dan tidak negatif
- Laporan dapat diakses sesuai role pengguna
- Sistem mudah digunakan
- **[NEW]** Riwayat transaksi dapat di-edit (auto-adjust stok)
- **[NEW]** Record aktivitas mencatat semua operasi penting
- **[NEW]** Laporan inventaris & prediksi akurat dan informatif

### Deliverables:
1. Aplikasi Sistem Informasi Penjualan & Inventori Berbasis Web
2. Basis data terstruktur lengkap
3. Dokumentasi sistem & laporan penelitian
4. Hasil pengujian menunjukkan keberhasilan sistem
5. **[NEW]** Dokumentasi fitur baru (Riwayat, Record, Laporan, Forecasting)
6. **[NEW]** Activity log examples untuk audit trail

---

## Glossary of Terms

| Istilah | Definisi |
|---|---|
| Sistem Informasi | Sistem untuk mengelola, memproses, sajikan informasi terkomputerisasi |
| Inventori | Persediaan barang toko |
| Transaksi Penjualan | Proses pencatatan penjualan barang |
| Invoice | Dokumen bukti transaksi |
| Admin | Pengguna untuk kelola data barang & transaksi |
| Pemilik Toko | Pengguna untuk monitor laporan & stok |
| Riwayat Transaksi | Daftar transaksi dengan opsi edit/delete |
| Record Aktivitas | Log semua aktivitas sistem untuk audit trail |
| Laporan Penjualan | Laporan transaksi per periode |
| Laporan Laba Rugi | Laporan keuangan (pendapatan vs biaya) |
| Laporan Inventaris | Laporan stok barang & top produk |
| Prediksi Penjualan | Forecast penjualan minggu depan |
| Exponential Smoothing | Algoritma forecasting berbasis trend |
| Audit Trail | Catatan lengkap aktivitas sistem untuk keamanan |
| Database Transaction | Operasi database dengan atomicity (semua atau tidak sama sekali) |

---

## Summary of Updates

### Key Changes from Original BRD:
- **Added 5 new major features** (Riwayat Transaksi x2, Record Aktivitas, Laporan Inventaris, Prediksi Penjualan)
- **Enhanced existing reports** with more detail and filtering options
- **Introduced Activity Logging** for audit trail & compliance
- **Implemented Database Transactions** for data integrity
- **Added Forecasting capability** for proactive stok management
- Total features: 16 (from original 9)
- Total user workflows: Significantly expanded

---

**Document Version:** 2.0 (Updated with New Features)  
**Last Updated:** 2026-07-04  
**Status:** Complete - Ready for Implementation


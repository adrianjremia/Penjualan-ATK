# Daftar Perubahan & Penambahan TSD (Technical Specification Document)

## Status: Update Diperlukan
Dokumen TSD awal dari 25 Maret 2026 sudah ketinggalan dengan perkembangan aplikasi. Berikut adalah daftar perubahan yang perlu dilakukan:

---

## 1. KEBUTUHAN FUNGSIONAL - TAMBAHAN

### Fitur Baru yang Perlu Ditambahkan:

#### FR-10: Riwayat Transaksi di Sisi Admin
- Admin dapat melihat riwayat semua transaksi yang telah dilakukan
- Admin dapat **edit** transaksi (mengubah jumlah item)
- Admin dapat **delete** transaksi (dengan refund stok otomatis)
- Setiap edit/delete terotomatis log ke activity log
- **Mulai:** Admin → Riwayat Transaksi → Edit/Delete
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-11: Riwayat Transaksi di Sisi Owner (Pemilik)
- Pemilik Toko dapat melihat riwayat transaksi (read-only + edit/delete)
- Sama dengan FR-10 tapi diakses oleh Pemilik
- Pemilik dapat filter berdasarkan admin yang melakukan transaksi
- **Mulai:** Pemilik → Riwayat Transaksi
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-12: Record Aktivitas (Activity Log)
- Sistem mencatat setiap aktivitas pengguna (login, create, edit, delete)
- Pemilik dapat melihat riwayat aktivitas semua pengguna
- Fitur filter berdasarkan: user, tipe action, tanggal, search deskripsi
- Untuk audit trail dan keamanan
- **Mulai:** Pemilik → Record Aktivitas
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-13: Laporan Penjualan (Sudah ada di TSD tapi perlu detail)
- Pemilik melihat laporan penjualan harian dalam periode tertentu
- Tampilkan: total transaksi, total revenue, rata-rata per hari
- Visualisasi dalam grafik
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-14: Laporan Laba Rugi (Sudah ada di TSD tapi perlu detail)
- Pemilik melihat laporan profit/loss per periode
- Hitung: Total Revenue, Total Modal (Cost of Goods), Total Profit
- Tampilkan margin keuntungan (%)
- Breakdown per hari
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-15: Laporan Inventaris (Stock Monitoring)
- Pemilik melihat laporan stok produk lengkap
- Tampilkan: nama produk, stok, kategori, total terjual, revenue per produk
- Top 5 produk terlaris
- Filter berdasarkan kategori/pencarian
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

#### FR-16: Prediksi Penjualan (Forecasting)
- Pemilik melihat prediksi penjualan produk minggu depan
- Menggunakan algoritma Single Exponential Smoothing
- Status stok: Critical, Medium, Safe
- Detail trend per produk
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

---

## 2. MODEL DATA - TAMBAHAN/PERUBAHAN

### Entitas Baru:

#### ActivityLog
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary Key |
| user_id | INT | FK ke Users |
| action | VARCHAR | create/edit/delete/login/logout |
| model_type | VARCHAR | Barang/Transaksi/User |
| model_id | INT | ID dari data yang diubah |
| description | TEXT | Deskripsi detail perubahan |
| old_values | JSON | Nilai lama (untuk edit) |
| new_values | JSON | Nilai baru (untuk edit) |
| created_at | TIMESTAMP | Waktu aktivitas |

#### Users - Kolom Tambahan:
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| name | VARCHAR | Nama lengkap (Baru) |
| email | VARCHAR | Email pengguna (Baru) |
| phone | VARCHAR | Nomor telepon (Baru) |
| created_at | TIMESTAMP | Waktu pembuatan (Baru) |
| updated_at | TIMESTAMP | Waktu update (Baru) |

#### Detail_Transaksi - Kolom Tambahan:
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| harga_satuan | DECIMAL | Harga per item saat transaksi (Baru) |

---

## 3. ARSITEKTUR & DESIGN PATTERN - PERUBAHAN

### Tambahan Pattern:
- **Service Layer:** ActivityLogService untuk handle logging aktivitas
- **Repository Pattern:** (Opsional) untuk abstraksi database queries
- **Event Listener:** (Opsional) untuk auto-trigger logging saat create/edit/delete

### Perubahan Existing:
- Tambah middleware untuk logging setiap request/action pengguna
- Update TransaksiController untuk handle edit/delete dengan refund stok
- Update BarangController untuk trigger activity logging

---

## 4. TECHNICAL STACK - UPDATE/TAMBAHAN

### Frontend - Perubahan:
| Layer | Teknologi | Keterangan |
|-------|-----------|-----------|
| Frontend | Blade Template | Tetap (sudah sesuai) |
| Frontend | Tailwind CSS | BARU - Styling modern (bukan Bootstrap) |
| Frontend | Alpine.js | Opsional - untuk interaktivitas ringan |

### Backend - Perubahan:
| Layer | Teknologi | Keterangan |
|-------|-----------|-----------|
| Backend | Laravel 10 | Tetap |
| Backend | Eloquent ORM | Tetap |
| Service Layer | PHP (Custom) | BARU - ActivityLogService |

### Database - Perubahan:
| Layer | Teknologi | Keterangan |
|-------|-----------|-----------|
| Database | MySQL | Tetap |
| Migration | Laravel Migration | BARU - Tambah tabel activity_logs |

---

## 5. FITUR KEAMANAN - TAMBAHAN

### FR-17: Activity Logging
- Setiap aksi penting dicatat: login, create, edit, delete
- Untuk audit trail dan investigasi
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

### FR-18: Stok Management Safety
- Tidak ada transaksi negatif (stok cukup check)
- Refund stok saat delete transaksi
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

### FR-19: Data Consistency
- Menggunakan database transactions untuk setiap operasi
- Prevent race condition pada stok update
- **Status:** ✅ SUDAH DIIMPLEMENTASIKAN

---

## 6. HALAMAN/INTERFACE - TAMBAHAN

### Dashboard Admin - Tetap sama:
- ✅ Dashboard
- ✅ Data Barang
- ✅ Transaksi Penjualan
- ✅ Riwayat Transaksi (BARU di FR-10)

### Dashboard Pemilik - PERUBAHAN SIGNIFIKAN:
- ✅ Dashboard (Baru)
- ✅ Laporan Penjualan
- ✅ Laporan Laba Rugi
- ✅ Laporan Inventaris
- ✅ Prediksi Penjualan
- ✅ Riwayat Transaksi (BARU di FR-11)
- ✅ Record Aktivitas (BARU di FR-12)

---

## 7. DOKUMENTASI TAMBAHAN

### Perlu Ditambahkan:
1. ✅ Activity Diagram (sudah dibuat)
2. ✅ Sequence Diagram untuk setiap fitur (sudah dibuat)
3. ✅ Entity Relationship Diagram (ERD) - Update
4. Database Schema Documentation
5. API Documentation (jika diperlukan)
6. User Manual / Standard Operating Procedure

---

## 8. TESTING & QA

### Perlu Ditambahkan:
- Unit tests untuk Service Layer
- Integration tests untuk flow transaksi
- User acceptance testing checklist
- Performance testing (jika needed)

---

## 9. DEPLOYMENT & CONFIGURATION

### Perlu Diupdate:
| Parameter | Nilai Baru | Keterangan |
|-----------|-----------|-----------|
| APP_ENV | production | Sudah siap prod |
| CACHE_DRIVER | file/redis | Untuk performa |
| SESSION_DRIVER | database/redis | Untuk keamanan |
| LOG_CHANNEL | stack | Untuk audit trail |

---

## 10. RINGKASAN PERUBAHAN

### Yang Ditambah (NEW):
- [ ] 6 Fitur baru (FR-10 s/d FR-16)
- [ ] Activity Log system
- [ ] Enhanced reporting features
- [ ] Forecasting capability
- [ ] Edit/Delete transaksi dengan refund stok
- [ ] Tailwind CSS styling
- [ ] Sequence & Activity diagrams

### Yang Diupdate:
- [ ] User model (tambah name, email, phone)
- [ ] Detail_Transaksi (tambah harga_satuan)
- [ ] TransaksiController (tambah edit/delete methods)
- [ ] ActivityLogService creation
- [ ] Technical Stack documentation
- [ ] Database schema

### Status Keseluruhan:
✅ **SEMUA FITUR SUDAH DIIMPLEMENTASIKAN**
- Aplikasi sudah melampaui TSD awal
- Versi terbaru bisa dianggap 2.0 bukan 1.0

---

## REKOMENDASI NEXT STEPS:

1. **Update Versi TSD** → dari 1.0 menjadi 2.0
2. **Update Tanggal** → dari 25 Maret 2026 menjadi saat ini
3. **Tambah semua FR baru** (FR-10 s/d FR-16)
4. **Tambah ActivityLog model** ke Model Data
5. **Update Technical Stack** dengan Tailwind CSS dan Service Layer
6. **Tambah Sequence Diagrams** untuk setiap fitur baru
7. **Buat ERD terbaru** dengan semua entity termasuk ActivityLog
8. **Update approval section** dan get new sign-off dari stakeholder


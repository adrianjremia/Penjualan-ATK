# Implementasi Fitur Baru: Edit Invoice & Activity Log

## 📋 Ringkasan

Implementasi ini menambahkan 3 fitur utama ke aplikasi Penjualan ATK:

1. **Admin dapat Edit Invoice** - Edit jumlah item dengan automatic stock adjustment
2. **Owner: Halaman Riwayat Transaksi Baru** - Full CRUD management (lihat, edit, hapus invoice)
3. **Activity Log System** - Track semua aktivitas aplikasi (hanya viewable oleh pemilik)

---

## 📁 File-File Baru yang Dibuat

### Database
- `database/migrations/2026_06_06_000001_create_activity_logs_table.php` - Migration untuk tabel activity_logs

### Models
- `app/Models/ActivityLog.php` - Model untuk activity log dengan helper methods

### Services
- `app/Services/ActivityLogService.php` - Service untuk logging aktivitas dengan static methods

### Controllers
- `app/Http/Controllers/Owner/RiwayatTransaksiController.php` - Controller untuk owner transaction management
- `app/Http/Controllers/Owner/ActivityLogController.php` - Controller untuk activity log page

### Views
- `resources/views/admin/transaksi/edit.blade.php` - Form edit invoice untuk admin
- `resources/views/owner/riwayat-transaksi/index.blade.php` - List semua transaksi (owner)
- `resources/views/owner/riwayat-transaksi/invoice.blade.php` - Invoice detail + edit/delete buttons (owner)
- `resources/views/owner/riwayat-transaksi/edit.blade.php` - Form edit invoice (owner)
- `resources/views/owner/activity-log/index.blade.php` - Activity log dashboard (owner)

---

## 🔧 File-File yang Dimodifikasi

### 1. `app/Http/Controllers/Admin/TransaksiController.php`
**Perubahan:**
- Tambah import `ActivityLogService`
- Tambah activity logging di method `store()` saat transaksi dibuat
- Tambah method `editInvoice($id)` - Show edit form
- Tambah method `updateInvoice($id)` - Process edit dengan stock adjustment

**Logika Stock Adjustment:**
```
if (new_qty > old_qty) {
  delta = new_qty - old_qty
  stok -= delta  // kurangi stok jika qty naik
} else if (new_qty < old_qty) {
  delta = old_qty - new_qty
  stok += delta  // tambah stok jika qty turun
}
```

### 2. `routes/web.php`
**Perubahan:**
- Tambah routes untuk admin edit invoice:
  - `GET /admin/riwayat-transaksi/{id}/edit` - Show edit form
  - `PUT /admin/riwayat-transaksi/{id}` - Update invoice

- Tambah routes untuk owner riwayat transaksi (prefix: `/pemilik/riwayat-transaksi`):
  - `GET /` - List semua transaksi
  - `GET /{id}` - View invoice
  - `GET /{id}/edit` - Edit form
  - `PUT /{id}` - Update invoice
  - `DELETE /{id}` - Hapus transaksi

- Tambah route untuk owner activity log:
  - `GET /pemilik/activity-log` - View activity log

### 3. `resources/views/admin/riwayatTransaksi/index.blade.php`
**Perubahan:**
- Tambah tombol "Edit" di action column untuk setiap transaksi
- Tombol ini link ke `admin.transaksi.edit`

---

## 🗄️ Database Schema

### activity_logs Table
```sql
- id (Primary Key)
- id_user (Foreign Key → users.id_user)
- action (VARCHAR) - misal: "create_transaksi", "edit_transaksi", "delete_transaksi"
- description (TEXT) - Deskripsi detail aktivitas
- model_type (VARCHAR) - Tipe model (Transaksi, Barang, etc)
- model_id (INT) - ID dari model yang diubah
- old_values (JSON) - Nilai lama (untuk perubahan)
- new_values (JSON) - Nilai baru (untuk perubahan)
- ip_address (VARCHAR) - IP address user
- user_agent (TEXT) - Browser user agent
- created_at & updated_at (TIMESTAMP)

Indexes:
- (id_user, created_at)
- (action, created_at)
- (model_type, model_id)
```

---

## 🚀 Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Admin Edit Invoice
1. Admin masuk ke `/admin/riwayat-transaksi`
2. Klik tombol "Edit" pada transaksi yang ingin diubah
3. Ubah jumlah item (stok akan adjust otomatis)
4. Klik "Simpan Perubahan"
5. Aktivitas akan tercatat di activity log

**Validasi:**
- Jumlah harus minimal 1
- Stok barang harus cukup (jika qty naik)
- Transaction-based untuk konsistensi data

### 3. Owner Riwayat Transaksi
1. Owner masuk ke `/pemilik/riwayat-transaksi`
2. Opsi:
   - **Lihat Invoice**: Klik "Lihat" untuk melihat detail
   - **Edit Invoice**: Klik "Edit" untuk ubah jumlah item
   - **Hapus**: Klik "Hapus" dengan confirmation dialog
3. Dapat filter berdasarkan:
   - Tanggal mulai & akhir
   - Kasir/User

**Fitur Hapus:**
- Otomatis refund stok ke barang yang terkait
- Konfirmasi sebelum delete
- Aktivitas tercatat

### 4. Owner Activity Log
1. Owner masuk ke `/pemilik/activity-log`
2. Filter dengan:
   - User/Kasir
   - Tipe Aksi
   - Tanggal range
   - Search description
3. Klik "Lihat" untuk melihat detail perubahan (old vs new values)

**Aksi yang tercatat:**
- `create_transaksi` - Penjualan baru dibuat
- `edit_transaksi` - Invoice diedit (beserta detail perubahan)
- `delete_transaksi` - Transaksi dihapus
- `create_barang` - Barang baru dibuat
- `edit_barang` - Barang diedit
- `delete_barang` - Barang dihapus

---

## 🔒 Security & Validation

### Authorization
- Admin edit/view hanya di `/admin/riwayat-transaksi`
- Owner management/view hanya di `/pemilik/riwayat-transaksi` & `/pemilik/activity-log`
- Middleware `role:0` untuk admin, `role:1` untuk pemilik

### Validation
- Quantity input: `required|integer|min:1`
- Stock check: Validasi sebelum update
- Request validation di controller

### Data Consistency
- Semua operasi edit/delete dalam DB transaction
- Stock adjustment atomic dengan transaksi update
- ActivityLog tercatat sebelum transaction commit

---

## 📊 Activity Log Service

### Usage Examples
```php
// Create action
ActivityLogService::logCreate($userId, 'transaksi', $id, ['total' => 100000]);

// Update action
ActivityLogService::logUpdate($userId, 'transaksi', $id, $oldValues, $newValues);

// Delete action
ActivityLogService::logDelete($userId, 'transaksi', $id, $values);

// Generic logging
ActivityLogService::log($userId, 'custom_action', 'Description', 'ModelType', $modelId, null, null);
```

### Automatic Field Capture
- `ip_address` - Diambil dari `Request::ip()`
- `user_agent` - Diambil dari `Request::header('User-Agent')`
- `created_at` - Timestamps Laravel otomatis

---

## ✅ Checklist Post-Implementation

- [x] Migration file created untuk activity_logs table
- [x] ActivityLog model dengan relationships
- [x] ActivityLogService untuk logging
- [x] Admin TransaksiController updated dengan edit methods
- [x] Owner RiwayatTransaksiController created dengan CRUD
- [x] Owner ActivityLogController created
- [x] Routes updated di web.php
- [x] Admin edit invoice view created
- [x] Owner riwayat transaksi views (index, invoice, edit)
- [x] Owner activity log view with filters & modal
- [x] Admin riwayat transaksi view updated dengan edit button

---

## 🧪 Testing Checklist

### Admin Edit Invoice
- [ ] Admin dapat membuka edit form transaksi
- [ ] Dapat mengubah jumlah item
- [ ] Stock berkurang jika qty naik
- [ ] Stock bertambah jika qty turun
- [ ] Total harga terupdate dengan benar
- [ ] Activity log tercatat saat edit
- [ ] Validation error jika qty < 1
- [ ] Validation error jika stock tidak cukup

### Owner Riwayat Transaksi
- [ ] Owner dapat melihat semua transaksi
- [ ] Filter by date range works
- [ ] Filter by kasir works
- [ ] Dapat view invoice detail
- [ ] Dapat edit invoice dengan stock adjustment
- [ ] Dapat delete transaksi dengan stock refund
- [ ] Confirmation dialog muncul saat delete
- [ ] Activity log tercatat

### Owner Activity Log
- [ ] Log page loads dengan all activities
- [ ] Filter by user works
- [ ] Filter by action works
- [ ] Filter by date range works
- [ ] Search description works
- [ ] Pagination works (50 items per page)
- [ ] Detail modal shows old vs new values
- [ ] IP address & user agent visible

---

## 📝 Catatan Penting

1. **Database Migration**: Pastikan menjalankan `php artisan migrate` setelah deploy
2. **Authorization**: Kedua role (admin & owner) punya halaman terpisah untuk transaction management
3. **Stock Audit Trail**: Setiap perubahan stock tercatat via activity log dengan detail values
4. **Soft Delete**: Delete transaksi melakukan soft delete (jika diimplementasikan nanti)
5. **Timestamps**: Activity log menggunakan UTC timestamp dari Laravel Carbon

---

## 🐛 Troubleshooting

### Activity Log tidak appear
- Cek migration sudah dijalankan: `php artisan migrate:status`
- Cek table `activity_logs` ada di database

### Edit button tidak muncul
- Clear Laravel cache: `php artisan cache:clear`
- Check routes: `php artisan route:list`

### Stock tidak terupdate
- Cek Barang model punya relationship dengan DetailTransaksi
- Pastikan DetailTransaksi punya relasi ke Barang

### Activity Log empty
- Cek bahwa aksi (create, update, delete) memanggil ActivityLogService
- Cek user ID tercatat dengan benar

---

## 📞 Support

Jika ada pertanyaan atau issue, refer ke file-file:
- Controllers: `/app/Http/Controllers/{Admin|Owner}/`
- Services: `/app/Services/ActivityLogService.php`
- Views: `/resources/views/{admin|owner}/`
- Routes: `/routes/web.php`

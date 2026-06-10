# Activity Diagram Specifications

## 1. RECORD AKTIVITAS (Activity Log) - Owner Side

### Deskripsi:
Menampilkan log semua aktivitas sistem yang dilakukan oleh staff/kasir (role 0).

### Flow:
1. **Start** → Owner membuka halaman Record Aktivitas
2. **Load Data**
   - Ambil data dari tabel `record_aktivitas`
   - Join dengan tabel `users`
3. **Display Filter Options**
   - Filter by User (dropdown semua staff)
   - Filter by Action Type (dropdown aksi unik: create, edit, delete, dll)
   - Filter by Date Range (tanggal mulai - tanggal akhir)
   - Search by Description
4. **Apply Filters** (Conditional)
   - IF user selected → filter by user
   - IF action selected → filter by action
   - IF date range filled → filter by created_at antara tanggal mulai & akhir
   - IF search text filled → search dalam description
5. **Sort & Paginate**
   - Sort by created_at descending (aktivitas terbaru di atas)
   - Paginate 50 records per page
6. **Display Results**
   - Tampilkan tabel dengan kolom:
     - ID Aktivitas
     - Nama User (staff/kasir yang melakukan aksi)
     - Action (create, edit, delete)
     - Description (detail perubahan)
     - Tanggal & Waktu
     - IP Address
7. **End** → User dapat filter, search, dan browse aktivitas

---

## 2. RIWAYAT TRANSAKSI ADMIN EDIT - Admin Side

### Deskripsi:
Admin dapat melihat, edit, dan menghapus transaksi penjualan yang sudah dibuat. Setiap perubahan dicatat dalam activity log.

### Flow A: View All Transactions
1. **Start** → Admin membuka Riwayat Transaksi
2. **Load Data**
   - Ambil semua transaksi dengan count detail items
3. **Apply Filters** (Conditional)
   - IF tanggal mulai filled → filter created_at >= tanggal mulai
   - IF tanggal akhir filled → filter created_at <= tanggal akhir
   - IF id_user selected → filter by id_user (staff yang membuat transaksi)
4. **Calculate Total**
   - Sum semua total_harga dari transaksi yang terfilter
5. **Sort & Display**
   - Sort by created_at descending
   - Tampilkan tabel dengan:
     - ID Transaksi
     - Tanggal
     - Nama Kasir/Staff
     - Jumlah Item
     - Total Harga
     - Action Buttons: Detail, Edit, Delete
6. **End**

### Flow B: Edit Transaction
1. **Start** → Admin klik tombol "Edit" pada transaksi
2. **Load Transaction**
   - Ambil data transaksi + detail items + info barang
3. **Display Edit Form**
   - Tampilkan tabel item dengan qty saat ini
   - Input field untuk qty baru
4. **Submit Update**
   - Validate jumlah harus integer >= 1
5. **Process Update** (dalam Transaction DB)
   - Loop setiap item:
     - Hitung qty delta (qty baru - qty lama)
     - IF qty naik → Check stok barang (error jika stok kurang)
     - Update stok barang (stok -= qty delta)
     - Update detail transaksi (jumlah & subtotal)
     - Record old values & new values
   - Hitung new total transaksi
   - Update transaksi total_harga
   - **Log Activity** → Catat di RecordAktivitas:
     - action: "edit_transaksi"
     - description: "Memperbarui transaksi ID xxx (jumlah: 2 → 3, ...)"
     - old_values: array data lama
     - new_values: array data baru
6. **Redirect** → Kembali ke halaman invoice dengan success message
7. **End**

### Flow C: Delete Transaction
1. **Start** → Admin klik tombol "Delete" pada transaksi
2. **Load Transaction**
   - Ambil data transaksi + semua detail items
3. **Confirm Delete** (Optional dialog)
4. **Process Delete** (dalam Transaction DB)
   - Loop setiap detail item:
     - Refund stok (stok += jumlah item)
     - Record deleted data
     - Delete detail item
   - Delete transaksi record
   - **Log Activity** → Catat di RecordAktivitas:
     - action: "delete_transaksi"
     - description: "Menghapus transaksi ID xxx"
     - old_values: array semua item + total
5. **Redirect** → Kembali ke list transaksi dengan success message
6. **End**

---

## Data Models & Relationships

### RecordAktivitas Table:
- id_aktivitas (PK)
- id_user (FK → users)
- action (string: create_*, edit_*, delete_*)
- description (text: detail perubahan)
- model_type (string: transaksi, barang, dll)
- model_id (int: ID record yang berubah)
- old_values (JSON: nilai sebelum)
- new_values (JSON: nilai sesudah)
- ip_address
- user_agent
- created_at

### Transaksi Table:
- id_transaksi (PK)
- id_user (FK → users) - staff yang buat
- total_harga
- created_at

### DetailTransaksi Table:
- id_detail (PK)
- id_transaksi (FK → transaksi)
- id_barang (FK → barang)
- jumlah
- harga
- subtotal
- created_at

### Barang Table:
- id_barang (PK)
- nama_barang
- stok
- harga
- dll

---

## Key Points:
1. **Activity Log** = Read-only view untuk owner, menampilkan audit trail semua aktivitas
2. **Edit Transaksi** = Admin bisa ubah qty items, sistem otomatis adjust stok & total
3. **Delete Transaksi** = Admin bisa hapus, stok dikembalikan otomatis
4. **Logging** = Setiap perubahan tercatat dengan user, waktu, IP, detail perubahan
5. **Database Transactions** = Gunakan DB::transaction untuk ensure data consistency (all or nothing)

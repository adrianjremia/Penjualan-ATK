# Panduan Setup Database di Localhost

## Option 1: Menggunakan phpMyAdmin

### Step 1: Buka phpMyAdmin
- Akses: `http://localhost/phpmyadmin`
- Login dengan username/password Anda

### Step 2: Pilih Database
- Di sidebar kiri, pilih database Anda (misal: `penjualan_atk`)
- Atau buat database baru jika belum ada

### Step 3: Jalankan SQL Query
- Klik tab **SQL** 
- Copy-paste seluruh isi dari file `DATABASE_SETUP.sql`
- Klik tombol **Execute** (atau tekan Ctrl+Enter)

---

## Option 2: Menggunakan Command Line MySQL

### Step 1: Buka Command Prompt / Terminal

### Step 2: Akses MySQL
```bash
mysql -u root -p
```
- Masukkan password MySQL Anda (jika ada)
- Atau tinggal Enter jika tidak ada password

### Step 3: Pilih Database
```sql
USE penjualan_atk;
```
(Sesuaikan dengan nama database Anda)

### Step 4: Copy-Paste Query
Salin seluruh isi file `DATABASE_SETUP.sql` ke MySQL console, lalu tekan Enter.

Atau bisa langsung dari file:
```bash
mysql -u root -p penjualan_atk < DATABASE_SETUP.sql
```

### Step 5: Verifikasi
```sql
SHOW TABLES;
DESCRIBE record_aktivitas;
```

---

## Option 3: Menggunakan Laravel Migration (Jika sudah setup Laravel)

Jika sudah punya Laravel project yang sudah configured:

### Step 1: Jalankan Migration
```bash
php artisan migrate
```

Ini akan otomatis membuat tabel `record_aktivitas` dan semua tabel lainnya sesuai migration files.

---

## Verifikasi Tabel

Setelah menjalankan query, verifikasi dengan:

```sql
-- Lihat struktur tabel
DESCRIBE record_aktivitas;

-- Atau lebih detail
SHOW FULL COLUMNS FROM record_aktivitas;

-- Lihat indexes
SHOW INDEXES FROM record_aktivitas;

-- Lihat jumlah baris (seharusnya 0)
SELECT COUNT(*) FROM record_aktivitas;
```

### Expected Output:
Tabel `record_aktivitas` harus memiliki 12 kolom:
- `id` (Primary Key)
- `id_user` (Foreign Key ke users)
- `action`
- `description`
- `model_type`
- `model_id`
- `old_values`
- `new_values`
- `ip_address`
- `user_agent`
- `created_at`
- `updated_at`

Dan 4 indexes:
- PRIMARY KEY: `id`
- INDEX: `id_user, created_at`
- INDEX: `action, created_at`
- INDEX: `model_type, model_id`
- FOREIGN KEY: `id_user` → `users.id`

---

## Troubleshooting

### Error: "Table 'penjualan_atk.record_aktivitas' already exists"
```sql
DROP TABLE IF EXISTS `record_aktivitas`;
-- Kemudian jalankan CREATE TABLE query lagi
```

### Error: "Cannot add or modify column: no such column 'id_user' in referenced table"
Pastikan tabel `users` sudah ada di database. Jika belum, buat terlebih dahulu:
```sql
-- Lihat struktur users table
DESCRIBE users;
```

### Error: "Unknown column 'id' in 'field list'"
Pastikan query SQL di-copy dengan benar tanpa ada potongan.

---

## Setelah Setup

Setelah tabel berhasil dibuat:

1. **Test dengan Insert Data:**
```sql
INSERT INTO record_aktivitas (id_user, action, description, created_at, updated_at) 
VALUES (1, 'test_action', 'Test record', NOW(), NOW());

SELECT * FROM record_aktivitas;
```

2. **Update `.env` di Laravel (jika diperlukan)**
Pastikan konfigurasi database sudah benar di file `.env`:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=penjualan_atk
DB_USERNAME=root
DB_PASSWORD=
```

3. **Test di Laravel:**
```bash
php artisan tinker
>>> App\Models\RecordAktivitas::count()
```
(Seharusnya return 0 atau jumlah records)

---

## Query SQL Lengkap

Lihat file `DATABASE_SETUP.sql` untuk query lengkap yang siap di-copy.

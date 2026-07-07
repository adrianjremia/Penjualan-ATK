# Penjelasan Implementasi Record Aktivitas & Tracking ID Transaksi

## Overview

Record Aktivitas (Activity Log) mencatat setiap action penting di sistem termasuk **ID Transaksi** yang diubah/dihapus. Ini untuk audit trail dan investigasi.

---

## Database Schema

### Tabel: `record_aktivitas`

```sql
CREATE TABLE record_aktivitas (
    id_aktivitas INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    action VARCHAR(50),                    -- Tipe action: "edit_Transaksi", "delete_Transaksi"
    description TEXT,                      -- Deskripsi lengkap action
    model_type VARCHAR(50),                -- Model yang diubah: "Transaksi", "Barang", dll
    model_id INT,                          -- ID dari model (ID TRANSAKSI disimpan di sini!)
    old_values JSON,                       -- Nilai lama sebelum ubah (qty lama, total lama)
    new_values JSON,                       -- Nilai baru setelah ubah (qty baru, total baru)
    ip_address VARCHAR(45),                -- IP address yang melakukan action
    user_agent TEXT,                       -- Browser/device info
    created_at TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id)
);
```

### Kolom Penting untuk Tracking ID Transaksi:
- **`model_id`** → Menyimpan ID Transaksi (T870, T869, dll)
- **`action`** → Tipe action ("edit_Transaksi", "delete_Transaksi")
- **`description`** → Deskripsi human-readable
- **`old_values`** & **`new_values`** → Detail perubahan

---

## ActivityLogService - Cara Kerja

File: `app/Services/ActivityLogService.php`

### Method: `logUpdate()`

```php
public static function logUpdate($userId, $modelType, $modelId, $oldValues, $newValues)
{
    $description = "Memperbarui {$modelType} ID {$modelId}";
    // ... build changes description ...
    
    return self::log(
        $userId,
        "edit_{$modelType}",           // action = "edit_Transaksi"
        $description,                  // description = "Memperbarui Transaksi ID T870 (qty: 2 → 3, ...)"
        $modelType,                    // model_type = "Transaksi"
        $modelId,                      // model_id = T870 (ID TRANSAKSI!)
        $oldValues,                    // old values sebelum ubah
        $newValues                     // new values setelah ubah
    );
}
```

### Method: `logDelete()`

```php
public static function logDelete($userId, $modelType, $modelId, $values = null)
{
    return self::log(
        $userId,
        "delete_{$modelType}",         // action = "delete_Transaksi"
        "Menghapus {$modelType} ID {$modelId}",  // description = "Menghapus Transaksi ID T870"
        $modelType,                    // model_type = "Transaksi"
        $modelId,                      // model_id = T870 (ID TRANSAKSI!)
        $values,                       // old values sebelum dihapus
        null                           // new values = null (sudah dihapus)
    );
}
```

---

## Implementasi di Controller

File: `app/Http/Controllers/Owner/RiwayatTransaksiController.php`

### Saat EDIT Transaksi:

```php
public function updateInvoice(Request $request, $id)
{
    $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);
    
    // Validate input
    $validated = $request->validate([...]);
    
    DB::transaction(function () use ($transaksi, $validated) {
        // Collect old & new values
        $oldValues = ['jumlah_item' => $transaksi->jumlah_item, ...];
        $newValues = ['jumlah_item' => $newItemCount, ...];
        
        // Process update...
        
        // LOG ACTIVITY dengan ID TRANSAKSI
        ActivityLogService::logUpdate(
            auth()->id(),                // User yang ubah
            'Transaksi',                 // Model type
            $transaksi->id_transaksi,    // ← ID TRANSAKSI disimpan di sini!
            $oldValues,
            $newValues
        );
    });
}
```

Hasil di database `record_aktivitas`:
```
id_aktivitas | id_user | action          | model_type | model_id | description
1            | 1       | edit_Transaksi  | Transaksi  | T870     | Memperbarui Transaksi ID T870 (jumlah_item: 2 → 3, total_harga: Rp 2.275.000 → Rp 3.412.500)
```

### Saat DELETE Transaksi:

```php
public function destroyInvoice($id)
{
    $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);
    
    DB::transaction(function () use ($transaksi) {
        // Collect values sebelum delete
        $values = $transaksi->toArray();
        
        // Refund stock...
        
        // Delete transaksi
        $transaksi->delete();
        
        // LOG ACTIVITY dengan ID TRANSAKSI
        ActivityLogService::logDelete(
            auth()->id(),                // User yang hapus
            'Transaksi',                 // Model type
            $transaksi->id_transaksi,    // ← ID TRANSAKSI disimpan di sini!
            $values
        );
    });
}
```

Hasil di database `record_aktivitas`:
```
id_aktivitas | id_user | action             | model_type | model_id | description
2            | 1       | delete_Transaksi   | Transaksi  | T870     | Menghapus Transaksi ID T870
```

---

## Flow Lengkap: Edit Transaksi T870

### Step 1: User Click Edit
```
Pemilik klik tombol Edit di transaksi T870
```

### Step 2: Form Ditampilkan
```
GET /pemilik/riwayat-transaksi/T870/edit
Controller: RiwayatTransaksiController@editInvoice($id = 'T870')
View: form dengan qty item sekarang
```

### Step 3: User Ubah Qty & Submit
```
User ubah qty dari 2 menjadi 3
Submit form POST ke /pemilik/riwayat-transaksi/T870
```

### Step 4: Processing & Validation
```
Controller: RiwayatTransaksiController@updateInvoice($id = 'T870')
- Validate input
- Hitung delta qty (3 - 2 = 1)
- Check stok cukup untuk delta
```

### Step 5: Update Database & Log
```
DB Transaction Start:
  1. Update detail_transaksi SET jumlah = 3
  2. Update barang SET stok = stok - 1 (decrement karena qty naik)
  3. Update transaksi SET total_harga = 3.412.500
  4. ← INSERT ke record_aktivitas dengan ID TRANSAKSI T870!
DB Transaction Commit
```

### Step 6: Record Aktivitas Terisi

Database `record_aktivitas`:
```
INSERT INTO record_aktivitas VALUES (
    NULL,                     -- id_aktivitas (auto)
    1,                        -- id_user (Pemilik)
    'edit_Transaksi',         -- action
    'Memperbarui Transaksi ID T870 (jumlah_item: 2 → 3, total_harga: Rp 2.275.000 → Rp 3.412.500)',
    'Transaksi',              -- model_type
    'T870',                   -- model_id ← ID TRANSAKSI!
    JSON: {old values},       -- old_values
    JSON: {new values},       -- new_values
    '192.168.1.100',          -- ip_address
    'Mozilla/5.0...',         -- user_agent
    NOW()                     -- created_at
);
```

### Step 7: Pemilik Bisa Lihat di Record Aktivitas

```
GET /pemilik/record-aktivitas

Tabel display:
No | User     | Action       | Description                                      | Tanggal
1  | Pemilik  | edit         | Memperbarui Transaksi ID T870 (jumlah_item: 2→3) | 2026-07-04 20:18:39
```

### Step 8: Pemilik Bisa Click Untuk Detail

```
- Lihat siapa yang edit (ID User: 1 = Pemilik)
- Lihat kapan (2026-07-04 20:18:39)
- Lihat perubahan apa (qty 2→3, total Rp 2.275.000 → Rp 3.412.500)
- Lihat dari mana (IP: 192.168.1.100)
- Lihat browser apa (Mozilla/5.0)
```

---

## Jawaban: "Gimana Record Aktivitas Ngambil ID Transaksi?"

### Jawabannya Simple:

**ID Transaksi dipass langsung dari controller ke ActivityLogService sebagai parameter `$modelId`**

```
Alur:
Controller tahu user ubah transaksi mana (dari URL param $id = 'T870')
    ↓
Controller collect old values & new values dari transaksi T870
    ↓
Controller call: ActivityLogService::logUpdate(auth()->id(), 'Transaksi', 'T870', oldVals, newVals)
    ↓
ActivityLogService insert ke record_aktivitas dengan model_id = 'T870'
    ↓
Database record_aktivitas tersimpan dengan ID Transaksi T870
```

### Tidak Ada "Magic" - Explicit & Clear:

Di controller, ID transaksi **sudah diketahui dari parameter URL** (`$id = 'T870'`), terus **dipass langsung ke ActivityLogService** sebagai `$modelId`.

---

## Implementasi di BRD

Di BRD sebelumnya, ini **tidak dijelaskan dengan detail** karena fokus ke user-facing features. Tapi di implementasi kodingan, sudah **clear dan terstruktur**:

| Aspect | BRD | Implementation |
|--------|-----|---|
| "Record Aktivitas" | Vague - "track aktivitas" | Clear - Setiap action log dengan model_type + model_id + old/new values |
| "Audit Trail" | Vague - "untuk compliance" | Clear - ActivityLogService + RecordAktivitas table + JSON values |
| "ID Transaksi" | Tidak disebutkan | Clear - model_id = ID Transaksi |
| "Tracking" | Tidak disebutkan | Clear - ip_address + user_agent + created_at |

---

## Kesimpulan

**Record Aktivitas ngambil ID Transaksi dari:**
1. **Controller** - Tahu ID transaksi dari URL parameter atau request data
2. **ActivityLogService** - Receive ID transaksi sebagai parameter `$modelId`
3. **Database** - Insert ke field `model_id` di tabel `record_aktivitas`

Jadi tracking ID transaksi adalah **explicit** - bukan automatic atau magic, tapi **di-pass langsung dari controller ke service**.


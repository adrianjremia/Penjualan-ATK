# Activity Diagram Swimlane - Laporan Inventaris

## Format: 2 Kolom (Owner | Sistem)

### ALUR LENGKAP:

**START** (circle)
↓
**Owner Column:**
- "Buka Laporan Inventaris"
↓
**Sistem Column:**
- "Load Semua Barang dari Database"
- "Hitung Total Terjual & Revenue"
↓
**Sistem Column:**
- "Tampilkan Daftar Inventaris"
↓
**Owner Column:**
- "Lihat Daftar Barang & Top 5"
↓
**Owner Column:**
- "Input Filter (Nama/Tanggal)" ← User dapat melakukan filter
↓
**Decision Diamond:** "Pakai Filter?"
├─ **YA** → Sistem: "Query dengan Filter"
│           Sistem: "Hitung Ulang Data"
│           Sistem: "Tampilkan Hasil Filter"
│           Owner: "Lihat Hasil Filtered"
└─ **TIDAK** → Tampilkan Daftar Standar

↓
**Owner Column:**
- "Cari/Download Laporan"
↓
**Sistem Column:**
- "Generate Summary Stats"
- "Hitung Total Produk & Revenue"
- "Export/Display Report"
↓
**Owner Column:**
- "Analisis Data Inventaris"
↓
**END** (circle)

### DETAIL KOMPONEN:

#### **Owner Lane (Kiri):**
1. Buka Laporan Inventaris (action)
2. Lihat Daftar Barang & Top 5 (view)
3. Input Filter (action)
4. Lihat Hasil Filtered (view)
5. Cari/Download Laporan (action)
6. Analisis Data Inventaris (end)

#### **Sistem Lane (Kanan):**
1. Load Semua Barang dari Database
2. Hitung Total Terjual & Revenue
3. Tampilkan Daftar Inventaris
4. [Decision: Pakai Filter?]
5. Query dengan Filter (if YA)
6. Hitung Ulang Data (if YA)
7. Tampilkan Hasil Filter (if YA)
8. Generate Summary Stats
9. Hitung Total Produk & Revenue
10. Export/Display Report

### PENJELASAN ALUR:

1. **Initialization:** Owner akses halaman, sistem load data barang lengkap
2. **Display:** Sistem menampilkan daftar barang dengan qty terjual dan revenue
3. **Filter Option:** Owner bisa filter berdasarkan nama barang atau tanggal range
4. **Decision Point:** Jika ada filter → query ulang dengan kondisi, jika tidak → tampilkan semua
5. **Summary:** Sistem generate summary (total produk, total revenue, top 5 terlaris)
6. **Export:** Owner bisa download atau lihat laporan dalam format tabel
7. **End:** Selesai, owner bisa analisis data

### UNTUK DRAW SECARA MANUAL:
- Gunakan tools: Draw.io, Lucidchart, atau Visio
- Atau bisa juga di PowerPoint/Google Slides dengan shapes dan connector lines
- Dimensi swimlane: ~800x600px untuk tampilan landscape

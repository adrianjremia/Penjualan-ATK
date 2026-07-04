# Panduan Membuat Activity Diagram Simple di PlantUML

## Alur Dasar Activity Diagram

Activity diagram menunjukkan **alur aktivitas/action** dari suatu fitur, mirip seperti flowchart.

### Struktur Dasar:

```
@startuml
title <font size=10>Nama Fitur</font>

start
:Action 1;
:Action 2;
:Action 3;
stop

@enduml
```

## Komponen Utama:

### 1. Start & Stop
```
start          → Awal proses
stop           → Akhir proses
```

### 2. Action/Activity (direpresentasikan dengan :text;)
```
:User buka halaman login;
:Input username & password;
:Klik tombol login;
```

### 3. Decision (Kondisi If-Else)
```
if (Kondisi?) then (yes)
    :Lakukan action jika true;
else (no)
    :Lakukan action jika false;
endif
```

### 4. Looping
```
repeat
    :Ulangi action;
until (Kondisi stop?)
```

## Contoh untuk Laporan Inventaris:

```
@startuml
title <font size=10>Activity Diagram - Laporan Inventaris</font>

start
:Owner buka halaman;
:Input filter pencarian & tanggal;
:Query database semua barang;
if (Ada pencarian?) then (yes)
    :Filter by nama barang;
else (no)
    :Tampilkan semua barang;
endif
:Hitung qty terjual per barang;
:Hitung revenue per barang;
:Cari top 5 produk terlaris;
:Hitung total produk & total revenue;
:Tampilkan hasil di halaman;
stop

@enduml
```

## Tips Membuat Activity Diagram:

1. **Mulai dari Start**
   - Apa trigger user untuk membuka fitur?
   - Misal: "User buka halaman X"

2. **Identifikasi Action Utama**
   - User input data?
   - System query database?
   - System processing/calculation?
   - Display hasil?

3. **Tambah Decision Points**
   - Ada kondisi if-else?
   - Ada validasi?
   - Ada branch logic?

4. **Selesai dengan Stop**
   - Fitur selesai?
   - User diredirect/refresh?
   - Simpan dengan Stop

5. **Jangan terlalu detail**
   - 8-15 action cukup
   - Fokus alur utama
   - Hindari action yang terlalu spesifik

## Alur Membuat Activity Diagram untuk Fitur:

### Step 1: Lihat Controller Method
- Baca method public di controller
- Identifikasi validasi, query, calculation, view return

### Step 2: List Semua Action (urut)
1. User trigger (buka halaman)
2. Input/filter user
3. Query database
4. Validasi/kondisi?
5. Processing/calculation
6. Return/display hasil

### Step 3: Identifikasi Decision Points
- Ada if-else logic?
- Ada query kondisional?
- Tambahkan if-then-else block

### Step 4: Tulis di PlantUML
- start
- Setiap action jadi 1 baris :action;
- Kondisi jadi if block
- stop

### Step 5: Test
- Copy-paste ke plantuml.com
- Lihat hasilnya
- Perbaiki jika ada yang tidak jelas

## Template Universal:

```
@startuml
title <font size=10>Nama Fitur</font>

start
:User action (trigger);
:Input/pilih data;
:Sistem query/validate;
if (Kondisi?) then (yes)
    :Aksi jika true;
else (no)
    :Aksi jika false;
endif
:Process/calculate;
:Return/display hasil;
stop

@enduml
```

Gunakan template ini untuk semua fitur - ubah action dan kondisi sesuai logic fitur!

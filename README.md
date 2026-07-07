# Sistem Informasi Penjualan dan Inventori ATK Berkat Kasih

Aplikasi web berbasis Laravel untuk mengelola penjualan, inventori, dan laporan bisnis pada Toko Alat Tulis Berkat Kasih.

## Fitur Utama

### Admin
- Kelola Data Barang (CRUD)
- Catat Transaksi Penjualan
- Edit Riwayat Transaksi
- Lihat Invoice & Detail Transaksi

### Pemilik
- Dashboard Penjualan
- Laporan Penjualan (dengan grafik & statistik)
- Laporan Laba Rugi (analisis profit & margin)
- Laporan Inventaris (top 5 produk terlaris)
- Prediksi Penjualan (forecasting mingguan)
- Riwayat Transaksi (view, edit, delete dengan auto-refund stok)
- Record Aktivitas (audit trail lengkap)

## Tech Stack

- **Backend**: Laravel 8+
- **Database**: MySQL
- **Frontend**: Blade Template, Custom CSS
- **Server**: Apache (XAMPP)
- **Language**: PHP 7.4+

## Instalasi

### Prerequisites
- PHP 7.4+
- MySQL
- Composer
- XAMPP atau web server lainnya

### Step by Step

1. Clone repository
```bash
git clone https://github.com/adrianjremia/Penjualan-ATK.git
cd Penjualan-ATK
```

2. Install dependencies
```bash
composer install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di file `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=penjualan_atk
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migration
```bash
php artisan migrate
```

6. Jalankan seeder (opsional - untuk data dummy)
```bash
php artisan db:seed
```

7. Jalankan aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Default Login

**Admin**
- Username: admin
- Password: password

**Pemilik**
- Username: pemilik
- Password: password

## Struktur Project

```
Penjualan-ATK/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   └── Owner/
│   ├── Models/
│   ├── Services/
│   │   ├── ActivityLogService.php
│   │   └── ForecastingService.php
│   └── ...
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── owner/
│   │   ├── layouts/
│   │   └── auth/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── database/
│   ├── migrations/
│   └── seeders/
├── .env.example
├── composer.json
└── README.md
```

## Routes / Endpoints

### Admin Routes
- `/admin/dashboard` - Dashboard Admin
- `/admin/barang` - Kelola Data Barang
- `/admin/transaksi` - Catat Transaksi Penjualan
- `/admin/riwayat-transaksi` - Riwayat Transaksi

### Owner Routes
- `/pemilik/dashboard` - Dashboard Pemilik
- `/pemilik/laporan-penjualan` - Laporan Penjualan
- `/pemilik/laporan-laba-rugi` - Laporan Laba Rugi
- `/pemilik/informasi-stok` - Laporan Inventaris
- `/pemilik/forecasting` - Prediksi Penjualan
- `/pemilik/riwayat-transaksi` - Riwayat Transaksi
- `/pemilik/record-aktivitas` - Record Aktivitas

### Auth Routes
- `/login` - Login page
- `/logout` - Logout

## Database Schema

### Main Tables
- `users` - Data pengguna (Admin & Pemilik)
- `barang` - Data barang/produk
- `transaksi` - Data transaksi penjualan
- `detail_transaksi` - Detail item per transaksi
- `activity_logs` - Log aktivitas sistem

## Fitur Detail

### Riwayat Transaksi
- **Admin**: Bisa view dan edit (tanpa delete)
- **Pemilik**: Bisa view, edit, dan delete dengan auto-refund stok

### Record Aktivitas
- Mencatat semua aktivitas: create, update, delete barang/transaksi
- Filter berdasarkan: user, action, tanggal, search
- Pagination 50 per halaman

### Laporan Penjualan
- Visualisasi grafik penjualan harian
- Filter berdasarkan periode tanggal
- Statistik: Total transaksi, total penjualan, rata-rata per hari
- Tabel breakdown detail per hari

### Laporan Laba Rugi
- Breakdown profit margin harian
- Analisis: Pendapatan, Modal, Laba
- Total keseluruhan & margin keuntungan %
- Filter periode

### Laporan Inventaris
- Daftar semua barang dengan stok terkini
- Top 5 produk terlaris (qty & revenue)
- Filter barang dan periode
- Summary statistik

### Prediksi Penjualan
- Algoritma: Single Exponential Smoothing (alpha=0.3)
- Forecast penjualan minggu depan per produk
- Trend persentase perubahan
- Status stok: Critical / Medium / Safe
- Filter produk spesifik

## Security Features

- Password encryption dengan bcrypt
- Role-based access control (Admin vs Pemilik)
- Session management
- CSRF token protection
- Input validation & sanitization
- Activity logging untuk audit trail
- Database transactions untuk data integrity

## Performance

- Pagination untuk data besar
- Query optimization dengan eager loading
- Caching untuk laporan statis
- Database indexing pada foreign keys

## Testing

Untuk menjalankan test:
```bash
php artisan test
```

## Troubleshooting

### Database Connection Error
- Pastikan MySQL running
- Check konfigurasi `.env`
- Jalankan `php artisan migrate`

### Permission Denied
- Check folder `storage` dan `bootstrap/cache` writable
- Jalankan: `chmod -R 775 storage bootstrap/cache`

### Artisan Commands Not Found
- Pastikan sudah `composer install`
- Jalankan: `php artisan --version`

## Contributing

1. Fork repository
2. Buat feature branch: `git checkout -b feature/nama-fitur`
3. Commit changes: `git commit -m 'Add fitur baru'`
4. Push ke branch: `git push origin feature/nama-fitur`
5. Open Pull Request

## Project Status

**Version**: 2.0  
**Status**: Active Development  
**Last Updated**: July 2026  

### Fitur Completed (16/16)
- ✅ Login & Authentication
- ✅ Kelola Barang
- ✅ Transaksi Penjualan
- ✅ Update Stok Otomatis
- ✅ Invoice
- ✅ Laporan Penjualan
- ✅ Laporan Laba Rugi
- ✅ Informasi Stok
- ✅ Dashboard
- ✅ Riwayat Transaksi (Admin & Pemilik)
- ✅ Record Aktivitas
- ✅ Laporan Inventaris
- ✅ Prediksi Penjualan

## License

Proyek penelitian - Universitas

## Contact & Support

- **Developer**: Adrian Jremia
- **Email**: adrian.jremia@student.uksw.edu
- **Repository**: https://github.com/adrianjremia/Penjualan-ATK

## Dokumentasi

Untuk dokumentasi lengkap, lihat:
- `BRD_FINAL.txt` - Business Requirements Document
- `PENJELASAN_FITUR_BARU.md` - Penjelasan detail semua fitur
- `DEVELOPMENT_CHANGES_SUMMARY.md` - Summary perubahan development

---

**Made with ❤️ for Toko Alat Tulis Berkat Kasih**

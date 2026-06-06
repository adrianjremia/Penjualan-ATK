<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
       return redirect('/login'); // atau redirect()->route('login');
   });
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// sementara dashboard dummy
Route::get('/dashboard', function () {
    return 'Login berhasil';
})->name('dashboard')->middleware('auth');

// ADMIN
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:0']);

// PEMILIK
Route::get('/dashboard/pemilik', [DashboardController::class, 'pemilik'])
    ->middleware(['auth', 'role:1'])
    ->name('dashboard.pemilik');

use App\Http\Controllers\Owner\LaporanController;

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/pemilik/laporan-penjualan', [LaporanController::class, 'penjualan'])
        ->name('laporan.penjualan');

    Route::get('/pemilik/laporan-laba-rugi', [LaporanController::class, 'labaRugi'])
        ->name('laporan.laba-rugi');

    Route::get('/pemilik/informasi-stok', [LaporanController::class, 'stok'])
        ->name('laporan.stok');

    Route::get('/pemilik/forecasting', [LaporanController::class, 'forecasting'])
        ->name('laporan.forecasting');
});



use App\Http\Controllers\Admin\BarangController;

Route::middleware(['auth', 'role:0'])->group(function () {

    Route::get('/admin/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/admin/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/admin/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/admin/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/admin/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/admin/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

});

Route::middleware(['auth', 'role:0'])->group(function () {
    Route::get('/admin/barang', [BarangController::class, 'index'])
        ->name('admin.barang.index');
    
    Route::get('/admin/barang/create', [BarangController::class, 'create'])
    ->name('admin.barang.create');

    Route::post('/admin/barang', [BarangController::class, 'store'])
    ->name('admin.barang.store');

    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])
    ->name('admin.barang.edit');

    Route::put('/barang/{id}', [BarangController::class, 'update'])
    ->name('admin.barang.update');

    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
    ->name('admin.barang.destroy');
});

use App\Http\Controllers\Admin\TransaksiController;

Route::prefix('admin')->middleware(['auth', 'role:0'])->group(function () {
    // FORM TRANSAKSI (kasir)
    Route::get('/transaksi', [TransaksiController::class, 'create'])
        ->name('admin.transaksi.create');

    Route::post('/transaksi', [TransaksiController::class, 'store'])
        ->name('admin.transaksi.store');

    // RIWAYAT TRANSAKSI
    Route::get('/riwayat-transaksi', [TransaksiController::class, 'index'])
        ->name('admin.transaksi.index');

    // INVOICE
    Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])
        ->name('admin.transaksi.invoice');

    // EDIT INVOICE
    Route::get('/riwayat-transaksi/{id}/edit', [TransaksiController::class, 'editInvoice'])
        ->name('admin.transaksi.edit');

    Route::put('/riwayat-transaksi/{id}', [TransaksiController::class, 'updateInvoice'])
        ->name('admin.transaksi.update');
});

// OWNER - Riwayat Transaksi
use App\Http\Controllers\Owner\RiwayatTransaksiController;
use App\Http\Controllers\Owner\ActivityLogController;

Route::middleware(['auth', 'role:1'])->group(function () {
    // Riwayat Transaksi
    Route::prefix('pemilik/riwayat-transaksi')->group(function () {
        Route::get('/', [RiwayatTransaksiController::class, 'index'])
            ->name('owner.riwayat-transaksi.index');

        Route::get('/{id}', [RiwayatTransaksiController::class, 'show'])
            ->name('owner.riwayat-transaksi.show');

        Route::get('/{id}/edit', [RiwayatTransaksiController::class, 'editInvoice'])
            ->name('owner.riwayat-transaksi.edit');

        Route::put('/{id}', [RiwayatTransaksiController::class, 'updateInvoice'])
            ->name('owner.riwayat-transaksi.update');

        Route::delete('/{id}', [RiwayatTransaksiController::class, 'destroy'])
            ->name('owner.riwayat-transaksi.destroy');
    });

    // Activity Log
    Route::get('/pemilik/activity-log', [ActivityLogController::class, 'index'])
        ->name('owner.activity-log.index');
});

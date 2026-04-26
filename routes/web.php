<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
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
});

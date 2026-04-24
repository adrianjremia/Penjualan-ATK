<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        // Total barang
        $totalBarang = Barang::count();

        // Transaksi hari ini
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())
            ->count();

        // Pendapatan hari ini
        $pendapatanHariIni = Transaksi::whereDate('created_at', Carbon::today())
            ->sum('total_harga');

        // 5 Transaksi terbaru
        $transaksiTerbaru = Transaksi::with('detailTransaksi')
            ->withCount('detailTransaksi')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalBarang',
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiTerbaru'
        ));
    }

    public function pemilik()
    {
        return view('dashboard.pemilik');
    }
}

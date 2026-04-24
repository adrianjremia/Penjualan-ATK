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
        // Total penjualan semua waktu
        $totalPenjualan = Transaksi::sum('total_harga');

        // Hitung total laba (total penjualan - total biaya beli)
        $totalLaba = 0;
        $transaksis = Transaksi::with('detailTransaksi.barang')->get();
        
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $laba = ($detail->barang->harga_jual - $detail->barang->harga_beli) * $detail->jumlah;
                $totalLaba += $laba;
            }
        }

        // Stok menipis (stok kurang dari 10)
        $stokMenipis = Barang::where('stok', '<', 10)->count();

        // Margin keuntungan
        $marginKeuntungan = $totalPenjualan > 0 ? round(($totalLaba / $totalPenjualan) * 100, 1) : 0;

        return view('dashboard.pemilik', compact(
            'totalPenjualan',
            'totalLaba',
            'stokMenipis',
            'marginKeuntungan'
        ));
    }
}

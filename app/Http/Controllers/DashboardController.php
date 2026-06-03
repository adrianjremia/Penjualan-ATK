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
        // Penjualan bulan ini
        $penjualanBulanIni = Transaksi::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_harga');

        // Hitung laba bulan ini
        $labaBulanIni = 0;
        $transaksisBulanIni = Transaksi::with('detailTransaksi.barang')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        
        foreach ($transaksisBulanIni as $transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $laba = ($detail->barang->harga_jual - $detail->barang->harga_beli) * $detail->jumlah;
                $labaBulanIni += $laba;
            }
        }

        // Penjualan hari ini
        $penjualanHariIni = Transaksi::whereDate('created_at', Carbon::today())
            ->sum('total_harga');

        // Stok kritis (stok < 5)
        $stokKritis = Barang::where('stok', '<', 5)->count();

        // Stok menengah (5-10)
        $stokMenengah = Barang::whereBetween('stok', [5, 10])->count();

        // Stok aman (>10)
        $stokAman = Barang::where('stok', '>', 10)->count();

        // Penjualan mingguan (last 7 days)
        $penjualanMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $total = Transaksi::whereDate('created_at', $date)->sum('total_harga');
            $penjualanMingguan[] = [
                'date' => $date,
                'total' => $total
            ];
        }

        // Top 5 Produk Terlaris bulan ini (by quantity)
        $top5Products = \DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->select('barang.id_barang', 'barang.nama_barang', 'barang.harga_jual')
            ->selectRaw('SUM(detail_transaksi.jumlah) as total_sold')
            ->selectRaw('SUM(detail_transaksi.jumlah * barang.harga_jual) as total_revenue')
            ->whereMonth('transaksi.created_at', Carbon::now()->month)
            ->whereYear('transaksi.created_at', Carbon::now()->year)
            ->groupBy('barang.id_barang', 'barang.nama_barang', 'barang.harga_jual')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Margin keuntungan bulan ini
        $marginBulanIni = $penjualanBulanIni > 0 ? round(($labaBulanIni / $penjualanBulanIni) * 100, 1) : 0;

        return view('dashboard.pemilik', compact(
            'penjualanBulanIni',
            'labaBulanIni',
            'penjualanHariIni',
            'stokKritis',
            'stokMenengah',
            'stokAman',
            'penjualanMingguan',
            'top5Products',
            'marginBulanIni'
        ));
    }
}

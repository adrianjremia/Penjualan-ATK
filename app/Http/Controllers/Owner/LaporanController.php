<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Services\ForecastingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = Transaksi::query();

        // Filter berdasarkan tanggal awal
        if ($request->filled('tanggal_awal')) {
            $tanggalAwal = $request->input('tanggal_awal');
            $query->whereDate('created_at', '>=', $tanggalAwal);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $tanggalAkhir = $request->input('tanggal_akhir');
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        // Total transaksi
        $totalTransaksi = $transaksis->count();

        // Total penjualan
        $totalPenjualan = $transaksis->sum('total_harga');

        // Group by date untuk laporan harian
        $laporanHarian = $transaksis->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($group) {
            $tanggal = Carbon::parse($group->first()->created_at);
            return [
                'tanggal' => $tanggal,
                'total_transaksi' => $group->count(),
                'total_penjualan' => $group->sum('total_harga'),
            ];
        })->sortByDesc('tanggal')->values();

        // Rata-rata per hari
        $rataRataPerHari = $totalTransaksi > 0 ? $totalPenjualan / count($laporanHarian) : 0;

        return view('owner.laporan.penjualan', compact(
            'totalTransaksi',
            'totalPenjualan',
            'laporanHarian',
            'rataRataPerHari'
        ));
    }

    public function labaRugi(Request $request)
    {
        $query = Transaksi::with('detailTransaksi.barang');

        // Filter berdasarkan tanggal awal
        if ($request->filled('tanggal_awal')) {
            $tanggalAwal = $request->input('tanggal_awal');
            $query->whereDate('created_at', '>=', $tanggalAwal);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $tanggalAkhir = $request->input('tanggal_akhir');
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        // Hitung pendapatan, modal, dan laba/rugi
        $totalPendapatan = 0;
        $totalModal = 0;
        $laporanHarian = [];

        foreach ($transaksis as $transaksi) {
            $tanggalKey = Carbon::parse($transaksi->created_at)->format('Y-m-d');
            
            if (!isset($laporanHarian[$tanggalKey])) {
                $laporanHarian[$tanggalKey] = [
                    'tanggal' => Carbon::parse($transaksi->created_at),
                    'pendapatan' => 0,
                    'modal' => 0,
                    'laba' => 0,
                ];
            }

            foreach ($transaksi->detailTransaksi as $detail) {
                $hargaJual = $detail->barang->harga_jual * $detail->jumlah;
                $hargaBeli = $detail->barang->harga_beli * $detail->jumlah;
                $laba = $hargaJual - $hargaBeli;

                $laporanHarian[$tanggalKey]['pendapatan'] += $hargaJual;
                $laporanHarian[$tanggalKey]['modal'] += $hargaBeli;
                $laporanHarian[$tanggalKey]['laba'] += $laba;

                $totalPendapatan += $hargaJual;
                $totalModal += $hargaBeli;
            }
        }

        // Sort by date descending
        krsort($laporanHarian);
        $laporanHarian = array_values($laporanHarian);

        $totalLaba = $totalPendapatan - $totalModal;
        $marginKeuntungan = $totalPendapatan > 0 ? round(($totalLaba / $totalPendapatan) * 100, 2) : 0;

        return view('owner.laporan.laba-rugi', compact(
            'totalPendapatan',
            'totalModal',
            'totalLaba',
            'laporanHarian',
            'marginKeuntungan'
        ));
    }

    public function stok(Request $request)
    {
        $query = Barang::query();

        // Search by nama barang
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        $barangs = $query->orderBy('nama_barang')->get();

        // Hitung revenue per barang dari transaksi bulan ini
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $barangsWithRevenue = $barangs->map(function($barang) use ($currentMonth, $endOfMonth) {
            $revenue = DetailTransaksi::join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                ->where('detail_transaksi.id_barang', $barang->id_barang)
                ->whereBetween('transaksi.created_at', [$currentMonth, $endOfMonth])
                ->sum(DB::raw('detail_transaksi.jumlah * ' . $barang->harga_jual));

            return array_merge($barang->toArray(), ['revenue' => $revenue ?? 0]);
        });

        // Top 5 Produk Terlaris Bulan Ini (by quantity sold)
        $top5Products = DetailTransaksi::join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('barangs', 'detail_transaksi.id_barang', '=', 'barangs.id_barang')
            ->select('barangs.id_barang', 'barangs.nama_barang', 'barangs.kategori', 'barangs.harga_jual')
            ->selectRaw('SUM(detail_transaksi.jumlah) as total_sold')
            ->selectRaw('SUM(detail_transaksi.jumlah * barangs.harga_jual) as total_revenue')
            ->whereBetween('transaksi.created_at', [$currentMonth, $endOfMonth])
            ->groupBy('barangs.id_barang', 'barangs.nama_barang', 'barangs.kategori', 'barangs.harga_jual')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $totalProduk = Barang::count();
        $totalRevenueBulanIni = $barangsWithRevenue->sum('revenue');

        return view('owner.laporan.stok', compact(
            'barangsWithRevenue',
            'top5Products',
            'totalProduk',
            'totalRevenueBulanIni'
        ));
    }

    public function forecasting(Request $request)
    {
        $method = $request->input('method', 'sma');
        $idBarang = $request->input('id_barang');

        // Generate forecast untuk semua produk atau produk tertentu
        if ($idBarang) {
            $forecast = ForecastingService::generateForecast($idBarang, $method);
            $barang = Barang::find($idBarang);
            
            $forecasts = [
                $idBarang => [
                    'id_barang' => $idBarang,
                    'nama_barang' => $barang->nama_barang,
                    'kategori' => $barang->kategori,
                    'stok_saat_ini' => $barang->stok,
                    'forecast' => $forecast['forecast'],
                    'historicalData' => $forecast['historicalData'],
                    'months' => $forecast['months'],
                    'needsRestock' => $barang->stok < $forecast['forecast']
                ]
            ];
        } else {
            $forecasts = ForecastingService::generateForecastForAllProducts($method);
        }

        // Get all barangs untuk dropdown filter
        $barangs = Barang::orderBy('nama_barang')->get();
        
        // Hitung ringkasan
        $totalProducts = count($forecasts);
        $productsNeedRestock = collect($forecasts)->filter(fn($f) => $f['needsRestock'])->count();

        return view('owner.laporan.forecasting', compact(
            'forecasts',
            'barangs',
            'method',
            'idBarang',
            'totalProducts',
            'productsNeedRestock'
        ));
    }
}

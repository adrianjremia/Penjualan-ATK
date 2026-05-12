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

        // Determine date range for filtering
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = $request->input('tanggal_awal');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $periodLabel = "dari " . Carbon::parse($tanggalAwal)->format('d M Y') . " hingga " . Carbon::parse($tanggalAkhir)->format('d M Y');
        } else {
            // Default ke bulan ini jika tidak ada filter
            $tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
            $tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
            $periodLabel = "Bulan " . Carbon::now()->format('F Y');
        }

        // Hitung revenue per barang berdasarkan periode yang dipilih
        $barangsWithRevenue = $barangs->map(function($barang) use ($tanggalAwal, $tanggalAkhir) {
            $revenue = DetailTransaksi::join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                ->where('detail_transaksi.id_barang', $barang->id_barang)
                ->whereBetween('transaksi.created_at', [$tanggalAwal, $tanggalAkhir])
                ->sum(DB::raw('detail_transaksi.jumlah * ' . $barang->harga_jual));

            return array_merge($barang->toArray(), ['revenue' => $revenue ?? 0]);
        });

        // Top 5 Produk Terlaris berdasarkan periode (by quantity sold)
        $top5Products = DetailTransaksi::join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->select('barang.id_barang', 'barang.nama_barang', 'barang.kategori', 'barang.harga_jual')
            ->selectRaw('SUM(detail_transaksi.jumlah) as total_sold')
            ->selectRaw('SUM(detail_transaksi.jumlah * barang.harga_jual) as total_revenue')
            ->whereBetween('transaksi.created_at', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('barang.id_barang', 'barang.nama_barang', 'barang.kategori', 'barang.harga_jual')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $totalProduk = Barang::count();
        $totalRevenuePeriode = $barangsWithRevenue->sum('revenue');

        return view('owner.laporan.stok', compact(
            'barangsWithRevenue',
            'top5Products',
            'totalProduk',
            'totalRevenuePeriode',
            'tanggalAwal',
            'tanggalAkhir',
            'periodLabel'
        ));
    }

    public function forecasting(Request $request)
    {
        $idBarang = $request->input('id_barang');

        // Generate forecast untuk semua produk
        $allForecasts = ForecastingService::generateForecastForAllProducts();

        // Jika ada produk yang dipilih, tampilkan detail untuk produk itu
        if ($idBarang) {
            $forecast = ForecastingService::generateForecast($idBarang);
            $barang = Barang::find($idBarang);
            
            // Format minggu labels untuk chart
            $formattedWeeks = array_map(function($week) {
                if (preg_match('/(\d{4})-W(\d{2})/', $week, $matches)) {
                    $year = $matches[1];
                    $weekNum = intval($matches[2]);
                    return "Minggu $weekNum";
                }
                return $week;
            }, $forecast['weeks']);
            
            $forecasts = [
                $idBarang => [
                    'id_barang' => $idBarang,
                    'nama_barang' => $barang->nama_barang,
                    'kategori' => $barang->kategori,
                    'stok_saat_ini' => $barang->stok,
                    'forecast_minggu_depan' => $forecast['forecast'],
                    'trend' => $forecast['trend'],
                    'status' => ForecastingService::determineStockStatus($barang, $forecast['weeklyBreakdown']),
                    'historicalData' => $forecast['historicalData'],
                    'weeks' => $formattedWeeks,
                    'weeklyBreakdown' => $forecast['weeklyBreakdown']
                ]
            ];
        } else {
            $forecasts = $allForecasts;
        }

        // Get all barangs untuk dropdown filter
        $barangs = Barang::orderBy('nama_barang')->get();
        
        // Hitung ringkasan dari semua produk
        $totalProducts = count($allForecasts);
        $productsCritical = collect($allForecasts)->filter(fn($f) => $f['status']['type'] === 'critical')->count();
        $productsMedium = collect($allForecasts)->filter(fn($f) => $f['status']['type'] === 'medium')->count();
        $productsSafe = collect($allForecasts)->filter(fn($f) => $f['status']['type'] === 'safe')->count();

        return view('owner.laporan.forecasting', compact(
            'forecasts',oih 
            'barangs',
            'idBarang',
            'totalProducts',
            'productsCritical',
            'productsMedium',
            'productsSafe',
            'allForecasts'
        ));
    }
}

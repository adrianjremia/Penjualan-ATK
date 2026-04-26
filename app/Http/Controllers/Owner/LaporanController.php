<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = Transaksi::query();

        // Filter berdasarkan tanggal awal
        if ($request->filled('tanggal_awal')) {
            $tanggalAwal = Carbon::createFromFormat('d/m/Y', $request->input('tanggal_awal'));
            $query->whereDate('created_at', '>=', $tanggalAwal);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $tanggalAkhir = Carbon::createFromFormat('d/m/Y', $request->input('tanggal_akhir'));
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
}

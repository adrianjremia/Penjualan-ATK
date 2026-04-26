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
}

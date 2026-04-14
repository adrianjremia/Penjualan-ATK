<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // FORM TRANSAKSI
    public function create()
    {
        $barang = Barang::where('stok', '>', 0)->get();
        return view('admin.transaksi.index', compact('barang'));
    }

public function index()
{
    $transaksis = Transaksi::withCount('detailTransaksi')
        ->orderBy('tanggal_transaksi', 'desc')
        ->get();

    $totalKeseluruhan = $transaksis->sum('total_harga');

    return view('admin.riwayatTransaksi.index', compact(
        'transaksis',
        'totalKeseluruhan'
    ));
}

    // SIMPAN TRANSAKSI
    public function store(Request $request)
{
    $cart = json_decode($request->cart, true);

    if (!$cart || count($cart) === 0) {
        return back()->with('error', 'Keranjang kosong');
    }

    $transaksi = null;

    DB::transaction(function () use ($cart, &$transaksi) {

        $transaksi = Transaksi::create([
            'id_user' => Auth::id(),
            'tanggal_transaksi' => now(),
            'total_harga' => 0
        ]);

        $total = 0;

        foreach ($cart as $item) {
            $barang = Barang::findOrFail($item['id_barang']);

            if ($barang->stok < $item['jumlah']) {
                throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi");
            }

            $subtotal = $item['harga'] * $item['jumlah'];

            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_barang' => $barang->id_barang,
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'subtotal' => $subtotal
            ]);

            $barang->decrement('stok', $item['jumlah']);
            $total += $subtotal;
        }

        $transaksi->update([
            'total_harga' => $total
        ]);
    });

    // 🔥 INI YANG PENTING
    return redirect()
        ->route('admin.transaksi.invoice', $transaksi->id_transaksi)
        ->with('success', 'Transaksi berhasil disimpan');
}

public function invoice($id)
{
    $transaksi = Transaksi::with(['detailTransaksi.barang', 'user'])
        ->findOrFail($id);

    return view('admin.transaksi.invoice', compact('transaksi'));
}
}
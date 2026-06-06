<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Services\ActivityLogService;
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

public function index(Request $request)
{
    $query = Transaksi::withCount('detailTransaksi');

    // Filter berdasarkan tanggal mulai
    if ($request->filled('tanggal_mulai')) {
        $tanggalMulai = $request->input('tanggal_mulai');
        $query->whereDate('created_at', '>=', $tanggalMulai);
    }

    // Filter berdasarkan tanggal akhir
    if ($request->filled('tanggal_akhir')) {
        $tanggalAkhir = $request->input('tanggal_akhir');
        $query->whereDate('created_at', '<=', $tanggalAkhir);
    }

    $transaksis = $query->orderBy('created_at', 'desc')->get();
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
            'tanggal_transaksi' => now()->format('Y-m-d H:i:s'),
            'total_harga' => 0,
            'created_at' => now()
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

        // Log activity
        ActivityLogService::logCreate(
            Auth::id(),
            'transaksi',
            $transaksi->id_transaksi,
            ['total_harga' => $total, 'items_count' => count($cart)]
        );
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

// EDIT INVOICE
public function editInvoice($id)
{
    $transaksi = Transaksi::with(['detailTransaksi.barang'])->findOrFail($id);
    return view('admin.transaksi.edit', compact('transaksi'));
}

// UPDATE INVOICE
public function updateInvoice(Request $request, $id)
{
    $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);

    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.id_detail' => 'required|exists:detail_transaksi,id_detail',
        'items.*.jumlah' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($transaksi, $validated) {
        $oldValues = [];
        $newValues = [];
        $newTotal = 0;

        foreach ($validated['items'] as $itemData) {
            $detail = DetailTransaksi::findOrFail($itemData['id_detail']);
            $barang = $detail->barang;

            $oldQty = $detail->jumlah;
            $newQty = $itemData['jumlah'];
            $qtyDelta = $newQty - $oldQty;

            // Validate stock availability
            if ($qtyDelta > 0) {
                // Increasing quantity - check if enough stock
                if ($barang->stok < $qtyDelta) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup untuk penambahan. Stok tersedia: {$barang->stok}");
                }
            }

            // Record old and new values
            $oldValues[] = [
                'id_detail' => $detail->id_detail,
                'barang' => $barang->nama_barang,
                'jumlah' => $oldQty,
                'subtotal' => $detail->subtotal
            ];

            $newSubtotal = $detail->harga * $newQty;
            $newValues[] = [
                'id_detail' => $detail->id_detail,
                'barang' => $barang->nama_barang,
                'jumlah' => $newQty,
                'subtotal' => $newSubtotal
            ];

            // Update stok
            if ($qtyDelta !== 0) {
                $barang->increment('stok', -$qtyDelta);
            }

            // Update detail transaksi
            $detail->update([
                'jumlah' => $newQty,
                'subtotal' => $newSubtotal
            ]);

            $newTotal += $newSubtotal;
        }

        // Update transaksi total
        $transaksi->update([
            'total_harga' => $newTotal
        ]);

        // Log activity
        ActivityLogService::logUpdate(
            Auth::id(),
            'transaksi',
            $transaksi->id_transaksi,
            $oldValues,
            $newValues
        );
    });

    return redirect()
        ->route('admin.transaksi.invoice', $transaksi->id_transaksi)
        ->with('success', 'Invoice berhasil diperbarui');
}
}

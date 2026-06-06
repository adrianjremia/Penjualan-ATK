<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    // INDEX - View all transactions
    public function index(Request $request)
    {
        $query = Transaksi::withCount('detailTransaksi');

        // Filter by start date
        if ($request->filled('tanggal_mulai')) {
            $tanggalMulai = $request->input('tanggal_mulai');
            $query->whereDate('created_at', '>=', $tanggalMulai);
        }

        // Filter by end date
        if ($request->filled('tanggal_akhir')) {
            $tanggalAkhir = $request->input('tanggal_akhir');
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }

        // Filter by user if specified
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->input('id_user'));
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalKeseluruhan = $transaksis->sum('total_harga');

        // Get list of users for filter dropdown
        $users = \App\Models\User::where('role', 0)->get(); // Get admins only

        return view('owner.riwayat-transaksi.index', compact(
            'transaksis',
            'totalKeseluruhan',
            'users'
        ));
    }

    // SHOW - View invoice
    public function show($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.barang', 'user'])
            ->findOrFail($id);

        return view('owner.riwayat-transaksi.invoice', compact('transaksi'));
    }

    // EDIT INVOICE - Show edit form
    public function editInvoice($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.barang'])->findOrFail($id);
        return view('owner.riwayat-transaksi.edit', compact('transaksi'));
    }

    // UPDATE INVOICE - Process edit with stock adjustment
    public function updateInvoice(Request $request, $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id_detail' => 'required|exists:detail_transaksi,id_detail',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Check stock availability before updating
        $stockWarnings = [];
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
                    $stockWarnings[] = "Stok {$barang->nama_barang} tidak cukup untuk penambahan. Stok tersedia: {$barang->stok}";
                }
            }
        }

        // If there are stock warnings, return to edit page with warning
        if (!empty($stockWarnings)) {
            return redirect()
                ->route('owner.riwayat-transaksi.edit', $transaksi->id_transaksi)
                ->with('warning', implode(' | ', $stockWarnings))
                ->withInput();
        }

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
            ->route('owner.riwayat-transaksi.show', $transaksi->id_transaksi)
            ->with('success', 'Invoice berhasil diperbarui');
    }

    // DESTROY - Delete transaction with stock refund
    public function destroy($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);
        $deletedData = [];

        DB::transaction(function () use ($transaksi, &$deletedData) {
            // Refund stock for all items
            foreach ($transaksi->detailTransaksi as $detail) {
                $deletedData[] = [
                    'id_detail' => $detail->id_detail,
                    'barang' => $detail->barang->nama_barang,
                    'jumlah' => $detail->jumlah,
                    'subtotal' => $detail->subtotal
                ];

                // Refund stock
                $detail->barang->increment('stok', $detail->jumlah);

                // Delete detail
                $detail->delete();
            }

            // Delete transaksi
            $transaksi->delete();

            // Log activity
            ActivityLogService::logDelete(
                Auth::id(),
                'transaksi',
                $transaksi->id_transaksi,
                ['items' => $deletedData, 'total' => $transaksi->total_harga]
            );
        });

        return redirect()
            ->route('owner.riwayat-transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan');
    }
}

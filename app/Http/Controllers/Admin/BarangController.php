<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Tampilkan semua barang
    public function index(Request $request)
    {
        // Handle sorting
        $currentSort = request('sort', 'id_barang');
        $currentDirection = request('direction', 'desc');

        // Validate allowed sort columns
        $allowedSorts = ['id_barang', 'nama_barang', 'kategori', 'harga_beli', 'harga_jual', 'stok', 'satuan'];
        if (!in_array($currentSort, $allowedSorts)) {
            $currentSort = 'id_barang';
            $currentDirection = 'desc';
        }

        // Validate direction
        if (!in_array($currentDirection, ['asc', 'desc'])) {
            $currentDirection = 'desc';
        }

        // Apply sorting to query
        $barang = Barang::orderBy($currentSort, $currentDirection)->get();

        return view('admin.barang.index', compact('barang', 'currentSort', 'currentDirection'));
    }

    // Form tambah barang
    public function create()
    {
        return view('admin.barang.create');
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|integer',
            'satuan'      => 'required',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang berhasil ditambahkan');
    }

    // Form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|integer',
            'satuan'      => 'required',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang berhasil diperbarui');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang berhasil dihapus');
    }
}

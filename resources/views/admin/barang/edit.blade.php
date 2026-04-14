@extends('layouts.admin')

@section('content')
<h3>Edit Barang</h3>

<form action="{{ route('admin.barang.update', $barang->id_barang) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required>
    </div>

    <div>
        <label>Kategori</label>
        <input type="text" name="kategori" value="{{ $barang->kategori }}" required>
    </div>

    <div>
        <label>Harga Beli</label>
        <input type="number" name="harga_beli" value="{{ $barang->harga_beli }}" required>
    </div>

    <div>
        <label>Harga Jual</label>
        <input type="number" name="harga_jual" value="{{ $barang->harga_jual }}" required>
    </div>

    <div>
        <label>Stok</label>
        <input type="number" name="stok" value="{{ $barang->stok }}" required>
    </div>

    <div>
        <label>Satuan</label>
        <input type="text" name="satuan" value="{{ $barang->satuan }}" required>
    </div>

    <button type="submit">Update</button>
</form>
@endsection
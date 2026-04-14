@extends('layouts.admin')

@section('content')
<h1>Tambah Barang</h1>

@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.barang.store') }}" method="POST">
    @csrf

    <div>
        <label>Nama Barang</label><br>
        <input type="text" name="nama_barang">
    </div>

    <div>
        <label>Kategori</label><br>
        <input type="text" name="kategori">
    </div>

    <div>
        <label>Harga Beli</label><br>
        <input type="number" name="harga_beli">
    </div>

    <div>
        <label>Harga Jual</label><br>
        <input type="number" name="harga_jual">
    </div>

    <div>
        <label>Stok</label><br>
        <input type="number" name="stok">
    </div>

    <div>
        <label>Satuan</label><br>
        <input type="text" name="satuan">
    </div>

    <br>
    <button type="submit">Simpan</button>
</form>
@endsection
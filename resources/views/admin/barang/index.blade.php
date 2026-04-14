@extends('layouts.admin')

@section('content')
<h1>Data Barang</h1>

<a href="{{ route('admin.barang.create') }}">+ Tambah Barang</a>
<br><br>

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($barang as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->kategori }}</td>
            <td>{{ $item->harga_beli }}</td>
            <td>{{ $item->harga_jual }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->satuan }}</td>
            <td>
    <a href="{{ route('admin.barang.edit', $item->id_barang) }}">
        Edit
    </a>

    <form action="{{ route('admin.barang.destroy', $item->id_barang) }}"
          method="POST"
          style="display:inline"
          onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
        @csrf
        @method('DELETE')

        <button type="submit">
            Hapus
        </button>
    </form>
</td>
        </tr>
        @empty
        <tr>
            <td colspan="8">Data barang kosong</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
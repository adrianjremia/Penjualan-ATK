@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        margin-bottom: 32px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 14px;
        color: #6b7280;
    }

    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 16px;
    }

    .search-box {
        flex: 1;
        max-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }

    .search-box input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-primary {
        padding: 10px 18px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .alert-success {
        padding: 12px 16px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 6px;
        border: 1px solid #a7f3d0;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .table-wrapper {
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .table thead th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #374151;
    }

    .table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .badge-stok {
        display: inline-block;
        padding: 4px 10px;
        background: #dcfce7;
        color: #166534;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-edit {
        background: transparent;
        color: #2563eb;
        border: none;
        padding: 0;
    }

    .btn-edit:hover {
        color: #1d4ed8;
    }

    .btn-delete {
        background: transparent;
        color: #ef4444;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        color: #dc2626;
    }

    .btn-delete img,
    .btn-edit img {
        width: 16px;
        height: 16px;
        object-fit: contain;
    }

    .empty-state {
        text-align: center;
        padding: 48px 16px;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }
</style>

<div class="page-header">
    <h1>Data Barang</h1>
    <p>Kelola data produk toko Anda</p>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="page-top">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari barang...">
    </div>
    <a href="{{ route('admin.barang.create') }}" class="btn-primary">
        <span>+</span> Tambah Barang
    </a>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang as $item)
            <tr>
                <td>{{ str_pad($item->id_barang, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->kategori }}</td>
                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td>
                    <span class="badge-stok">{{ $item->stok }} {{ $item->satuan }}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.barang.edit', $item->id_barang) }}" class="btn-icon btn-edit" title="Edit">
                            <img src="{{ asset('images/icons/edit.png') }}" alt="Edit">
                            Edit
                        </a>
                        <form action="{{ route('admin.barang.destroy', $item->id_barang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                <img src="{{ asset('images/icons/hapus.png') }}" alt="Hapus">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <p>Data barang kosong</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('.table tbody tr');

        tableRows.forEach(row => {
            if (row.textContent.toLowerCase().includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection

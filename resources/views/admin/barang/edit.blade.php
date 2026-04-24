@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: #6b7280;
        font-size: 14px;
    }

    .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        background: #ffffff;
        max-width: 600px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-group input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-group input::placeholder {
        color: #9ca3af;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 20px;
        list-style: none;
    }

    .alert-error li {
        color: #991b1b;
        font-size: 13px;
        padding: 4px 0;
        padding-left: 20px;
        position: relative;
    }

    .alert-error li:before {
        content: "×";
        position: absolute;
        left: 0;
        font-weight: bold;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-secondary {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        text-decoration: none;
        font-size: 13px;
        margin-bottom: 16px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: #1f2937;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .card {
            max-width: 100%;
        }
    }
</style>

<a href="{{ route('admin.barang.index') }}" class="back-link">← Kembali ke Data Barang</a>

<div class="page-header">
    <h1>Edit Barang</h1>
    <p>Perbarui informasi barang</p>
</div>

<div class="card">
    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.barang.update', $barang->id_barang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input 
                    type="text" 
                    id="nama_barang"
                    name="nama_barang" 
                    placeholder="Contoh: Indomie Goreng"
                    value="{{ $barang->nama_barang }}" 
                    required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input 
                    type="text" 
                    id="kategori"
                    name="kategori" 
                    placeholder="Contoh: Makanan"
                    value="{{ $barang->kategori }}" 
                    required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="harga_beli">Harga Beli</label>
                <input 
                    type="number" 
                    id="harga_beli"
                    name="harga_beli" 
                    placeholder="0"
                    value="{{ $barang->harga_beli }}" 
                    required>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input 
                    type="number" 
                    id="harga_jual"
                    name="harga_jual" 
                    placeholder="0"
                    value="{{ $barang->harga_jual }}" 
                    required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="stok">Stok</label>
                <input 
                    type="number" 
                    id="stok"
                    name="stok" 
                    placeholder="0"
                    value="{{ $barang->stok }}" 
                    required>
            </div>

            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input 
                    type="text" 
                    id="satuan"
                    name="satuan" 
                    placeholder="Contoh: pcs, box, kg"
                    value="{{ $barang->satuan }}" 
                    required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Barang</button>
            <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection

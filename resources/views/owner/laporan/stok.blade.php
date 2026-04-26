@extends('layouts.owner')

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-icon {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }

    .stat-value.aman {
        color: #059669;
    }

    .stat-value.menipis {
        color: #dc2626;
    }

    .stat-description {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 8px;
    }

    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
    }

    .card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .search-box input {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-icon {
        width: 20px;
        height: 20px;
        color: #9ca3af;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    table th {
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: #1f2937;
    }

    table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    table tbody tr:last-child td {
        border-bottom: none;
    }

    table tbody tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.aman {
        background: #ecfdf5;
        color: #059669;
    }

    .status-badge.menipis {
        background: #fef2f2;
        color: #dc2626;
    }

    .status-badge img {
        width: 14px;
        height: 14px;
        object-fit: contain;
    }

    .legend {
        display: flex;
        gap: 32px;
        padding: 16px 0;
        margin-top: 20px;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    .legend-dot.aman {
        background: #059669;
    }

    .legend-dot.menipis {
        background: #dc2626;
    }

    .alert-warning {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 6px;
        padding: 16px;
        margin-top: 24px;
        display: flex;
        gap: 12px;
    }

    .alert-warning img {
        width: 20px;
        height: 20px;
        object-fit: contain;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 600;
        color: #991b1b;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .alert-message {
        color: #7f1d1d;
        font-size: 13px;
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .legend {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>

<div class="page-header">
    <h1>Informasi Stok</h1>
    <p>Monitor ketersediaan stok barang di toko</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Produk</span>
            <img src="{{ asset('images/icons/totalProduk.png') }}" alt="Total" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">{{ $totalProduk }}</div>
            <div class="stat-description">Item terdaftar</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Stok Aman</span>
            <img src="{{ asset('images/icons/check.png') }}" alt="Aman" class="stat-icon">
        </div>
        <div>
            <div class="stat-value aman">{{ $stokAman }}</div>
            <div class="stat-description">Produk stok cukup</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Stok Menipis</span>
            <img src="{{ asset('images/icons/warning.png') }}" alt="Menipis" class="stat-icon">
        </div>
        <div>
            <div class="stat-value menipis">{{ $stokMenipis }}</div>
            <div class="stat-description">Perlu restock segera</div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Daftar Stok Barang</h2>

    <div class="search-box">
        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <form method="GET" style="display: flex; flex: 1; gap: 8px;">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari barang..." 
                value="{{ request('search') }}"
                onchange="this.form.submit()">
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Status Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr>
                    <td>P{{ str_pad($barang->id_barang, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori }}</td>
                    <td>{{ $barang->stok }} {{ $barang->satuan }}</td>
                    <td>
                        @if($barang->stok >= 10)
                            <span class="status-badge aman">
                                <img src="{{ asset('images/icons/check.png') }}" alt="Aman">
                                Aman
                            </span>
                        @else
                            <span class="status-badge menipis">
                                <img src="{{ asset('images/icons/warning.png') }}" alt="Menipis">
                                Menipis
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #9ca3af;">Tidak ada barang yang sesuai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="legend-dot aman"></div>
            <span><strong>Aman:</strong> Stok ≥ 10 unit</span>
        </div>
        <div class="legend-item">
            <div class="legend-dot menipis"></div>
            <span><strong>Menipis:</strong> Stok < 10 unit (perlu restock)</span>
        </div>
    </div>

    @if($stokMenipis > 0)
    <div class="alert-warning">
        <img src="{{ asset('images/icons/warning.png') }}" alt="Warning">
        <div class="alert-content">
            <div class="alert-title">Perhatian: {{ $stokMenipis }} Produk Stok Menipis</div>
            <div class="alert-message">Segera lakukan restock untuk produk dengan status "Menipis" agar tidak kehabisan stok.</div>
        </div>
    </div>
    @endif
</div>

@endsection

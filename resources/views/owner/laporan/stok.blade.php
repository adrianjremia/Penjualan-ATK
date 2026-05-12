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
        margin-bottom: 24px;
    }

    .card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .top5-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .top5-item {
        background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .top5-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #3b82f6;
        color: white;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
    }

    .top5-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }

    .top5-stats {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
    }

    .top5-stat {
        flex: 1;
    }

    .top5-stat-label {
        color: #6b7280;
        font-size: 11px;
        margin-bottom: 4px;
    }

    .top5-stat-value {
        color: #1f2937;
        font-weight: 600;
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

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .top5-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>Laporan Penjualan & Inventaris</h1>
    <p>Analisis penjualan dan status stok barang</p>
</div>

<!-- Filter Periode -->
<div class="card">
    <h2>Filter Periode</h2>
    
    <form method="GET" id="filterForm" class="filter-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 0;">
        <div class="filter-item">
            <label>Tanggal Awal</label>
            <input type="date" id="tanggalAwal" name="tanggal_awal" value="{{ $tanggalAwal }}">
        </div>

        <div class="filter-item">
            <label>Tanggal Akhir</label>
            <input type="date" id="tanggalAkhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalAwal = document.getElementById('tanggalAwal');
    const tanggalAkhir = document.getElementById('tanggalAkhir');
    const filterForm = document.getElementById('filterForm');

    function submitFilter() {
        filterForm.submit();
    }

    tanggalAwal.addEventListener('change', submitFilter);
    tanggalAkhir.addEventListener('change', submitFilter);
});
</script>

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
            <span class="stat-label">Revenue Periode</span>
            <img src="{{ asset('images/icons/pendapatanHarian.png') }}" alt="Revenue" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalRevenuePeriode, 0, ',', '.') }}</div>
            <div class="stat-description">{{ $periodLabel }}</div>
        </div>
    </div>
</div>

@if($top5Products->count() > 0)
<div class="card">
    <h2>Top 5 Produk Terlaris {{ $periodLabel }}</h2>

    <div class="top5-grid">
        @foreach($top5Products as $index => $product)
        <div class="top5-item">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div class="top5-rank">{{ $index + 1 }}</div>
                <div style="flex: 1;">
                    <div class="top5-name">{{ $product->nama_barang }}</div>
                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">{{ $product->kategori }}</div>
                </div>
            </div>

            <div class="top5-stats">
                <div class="top5-stat">
                    <div class="top5-stat-label">Terjual</div>
                    <div class="top5-stat-value">{{ $product->total_sold }} pcs</div>
                </div>
                <div class="top5-stat">
                    <div class="top5-stat-label">Revenue</div>
                    <div class="top5-stat-value">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card">
    <h2>Daftar Inventaris & Penjualan Produk</h2>

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
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangsWithRevenue as $barang)
                <tr>
                    <td>P{{ str_pad($barang['id_barang'], 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $barang['nama_barang'] }}</td>
                    <td>{{ $barang['kategori'] }}</td>
                    <td>{{ $barang['stok'] }} {{ $barang['satuan'] }}</td>
                    <td>Rp {{ number_format($barang['revenue'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #9ca3af;">Tidak ada barang yang sesuai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

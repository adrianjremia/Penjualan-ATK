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

    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .card h2 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .filter-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-item label {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }

    .filter-item input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background: #f9fafb;
        font-family: inherit;
    }

    .filter-item input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-description {
        font-size: 12px;
        color: #9ca3af;
    }

    .stat-icon {
        width: 32px;
        height: 32px;
        object-fit: contain;
        flex-shrink: 0;
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

    table tbody tr:hover {
        background: #f9fafb;
    }

    .summary-box {
        background: #f0f9ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .summary-label {
        font-size: 13px;
        color: #6b7280;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #2563eb;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .filter-group {
            grid-template-columns: 1fr;
        }

        .summary-box {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }
</style>

<div class="page-header">
    <h1>Laporan Penjualan</h1>
    <p>Analisis penjualan berdasarkan periode waktu</p>
</div>

<div class="card">
    <h2>Filter Periode</h2>
    
    <form method="GET" class="filter-group">
        <div class="filter-item">
            <label>Tanggal Awal</label>
            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
        </div>

        <div class="filter-item">
            <label>Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        </div>

        <button type="submit" style="align-self: flex-end; padding: 10px 24px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Filter</button>
    </form>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Total Transaksi</span>
            <span class="stat-value">{{ $totalTransaksi }}</span>
            <span class="stat-description">Semua waktu</span>
        </div>
        <img src="{{ asset('images/icons/transaksiPenjualan.png') }}" alt="Transaksi" class="stat-icon">
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Total Penjualan</span>
            <span class="stat-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
            <span class="stat-description">Semua waktu</span>
        </div>
        <img src="{{ asset('images/icons/labaRugi.png') }}" alt="Penjualan" class="stat-icon">
    </div>
</div>

<div class="card">
    <h2>Laporan Harian</h2>

    @if($laporanHarian->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Transaksi</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanHarian as $item)
                    <tr>
                        <td>{{ $item['tanggal']->translatedFormat('d F Y') }}</td>
                        <td>{{ $item['total_transaksi'] }} transaksi</td>
                        <td>Rp {{ number_format($item['total_penjualan'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <span class="summary-label">Total Keseluruhan:</span>
                <span class="summary-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Rata-rata per hari:</span>
                <span class="summary-value">Rp {{ number_format($rataRataPerHari, 0, ',', '.') }}</span>
            </div>
        </div>
    @else
        <div class="empty-state">
            <p>Belum ada data penjualan untuk periode ini</p>
        </div>
    @endif
</div>

@endsection

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
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
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
        line-height: 1.2;
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

    .weekly-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
    }

    .weekly-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
    }

    .weekly-date {
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .weekly-value {
        font-size: 16px;
        font-weight: 700;
        color: #2563eb;
    }

    .inventory-status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .inventory-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        border-left: 4px solid #9ca3af;
    }

    .inventory-card.kritis {
        border-left-color: #ef4444;
        background: #fef2f2;
    }

    .inventory-card.menengah {
        border-left-color: #f59e0b;
        background: #fffbf0;
    }

    .inventory-card.aman {
        border-left-color: #10b981;
        background: #f0fdf4;
    }

    .inventory-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .inventory-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .inventory-card.kritis .inventory-value {
        color: #ef4444;
    }

    .inventory-card.menengah .inventory-value {
        color: #f59e0b;
    }

    .inventory-card.aman .inventory-value {
        color: #10b981;
    }

    .inventory-desc {
        font-size: 12px;
        color: #6b7280;
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

    .alerts-section {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .alert-item {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 16px;
        border-radius: 6px;
    }

    .alert-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .alert-desc {
        font-size: 13px;
        color: #6b7280;
    }

    .empty-state {
        text-align: center;
        padding: 32px 20px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .inventory-status-grid {
            grid-template-columns: 1fr;
        }

        .weekly-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
    }
</style>

<div class="page-header">
    <h1>Dashboard Pemilik Toko</h1>
    <p>Ringkasan laporan bisnis Anda bulan ini</p>
</div>

<!-- KPI Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Penjualan Bulan Ini</span>
            <span class="stat-value">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</span>
            <span class="stat-description">Total revenue</span>
        </div>
        <img src="{{ asset('images/icons/pendapatanHarian.png') }}" alt="Penjualan" class="stat-icon">
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Laba Bulan Ini</span>
            <span class="stat-value">Rp {{ number_format($labaBulanIni, 0, ',', '.') }}</span>
            <span class="stat-description">Margin: {{ $marginBulanIni }}%</span>
        </div>
        <img src="{{ asset('images/icons/laba.png') }}" alt="Laba" class="stat-icon">
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Penjualan Hari Ini</span>
            <span class="stat-value">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</span>
            <span class="stat-description">Revenue hari ini</span>
        </div>
        <img src="{{ asset('images/icons/chartUp.png') }}" alt="Hari Ini" class="stat-icon">
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <span class="stat-label">Stok Kritis</span>
            <span class="stat-value">{{ $stokKritis }}</span>
            <span class="stat-description">Produk perlu restock</span>
        </div>
        <img src="{{ asset('images/icons/warning.png') }}" alt="Kritis" class="stat-icon">
    </div>
</div>

<!-- Weekly Sales Overview -->
<div class="card">
    <h2>Penjualan 7 Hari Terakhir</h2>
    <div class="weekly-grid">
        @foreach($penjualanMingguan as $item)
        <div class="weekly-item">
            <div class="weekly-date">{{ Carbon\Carbon::parse($item['date'])->translatedFormat('d M') }}</div>
            <div class="weekly-value">Rp {{ number_format($item['total'], 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>
</div>

<!-- Inventory Health -->
<div class="card">
    <h2>Status Inventaris</h2>
    <div class="inventory-status-grid">
        <div class="inventory-card kritis">
            <div class="inventory-label">Stok Kritis</div>
            <div class="inventory-value">{{ $stokKritis }}</div>
            <div class="inventory-desc">Stok < 5 unit (segera restock)</div>
        </div>

        <div class="inventory-card menengah">
            <div class="inventory-label">Stok Menengah</div>
            <div class="inventory-value">{{ $stokMenengah }}</div>
            <div class="inventory-desc">Stok 5-10 unit (perhatikan)</div>
        </div>

        <div class="inventory-card aman">
            <div class="inventory-label">Stok Aman</div>
            <div class="inventory-value">{{ $stokAman }}</div>
            <div class="inventory-desc">Stok > 10 unit (aman)</div>
        </div>
    </div>
</div>

<!-- Top 5 Products -->
@if($top5Products->count() > 0)
<div class="card">
    <h2>Top 5 Produk Terlaris (Bulan Ini)</h2>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Terjual</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top5Products as $product)
                <tr>
                    <td>{{ $product->nama_barang }}</td>
                    <td>{{ $product->total_sold }} unit</td>
                    <td>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <h2>Top 5 Produk Terlaris (Bulan Ini)</h2>
    <div class="empty-state">
        <p>Belum ada penjualan bulan ini</p>
    </div>
</div>
@endif

<!-- Alerts -->
@php
    $alerts = [];
    if ($stokKritis > 0) {
        $alerts[] = [
            'title' => 'Stok Kritis',
            'desc' => "Anda memiliki $stokKritis produk dengan stok < 5 unit. Segera lakukan pengadaan barang."
        ];
    }
    if ($stokMenengah > 2) {
        $alerts[] = [
            'title' => 'Perhatian Stok',
            'desc' => "Anda memiliki $stokMenengah produk dengan stok menengah. Monitor inventory untuk mencegah kehabisan stok."
        ];
    }
@endphp

@if(count($alerts) > 0)
<div class="card">
    <h2>Notifikasi</h2>
    <div class="alerts-section">
        @foreach($alerts as $alert)
        <div class="alert-item">
            <div class="alert-title">{{ $alert['title'] }}</div>
            <div class="alert-desc">{{ $alert['desc'] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

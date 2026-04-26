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

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 32px;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
    }

    .info-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 14px;
        color: #6b7280;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .info-value.profit {
        color: #059669;
    }

    .info-value.active {
        color: #059669;
    }

    .quick-access {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .quick-link {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }

    .quick-link:last-child {
        border-bottom: none;
    }

    .quick-link:hover {
        color: #2563eb;
    }

    .quick-link-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .quick-link-desc {
        font-size: 12px;
        color: #9ca3af;
    }

    @media (max-width: 1024px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>Dashboard Pemilik Toko</h1>
    <p>Ringkasan laporan bisnis Anda</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Penjualan</span>
            <img src="{{ asset('images/icons/pendapatanHarian.png') }}" alt="Penjualan" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
            <div class="stat-description">Semua waktu</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Laba</span>
            <img src="{{ asset('images/icons/laba.png') }}" alt="Laba" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalLaba, 0, ',', '.') }}</div>
            <div class="stat-description">Laba bersih</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Stok Menipis</span>
            <img src="{{ asset('images/icons/warning.png') }}" alt="Warning" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">{{ $stokMenipis }}</div>
            <div class="stat-description">Produk perlu restock</div>
        </div>
    </div>
</div>

<div class="info-grid">
    <div class="info-card">
        <h3>Informasi Bisnis</h3>
        
        <div class="info-row">
            <span class="info-label">Margin Keuntungan:</span>
            <span class="info-value profit">{{ $marginKeuntungan }}%</span>
        </div>

        <div class="info-row">
            <span class="info-label">Status Toko:</span>
            <span class="info-value active">Aktif</span>
        </div>
    </div>

    <div class="info-card">
        <h3>Akses Cepat</h3>

        <div class="quick-access">
            <a href="{{ url('/pemilik/laporan-penjualan') }}" class="quick-link">
                <span class="quick-link-title">Laporan Penjualan</span>
                <span class="quick-link-desc">Lihat detail penjualan per periode</span>
            </a>

            <a href="{{ url('/pemilik/laporan-laba-rugi') }}" class="quick-link">
                <span class="quick-link-title">Laporan Laba Rugi</span>
                <span class="quick-link-desc">Analisis keuntungan bisnis</span>
            </a>

            <a href="{{ url('/pemilik/informasi-stok') }}" class="quick-link">
                <span class="quick-link-title">Informasi Stok</span>
                <span class="quick-link-desc">Monitor ketersediaan barang</span>
            </a>
        </div>
    </div>
</div>

@endsection

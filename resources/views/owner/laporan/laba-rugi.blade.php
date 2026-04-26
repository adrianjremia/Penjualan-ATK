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
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        background: #ffffff;
        margin-bottom: 24px;
    }

    .card h2 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .filter-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }

    .filter-group input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
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
        width: 24px;
        height: 24px;
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

    .stat-card.profit .stat-value {
        color: #059669;
    }

    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 24px;
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

    .text-profit {
        color: #059669;
        font-weight: 600;
    }

    .text-expense {
        color: #ef4444;
        font-weight: 600;
    }

    .summary-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 6px;
        margin-bottom: 24px;
        text-align: center;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .summary-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .summary-value.profit {
        color: #059669;
    }

    .margin-section {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }

    .margin-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .margin-value {
        font-size: 36px;
        font-weight: 700;
        color: #059669;
    }

    @media (max-width: 768px) {
        .filter-section {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .summary-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>Laporan Laba Rugi</h1>
    <p>Analisis keuntungan dan modal bisnis Anda</p>
</div>

<div class="card">
    <h2>Filter Periode</h2>

    <form method="GET" id="filterForm" class="filter-section">
        <div class="filter-group">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}">
        </div>

        <div class="filter-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        </div>
    </form>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Pendapatan</span>
            <img src="{{ asset('images/icons/chartUp.png') }}" alt="Pendapatan" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-description">Penjualan kotor</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Modal</span>
            <img src="{{ asset('images/icons/chartDown.png') }}" alt="Modal" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
            <div class="stat-description">Harga beli barang</div>
        </div>
    </div>

    <div class="stat-card profit">
        <div class="stat-header">
            <span class="stat-label">Laba Bersih</span>
            <img src="{{ asset('images/icons/laba.png') }}" alt="Laba" class="stat-icon">
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalLaba, 0, ',', '.') }}</div>
            <div class="stat-description">Margin: {{ $marginKeuntungan }}%</div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Ringkasan Laba Rugi Harian</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pendapatan</th>
                    <th>Modal</th>
                    <th>Laba/Rugi</th>
                    <th>Margin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanHarian as $laporan)
                <tr>
                    <td>{{ $laporan['tanggal']->translatedFormat('d F Y') }}</td>
                    <td class="text-profit">Rp {{ number_format($laporan['pendapatan'], 0, ',', '.') }}</td>
                    <td class="text-expense">Rp {{ number_format($laporan['modal'], 0, ',', '.') }}</td>
                    <td class="text-profit">Rp {{ number_format($laporan['laba'], 0, ',', '.') }}</td>
                    <td>{{ $laporan['pendapatan'] > 0 ? round(($laporan['laba'] / $laporan['pendapatan']) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 32px 12px; color: #9ca3af;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="summary-section">
        <div class="summary-item">
            <span class="summary-label">Total Pendapatan</span>
            <span class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Total Modal</span>
            <span class="summary-value">Rp {{ number_format($totalModal, 0, ',', '.') }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Laba Bersih</span>
            <span class="summary-value profit">Rp {{ number_format($totalLaba, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="margin-section">
        <div class="margin-label">Margin Keuntungan</div>
        <div class="margin-value">{{ $marginKeuntungan }}%</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalAwal = document.getElementById('tanggal_awal');
    const tanggalAkhir = document.getElementById('tanggal_akhir');
    const filterForm = document.getElementById('filterForm');

    function submitFilter() {
        filterForm.submit();
    }

    tanggalAwal.addEventListener('change', submitFilter);
    tanggalAkhir.addEventListener('change', submitFilter);
});
</script>

@endsection

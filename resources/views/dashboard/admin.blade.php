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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    .stat-content h3 {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #9ca3af;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        background: #ffffff;
    }

    .card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
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

    .id-transaksi {
        font-weight: 600;
        color: #1f2937;
    }

    .tanggal-cell {
        color: #6b7280;
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 32px 12px;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang di sistem Point of Sale</p>
</div>

<!-- STAT CARDS -->
<div class="stats-grid">
    <!-- Total Barang -->
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Barang</h3>
            <div class="stat-value">{{ $totalBarang }}</div>
            <div class="stat-label">Produk terdaftar</div>
        </div>
        <div class="stat-icon">
            <img src="{{ asset('images/icons/dataBarang.png') }}" alt="Total Barang">
        </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="stat-card">
        <div class="stat-content">
            <h3>Transaksi Hari Ini</h3>
            <div class="stat-value">{{ $transaksiHariIni }}</div>
            <div class="stat-label">Transaksi</div>
        </div>
        <div class="stat-icon">
            <img src="{{ asset('images/icons/transaksiPenjualan.png') }}" alt="Transaksi Hari Ini">
        </div>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="stat-card">
        <div class="stat-content">
            <h3>Pendapatan Hari Ini</h3>
            <div class="stat-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="stat-label">Total penjualan</div>
        </div>
        <div class="stat-icon">
            <img src="{{ asset('images/icons/pendapatanHarian.png') }}" alt="Pendapatan Hari Ini">
        </div>
    </div>
</div>

<!-- TRANSAKSI TERBARU -->
<div class="card">
    <h2>Transaksi Terbaru</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Jumlah Item</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transaksiTerbaru as $t)
                <tr>
                    <td class="id-transaksi">T{{ str_pad($t->id_transaksi, 3, '0', STR_PAD_LEFT) }}</td>

                    <td class="tanggal-cell">
                        {{ \Carbon\Carbon::parse($t->created_at)->translatedFormat('d F Y \p\u\k\u\l H.i') }}
                    </td>

                    <td>{{ $t->detail_transaksi_count }} item</td>

                    <td>
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">Belum ada transaksi hari ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

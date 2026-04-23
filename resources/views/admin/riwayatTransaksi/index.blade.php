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
    }

    .card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .filter-section {
        display: flex;
        gap: 24px;
        align-items: flex-end;
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
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group label img {
        width: 16px;
        height: 16px;
        object-fit: contain;
    }

    .filter-group input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        background: #f9fafb;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
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
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: none;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        padding: 0;
    }

    .btn-detail:hover {
        color: #1f2937;
    }

    .btn-detail img {
        width: 16px;
        height: 16px;
        object-fit: contain;
    }

    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 32px 12px;
    }

    .summary-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 24px;
    }

    .summary-left {
        color: #6b7280;
        font-size: 14px;
    }

    .summary-right {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            gap: 16px;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        table {
            font-size: 12px;
        }

        table th,
        table td {
            padding: 10px 8px;
        }

        .summary-section {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }
</style>

<div class="page-header">
    <h1>Riwayat Transaksi</h1>
    <p>Lihat semua transaksi yang telah dilakukan</p>
</div>

<div class="card">
    <h2>Daftar Transaksi</h2>

    <div class="filter-section">
        <div class="filter-group">
            <label>
                <img src="{{ asset('images/icons/date.png') }}" alt="Tanggal">
                Tanggal Mulai
            </label>
            <input type="date" id="tanggalMulai" placeholder="dd/mm/yyyy">
        </div>

        <div class="filter-group">
            <label>
                <img src="{{ asset('images/icons/date.png') }}" alt="Tanggal">
                Tanggal Akhir
            </label>
            <input type="date" id="tanggalAkhir" placeholder="dd/mm/yyyy">
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Jumlah Item</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transaksis as $t)
                <tr>
                    <td class="id-transaksi">T{{ str_pad($t->id_transaksi, 3, '0', STR_PAD_LEFT) }}</td>

                    <td class="tanggal-cell">
                        {{ $t->tanggal_transaksi->translatedFormat('d F Y \p\u\k\u\l H.i') }}
                    </td>

                    <td>{{ $t->detail_transaksi_count }} item</td>

                    <td>
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </td>

                    <td>
                        <a href="{{ route('admin.transaksi.invoice', $t->id_transaksi) }}" class="btn-detail">
                            <img src="{{ asset('images/icons/detail.png') }}" alt="Detail">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="summary-section">
        <div class="summary-left">
            Total {{ $transaksis->count() }} Transaksi
        </div>

        <div class="summary-right">
            Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
        </div>
    </div>
</div>

@endsection

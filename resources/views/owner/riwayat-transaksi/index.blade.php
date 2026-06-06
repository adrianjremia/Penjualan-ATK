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
        flex-wrap: wrap;
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

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        background: #f9fafb;
    }

    .filter-group input:focus,
    .filter-group select:focus {
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

    .user-cell {
        color: #6b7280;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: transparent;
        border: none;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        padding: 4px 8px;
    }

    .btn-action:hover {
        color: #1f2937;
    }

    .btn-action.danger:hover {
        color: #dc2626;
    }

    .btn-action img {
        width: 14px;
        height: 14px;
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

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
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

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1>Riwayat Transaksi</h1>
    <p>Kelola semua transaksi penjualan - lihat, edit, atau hapus</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h2>Daftar Transaksi</h2>

    <form method="GET" id="filterForm" class="filter-section">
        <div class="filter-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        </div>

        <div class="filter-group">
            <label>Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        </div>

        <div class="filter-group">
            <label>Filter Kasir</label>
            <select name="id_user">
                <option value="">Semua Kasir</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_user }}" {{ request('id_user') == $user->id_user ? 'selected' : '' }}>
                        {{ $user->username }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
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
                        {{ \Carbon\Carbon::parse($t->created_at)->translatedFormat('d F Y \p\u\k\u\l H.i') }}
                    </td>

                    <td class="user-cell">{{ $t->user->username ?? 'Admin' }}</td>

                    <td>{{ $t->detail_transaksi_count }} item</td>

                    <td>
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </td>

                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('owner.riwayat-transaksi.show', $t->id_transaksi) }}" class="btn-action" title="Lihat Invoice">
                                👁️ Lihat
                            </a>
                            <a href="{{ route('owner.riwayat-transaksi.edit', $t->id_transaksi) }}" class="btn-action" title="Edit Invoice">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('owner.riwayat-transaksi.destroy', $t->id_transaksi) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan otomatis.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action danger" title="Hapus Transaksi">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">Belum ada transaksi</td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
    const filterUser = document.querySelector('select[name="id_user"]');
    const filterForm = document.getElementById('filterForm');

    function submitFilter() {
        filterForm.submit();
    }

    tanggalMulai.addEventListener('change', submitFilter);
    tanggalAkhir.addEventListener('change', submitFilter);
    filterUser.addEventListener('change', submitFilter);
});
</script>

@endsection

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
        gap: 16px;
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
        min-width: 150px;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
    }

    .search-group {
        display: flex;
        align-items: flex-end;
        gap: 8px;
    }

    .search-group input {
        flex: 1;
        min-width: 250px;
    }

    .search-group button {
        padding: 10px 16px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .search-group button:hover {
        background-color: #2563eb;
    }

    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 20px;
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
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .timestamp {
        color: #6b7280;
        font-size: 13px;
        white-space: nowrap;
    }

    .user-name {
        font-weight: 600;
        color: #1f2937;
    }

    .action-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .action-create {
        background-color: #dcfce7;
        color: #166534;
    }

    .action-edit {
        background-color: #dbeafe;
        color: #164e63;
    }

    .action-delete {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .description {
        color: #666;
        font-size: 13px;
        max-width: 400px;
        word-wrap: break-word;
    }

    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 40px 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }

    .pagination .active {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .filter-group input,
        .filter-group select {
            min-width: auto;
        }

        .search-group {
            flex-direction: column;
        }

        .search-group input {
            min-width: auto;
        }

        .search-group button {
            width: 100%;
        }

        table {
            font-size: 12px;
        }

        table th,
        table td {
            padding: 10px 8px;
        }

        .description {
            max-width: 200px;
            font-size: 12px;
        }

        .timestamp {
            font-size: 12px;
        }
    }
</style>

<div class="page-header">
    <h1>Log Aktivitas</h1>
    <p>Pantau semua aktivitas dalam aplikasi</p>
</div>

<div class="card">
    <h2>Daftar Aktivitas</h2>

    <form method="GET" id="filterForm" class="filter-section">
        <div class="filter-group">
            <label>Mulai Tanggal</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        </div>

        <div class="filter-group">
            <label>Akhir Tanggal</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        </div>

        <div class="filter-group">
            <label>Filter User</label>
            <select name="id_user">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_user }}" {{ request('id_user') == $user->id_user ? 'selected' : '' }}>
                        {{ $user->username }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Filter Aksi</label>
            <select name="action">
                <option value="">Semua Aksi</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($action)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="search-group">
            <label style="display: none;">Cari</label>
            <input type="text" name="search" placeholder="Cari deskripsi..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </div>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th style="width: 100px; text-align: center;">Detail</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recordAktivitas as $log)
                <tr>
                    <td class="timestamp">
                        {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y H:i:s') }}
                    </td>

                    <td class="user-name">
                        {{ $log->user->username ?? 'System' }}
                    </td>

                    <td>
                        @php
                            $actionClass = 'action-edit';
                            if (strpos($log->action, 'create') !== false) {
                                $actionClass = 'action-create';
                            } elseif (strpos($log->action, 'delete') !== false) {
                                $actionClass = 'action-delete';
                            }
                        @endphp
                        <span class="action-badge {{ $actionClass }}">
                            {{ str_replace('_', ' ', ucfirst($log->action)) }}
                        </span>
                    </td>

                    <td class="description">
                        {{ $log->description }}
                    </td>

                    <td style="text-align: center;">
                        @if($log->old_values || $log->new_values)
                            <button class="btn-detail" onclick="showDetails('{{ addslashes(json_encode($log)) }}')">
                                👁️ Lihat
                            </button>
                        @else
                            <span style="color: #9ca3af; font-size: 12px;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">Belum ada aktivitas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($recordAktivitas->total() > 0)
        <div class="pagination">
            @if($recordAktivitas->onFirstPage())
                <span>← Sebelumnya</span>
            @else
                <a href="{{ $recordAktivitas->previousPageUrl() }}">← Sebelumnya</a>
            @endif

            @foreach ($recordAktivitas->getUrlRange(1, $recordAktivitas->lastPage()) as $page => $url)
                @if ($page == $recordAktivitas->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($recordAktivitas->hasMorePages())
                <a href="{{ $recordAktivitas->nextPageUrl() }}">Selanjutnya →</a>
            @else
                <span>Selanjutnya →</span>
            @endif
        </div>
    @endif
</div>

<!-- Modal untuk detail -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #ffffff;
        margin: 10% auto;
        padding: 24px;
        border-radius: 8px;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 20px;
    }

    .close:hover {
        color: #000;
    }

    .detail-section {
        margin-bottom: 20px;
    }

    .detail-section h3 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item {
        padding: 10px;
        background-color: #f9fafb;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 8px;
        border-left: 3px solid #3b82f6;
    }

    .detail-label {
        color: #666;
        font-weight: 600;
    }

    .detail-value {
        color: #333;
        word-break: break-all;
    }

    .comparison {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .comparison-col {
        padding: 12px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }

    .old-value {
        background-color: #fee2e2;
    }

    .new-value {
        background-color: #dcfce7;
    }

    .comparison-col h4 {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .comparison-col .old-value {
        color: #991b1b;
    }

    .comparison-col .new-value {
        color: #166534;
    }
</style>

<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDetails()">&times;</span>
        <h2 style="margin-bottom: 20px;">Detail Aktivitas</h2>
        <div id="detailContent"></div>
    </div>
</div>

<script>
    function showDetails(logJson) {
        const log = JSON.parse(logJson);
        const modal = document.getElementById('detailModal');
        let html = '';

        // Informasi dasar
        html += '<div class="detail-section">';
        html += '<h3>Informasi Umum</h3>';
        html += '<div class="detail-item">';
        html += '<span class="detail-label">Model:</span> ';
        html += '<span class="detail-value">' + (log.model_type || '-') + ' (ID: ' + log.model_id + ')</span>';
        html += '</div>';
        if (log.ip_address) {
            html += '<div class="detail-item">';
            html += '<span class="detail-label">IP Address:</span> ';
            html += '<span class="detail-value">' + log.ip_address + '</span>';
            html += '</div>';
        }
        html += '</div>';

        // Old values
        if (log.old_values && Object.keys(log.old_values).length > 0) {
            html += '<div class="detail-section">';
            html += '<h3>Perubahan Data</h3>';
            html += '<div class="comparison">';
            html += '<div class="comparison-col old-value">';
            html += '<h4>Nilai Sebelumnya</h4>';
            html += '<div class="detail-value">' + JSON.stringify(log.old_values, null, 2) + '</div>';
            html += '</div>';
            html += '<div class="comparison-col new-value">';
            html += '<h4>Nilai Sesudahnya</h4>';
            html += '<div class="detail-value">' + JSON.stringify(log.new_values, null, 2) + '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }

        document.getElementById('detailContent').innerHTML = html;
        modal.style.display = 'block';
    }

    function closeDetails() {
        document.getElementById('detailModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>

<style>
    .btn-detail {
        background: transparent;
        border: none;
        cursor: pointer;
        color: #3b82f6;
        font-size: 14px;
        transition: all 0.2s;
        padding: 4px 8px;
    }

    .btn-detail:hover {
        color: #2563eb;
    }
</style>

@endsection

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

    table th a {
        color: #1f2937;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        user-select: none;
    }

    table th a:hover {
        color: #3b82f6;
    }

    table th a .sort-indicator {
        display: inline-block;
        font-size: 12px;
        margin-left: 4px;
        color: #9ca3af;
    }

    table th a.active .sort-indicator {
        color: #3b82f6;
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

    .btn-action:hover {
        color: #1f2937;
    }

    .btn-action.danger:hover {
        color: #dc2626;
    }

    .btn-action img {
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

{{ $slot }}

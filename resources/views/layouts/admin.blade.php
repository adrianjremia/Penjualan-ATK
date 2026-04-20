<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            display: flex;
            background: #f3f4f6;
        }

        .sidebar {
            width: 220px;
            background: #ffffff;
            min-height: 100vh;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 12px;
            color: #6b7280;
        }

        .sidebar-menu {
            padding: 16px 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 12px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f3f4f6;
            color: #2563eb;
        }

        .sidebar-menu img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 8px;
            right: 8px;
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            margin-top: 12px;
            padding-top: 12px;
        }

        .sidebar-footer form {
            width: 100%;
        }

        .sidebar-footer button {
            width: 100%;
            padding: 10px 12px;
            background: #ef4444;
            border: none;
            color: white;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .sidebar-footer button:hover {
            background: #dc2626;
        }

        .main-wrapper {
            margin-left: 220px;
            flex: 1;
            min-height: 100vh;
        }

        .content {
            padding: 32px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h3>Admin Panel</h3>
        <p>Point of Sale</p>
    </div>

    <div class="sidebar-menu">
        <a href="{{ url('/dashboard/admin') }}" class="{{ request()->is('dashboard/admin*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/dashboard.png') }}" alt="Dashboard">
            Dashboard
        </a>
        <a href="{{ url('/admin/barang') }}" class="{{ request()->is('admin/barang*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/dataBarang.png') }}" alt="Data Barang">
            Data Barang
        </a>
        <a href="{{ url('/admin/transaksi') }}" class="{{ request()->is('admin/transaksi') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/transaksiPenjualan.png') }}" alt="Transaksi">
            Transaksi Penjualan
        </a>
        <a href="{{ url('/admin/riwayat-transaksi') }}" class="{{ request()->is('admin/riwayat-transaksi*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/riwayatTransaksi.png') }}" alt="Riwayat">
            Riwayat Transaksi
        </a>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 2H13C13.5304 2 14.0391 2.21071 14.4142 2.58579C14.7893 2.96086 15 3.46957 15 4V12C15 12.5304 14.7893 13.0391 14.4142 13.4142C14.0391 13.7893 13.5304 14 13 14H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 11.5L11 8.5L8 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 8.5H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>

<div class="main-wrapper">
    <div class="content">
        @yield('content')
    </div>
</div>

</body>
</html>

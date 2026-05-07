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
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.2s;
            margin-bottom: 8px;
        }

        .sidebar-menu a:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .sidebar-menu a.active {
            background: #dbeafe;
            color: #2563eb;
            font-weight: 500;
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
            background: transparent;
            border: 1px solid #fee2e2;
            color: #ef4444;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .sidebar-footer button:hover {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .sidebar-footer img {
            width: 16px;
            height: 16px;
            object-fit: contain;
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
        <h3>Pemilik Toko</h3>
        <p>Point of Sale</p>
    </div>

    <div class="sidebar-menu">
        <a href="{{ url('/dashboard/pemilik') }}" class="{{ request()->is('dashboard/pemilik*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/dashboard.png') }}" alt="Dashboard">
            Dashboard
        </a>
        <a href="{{ url('/pemilik/laporan-penjualan') }}" class="{{ request()->is('pemilik/laporan-penjualan*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/pendapatanHarian.png') }}" alt="Laporan Penjualan">
            Laporan Penjualan
        </a>
        <a href="{{ url('/pemilik/laporan-laba-rugi') }}" class="{{ request()->is('pemilik/laporan-laba-rugi*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/labaRugi.png') }}" alt="Laporan Laba Rugi">
            Laporan Laba Rugi
        </a>
        <a href="{{ url('/pemilik/informasi-stok') }}" class="{{ request()->is('pemilik/informasi-stok*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/dataBarang.png') }}" alt="Informasi Stok">
            Informasi Stok
        </a>
        <a href="{{ url('/pemilik/forecasting') }}" class="{{ request()->is('pemilik/forecasting*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/chartUp.png') }}" alt="Prediksi Penjualan">
            Prediksi Penjualan
        </a>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <img src="{{ asset('images/icons/logout.png') }}" alt="Logout">
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

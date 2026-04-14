<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background: #1f2937;
            color: white;
            min-height: 100vh;
        }
        .sidebar h2 {
            padding: 20px;
            margin: 0;
            background: #111827;
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #374151;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="{{ url('/dashboard/admin') }}">Dashboard</a>
    <a href="{{ url('/admin/barang') }}">Data Barang</a>
    <a href="{{ url('/admin/transaksi') }}">Transaksi Penjualan</a>
    <a href="{{ url('/admin/riwayat-transaksi') }}">Riwayat Transaksi</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button style="width:100%; padding:15px; background:#dc2626; border:none; color:white;">
            Logout
        </button>
    </form>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>
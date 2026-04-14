<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaksi->id_transaksi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }

        .header-left h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .header-left p {
            color: #666;
            font-size: 14px;
        }

        .header-right {
            text-align: right;
        }

        .header-right .label {
            color: #666;
            font-size: 13px;
            display: block;
            margin-bottom: 3px;
        }

        .header-right .value {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .store-info {
            margin-bottom: 30px;
        }

        .store-info h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .store-info p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 3px;
        }

        .transaction-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
            padding: 20px 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-block label {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-block .value {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            border-top: 2px solid #1a1a1a;
            border-bottom: 2px solid #1a1a1a;
        }

        table th {
            padding: 12px 0;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 12px 0;
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        table tbody tr:last-child td {
            border-bottom: 2px solid #1a1a1a;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #666;
        }

        .summary-row .label {
            color: #666;
        }

        .summary-row .value {
            color: #333;
            font-weight: 500;
        }

        .total-payment {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f8f9ff;
            border-radius: 4px;
            margin-top: 20px;
        }

        .total-payment .label {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-payment .amount {
            font-size: 28px;
            font-weight: 700;
            color: #4f46e5;
        }

        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #f0f0f0;
            text-align: center;
        }

        .footer-message {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .footer-message strong {
            display: block;
            color: #1a1a1a;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background-color: transparent;
            color: #666;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #f5f5f5;
            border-color: #999;
        }

        .icon {
            font-size: 16px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .button-group {
                display: none;
            }

            .back-link {
                display: none;
            }
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            gap: 12px;
            color: #4338ca;
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.transaksi.index') }}" class="back-link">← Kembali ke Dashboard</a>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>INVOICE</h1>
                <p>Sistem Point of Sale</p>
            </div>
            <div class="header-right">
                <span class="label">ID Transaksi:</span>
                <div class="value">T{{ str_pad($transaksi->id_transaksi, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Store Information -->
        <div class="store-info">
            <h3>Toko Serba Ada</h3>
            <p>Jl. Contoh No. 123, Jakarta</p>
            <p>Telp: (021) 12345678</p>
        </div>

        <!-- Transaction Information -->
        <div class="transaction-info">
            <div class="info-block">
                <label>Tanggal Transaksi:</label>
                <div class="value">
                    @php
                        $tanggal = \Carbon\Carbon::parse($transaksi->tanggal_transaksi);
                        echo $tanggal->format('d F Y');
                        echo ' pukul ' . $tanggal->format('H:i');
                    @endphp
                </div>
            </div>
            <div class="info-block">
                <label>Kasir:</label>
                <div class="value">{{ $transaksi->user->username ?? 'Admin' }}</div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <h2 class="section-title">Detail Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalItem = 0;
                    $totalBarang = 0;
                @endphp
                @forelse($transaksi->detailTransaksi as $detail)
                    @php
                        $totalItem++;
                        $totalBarang += $detail->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td class="text-right">Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-right">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span class="label">Total Item:</span>
                <span class="value">{{ $totalItem }}</span>
            </div>
            <div class="summary-row">
                <span class="label">Total Barang:</span>
                <span class="value">{{ $totalBarang }}</span>
            </div>
        </div>

        <!-- Total Payment -->
        <div class="total-payment">
            <div class="label">Total Pembayaran:</div>
            <div class="amount">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">
                <strong>Terima kasih atas kunjungan Anda</strong>
                Barang yang sudah dibeli tidak dapat dikembalikan
            </div>
            <div class="button-group">
                <button class="btn btn-primary" onclick="window.print()">
                    <span class="icon">🖨️</span> Cetak Invoice
                </button>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>

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

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }

    .summary-label {
        font-size: 12px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
    }

    .summary-card.critical {
        border-left: 4px solid #dc2626;
        background-color: #fef2f2;
    }

    .summary-card.critical .summary-value {
        color: #dc2626;
    }

    .summary-card.medium {
        border-left: 4px solid #f59e0b;
        background-color: #fffbf0;
    }

    .summary-card.medium .summary-value {
        color: #f59e0b;
    }

    .summary-card.safe {
        border-left: 4px solid #059669;
        background-color: #f0fdf4;
    }

    .summary-card.safe .summary-value {
        color: #059669;
    }

    .filter-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 32px;
    }

    .filter-section label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .filter-section select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #1f2937;
        background: white;
    }

    .detail-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .detail-header {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .status-alert {
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
    }

    .status-alert.critical {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }

    .status-alert.medium {
        background-color: #fef3c7;
        border: 1px solid #fde68a;
        color: #78350f;
    }

    .status-alert.safe {
        background-color: #f0fdf4;
        border: 1px solid #86efac;
        color: #15803d;
    }

    .trend-section {
        background: #f3f4f6;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .trend-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .trend-value {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .weekly-breakdown {
        margin-top: 24px;
    }

    .weekly-breakdown h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .weekly-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .weekly-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .weekly-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #374151;
    }

    .weekly-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
    }

    .weekly-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .forecast-chart {
        margin-top: 24px;
        margin-bottom: 24px;
        position: relative;
        height: 300px;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .product-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .product-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.1);
    }

    .product-card.critical {
        border-left: 4px solid #dc2626;
    }

    .product-card.medium {
        border-left: 4px solid #f59e0b;
    }

    .product-card.safe {
        border-left: 4px solid #059669;
    }

    .product-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .product-info {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .product-status {
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .product-status.critical {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .product-status.medium {
        background-color: #fef3c7;
        color: #92400e;
    }

    .product-status.safe {
        background-color: #f0fdf4;
        color: #166534;
    }
</style>

<div class="page-header">
    <h1>Prediksi Penjualan (Forecasting Mingguan)</h1>
    <p>Analisis prediksi penjualan mingguan dengan Single Exponential Smoothing (Alpha 0.3)</p>
</div>

<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card critical">
        <div class="summary-label" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <img src="{{ asset('images/icons/warning.png') }}" alt="Warning" style="width: 16px; height: 16px;">
            Stok Kritis
        </div>
        <div class="summary-value">{{ $productsCritical }}</div>
    </div>

    <div class="summary-card medium">
        <div class="summary-label" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <img src="{{ asset('images/icons/info.png') }}" alt="Info" style="width: 16px; height: 16px;">
            Stok Menengah
        </div>
        <div class="summary-value">{{ $productsMedium }}</div>
    </div>

    <div class="summary-card safe">
        <div class="summary-label" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <img src="{{ asset('images/icons/check.png') }}" alt="Check" style="width: 16px; height: 16px;">
            Stok Aman
        </div>
        <div class="summary-value">{{ $productsSafe }}</div>
    </div>

    <div class="summary-card">
        <div class="summary-label" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <img src="{{ asset('images/icons/chartBar.png') }}" alt="Chart" style="width: 16px; height: 16px;">
            Total Produk
        </div>
        <div class="summary-value">{{ $totalProducts }}</div>
    </div>
    <div class="summary-card medium">
        <div class="summary-label">Stok Menengah</div>
        <div class="summary-value">{{ $productsMedium }}</div>
    </div>
    <div class="summary-card safe">
        <div class="summary-label">Stok Aman</div>
        <div class="summary-value">{{ $productsSafe }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-label">Total Produk</div>
        <div class="summary-value">{{ $totalProducts }}</div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <label for="productFilter">Pilih Produk untuk Melihat Detail</label>
    <form method="GET" id="filterForm">
        <select id="productFilter" name="id_barang" onchange="document.getElementById('filterForm').submit()">
            <option value="">-- Ringkasan Semua Produk --</option>
            @foreach($barangs as $barang)
                <option value="{{ $barang->id_barang }}" {{ $idBarang == $barang->id_barang ? 'selected' : '' }}>
                    {{ $barang->nama_barang }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@if($idBarang && isset($forecasts[$idBarang]))
    @php
        $forecast = $forecasts[$idBarang];
        $status = $forecast['status'];
    @endphp
    
    <div class="detail-section">
        <div class="detail-header">📊 Detail Produk: {{ $forecast['nama_barang'] }}</div>

        <!-- Status Alert -->
        <div class="status-alert {{ $status['type'] }}">
            {!! $status['message'] !!}
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Stok Saat Ini</div>
                <div class="stat-value">{{ $forecast['stok_saat_ini'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prediksi Minggu Depan</div>
                <div class="stat-value" style="color: #3b82f6;">{{ $forecast['forecast_minggu_depan'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kategori</div>
                <div class="stat-value" style="font-size: 16px;">{{ $forecast['kategori'] }}</div>
            </div>
        </div>

        <!-- Trend Section -->
        <div class="trend-section">
            <div class="trend-label" style="display: flex; align-items: center; gap: 8px;">
                <img src="{{ asset('images/icons/chartUp.png') }}" alt="Trend" style="width: 18px; height: 18px;">
                Analisis Tren Mingguan
            </div>
            <div class="trend-value">
                Penjualan produk ini <strong>{{ $forecast['trend']['direction'] }}</strong> sebesar <strong>{{ $forecast['trend']['percentage'] }}%</strong> dibandingkan minggu lalu.
                <br>
                <span style="font-size: 13px; color: #6b7280; font-weight: normal;">(Minggu lalu: {{ $forecast['trend']['previousWeek'] }} unit → Minggu ini: {{ $forecast['trend']['lastWeek'] }} unit)</span>
            </div>
        </div>

        <!-- Weekly Breakdown -->
        @if(!empty($forecast['weeklyBreakdown']))
        <div class="weekly-breakdown">
            <h4 style="display: flex; align-items: center; gap: 8px;">
                <img src="{{ asset('images/icons/chartBar.png') }}" alt="Bar Chart" style="width: 18px; height: 18px;">
                Prediksi Penjualan 4 Minggu ke Depan
            </h4>
            <table class="weekly-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th style="text-align: right;">Prediksi Penjualan (unit)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forecast['weeklyBreakdown'] as $week)
                    <tr>
                        <td>{{ $week['week'] }}</td>
                        <td style="text-align: right; font-weight: 600; color: #3b82f6;">{{ $week['forecast'] }} unit</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Historical Data Chart (if available) -->
        @if(!empty($forecast['historicalData']))
        <div class="forecast-chart">
            <h4 style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <img src="{{ asset('images/icons/chartUp.png') }}" alt="Chart" style="width: 18px; height: 18px;">
                Grafik Penjualan Historis (Per Minggu)
            </h4>
            <canvas id="forecastChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            const ctx = document.getElementById('forecastChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($forecast['weeks']) !!},
                        datasets: [{
                            label: 'Penjualan Aktual',
                            data: {!! json_encode($forecast['historicalData']) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        </script>
        @endif
    </div>
@else
    <!-- All Products Overview -->
    <div class="detail-section">
        <div class="detail-header" style="display: flex; align-items: center; gap: 8px;">
            <img src="{{ asset('images/icons/chartBar.png') }}" alt="Chart" style="width: 20px; height: 20px;">
            Ringkasan Semua Produk
        </div>
        
        @if(!empty($allForecasts))
        <div class="product-list">
            @foreach($allForecasts as $item)
            <a href="?id_barang={{ $item['id_barang'] }}" class="product-card {{ $item['status']['type'] }}">
                <div class="product-name">{{ $item['nama_barang'] }}</div>
                <div class="product-info">Stok: <strong>{{ $item['stok_saat_ini'] }} unit</strong></div>
                <div class="product-info">Prediksi Minggu: <strong style="color: #3b82f6;">{{ $item['forecast_minggu_depan'] }} unit</strong></div>
                <div class="product-info">Kategori: {{ $item['kategori'] }}</div>
                <div class="product-info" style="font-size: 12px; margin-top: 8px;">
                    Tren: <strong>{{ $item['trend']['direction'] }} {{ $item['trend']['percentage'] }}%</strong>
                </div>
                <div class="product-status {{ $item['status']['type'] }}" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                    @if($item['status']['type'] === 'critical')
                        <img src="{{ asset('images/icons/warning.png') }}" alt="Warning" style="width: 16px; height: 16px;">
                        KRITIS
                    @elseif($item['status']['type'] === 'medium')
                        <img src="{{ asset('images/icons/info.png') }}" alt="Info" style="width: 16px; height: 16px;">
                        MENENGAH
                    @else
                        <img src="{{ asset('images/icons/check.png') }}" alt="Check" style="width: 16px; height: 16px;">
                        AMAN
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="no-data">
            Belum ada data penjualan untuk prediksi
        </div>
        @endif

        <div style="margin-top: 24px; padding: 16px; background: #f0f9ff; border-left: 4px solid #0284c7; border-radius: 4px;">
            <div style="font-size: 13px; color: #0c4a6e;">
                <strong>ℹ️ Penjelasan Status:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    <li><strong>⚠️ KRITIS:</strong> Stok tidak cukup untuk minggu depan. Segera lakukan pengadaan.</li>
                    <li><strong>ℹ️ MENENGAH:</strong> Stok aman minggu depan, namun akan habis dalam beberapa minggu.</li>
                    <li><strong>✓ AMAN:</strong> Stok cukup untuk 4 minggu ke depan.</li>
                </ul>
            </div>
        </div>
    </div>
@endif

@endsection

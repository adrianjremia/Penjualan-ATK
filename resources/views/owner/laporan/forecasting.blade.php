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

    .filter-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }

    .filter-group select,
    .filter-group input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 20px;
    }

    .forecast-table {
        width: 100%;
        border-collapse: collapse;
    }

    .forecast-table thead {
        background: #f3f4f6;
    }

    .forecast-table th {
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        border-bottom: 1px solid #e5e7eb;
    }

    .forecast-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #374151;
    }

    .forecast-table tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-safe {
        background: #d1fae5;
        color: #065f46;
    }

    .status-warning {
        background: #fee2e2;
        color: #991b1b;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            min-width: unset;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 300px;
        }
    }
</style>

<div class="page-header">
    <h1>Prediksi Penjualan</h1>
    <p>Analisis tren dan forecast penjualan untuk perencanaan stok</p>
</div>

<div class="filter-card">
    <div class="filter-group">
        <label for="method">Metode Forecasting</label>
        <select id="method" name="method" onchange="location.href='?method=' + this.value + (document.getElementById('id_barang').value ? '&id_barang=' + document.getElementById('id_barang').value : '')">
            <option value="sma" {{ $method === 'sma' ? 'selected' : '' }}>Simple Moving Average (SMA)</option>
            <option value="ses" {{ $method === 'ses' ? 'selected' : '' }}>Exponential Smoothing (SES)</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="id_barang">Filter Produk</label>
        <select id="id_barang" name="id_barang" onchange="location.href='?method={{ $method }}' + (this.value ? '&id_barang=' + this.value : '')">
            <option value="">Semua Produk</option>
            @foreach($barangs as $barang)
                <option value="{{ $barang->id_barang }}" {{ $idBarang == $barang->id_barang ? 'selected' : '' }}>
                    {{ $barang->nama_barang }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Produk</div>
        <div class="stat-value">{{ $totalProducts }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Perlu Restock</div>
        <div class="stat-value" style="color: #dc2626;">{{ $productsNeedRestock }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Metode Forecasting</div>
        <div class="stat-value" style="font-size: 16px;">{{ $method === 'sma' ? 'SMA-3' : 'Exponential Smoothing' }}</div>
    </div>
</div>

@if($idBarang && isset($forecasts[$idBarang]))
    @php $forecast = $forecasts[$idBarang]; @endphp
    
    <div class="card">
        <h3>{{ $forecast['nama_barang'] }} - Detail Prediksi</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div>
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Stok Saat Ini</div>
                <div style="font-size: 24px; font-weight: 700; color: #1f2937;">{{ $forecast['stok_saat_ini'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Prediksi Penjualan Bulan Depan</div>
                <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">{{ $forecast['forecast'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Status Stok</div>
                <div style="font-size: 14px; margin-top: 4px;">
                    @if($forecast['needsRestock'])
                        <span class="status-badge status-warning">⚠️ Perlu Restock</span>
                    @else
                        <span class="status-badge status-safe">✓ Aman</span>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($forecast['historicalData']))
            <div class="chart-container">
                <canvas id="forecastChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('forecastChart').getContext('2d');
                const historicalData = {!! json_encode($forecast['historicalData']) !!};
                const months = {!! json_encode($forecast['months']) !!};
                const forecast = {!! json_encode($forecast['forecast']) !!};
                
                const chartData = {
                    labels: months.concat(['Prediksi']),
                    datasets: [
                        {
                            label: 'Penjualan Aktual',
                            data: historicalData.concat(null),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Prediksi',
                            data: Array(historicalData.length - 1).fill(null).concat(historicalData[historicalData.length - 1], forecast),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4,
                            pointRadius: 5
                        }
                    ]
                };

                new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

            <div style="margin-top: 40px;">
                <h3 style="margin-bottom: 20px; font-size: 18px; font-weight: 600; color: #1f2937;">Data Penjualan Bulanan Tahun 2026</h3>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Bulan</th>
                                <th style="padding: 12px; text-align: right; font-weight: 600; color: #374151;">Total Terjual (unit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecast['monthlyData'] as $data)
                                <tr style="border-bottom: 1px solid #e5e7eb; @if($data['total_sold'] > 0) background-color: #f9fafb; @endif">
                                    <td style="padding: 12px; color: #1f2937;">{{ $data['month'] }} 2026</td>
                                    <td style="padding: 12px; text-align: right; @if($data['total_sold'] > 0) color: #059669; font-weight: 600; @else color: #9ca3af; @endif">
                                        {{ $data['total_sold'] }} unit
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="card">
        <h3>Tabel Prediksi Penjualan - {{ ucfirst($method) }}</h3>

        @if(empty($forecasts))
            <div class="no-data">
                <p>Belum ada data transaksi untuk dilakukan prediksi</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="forecast-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Prediksi (Bulan Depan)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forecasts as $f)
                            <tr>
                                <td>
                                    <a href="?method={{ $method }}&id_barang={{ $f['id_barang'] }}" 
                                       style="color: #3b82f6; text-decoration: none; cursor: pointer;">
                                        {{ $f['nama_barang'] }}
                                    </a>
                                </td>
                                <td>{{ $f['kategori'] }}</td>
                                <td style="font-weight: 600;">{{ $f['stok_saat_ini'] }}</td>
                                <td style="font-weight: 600; color: #3b82f6;">{{ $f['forecast'] }}</td>
                                <td>
                                    @if($f['needsRestock'])
                                        <span class="status-badge status-warning">⚠️ Restock Segera</span>
                                    @else
                                        <span class="status-badge status-safe">✓ Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; padding: 16px; background: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <div style="font-size: 13px; color: #1e40af;">
                    <strong>📊 Penjelasan:</strong>
                    <ul style="margin: 8px 0 0 20px; padding: 0;">
                        <li><strong>Prediksi:</strong> Estimasi jumlah penjualan bulan depan berdasarkan data historis</li>
                        <li><strong>Status Restock:</strong> Jika stok saat ini lebih rendah dari prediksi penjualan, produk perlu direstock</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif

@endsection
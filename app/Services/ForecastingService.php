<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon;

class ForecastingService
{
    /**
     * Hitung Simple Moving Average (SMA)
     */
    public static function calculateSMA($data, $period = 3)
    {
        if (count($data) < $period) {
            return null;
        }
        
        $sum = 0;
        for ($i = 0; $i < $period; $i++) {
            $sum += $data[count($data) - 1 - $i];
        }
        
        return $sum / $period;
    }

    /**
     * Hitung Single Exponential Smoothing (SES)
     */
    public static function calculateSES($data, $alpha = 0.3)
    {
        if (empty($data)) {
            return null;
        }

        $forecast = $data[0];
        
        for ($i = 1; $i < count($data); $i++) {
            $forecast = $alpha * $data[$i] + (1 - $alpha) * $forecast;
        }
        
        return $forecast;
    }

    /**
     * Hitung Mean Absolute Percentage Error (MAPE)
     */
    public static function calculateMAPE($actual, $predicted)
    {
        if (count($actual) !== count($predicted) || empty($actual)) {
            return 0;
        }

        $sum = 0;
        $validCount = 0;

        for ($i = 0; $i < count($actual); $i++) {
            if ($actual[$i] > 0) {
                $sum += abs(($actual[$i] - $predicted[$i]) / $actual[$i]);
                $validCount++;
            }
        }

        return $validCount > 0 ? round(($sum / $validCount) * 100, 2) : 0;
    }

    /**
     * Dapatkan data penjualan per bulan untuk produk tertentu
     */
    public static function getSalesDataByMonth($idBarang = null)
    {
        $query = DetailTransaksi::query()
            ->with('transaksi')
            ->whereHas('transaksi', function ($q) {
                // Data dari Januari 2026 sampai sekarang
                $q->whereYear('created_at', 2026)
                  ->whereMonth('created_at', '<=', Carbon::now()->month);
            });

        if ($idBarang) {
            $query->where('id_barang', $idBarang);
        }

        $details = $query->get();

        // Group by month
        $salesByMonth = [];
        
        foreach ($details as $detail) {
            $month = Carbon::parse($detail->transaksi->created_at)->format('Y-m');
            
            if (!isset($salesByMonth[$month])) {
                $salesByMonth[$month] = 0;
            }
            
            $salesByMonth[$month] += $detail->jumlah;
        }

        // Sort by month
        ksort($salesByMonth);

        return $salesByMonth;
    }

    /**
     * Generate forecast untuk bulan berikutnya
     */
    public static function generateForecast($idBarang = null, $method = 'sma')
    {
        $salesData = self::getSalesDataByMonth($idBarang);
        $data = array_values($salesData);

        if (empty($data)) {
            return [
                'forecast' => 0,
                'mape' => 0,
                'method' => $method,
                'historicalData' => [],
                'months' => []
            ];
        }

        // Hitung forecast berdasarkan method
        if ($method === 'ses') {
            $forecast = self::calculateSES($data);
        } else {
            $forecast = self::calculateSMA($data);
        }

        // Validasi forecast tidak negatif
        $forecast = max(0, round($forecast));

        // Hitung MAPE dengan membandingkan predicted vs actual
        // Gunakan last 3 months sebagai test set
        $mape = 0;
        if (count($data) >= 4) {
            $trainData = array_slice($data, 0, count($data) - 1);
            $testData = array_slice($data, count($data) - 1);
            
            if ($method === 'ses') {
                $predictedTest = self::calculateSES($trainData);
            } else {
                $predictedTest = self::calculateSMA($trainData);
            }
            
            $mape = self::calculateMAPE($testData, [$predictedTest]);
        }

        return [
            'forecast' => $forecast,
            'mape' => $mape,
            'method' => $method,
            'historicalData' => $data,
            'months' => array_keys($salesData)
        ];
    }

    /**
     * Generate forecast untuk semua produk atau produk tertentu
     */
    public static function generateForecastForAllProducts($method = 'sma')
    {
        $barangs = \App\Models\Barang::all();
        $forecasts = [];

        foreach ($barangs as $barang) {
            $forecastData = self::generateForecast($barang->id_barang, $method);
            
            $forecasts[$barang->id_barang] = [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori,
                'stok_saat_ini' => $barang->stok,
                'forecast' => $forecastData['forecast'],
                'mape' => $forecastData['mape'],
                'historicalData' => $forecastData['historicalData'],
                'months' => $forecastData['months'],
                'needsRestock' => $barang->stok < $forecastData['forecast']
            ];
        }

        return $forecasts;
    }
}

<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Carbon\Carbon;

class ForecastingService
{
    const ALPHA = 0.3; // Constant alpha untuk SES

    /**
     * Hitung Single Exponential Smoothing (SES) dengan Alpha 0.3
     */
    public static function calculateSES($data, $alpha = self::ALPHA)
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
     * Dapatkan data penjualan per minggu untuk produk tertentu
     */
    public static function getSalesDataByWeek($idBarang = null)
    {
        $query = DetailTransaksi::query()
            ->with('transaksi')
            ->whereHas('transaksi', function ($q) {
                // Data dari tahun lalu atau dari awal tahun 2026 sampai sekarang
                $q->where('created_at', '>=', Carbon::now()->startOfYear())
                  ->where('created_at', '<=', Carbon::now());
            });

        if ($idBarang) {
            $query->where('id_barang', $idBarang);
        }

        $details = $query->get();

        // Group by week
        $salesByWeek = [];
        
        foreach ($details as $detail) {
            $weekKey = Carbon::parse($detail->transaksi->created_at)->format('Y-W');
            
            if (!isset($salesByWeek[$weekKey])) {
                $salesByWeek[$weekKey] = 0;
            }
            
            $salesByWeek[$weekKey] += $detail->jumlah;
        }

        // Sort by week
        ksort($salesByWeek);

        return $salesByWeek;
    }

    /**
     * Hitung trend perubahan antara minggu saat ini dan minggu lalu
     */
    public static function calculateWeeklyTrend($salesData)
    {
        if (count($salesData) < 2) {
            return ['percentage' => 0, 'direction' => 'Stabil'];
        }

        $data = array_values($salesData);
        $lastWeek = $data[count($data) - 1];
        $previousWeek = $data[count($data) - 2];

        if ($previousWeek == 0) {
            return ['percentage' => 0, 'direction' => 'Stabil'];
        }

        $percentage = round(abs(($lastWeek - $previousWeek) / $previousWeek) * 100, 1);
        $direction = $lastWeek > $previousWeek ? 'Meningkat' : 'Menurun';

        return [
            'percentage' => $percentage,
            'direction' => $direction,
            'lastWeek' => $lastWeek,
            'previousWeek' => $previousWeek
        ];
    }

    /**
     * Generate forecast untuk minggu berikutnya
     */
    public static function generateForecast($idBarang)
    {
        $salesData = self::getSalesDataByWeek($idBarang);
        $data = array_values($salesData);

        if (empty($data)) {
            return [
                'forecast' => 0,
                'trend' => ['percentage' => 0, 'direction' => 'Stabil'],
                'historicalData' => [],
                'weeks' => [],
                'weeklyBreakdown' => []
            ];
        }

        // Calculate SES forecast untuk minggu depan
        $forecast = self::calculateSES($data);
        $forecast = max(0, round($forecast));

        // Hitung trend
        $trend = self::calculateWeeklyTrend($salesData);

        // Siapkan breakdown untuk 4 minggu ke depan (prediksi)
        $weeklyBreakdown = self::calculateWeeklyBreakdown($data, $forecast);

        return [
            'forecast' => $forecast,
            'trend' => $trend,
            'historicalData' => $data,
            'weeks' => array_keys($salesData),
            'weeklyBreakdown' => $weeklyBreakdown
        ];
    }

    /**
     * Hitung prediksi untuk 4 minggu ke depan
     */
    public static function calculateWeeklyBreakdown($data, $nextWeekForecast)
    {
        if (empty($data)) {
            return [];
        }

        // Gunakan SES untuk generate prediksi 4 minggu ke depan
        $breakdown = [];
        $lastForecast = $nextWeekForecast;

        for ($i = 1; $i <= 4; $i++) {
            // Setiap minggu, gunakan forecast sebelumnya untuk SES
            $weekForecast = round(self::ALPHA * $lastForecast + (1 - self::ALPHA) * $lastForecast);
            $breakdown[] = [
                'week' => "Minggu ke-$i",
                'forecast' => $weekForecast
            ];
            $lastForecast = $weekForecast;
        }

        return $breakdown;
    }

    /**
     * Generate forecast untuk semua produk
     */
    public static function generateForecastForAllProducts()
    {
        $barangs = Barang::all();
        $forecasts = [];

        foreach ($barangs as $barang) {
            $forecastData = self::generateForecast($barang->id_barang);
            
            // Determine status berdasarkan stok vs prediksi
            $status = self::determineStockStatus($barang, $forecastData['weeklyBreakdown']);
            
            $forecasts[$barang->id_barang] = [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori,
                'stok_saat_ini' => $barang->stok,
                'forecast_minggu_depan' => $forecastData['forecast'],
                'trend' => $forecastData['trend'],
                'status' => $status,
                'historicalData' => $forecastData['historicalData'],
                'weeks' => $forecastData['weeks'],
                'weeklyBreakdown' => $forecastData['weeklyBreakdown']
            ];
        }

        return $forecasts;
    }

    /**
     * Tentukan status stok berdasarkan prediksi
     */
    public static function determineStockStatus($barang, $weeklyBreakdown)
    {
        if (empty($weeklyBreakdown)) {
            return [
                'type' => 'unknown',
                'message' => 'Data tidak cukup untuk prediksi',
                'weeksUntilEmpty' => null
            ];
        }

        $stok = $barang->stok;
        $week1Forecast = $weeklyBreakdown[0]['forecast'] ?? 0;

        // Cek stok kritis (tidak cukup untuk minggu depan)
        if ($stok < $week1Forecast) {
            return [
                'type' => 'critical',
                'message' => "⚠️ STOK KRITIS: Stok saat ini ({$stok}) diprediksi tidak cukup untuk kebutuhan minggu depan ({$week1Forecast}). Segera lakukan pengadaan barang.",
                'weeksUntilEmpty' => 0
            ];
        }

        // Cek stok menengah (aman minggu depan tapi tidak 4 minggu)
        $totalNeeded = 0;
        $weeksUntilEmpty = 0;

        for ($i = 0; $i < 4; $i++) {
            $totalNeeded += $weeklyBreakdown[$i]['forecast'] ?? 0;
            if ($stok >= $totalNeeded) {
                $weeksUntilEmpty = $i + 1;
            } else {
                break;
            }
        }

        if ($weeksUntilEmpty > 0 && $weeksUntilEmpty < 4) {
            return [
                'type' => 'medium',
                'message' => "ℹ️ STOK MENENGAH: Stok aman untuk minggu depan, namun diprediksi habis dalam {$weeksUntilEmpty} minggu.",
                'weeksUntilEmpty' => $weeksUntilEmpty
            ];
        }

        // Stok aman untuk 4 minggu ke depan
        return [
            'type' => 'safe',
            'message' => "✓ STOK AMAN: Stok cukup untuk kebutuhan 4 minggu ke depan.",
            'weeksUntilEmpty' => 4
        ];
    }
}

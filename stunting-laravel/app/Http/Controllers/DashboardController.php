<?php

namespace App\Http\Controllers;

use App\Models\StuntingPrediction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total = StuntingPrediction::count();
        $totalStunting = StuntingPrediction::where('prediction_code', 1)->count();
        $totalNormal = $total - $totalStunting;
        $stuntingPercent = $total > 0 ? round(($totalStunting / $total) * 100, 1) : 0;

        // Rata-rata probabilitas stunting dari seluruh prediksi
        $avgProbability = StuntingPrediction::whereNotNull('probability_stunting_percent')
            ->avg('probability_stunting_percent');

        // Tren 7 hari terakhir: jumlah prediksi per hari
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $trendRaw = StuntingPrediction::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total,
                         SUM(CASE WHEN prediction_code = 1 THEN 1 ELSE 0 END) as stunting')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $trendLabels = [];
        $trendTotal = [];
        $trendStunting = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->translatedFormat('d M');
            $trendTotal[] = (int) ($trendRaw[$date]->total ?? 0);
            $trendStunting[] = (int) ($trendRaw[$date]->stunting ?? 0);
        }

        // Distribusi berdasarkan faktor risiko kualitatif (untuk insight cepat)
        $riskFactors = [
            'ASI Tidak Eksklusif' => StuntingPrediction::where('asi_eksklusif', 'Tidak')->where('prediction_code', 1)->count(),
            'Sanitasi Tidak Layak' => StuntingPrediction::where('sanitasi_layak', 'Tidak')->where('prediction_code', 1)->count(),
            'Imunisasi Tidak Lengkap' => StuntingPrediction::where('imunisasi_lengkap', 'Tidak')->where('prediction_code', 1)->count(),
        ];

        $recentPredictions = StuntingPrediction::latest()->take(5)->get();

        return view('dashboard', compact(
            'total',
            'totalStunting',
            'totalNormal',
            'stuntingPercent',
            'avgProbability',
            'trendLabels',
            'trendTotal',
            'trendStunting',
            'riskFactors',
            'recentPredictions'
        ));
    }
}

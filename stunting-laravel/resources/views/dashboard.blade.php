@extends('layouts.app')

@section('title', 'Dashboard - Prediksi Stunting')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">📊 Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan data prediksi stunting balita</p>
</div>

{{-- Kartu Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Prediksi</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($total) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terdeteksi Stunting</p>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($totalStunting) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stuntingPercent }}% dari total</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tidak Stunting</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalNormal) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $total > 0 ? round(100 - $stuntingPercent, 1) : 0 }}% dari total</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rata-rata Probabilitas</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $avgProbability ? number_format($avgProbability, 1) : 0 }}%</p>
    </div>
</div>

@if($total === 0)
    <div class="bg-white rounded-xl shadow p-10 text-center mb-8">
        <p class="text-4xl mb-3">📭</p>
        <p class="text-gray-500 mb-4">Belum ada data prediksi. Mulai dengan membuat prediksi pertama.</p>
        <a href="{{ route('stunting.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm">
            + Buat Prediksi
        </a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Chart: Distribusi hasil --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm">Distribusi Hasil Klasifikasi</h3>
            <div class="max-w-[220px] mx-auto">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        {{-- Chart: Tren 7 hari terakhir --}}
        <div class="bg-white rounded-xl shadow p-5 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm">Tren Prediksi 7 Hari Terakhir</h3>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Faktor risiko & prediksi terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm">Faktor Risiko Terbanyak (kasus Stunting)</h3>
            <div class="space-y-3">
                @foreach($riskFactors as $label => $count)
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>{{ $label }}</span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-red-400 h-2 rounded-full"
                                 style="width: {{ $totalStunting > 0 ? min(100, round($count / $totalStunting * 100)) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 text-sm">Prediksi Terbaru</h3>
                <a href="{{ route('stunting.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="space-y-2">
                @foreach($recentPredictions as $p)
                    <a href="{{ route('stunting.show', $p->id) }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm border border-gray-100">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-lg">{{ $p->prediction_code == 1 ? '⚠️' : '✅' }}</span>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $p->nama_balita ?? 'Tanpa nama' }}</p>
                                <p class="text-xs text-gray-400">{{ $p->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full shrink-0
                            {{ $p->prediction_code == 1 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $p->prediction_status }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart distribusi (doughnut)
            new Chart(document.getElementById('distributionChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Stunting', 'Tidak Stunting'],
                    datasets: [{
                        data: [{{ $totalStunting }}, {{ $totalNormal }}],
                        backgroundColor: ['#f87171', '#4ade80'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
                }
            });

            // Chart tren (line)
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [
                        {
                            label: 'Total Prediksi',
                            data: {!! json_encode($trendTotal) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Stunting',
                            data: {!! json_encode($trendStunting) !!},
                            borderColor: '#f87171',
                            backgroundColor: 'rgba(248,113,113,0.1)',
                            tension: 0.3,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
                }
            });
        });
    </script>
@endif
@endsection

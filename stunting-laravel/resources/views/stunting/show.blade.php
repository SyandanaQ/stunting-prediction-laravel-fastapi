@extends('layouts.app')

@section('title', 'Hasil Prediksi - Prediksi Stunting')

@php
    // Kumpulkan faktor risiko yang terdeteksi pada data ini, untuk rekomendasi tindak lanjut otomatis.
    $riskFactors = [];
    if ($stunting->asi_eksklusif === 'Tidak') {
        $riskFactors[] = 'Balita tidak mendapat ASI eksklusif — edukasi & dukungan menyusui perlu ditingkatkan.';
    }
    if ($stunting->sanitasi_layak === 'Tidak') {
        $riskFactors[] = 'Akses sanitasi belum layak — risiko infeksi berulang, perbaikan sanitasi lingkungan disarankan.';
    }
    if ($stunting->imunisasi_lengkap === 'Tidak') {
        $riskFactors[] = 'Imunisasi belum lengkap — lengkapi jadwal imunisasi sesuai anjuran Posyandu/Puskesmas.';
    }
    if ($stunting->riwayat_diare >= 3) {
        $riskFactors[] = 'Riwayat diare cukup sering (' . $stunting->riwayat_diare . ' kali) — periksa kualitas air minum dan kebersihan makanan.';
    }
    if ($stunting->protein_harian < 25) {
        $riskFactors[] = 'Asupan protein harian tergolong rendah (' . $stunting->protein_harian . ' g) — tingkatkan konsumsi telur, ikan, atau daging.';
    }
    if ($stunting->frekuensi_makan < 3) {
        $riskFactors[] = 'Frekuensi makan kurang dari 3x/hari — atur pola makan lebih teratur.';
    }
@endphp

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Hasil Prediksi</h1>
        <a href="{{ route('stunting.index') }}" class="text-sm text-blue-600 hover:underline">← Riwayat</a>
    </div>

    @if($stunting->prediction_code == 1)
        <div class="bg-red-50 border-2 border-red-400 rounded-2xl p-6 sm:p-8 mb-5 text-center shadow">
            <p class="text-5xl sm:text-6xl mb-3">⚠️</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-red-700">STUNTING</h2>
            <p class="text-red-500 mt-2 text-sm">Balita terdeteksi berisiko stunting. Segera konsultasi ke tenaga medis / Puskesmas terdekat.</p>
        </div>
    @else
        <div class="bg-green-50 border-2 border-green-400 rounded-2xl p-6 sm:p-8 mb-5 text-center shadow">
            <p class="text-5xl sm:text-6xl mb-3">✅</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-green-700">TIDAK STUNTING</h2>
            <p class="text-green-500 mt-2 text-sm">Pertumbuhan balita dalam kondisi normal. Tetap pantau tumbuh kembangnya secara berkala.</p>
        </div>
    @endif

    @if($stunting->probability_stunting_percent !== null)
        <div class="bg-white rounded-xl shadow p-4 mb-5">
            <p class="text-gray-500 text-sm mb-1 text-center">Probabilitas Stunting</p>
            <p class="text-3xl font-bold text-center {{ $stunting->prediction_code == 1 ? 'text-red-600' : 'text-green-600' }}">
                {{ number_format($stunting->probability_stunting_percent, 2) }}%
            </p>
            <div class="w-full bg-gray-100 rounded-full h-2.5 mt-3">
                <div class="h-2.5 rounded-full {{ $stunting->prediction_code == 1 ? 'bg-red-500' : 'bg-green-500' }}"
                     style="width: {{ min(100, max(0, $stunting->probability_stunting_percent)) }}%"></div>
            </div>
        </div>
    @endif

    {{-- Rekomendasi tindak lanjut otomatis --}}
    @if(count($riskFactors) > 0)
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-5 mb-5">
            <h3 class="font-semibold text-amber-800 mb-3 text-sm flex items-center gap-2">
                💡 Rekomendasi Tindak Lanjut
            </h3>
            <ul class="space-y-2">
                @foreach($riskFactors as $factor)
                    <li class="text-sm text-amber-700 flex gap-2">
                        <span class="shrink-0">•</span>
                        <span>{{ $factor }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="text-xs text-amber-600 mt-3 italic">
                Catatan: rekomendasi ini bersifat umum berdasarkan data yang diinput, bukan pengganti diagnosis atau saran medis profesional.
            </p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-5 mb-5 text-sm text-gray-700">
        <h3 class="font-semibold text-gray-800 mb-3 text-base">Detail Data</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Nama Balita</span><span class="font-medium text-right">{{ $stunting->nama_balita ?? '-' }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Usia</span><span class="font-medium text-right">{{ $stunting->usia_bulan }} bulan</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Jenis Kelamin</span><span class="font-medium text-right">{{ $stunting->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Berat Lahir</span><span class="font-medium text-right">{{ $stunting->berat_lahir_kg }} kg</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Panjang Lahir</span><span class="font-medium text-right">{{ $stunting->panjang_lahir_cm }} cm</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">ASI Eksklusif</span><span class="font-medium text-right">{{ $stunting->asi_eksklusif }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Protein Harian</span><span class="font-medium text-right">{{ $stunting->protein_harian }} g</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Frekuensi Makan</span><span class="font-medium text-right">{{ $stunting->frekuensi_makan }}x/hari</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Tinggi Ibu</span><span class="font-medium text-right">{{ $stunting->tinggi_ibu_cm }} cm</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Riwayat Diare</span><span class="font-medium text-right">{{ $stunting->riwayat_diare }} kali</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Pendapatan Keluarga</span><span class="font-medium text-right">Rp {{ number_format($stunting->pendapatan_keluarga, 0, ',', '.') }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Sanitasi Layak</span><span class="font-medium text-right">{{ $stunting->sanitasi_layak }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Imunisasi Lengkap</span><span class="font-medium text-right">{{ $stunting->imunisasi_lengkap }}</span></div>
            <div class="flex justify-between border-b py-2"><span class="text-gray-500">Risk Score</span><span class="font-medium text-right">{{ $stunting->risk_score }}</span></div>
            <div class="flex justify-between border-b py-2 sm:border-b-0"><span class="text-gray-500">Diprediksi oleh</span><span class="font-medium text-right">{{ $stunting->predicted_by ?? '-' }}</span></div>
            <div class="flex justify-between py-2"><span class="text-gray-500">Waktu</span><span class="font-medium text-right">{{ $stunting->created_at->format('d M Y H:i') }}</span></div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('stunting.create') }}"
           class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
            + Prediksi Baru
        </a>
        <a href="{{ route('stunting.index') }}"
           class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm">
            Lihat Riwayat
        </a>
    </div>

</div>
@endsection

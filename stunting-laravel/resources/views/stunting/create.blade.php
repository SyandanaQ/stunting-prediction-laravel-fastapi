@extends('layouts.app')

@section('title', 'Prediksi Baru - Prediksi Stunting')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🩺 Prediksi Stunting Balita</h1>
            <p class="text-sm text-gray-500 mt-1">Isi data balita untuk mendapatkan hasil klasifikasi</p>
        </div>
        <a href="{{ route('stunting.index') }}" class="text-sm text-blue-600 hover:underline whitespace-nowrap ml-4">← Riwayat</a>
    </div>

    <div class="bg-white rounded-xl shadow p-5 sm:p-6">
        <form action="{{ route('stunting.store') }}" method="POST" id="predictionForm" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Balita</label>
                <input type="text" name="nama_balita" value="{{ old('nama_balita') }}"
                       placeholder="Opsional"
                       class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usia (bulan) *</label>
                    <input type="number" name="usia_bulan" value="{{ old('usia_bulan') }}" min="0" max="60"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('usia_bulan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                            class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berat Lahir (kg) *</label>
                    <input type="number" step="0.01" min="0.5" max="7" id="berat_lahir_kg" name="berat_lahir_kg" value="{{ old('berat_lahir_kg') }}"
                           placeholder="cth: 3.2"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('berat_lahir_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Panjang Lahir (cm) *</label>
                    <input type="number" step="0.1" min="25" max="65" name="panjang_lahir_cm" value="{{ old('panjang_lahir_cm') }}"
                           placeholder="cth: 50.0"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('panjang_lahir_cm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ASI Eksklusif *</label>
                    <select name="asi_eksklusif" id="asi_eksklusif"
                            class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">-- Pilih --</option>
                        <option value="Ya"    {{ old('asi_eksklusif')=='Ya'?'selected':'' }}>Ya</option>
                        <option value="Tidak" {{ old('asi_eksklusif')=='Tidak'?'selected':'' }}>Tidak</option>
                    </select>
                    @error('asi_eksklusif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Protein Harian (gram) *</label>
                    <input type="number" step="0.1" min="0" max="150" id="protein_harian" name="protein_harian" value="{{ old('protein_harian') }}"
                           placeholder="cth: 45.0"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('protein_harian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frekuensi Makan (x/hari) *</label>
                    <input type="number" min="0" max="10" id="frekuensi_makan" name="frekuensi_makan" value="{{ old('frekuensi_makan') }}"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('frekuensi_makan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi Ibu (cm) *</label>
                    <input type="number" step="0.1" min="120" max="200" id="tinggi_ibu_cm" name="tinggi_ibu_cm" value="{{ old('tinggi_ibu_cm') }}"
                           placeholder="cth: 160.0"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('tinggi_ibu_cm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Diare (kali/bulan) *</label>
                    <input type="number" min="0" max="20" id="riwayat_diare" name="riwayat_diare" value="{{ old('riwayat_diare') }}"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('riwayat_diare')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendapatan Keluarga (Rp/bulan) *</label>
                    <input type="number" min="0" id="pendapatan_keluarga" name="pendapatan_keluarga" value="{{ old('pendapatan_keluarga') }}"
                           placeholder="cth: 6000000"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('pendapatan_keluarga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sanitasi Layak *</label>
                    <select name="sanitasi_layak" id="sanitasi_layak"
                            class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">-- Pilih --</option>
                        <option value="Ya"    {{ old('sanitasi_layak')=='Ya'?'selected':'' }}>Ya</option>
                        <option value="Tidak" {{ old('sanitasi_layak')=='Tidak'?'selected':'' }}>Tidak</option>
                    </select>
                    @error('sanitasi_layak')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imunisasi Lengkap *</label>
                    <select name="imunisasi_lengkap" id="imunisasi_lengkap"
                            class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">-- Pilih --</option>
                        <option value="Ya"    {{ old('imunisasi_lengkap')=='Ya'?'selected':'' }}>Ya</option>
                        <option value="Tidak" {{ old('imunisasi_lengkap')=='Tidak'?'selected':'' }}>Tidak</option>
                    </select>
                    @error('imunisasi_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">Risk Score (0-100) *</label>
                    <button type="button" id="autoCalcBtn"
                            class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                        ⚡ Hitung otomatis dari data di atas
                    </button>
                </div>
                <input type="number" step="0.1" min="0" max="100" id="risk_score" name="risk_score" value="{{ old('risk_score') }}"
                       placeholder="cth: 35.0"
                       class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                <p class="text-xs text-gray-400 mt-1">
                    Estimasi otomatis bersifat perkiraan kasar. Jika Anda punya angka resmi dari tenaga medis, gunakan itu.
                </p>
                @error('risk_score')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed text-white font-semibold py-2.5 rounded-lg transition text-sm mt-2 flex items-center justify-center gap-2">
                <span id="submitText">🔍 Prediksi Sekarang</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Estimasi risk_score kasar berdasarkan faktor-faktor lain di form.
    // Ini HANYA bantuan UX, bukan formula resmi dari model ML.
    document.getElementById('autoCalcBtn').addEventListener('click', function () {
        const val = (id) => document.getElementById(id).value;
        const num = (id, fallback = 0) => {
            const v = parseFloat(val(id));
            return isNaN(v) ? fallback : v;
        };

        let score = 30; // baseline netral

        // Gizi & pemberian makan
        const protein = num('protein_harian', 30);
        score += (30 - protein) * 0.6;

        const frekuensi = num('frekuensi_makan', 3);
        score += (3 - frekuensi) * 4;

        if (val('asi_eksklusif') === 'Tidak') score += 10;

        // Kesehatan
        const diare = num('riwayat_diare', 0);
        score += diare * 5;

        if (val('sanitasi_layak') === 'Tidak') score += 10;
        if (val('imunisasi_lengkap') === 'Tidak') score += 8;

        // Faktor ibu & sosio-ekonomi
        const tinggiIbu = num('tinggi_ibu_cm', 155);
        score += (155 - tinggiIbu) * 0.4;

        const pendapatan = num('pendapatan_keluarga', 4000000);
        score += (4000000 - pendapatan) / 300000;

        const beratLahir = num('berat_lahir_kg', 3.0);
        score += (3.0 - beratLahir) * 15;

        score = Math.max(0, Math.min(100, Math.round(score * 10) / 10));

        document.getElementById('risk_score').value = score;
    });

    // Cegah double-submit & beri feedback loading saat menunggu respons FastAPI
    document.getElementById('predictionForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('submitText');
        btn.disabled = true;
        text.textContent = '⏳ Memproses prediksi...';
    });
</script>
@endpush
@endsection

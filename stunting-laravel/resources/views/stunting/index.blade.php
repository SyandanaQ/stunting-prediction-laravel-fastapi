@extends('layouts.app')

@section('title', 'Riwayat Prediksi - Prediksi Stunting')

@section('content')
<div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">📋 Riwayat Prediksi Stunting</h1>
    <a href="{{ route('stunting.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition text-center">
        + Prediksi Baru
    </a>
</div>

{{-- Form Search & Filter --}}
<form method="GET" action="{{ route('stunting.index') }}"
      class="bg-white rounded-xl shadow p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama balita..."
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400">

    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Status</option>
        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>⚠️ Stunting</option>
        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>✅ Tidak Stunting</option>
    </select>

    <div class="flex gap-2">
        <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Cari
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('stunting.index') }}"
               class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-lg transition">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Tabel (desktop) --}}
<div class="bg-white rounded-xl shadow overflow-hidden hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">#</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Nama Balita</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Usia</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Probabilitas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Oleh</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Waktu</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($predictions as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-500">{{ $p->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $p->nama_balita ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->usia_bulan }} bln</td>
                    <td class="px-4 py-3">
                        @if($p->prediction_code == 1)
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">⚠️ Stunting</span>
                        @else
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">✅ Tidak Stunting</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $p->probability_stunting_percent !== null ? number_format($p->probability_stunting_percent, 2).'%' : '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->predicted_by ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('stunting.show', $p->id) }}" class="text-blue-600 hover:underline text-xs font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">Belum ada data prediksi yang cocok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Card list (mobile) --}}
<div class="md:hidden space-y-3">
    @forelse($predictions as $p)
        <a href="{{ route('stunting.show', $p->id) }}"
           class="block bg-white rounded-xl shadow p-4 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-2">
                <p class="font-semibold text-gray-800">{{ $p->nama_balita ?? 'Tanpa nama' }}</p>
                @if($p->prediction_code == 1)
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold shrink-0">⚠️ Stunting</span>
                @else
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold shrink-0">✅ Normal</span>
                @endif
            </div>
            <div class="text-xs text-gray-500 space-y-1">
                <p>Usia: {{ $p->usia_bulan }} bulan &middot; Probabilitas: {{ $p->probability_stunting_percent !== null ? number_format($p->probability_stunting_percent, 1).'%' : '-' }}</p>
                <p>Oleh {{ $p->predicted_by ?? '-' }} &middot; {{ $p->created_at->format('d M Y H:i') }}</p>
            </div>
        </a>
    @empty
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-400 text-sm">
            Belum ada data prediksi yang cocok.
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $predictions->links() }}
</div>
@endsection

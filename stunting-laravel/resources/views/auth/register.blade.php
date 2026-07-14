@extends('layouts.app')

@section('title', 'Daftar - Prediksi Stunting')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-sm">
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">🩺</div>
            <h1 class="text-xl font-bold text-gray-800">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Prediksi Stunting Balita</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" autofocus
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="Nama Anda" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="nama@email.com" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="Minimal 8 karakter" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="Ulangi password" required>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Daftar
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection

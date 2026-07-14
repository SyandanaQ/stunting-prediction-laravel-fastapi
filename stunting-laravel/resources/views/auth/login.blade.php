@extends('layouts.app')

@section('title', 'Masuk - Prediksi Stunting')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-sm">
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">🩺</div>
            <h1 class="text-xl font-bold text-gray-800">Masuk ke Akun Anda</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Prediksi Stunting Balita</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            @if ($errors->any() && !$errors->has('api'))
                <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" autofocus
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="nama@email.com" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                           class="border border-gray-300 rounded-lg w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="••••••••" required>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-4">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection

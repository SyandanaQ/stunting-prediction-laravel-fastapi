<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Prediksi Stunting')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 antialiased">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="flex items-center gap-2 font-bold text-gray-800 text-lg">
                    <span>🩺</span>
                    <span class="hidden sm:inline">Prediksi Stunting</span>
                </a>

                @auth
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('stunting.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stunting.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            Riwayat
                        </a>
                        <a href="{{ route('stunting.create') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stunting.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            Prediksi Baru
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline text-sm text-gray-500">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm font-medium text-gray-600 hover:text-red-600 transition px-3 py-2 rounded-lg hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            @auth
                {{-- Menu mobile (di bawah 768px) --}}
                <div class="md:hidden flex items-center gap-1 pb-3 -mt-1 overflow-x-auto">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('stunting.index') }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition {{ request()->routeIs('stunting.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Riwayat
                    </a>
                    <a href="{{ route('stunting.create') }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition {{ request()->routeIs('stunting.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Prediksi Baru
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-start gap-2">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-start gap-2">
                <span>❌</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->has('api'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-start gap-2">
                <span>❌</span>
                <span>{{ $errors->first('api') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen flex items-center justify-center py-10 px-4 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.22),_transparent_30%),linear-gradient(180deg,_#f8fafc_0%,_#e0f2fe_100%)]">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center justify-center rounded-full bg-white/90 p-4 shadow-lg shadow-slate-300/30 border border-slate-200">
                    <x-application-logo class="w-16 h-16 fill-current text-sky-600" />
                </a>
                <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-sm font-semibold text-sky-700">
                    <span>Selamat datang di</span>
                    <span class="text-slate-900">CafeSync</span>
                </div>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ config('app.name', 'CafeSync') }}</h1>
                <p class="mt-2 text-sm text-slate-600">Masuk untuk mengelola transaksi dan laporan kasir dengan cepat dan mudah.</p>
            </div>

            <div class="bg-white/95 shadow-2xl border border-slate-200/80 backdrop-blur-xl overflow-hidden rounded-[2rem]">
                <div class="px-6 py-8 sm:px-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>

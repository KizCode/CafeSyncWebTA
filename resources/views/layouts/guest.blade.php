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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center py-8 bg-slate-50">
            <div class="w-full sm:max-w-xl px-4">
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center justify-center">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-700" />
                    </a>
                    <h1 class="mt-4 text-3xl font-semibold text-gray-900">{{ config('app.name', 'Laravel') }}</h1>
                    <p class="mt-2 text-sm text-gray-500">Masuk untuk mengelola transaksi dan laporan kasir.</p>
                </div>

                <div class="bg-white shadow-xl border border-slate-200 overflow-hidden sm:rounded-3xl">
                    <div class="px-6 py-8 sm:px-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

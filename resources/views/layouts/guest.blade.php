<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CafeSync') }} — Masuk</title>

    @include('layouts.partials.fonts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="auth-page">
        <div class="auth-page__decor" aria-hidden="true"></div>

        <div class="auth-page__inner">
            <header class="auth-page__brand">
                <a href="{{ route('login') }}" class="inline-block" aria-label="CafeSync">
                    <x-cafesync-logo size="lg" />
                </a>
                <div class="auth-page__intro">
                    <span class="auth-page__badge">
                        <i class="fas fa-seedling" aria-hidden="true"></i>
                        Kopi di tengah sawah
                    </span>
                    <h1 class="auth-page__title">{{ config('app.name', 'CafeSync') }}</h1>
                    <p class="auth-page__subtitle">
                        Kelola transaksi, stok, dan laporan kasir dengan mudah — suasana hangat seperti warung kopi pedesaan.
                    </p>
                </div>
            </header>

            <div class="auth-card">
                <div class="auth-card__body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CafeSync'))</title>

    @include('layouts.partials.fonts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('layouts.partials.cashier-core-styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>

<body class="standalone-page">
    <div class="auth-page__decor" aria-hidden="true"></div>

    <div class="standalone-page__inner">
        <header class="standalone-page__brand">
            <a href="{{ route('cashier.index') }}" aria-label="CafeSync">
                <x-cafesync-logo size="md" />
            </a>
        </header>

        <div class="standalone-page__content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>

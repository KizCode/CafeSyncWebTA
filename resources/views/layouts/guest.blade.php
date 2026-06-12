<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('ui.login_page_title', ['app' => config('app.name', 'CafeSync')]) }}</title>

    @include('layouts.partials.fonts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ file_exists(public_path('css/auth.css')) ? filemtime(public_path('css/auth.css')) : time() }}">
    <style>
        /* Fallback jika file CSS di-cache versi lama di server */
        .auth-page *, .auth-page *::before, .auth-page *::after { box-sizing: border-box; }
        .auth-page button, .auth-page input { font-family: inherit; }
        .auth-page .auth-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.65rem;
            font-size: 0.9375rem;
            border: 1px solid rgba(92, 74, 50, 0.12);
            border-radius: 0.85rem;
            background: #fff;
            outline: none;
        }
        .auth-page .auth-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }
        .auth-page .auth-btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.8rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 0.85rem;
            cursor: pointer;
            appearance: none;
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="auth-page__decor" aria-hidden="true"></div>
        <div class="auth-page__lang">
            <x-language-switcher />
        </div>

        <div class="auth-page__inner">
            <header class="auth-page__brand">
                <a href="{{ route('login') }}" class="inline-block" aria-label="CafeSync">
                    <x-cafesync-logo size="md" />
                </a>
            </header>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.partials.app-shell-head')
</head>
<body class="area-admin">
    @include('layouts.partials.app-shell-header', [
        'homeRoute' => route('admin.dashboard'),
        'areaLabel' => __('ui.admin_panel'),
    ])

    <aside class="sidebar no-print" id="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-brand__tag">
                <i class="fas fa-shield-halved" aria-hidden="true"></i>
                <span>{{ __('ui.admin_panel') }}</span>
            </span>
        </div>
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">{{ __('ui.administration') }}</p>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-gauge-high"></i></div>
                        <span class="sidebar-text">{{ __('ui.dashboard') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.products.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-mug-saucer"></i></div>
                        <span class="sidebar-text">{{ __('ui.products_recipes') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('transactions.history') }}" class="sidebar-menu-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-receipt"></i></div>
                        <span class="sidebar-text">{{ __('ui.transactions') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-chart-line"></i></div>
                        <span class="sidebar-text">{{ __('ui.reports') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('settings.queue') }}" class="sidebar-menu-link {{ request()->routeIs('settings.queue*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-cog"></i></div>
                        <span class="sidebar-text">{{ __('ui.queue_settings') }}</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-divider"></div>
            <p class="sidebar-section-label">{{ __('ui.account') }}</p>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('profile.edit') }}" class="sidebar-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-user"></i></div>
                        <span class="sidebar-text">{{ __('ui.profile') }}</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer-info">
            <div class="sidebar-footer-card">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
                <p>{{ __('ui.admin_footer') }}</p>
            </div>
        </div>
    </aside>

    <main class="main-content page-area" id="mainContent">
        @if (session('error'))
            <div class="container-fluid page-shell pt-3 mb-0">
                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('ui.close') }}"></button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <x-receipt-modal />

    @prepend('scripts')
        <script>
            window.CafeSyncReceiptLabels = {
                title: @json(__('ui.transaction_receipt')),
                paymentTitle: @json(__('ui.payment_receipt')),
                paymentDesc: @json(__('ui.receipt_popup_desc')),
                historyDesc: @json(__('ui.transaction_receipt')),
                close: @json(__('ui.close')),
                continuePos: @json(__('ui.continue_pos')),
            };
        </script>
        <script src="{{ asset('js/receipt-popup.js') }}?v={{ file_exists(public_path('js/receipt-popup.js')) ? filemtime(public_path('js/receipt-popup.js')) : time() }}"></script>
    @endprepend
    @include('layouts.partials.app-shell-scripts')
</body>
</html>

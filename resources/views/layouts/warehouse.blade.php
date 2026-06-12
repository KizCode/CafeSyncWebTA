<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.partials.app-shell-head')
</head>
<body class="area-gudang">
    @include('layouts.partials.app-shell-header', [
        'homeRoute' => route('warehouse.index'),
        'areaLabel' => __('ui.warehouse'),
    ])

    <aside class="sidebar no-print" id="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-brand__tag">
                <i class="fas fa-warehouse" aria-hidden="true"></i>
                <span>{{ __('ui.warehouse_menu') }}</span>
            </span>
        </div>
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">{{ __('ui.warehouse') }}</p>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('warehouse.index') }}" class="sidebar-menu-link {{ request()->routeIs('warehouse.index') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-gauge-high"></i></div>
                        <span class="sidebar-text">{{ __('ui.dashboard') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('warehouse.ingredients.index') }}" class="sidebar-menu-link {{ request()->routeIs('warehouse.ingredients.*') ? 'active' : '' }}">
                        <div class="sidebar-icon"><i class="fas fa-boxes-stacked"></i></div>
                        <span class="sidebar-text">{{ __('ui.ingredients') }}</span>
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
                <i class="fas fa-boxes-stacked" aria-hidden="true"></i>
                <p>{{ __('ui.warehouse_footer') }}</p>
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

    @include('layouts.partials.app-shell-scripts')
</body>
</html>

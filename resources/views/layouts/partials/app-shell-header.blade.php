<header class="top-header no-print">
    <div class="header-left">
        <button id="toggleSidebar" class="btn-toggle" type="button" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a href="{{ $homeRoute ?? route('dashboard') }}" class="logo-section text-decoration-none">
            <div class="logo-icon">
                <i class="fas fa-mug-hot"></i>
            </div>
            <div class="logo-text">
                <div class="logo-name">CafeSync</div>
                <div class="logo-subtitle">{{ $areaLabel ?? 'POS' }}</div>
            </div>
        </a>
    </div>

    <div class="header-right">
        <div class="header-actions">
            <x-language-switcher class="me-1" />

            <button id="themeToggleBtn" class="btn-toggle" type="button" aria-label="{{ __('ui.toggle_theme') }}">
                <i id="themeToggleIcon" class="fas fa-moon"></i>
            </button>

            <div class="user-dropdown">
                <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="Menu pengguna">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10B981&color=fff"
                        alt="" class="user-avatar">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                    <li>
                        <div class="dropdown-header-user">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                            @if (Auth::user()->role)
                                <small class="d-block text-muted">{{ Auth::user()->translatedRoleName() }}</small>
                            @endif
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2 text-success"></i> {{ __('ui.profile') }}
                        </a></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> {{ __('ui.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

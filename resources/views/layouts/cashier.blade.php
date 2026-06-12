<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pos System')</title>

    <style>
        html { background: #f5f1e8; }
        body { background: #f5f1e8; color: #111827; }
        html.dark { background: #0f172a; }
        html.dark body { background: #0f172a; color: #e2e8f0; }
    </style>
    <script>
        (function() {
            try {
                if (localStorage.getItem('appTheme') === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {
                // Ignore if storage access is blocked.
            }
        })();
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('layouts.partials.fonts')

    @include('layouts.partials.cashier-core-styles')

    @stack('styles')
</head>

<body class="area-kasir">
    <!-- Top Header -->
    <header class="top-header no-print">
        <div class="header-left">
            <button id="toggleSidebar" class="btn-toggle" type="button" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('cashier.index') }}" class="logo-section text-decoration-none">
                <div class="logo-icon">
                    <i class="fas fa-mug-hot"></i>
                </div>
                <div class="logo-text">
                    <div class="logo-name">CafeSync</div>
                    <div class="logo-subtitle">{{ __('ui.cashier') }}</div>
                </div>
            </a>
        </div>

        <div class="header-center d-none d-lg-flex">
            <div class="header-datetime">
                <div class="header-info-item">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <span id="currentDate"></span>
                </div>
                <div class="header-info-divider" aria-hidden="true"></div>
                <div class="header-info-item">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span id="currentTime"></span>
                </div>
            </div>
        </div>

        <div class="header-right">
            <div class="header-actions">
            <x-language-switcher class="me-1" />

            <button id="themeToggleBtn" class="btn-toggle" type="button" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-title="{{ __('ui.toggle_theme') }}" aria-label="{{ __('ui.toggle_theme') }}">
                <i id="themeToggleIcon" class="fas fa-moon"></i>
            </button>

            <div class="user-dropdown">
                <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-label="Menu pengguna">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10B981&color=fff"
                        alt="" class="user-avatar">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                    <li>
                        <div class="dropdown-header-user">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2 text-success"></i>
                            <span>{{ __('ui.profile') }}</span>
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.account') }}">
                            <i class="fas fa-lock me-2 text-success"></i>
                            <span>{{ __('ui.account_security') }}</span>
                        </a></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>{{ __('ui.logout') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            </div>
        </div>
    </header>

    <aside class="sidebar no-print" id="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-brand__tag">
                <i class="fas fa-seedling" aria-hidden="true"></i>
                <span>{{ __('ui.main_menu') }}</span>
            </span>
        </div>
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">{{ __('ui.cashier') }}</p>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('cashier.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('cashier.index') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.cashier') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.cashier') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('queue.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('queue.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.production_queue') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.queue') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('cashier.products.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('cashier.products.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.products_recipes') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-mug-saucer"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.products') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('transactions.history') }}"
                        class="sidebar-menu-link {{ request()->routeIs('transactions.history') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.transaction_history') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.transactions') }}</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-divider"></div>

            <p class="sidebar-section-label">{{ __('ui.account') }}</p>
            <ul class="sidebar-menu sidebar-footer">
                <li class="sidebar-menu-item">
                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.profile') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.profile') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link" onclick="event.preventDefault(); showAbout()"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('ui.about') }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <span class="sidebar-text">{{ __('ui.about') }}</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer-info">
            <div class="sidebar-footer-card">
                <i class="fas fa-mug-hot" aria-hidden="true"></i>
                <p>{{ __('ui.cashier_footer') }}</p>
            </div>
            <div class="version-badge">v1.0.0</div>
        </div>
    </aside>

    <!-- Main Content -->
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Setup CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Bootstrap tooltips
        function initTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover',
                    delay: {
                        show: 200,
                        hide: 100
                    }
                });
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initTooltips);

        // Sidebar toggle with smooth animation
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');

        // Load saved state
        const sidebarState = localStorage.getItem('sidebarMini');
        if (sidebarState === 'true') {
            sidebar.classList.add('mini');
            mainContent.classList.add('mini');
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mini');
            mainContent.classList.toggle('mini');
            localStorage.setItem('sidebarMini', sidebar.classList.contains('mini'));

            // Reinitialize tooltips after animation
            setTimeout(initTooltips, 300);
        });

        // Locale tanggal mengikuti bahasa UI (harus tersedia sebelum updateDateTime)
        window.CafeSyncLocale = @json(app()->getLocale() === 'id' ? 'id-ID' : 'en-US');

        // Update date and time (ikut bahasa UI: ID / EN)
        function updateDateTime() {
            const locale = window.CafeSyncLocale || 'id-ID';
            const now = new Date();
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateStr = now.toLocaleDateString(locale, dateOptions);
            const timeStr = now.toLocaleTimeString(locale, {
                hour: '2-digit',
                minute: '2-digit'
            });

            const dateEl = document.getElementById('currentDate');
            const timeEl = document.getElementById('currentTime');
            if (dateEl) dateEl.textContent = dateStr;
            if (timeEl) timeEl.textContent = timeStr;
        }

        updateDateTime();
        setInterval(updateDateTime, 60000); // Update every minute

        // Show About Modal
        function showAbout() {
            const swalContent = `
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">☕</div>
                    <h3 style="margin: 0 0 8px 0;">CafeSync POS</h3>
                    <p style="margin: 0 0 16px 0; color: #6b7280;">Point of Sale System</p>
                    <div style="background: #f3f4f6; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #1f2937;"><strong>Versi:</strong> 1.0.0</p>
                        <p style="margin: 8px 0 0 0; font-size: 14px; color: #6b7280;">© 2026 - Semua hak dilindungi</p>
                    </div>
                </div>
            `;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: swalContent,
                    icon: 'info',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#10b981'
                });
            } else {
                alert('CafeSync POS System v1.0.0\n© 2026 - Semua hak dilindungi');
            }
        }

        // Refresh CSRF token every 10 minutes
        setInterval(function() {
            fetch('/refresh-csrf')
                .then(response => response.json())
                .then(data => {
                    $('meta[name="csrf-token"]').attr('content', data.token);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': data.token
                        }
                    });
                })
                .catch(() => {
                    // Silently fail
                });
        }, 600000); // 10 minutes

        // Theme mode
        const THEME_KEY = 'appTheme';
        const themeToggleBtn = document.getElementById('themeToggleBtn');

        function updateThemeIcon(dark) {
            const icon = document.getElementById('themeToggleIcon');
            if (!icon) return;
            icon.className = dark ? 'fas fa-sun' : 'fas fa-moon';
            themeToggleBtn?.classList.toggle('is-dark', dark);
            const themeLight = @json(__('ui.theme_light'));
            const themeDark = @json(__('ui.theme_dark'));
            themeToggleBtn?.setAttribute('data-bs-title', dark ? themeLight : themeDark);
            themeToggleBtn?.setAttribute('title', dark ? themeLight : themeDark);
        }

        function applyTheme(theme) {
            const dark = theme === 'dark';
            document.documentElement.classList.toggle('dark', dark);
            document.body.classList.toggle('dark', dark);
            localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light');
            updateThemeIcon(dark);
        }

        function initTheme() {
            const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
            applyTheme(savedTheme);
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    const currentDark = document.documentElement.classList.contains('dark');
                    applyTheme(currentDark ? 'light' : 'dark');
                });
            }
        }

        function stylesheetKey(href) {
            try {
                return new URL(href, location.origin).pathname;
            } catch (error) {
                return href;
            }
        }

        function syncStylesheets(doc) {
            const loaded = new Set(
                Array.from(document.querySelectorAll('head link[rel="stylesheet"]'))
                    .map(function(link) {
                        return stylesheetKey(link.getAttribute('href') || '');
                    })
            );

            doc.querySelectorAll('head link[rel="stylesheet"]').forEach(function(link) {
                const href = link.getAttribute('href');
                if (!href) return;
                const key = stylesheetKey(href);
                if (loaded.has(key)) return;
                loaded.add(key);
                document.head.appendChild(link.cloneNode(true));
            });
        }

        function syncPageScripts(doc) {
            const skipSrc = ['bootstrap', 'jquery', 'report-preview.js', 'queue-board.js', 'queue-icon-picker.js', 'cashier.js'];

            doc.querySelectorAll('body script').forEach(function(script) {
                const src = script.getAttribute('src');

                if (src) {
                    if (skipSrc.some(function(part) {
                            return src.includes(part);
                        })) {
                        return;
                    }
                    const key = stylesheetKey(src);
                    const exists = Array.from(document.querySelectorAll('body script[src]')).some(function(existing) {
                        return stylesheetKey(existing.getAttribute('src') || '') === key;
                    });
                    if (exists) return;
                    const el = document.createElement('script');
                    el.src = src;
                    document.body.appendChild(el);
                    return;
                }

                const content = (script.textContent || '').trim();
                if (!content) return;
                if (!content.includes('queueBoardConfig')) return;

                const el = document.createElement('script');
                el.textContent = content;
                document.body.appendChild(el);
            });
        }

        function reinitializePage() {
            initTooltips();
            if (typeof window.initQueueIconPickers === 'function') {
                window.initQueueIconPickers();
            }
            document.dispatchEvent(new CustomEvent('page:loaded'));
        }

        function shouldHandleLink(link) {
            if (!link || link.target && link.target !== '_self') return false;
            if (link.hasAttribute('download') || link.dataset.noAjax) return false;
            if (link.closest('[data-bs-toggle]')) return false;
            if (link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return false;
            const url = new URL(link.href, location.origin);
            if (url.pathname.startsWith('/locale/')) return false;
            return url.origin === location.origin && url.href !== location.href;
        }

        async function loadContent(url, pushState = true) {
            document.body.classList.add('page-loading');
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMain = doc.getElementById('mainContent');
                const newSidebar = doc.querySelector('.sidebar-nav');
                const newTitle = doc.querySelector('title');

                if (newMain) {
                    mainContent.innerHTML = newMain.innerHTML;
                } else {
                    throw new Error('Konten utama tidak ditemukan');
                }

                if (newSidebar) {
                    document.querySelector('.sidebar-nav').innerHTML = newSidebar.innerHTML;
                }

                if (newTitle) {
                    document.title = newTitle.textContent;
                }

                syncStylesheets(doc);
                syncPageScripts(doc);

                if (pushState) {
                    history.pushState({
                        url
                    }, '', url);
                }

                window.scrollTo(0, 0);
                reinitializePage();
            } catch (error) {
                window.location.href = url;
            } finally {
                document.body.classList.remove('page-loading');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            history.replaceState({
                url: location.href
            }, '', location.href);

            document.body.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && shouldHandleLink(link)) {
                    e.preventDefault();
                    loadContent(link.href);
                }
            });
        });

        window.addEventListener('popstate', function(event) {
            const url = event.state?.url || location.href;
            if (url) {
                loadContent(url, false);
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown') && !e.target.closest('.notification-bell')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(el => {
                    const instance = bootstrap.Dropdown.getInstance(el);
                    if (instance) instance.hide();
                });
            }
        });

        // Keyboard shortcut for sidebar toggle (Alt + S)
        document.addEventListener('keydown', function(e) {
            if ((e.altKey || e.ctrlKey) && e.key === 's') {
                e.preventDefault();
                toggleBtn.click();
            }
        });
    </script>

    <script src="{{ asset('js/report-preview.js') }}"></script>
    <script src="{{ asset('js/queue-board.js') }}"></script>
    <script src="{{ asset('js/queue-icon-picker.js') }}"></script>
    @include('layouts.partials.i18n-script')
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
    @stack('scripts')
</body>

</html>

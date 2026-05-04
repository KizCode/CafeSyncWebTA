<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pos System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/cashier.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Top Header -->
    <header class="top-header no-print">
        <div class="header-left">
            <button id="toggleSidebar" class="btn-toggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-mug-hot"></i>
                </div>
                <div class="logo-text">
                    <div class="logo-name">CafeSync</div>
                    <div class="logo-subtitle">POS System</div>
                </div>
            </div>
        </div>

        <!-- Center Info -->
        <div class="header-center d-none d-lg-flex">
            <div class="header-info-item">
                <i class="fas fa-calendar-alt text-muted"></i>
                <span id="currentDate" class="text-muted small"></span>
            </div>
            <div class="header-info-divider"></div>
            <div class="header-info-item">
                <i class="fas fa-clock text-muted"></i>
                <span id="currentTime" class="text-muted small"></span>
            </div>
        </div>

        <!-- Right Section -->
        <div class="header-right">
            <!-- Notifications -->
            <div class="notification-bell dropdown">
                <button class="notification-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <li><h6 class="px-3 py-2 dropdown-header fw-700">Notifikasi</h6></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="notification-item">
                            <div class="notification-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="notification-content">
                                <p class="mb-0 fw-500">Stok Terbatas</p>
                                <small class="text-muted">Kopi arabika tinggal 5 items</small>
                            </div>
                        </div>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="notification-item">
                            <div class="notification-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <p class="mb-0 fw-500">Transaksi Berhasil</p>
                                <small class="text-muted">10 transaksi hari ini</small>
                            </div>
                        </div>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="text-center dropdown-item text-primary small fw-500" href="#">Lihat semua notifikasi</a></li>
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="user-dropdown">
                <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=10B981&color=fff"
                         alt="User Avatar" class="user-avatar">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2 text-primary"></i>
                        <span>Profile</span>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-history me-2 text-info"></i>
                        <span>Riwayat Login</span>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger w-100 text-start">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <span>Logout</span>
                        </button>
                    </form></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar no-print" id="sidebar">
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('cashier.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('cashier.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Kasir">
                        <div class="sidebar-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <span class="sidebar-text">Kasir</span>
                        <span class="sidebar-badge">Live</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('transactions.history') }}"
                        class="sidebar-menu-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Transaksi">
                        <div class="sidebar-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <span class="sidebar-text">Transaksi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('reports.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Laporan">
                        <div class="sidebar-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="sidebar-text">Laporan</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-divider"></div>

            <ul class="sidebar-menu sidebar-footer">
                <li class="sidebar-menu-item">
                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-menu-link"
                        data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Pengaturan">
                        <div class="sidebar-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="sidebar-text">Pengaturan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#"
                        class="sidebar-menu-link"
                        onclick="event.preventDefault(); showAbout()"
                        data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Tentang">
                        <div class="sidebar-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <span class="sidebar-text">Tentang</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer Info -->
        <div class="sidebar-footer-info">
            <div class="version-badge">v1.0.0</div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

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
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover',
                    delay: { show: 200, hide: 100 }
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

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', dateOptions);
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            document.getElementById('currentDate').textContent = dateStr;
            document.getElementById('currentTime').textContent = timeStr;
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

    @stack('scripts')
</body>

</html>

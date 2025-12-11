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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #10B981;
            --secondary-color: #059669;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9FAFB;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 8px 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            height: 56px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 56px;
            width: 240px;
            height: calc(100vh - 56px);
            background: white;
            border-right: 1px solid #E5E7EB;
            z-index: 900;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar.mini {
            width: 72px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 8px 0;
            margin: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 10px 24px;
            color: #030712;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            white-space: nowrap;
        }

        .sidebar-menu-link:hover {
            background: #F3F4F6;
            color: #030712;
        }

        .sidebar-menu-link.active {
            background: #F0FDF4;
            color: var(--primary-color);
            font-weight: 500;
        }

        .sidebar-menu-link i {
            font-size: 20px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar.mini .sidebar-menu-link {
            justify-content: center;
            gap: 0;
        }

        .sidebar.mini .sidebar-menu-link span {
            display: none;
        }

        .sidebar-footer {
            padding: 8px 0;
            border-top: 1px solid #E5E7EB;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            margin-top: 56px;
            padding: 24px;
            min-height: calc(100vh - 56px);
        }

        .main-content.mini {
            margin-left: 72px;
        }

        .btn-toggle {
            background: transparent;
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            color: #030712;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-toggle:hover {
            background: #F3F4F6;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            background: transparent;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .user-btn:hover {
            background: #F3F4F6;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header no-print">
        <div class="d-flex align-items-center gap-2">
            <button id="toggleSidebar" class="btn-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo-section">
                <div class="logo-icon">C</div>
                <span class="fw-semibold">CafeSync</span>
            </div>
        </div>

        <div class="user-dropdown">
            <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle fa-lg"></i>
                <span>{{ Auth::user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar no-print" id="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('cashier.index') }}" class="sidebar-menu-link {{ request()->routeIs('cashier.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.history') }}" class="sidebar-menu-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu sidebar-footer">
            <li>
                <a href="{{ route('profile.edit') }}" class="sidebar-menu-link">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
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

        // Sidebar toggle
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

            // Save state
            localStorage.setItem('sidebarMini', sidebar.classList.contains('mini'));
        });

        // Refresh CSRF token every 10 minutes
        setInterval(function() {
            fetch('/refresh-csrf').then(response => response.json()).then(data => {
                $('meta[name="csrf-token"]').attr('content', data.token);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': data.token
                    }
                });
            }).catch(() => {
                // Silently fail
            });
        }, 600000); // 10 minutes
    </script>

    @stack('scripts')
</body>
</html>

@include('layouts.partials.i18n-script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (sidebar && mainContent && toggleBtn) {
        if (localStorage.getItem('sidebarMini') === 'true') {
            sidebar.classList.add('mini');
            mainContent.classList.add('mini');
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mini');
            mainContent.classList.toggle('mini');
            localStorage.setItem('sidebarMini', sidebar.classList.contains('mini'));
        });
    }

    const THEME_KEY = 'appTheme';
    const themeToggleBtn = document.getElementById('themeToggleBtn');

    function updateThemeIcon(dark) {
        const icon = document.getElementById('themeToggleIcon');
        if (!icon) return;
        icon.className = dark ? 'fas fa-sun' : 'fas fa-moon';
    }

    function applyTheme(theme) {
        const dark = theme === 'dark';
        document.documentElement.classList.toggle('dark', dark);
        document.body.classList.toggle('dark', dark);
        localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light');
        updateThemeIcon(dark);
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyTheme(localStorage.getItem(THEME_KEY) || 'light');
        themeToggleBtn?.addEventListener('click', function() {
            applyTheme(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
        });
    });
</script>
@stack('scripts')

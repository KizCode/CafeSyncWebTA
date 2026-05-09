import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const THEME_KEY = 'appTheme';

function applyAppTheme(theme) {
    const enabled = theme === 'dark';
    document.documentElement.classList.toggle('dark', enabled);
    localStorage.setItem(THEME_KEY, enabled ? 'dark' : 'light');
}

function initAppTheme() {
    const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
    applyAppTheme(savedTheme);

    const themeToggle = document.getElementById('themeToggleSwitch');
    if (themeToggle) {
        themeToggle.checked = savedTheme === 'dark';
        themeToggle.addEventListener('change', function () {
            applyAppTheme(this.checked ? 'dark' : 'light');
        });
    }
}

document.addEventListener('DOMContentLoaded', initAppTheme);

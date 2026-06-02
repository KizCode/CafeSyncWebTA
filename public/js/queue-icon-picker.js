(function () {
    function closeAllPickers(except) {
        document.querySelectorAll('[data-icon-picker]').forEach(function (picker) {
            if (picker === except) return;
            const dropdown = picker.querySelector('[data-icon-dropdown]');
            const trigger = picker.querySelector('[data-icon-trigger]');
            if (dropdown) dropdown.hidden = true;
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            picker.classList.remove('is-open');
        });
    }

    function initIconPickers(root) {
        (root || document).querySelectorAll('[data-icon-picker]').forEach(function (picker) {
            if (picker.dataset.iconInitialized === '1') return;
            picker.dataset.iconInitialized = '1';

            const input = picker.querySelector('[data-icon-input]');
            const trigger = picker.querySelector('[data-icon-trigger]');
            const dropdown = picker.querySelector('[data-icon-dropdown]');
            const preview = picker.querySelector('[data-icon-preview]');

            if (!input || !trigger || !dropdown) return;

            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            const rowColor = picker.closest('.queue-status-form, .queue-status-row')?.querySelector('[data-status-color-input]');
            const chipPreview = picker.closest('.queue-status-form, .queue-status-row')?.querySelector('.queue-status-form__preview, [style*="--status-color"]');

            function syncColor() {
                if (!rowColor) return;
                if (preview) preview.style.background = rowColor.value;
                if (chipPreview) chipPreview.style.setProperty('--status-color', rowColor.value);
            }

            syncColor();
            rowColor?.addEventListener('input', syncColor);

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = !dropdown.hidden;
                closeAllPickers();
                dropdown.hidden = isOpen;
                trigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                picker.classList.toggle('is-open', !isOpen);
            });

            picker.querySelectorAll('[data-icon-value]').forEach(function (option) {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const icon = option.dataset.iconValue;
                    input.value = icon;

                    if (preview) {
                        preview.innerHTML = '<i class="fas ' + icon + '" aria-hidden="true"></i>';
                    }

                    picker.querySelectorAll('[data-icon-value]').forEach(function (opt) {
                        const selected = opt === option;
                        opt.classList.toggle('is-selected', selected);
                        opt.setAttribute('aria-selected', selected ? 'true' : 'false');
                    });

                    dropdown.hidden = true;
                    trigger.setAttribute('aria-expanded', 'false');
                    picker.classList.remove('is-open');

                    const row = picker.closest('.queue-status-form, .queue-status-row');
                    const chip = row?.querySelector('[data-status-chip-icon]');
                    if (chip) {
                        chip.className = 'fas ' + icon;
                    }
                });
            });
        });
    }

    document.addEventListener('click', function () {
        closeAllPickers();
    });

    document.addEventListener('DOMContentLoaded', function () {
        initIconPickers();
    });

    document.addEventListener('page:loaded', function () {
        initIconPickers();
    });

    window.initQueueIconPickers = initIconPickers;
})();

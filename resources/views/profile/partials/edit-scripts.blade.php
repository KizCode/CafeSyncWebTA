<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.js-toggle-edit');

        function setPanelEditing(panel, editing) {
            if (!panel) return;
            panel.classList.toggle('is-editing', editing);
            const badge = panel.querySelector('.js-mode-badge');
            const notice = panel.querySelector('.js-lock-notice');
            if (badge) {
                badge.textContent = editing ? 'Mode edit' : 'Mode lihat';
                badge.classList.toggle('is-editing', editing);
            }
            if (notice) {
                notice.style.display = editing ? 'none' : '';
            }
        }

        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const fieldset = document.getElementById(targetId);
                const panel = fieldset?.closest('.profile-panel');
                const submitButton = fieldset.querySelector('.js-submit-button');
                const cancelButton = document.querySelector(
                    `.js-cancel-edit[data-target="${targetId}"]`);

                if (fieldset.disabled) {
                    fieldset.disabled = false;
                    submitButton.disabled = false;
                    this.classList.add('d-none');
                    cancelButton.classList.remove('d-none');
                    setPanelEditing(panel, true);
                    fieldset.querySelector('.profile-field__input')?.focus();
                }
            });
        });

        document.querySelectorAll('.js-cancel-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const fieldset = document.getElementById(targetId);
                const panel = fieldset?.closest('.profile-panel');
                const submitButton = fieldset.querySelector('.js-submit-button');
                const editButton = document.querySelector(
                    `.js-toggle-edit[data-target="${targetId}"]`);
                const form = fieldset.closest('form');

                if (form) form.reset();

                fieldset.disabled = true;
                submitButton.disabled = true;
                this.classList.add('d-none');
                editButton.classList.remove('d-none');
                setPanelEditing(panel, false);
            });
        });
    });
</script>

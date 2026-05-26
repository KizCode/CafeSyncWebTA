<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.js-toggle-edit');

        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const fieldset = document.getElementById(targetId);
                const submitButton = fieldset.querySelector('.js-submit-button');
                const cancelButton = document.querySelector(
                    `.js-cancel-edit[data-target="${targetId}"]`);

                if (fieldset.disabled) {
                    fieldset.disabled = false;
                    submitButton.disabled = false;
                    this.classList.add('d-none');
                    cancelButton.classList.remove('d-none');
                    fieldset.closest('.profile-panel')?.classList.add('is-editing');
                }
            });
        });

        const cancelButtons = document.querySelectorAll('.js-cancel-edit');
        cancelButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const fieldset = document.getElementById(targetId);
                const submitButton = fieldset.querySelector('.js-submit-button');
                const editButton = document.querySelector(
                    `.js-toggle-edit[data-target="${targetId}"]`);
                const form = fieldset.closest('form');

                if (form) {
                    form.reset();
                }

                fieldset.disabled = true;
                submitButton.disabled = true;
                this.classList.add('d-none');
                editButton.classList.remove('d-none');
                fieldset.closest('.profile-panel')?.classList.remove('is-editing');
            });
        });
    });
</script>

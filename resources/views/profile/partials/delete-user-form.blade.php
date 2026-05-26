<section class="profile-panel profile-danger-zone">
    <div class="profile-panel__head profile-panel__head--danger">
        <div class="d-flex gap-3 flex-grow-1 min-w-0">
            <div class="profile-panel__icon profile-panel__icon--danger">
                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title profile-panel__title--danger">{{ __('Delete Account') }}</h3>
                <p class="profile-panel__desc">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="profile-panel__body">
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
            <i class="fas fa-trash-alt me-1"></i>{{ __('Delete Account') }}
        </button>
    </div>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1"
        aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ __('Are you sure you want to delete your account?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror" id="password"
                            name="password" placeholder="{{ __('Password') }}">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                modal.show();
            });
        </script>
    @endif
</section>

<section class="profile-panel">
    <div class="profile-panel__head">
        <div class="d-flex gap-3 flex-grow-1 min-w-0">
            <div class="profile-panel__icon">
                <i class="fas fa-key" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title">{{ __('Update Password') }}</h3>
                <p class="profile-panel__desc">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </div>
        </div>
        <div class="profile-panel__actions">
            <button type="button" class="btn btn-sm profile-btn-edit js-toggle-edit" data-target="password-fieldset">
                <i class="fas fa-pen me-1"></i>{{ __('Edit') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit d-none"
                data-target="password-fieldset">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>

    <div class="profile-panel__body">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <fieldset id="password-fieldset" disabled>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password"
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                id="update_password_current_password" name="current_password"
                                autocomplete="current-password" placeholder="••••••••">
                        </div>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i
                                    class="fas fa-lock-open text-muted"></i></span>
                            <input type="password"
                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                id="update_password_password" name="password" autocomplete="new-password"
                                placeholder="••••••••">
                        </div>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="update_password_password_confirmation"
                            class="form-label">{{ __('Confirm Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i
                                    class="fas fa-check-double text-muted"></i></span>
                            <input type="password"
                                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                id="update_password_password_confirmation" name="password_confirmation"
                                autocomplete="new-password" placeholder="••••••••">
                        </div>
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-panel__footer mt-4">
                    <button type="submit" class="btn btn-success js-submit-button" disabled>
                        <i class="fas fa-save me-1"></i>{{ __('Save') }}
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="profile-saved">
                            <i class="fas fa-check-circle"></i>{{ __('Saved.') }}
                        </span>
                    @endif
                </div>
            </fieldset>
        </form>
    </div>
</section>

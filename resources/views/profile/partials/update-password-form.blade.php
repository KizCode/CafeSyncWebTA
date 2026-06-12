<section class="profile-panel" data-panel="password">
    <div class="profile-panel__head">
        <div class="profile-panel__head-main">
            <div class="profile-panel__icon">
                <i class="fas fa-key" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title">{{ __('ui.password_title') }}</h3>
                <p class="profile-panel__desc">{{ __('ui.password_panel_desc') }}</p>
            </div>
        </div>
        <div class="profile-panel__actions">
            <span class="profile-mode-badge js-mode-badge">{{ __('ui.view_mode') }}</span>
            <button type="button" class="btn btn-sm profile-btn-edit js-toggle-edit" data-target="password-fieldset">
                <i class="fas fa-pen me-1"></i>{{ __('ui.edit') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit d-none"
                data-target="password-fieldset">
                {{ __('ui.cancel') }}
            </button>
        </div>
    </div>

    <div class="profile-panel__body">
        <div class="profile-lock-notice js-lock-notice">
            <div class="profile-lock-notice__icon"><i class="fas fa-lock" aria-hidden="true"></i></div>
            <div>
                <strong>{{ __('ui.password_locked') }}</strong>
                <p>{{ __('ui.password_locked_hint') }}</p>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="profile-form">
            @csrf
            @method('put')

            <fieldset id="password-fieldset" disabled>
                <div class="profile-fields">
                    <div class="profile-field profile-field--full">
                        <label for="update_password_current_password" class="profile-field__label">
                            <i class="fas fa-lock" aria-hidden="true"></i> {{ __('ui.current_password') }}
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('current_password', 'updatePassword') is-invalid @enderror"
                            id="update_password_current_password" name="current_password" autocomplete="current-password"
                            placeholder="{{ __('ui.current_password_placeholder') }}">
                        @error('current_password', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="update_password_password" class="profile-field__label">
                            <i class="fas fa-lock-open" aria-hidden="true"></i> {{ __('ui.new_password') }}
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('password', 'updatePassword') is-invalid @enderror"
                            id="update_password_password" name="password" autocomplete="new-password"
                            placeholder="{{ __('ui.new_password_placeholder') }}">
                        @error('password', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="update_password_password_confirmation" class="profile-field__label">
                            <i class="fas fa-check-double" aria-hidden="true"></i> {{ __('ui.confirm_new_password') }}
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                            id="update_password_password_confirmation" name="password_confirmation"
                            autocomplete="new-password" placeholder="{{ __('ui.confirm_new_password_placeholder') }}">
                        @error('password_confirmation', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-panel__footer">
                    <button type="submit" class="btn btn-success profile-btn-save js-submit-button" disabled>
                        <i class="fas fa-save me-1"></i>{{ __('ui.save_password') }}
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="profile-saved">
                            <i class="fas fa-check-circle"></i> {{ __('ui.password_updated') }}
                        </span>
                    @endif
                </div>
            </fieldset>
        </form>
    </div>
</section>

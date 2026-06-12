<section class="profile-panel" data-panel="profile-info">
    <div class="profile-panel__head">
        <div class="profile-panel__head-main">
            <div class="profile-panel__icon">
                <i class="fas fa-id-card" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title">{{ __('ui.profile_info_title') }}</h3>
                <p class="profile-panel__desc">{{ __('ui.profile_info_desc') }}</p>
            </div>
        </div>
        <div class="profile-panel__actions">
            <span class="profile-mode-badge js-mode-badge">{{ __('ui.view_mode') }}</span>
            <button type="button" class="btn btn-sm profile-btn-edit js-toggle-edit"
                data-target="profile-info-fieldset">
                <i class="fas fa-pen me-1"></i>{{ __('ui.edit') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit d-none"
                data-target="profile-info-fieldset">
                {{ __('ui.cancel') }}
            </button>
        </div>
    </div>

    <div class="profile-panel__body">
        <div class="profile-lock-notice js-lock-notice">
            <div class="profile-lock-notice__icon"><i class="fas fa-lock" aria-hidden="true"></i></div>
            <div>
                <strong>{{ __('ui.data_locked') }}</strong>
                <p>{{ __('ui.data_locked_hint') }}</p>
            </div>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('patch')

            <fieldset id="profile-info-fieldset" disabled>
                <div class="profile-fields">
                    <div class="profile-field">
                        <label for="name" class="profile-field__label">
                            <i class="fas fa-user" aria-hidden="true"></i> {{ __('ui.full_name') }}
                        </label>
                        <input type="text" class="form-control profile-field__input @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus
                            autocomplete="name" placeholder="{{ __('ui.full_name_placeholder') }}">
                        @error('name')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="email" class="profile-field__label">
                            <i class="fas fa-envelope" aria-hidden="true"></i> {{ __('ui.email_address') }}
                        </label>
                        <input type="email"
                            class="form-control profile-field__input @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                            placeholder="nama@email.com">
                        @error('email')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <div class="profile-alert profile-alert--warning mt-2">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    {{ __('ui.email_not_verified') }}
                                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                                        {{ __('ui.resend_verification') }}
                                    </button>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="text-success mb-0 mt-1 small">{{ __('ui.verification_sent') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="profile-panel__footer">
                    <button type="submit" class="btn btn-success profile-btn-save js-submit-button" disabled>
                        <i class="fas fa-save me-1"></i>{{ __('ui.save_changes') }}
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="profile-saved">
                            <i class="fas fa-check-circle"></i> {{ __('ui.changes_saved') }}
                        </span>
                    @endif
                </div>
            </fieldset>
        </form>
    </div>
</section>

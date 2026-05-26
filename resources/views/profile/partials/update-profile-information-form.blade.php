<section class="profile-panel">
    <div class="profile-panel__head">
        <div class="d-flex gap-3 flex-grow-1 min-w-0">
            <div class="profile-panel__icon">
                <i class="fas fa-id-card" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title">{{ __('Profile Information') }}</h3>
                <p class="profile-panel__desc">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
            </div>
        </div>
        <div class="profile-panel__actions">
            <button type="button" class="btn btn-sm profile-btn-edit js-toggle-edit"
                data-target="profile-info-fieldset">
                <i class="fas fa-pen me-1"></i>{{ __('Edit') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit d-none"
                data-target="profile-info-fieldset">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>

    <div class="profile-panel__body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <fieldset id="profile-info-fieldset" disabled>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}" required autofocus
                                autocomplete="name">
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i
                                    class="fas fa-envelope text-muted"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <div class="alert alert-warning mt-3 mb-0 py-2 small">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="text-success mb-0 mt-2">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="profile-panel__footer mt-4">
                    <button type="submit" class="btn btn-success js-submit-button" disabled>
                        <i class="fas fa-save me-1"></i>{{ __('Save') }}
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="profile-saved">
                            <i class="fas fa-check-circle"></i>{{ __('Saved.') }}
                        </span>
                    @endif
                </div>
            </fieldset>
        </form>
    </div>
</section>

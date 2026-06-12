<x-guest-layout>
    @if (session('status'))
        <div class="auth-flash auth-flash--success">{{ session('status') }}</div>
    @endif

    <div class="auth-card__header">
        <h1 class="auth-card__header-title">{{ __('ui.login_title') }}</h1>
        <p class="auth-card__header-desc">{{ __('ui.login_desc') }}</p>
    </div>

    <div class="auth-card__body">
        <form method="POST" action="{{ route('login') }}" class="auth-form" autocomplete="on">
            @csrf

            <div class="auth-field">
                <label class="auth-label" for="login">{{ __('ui.username_or_email') }}</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-user auth-input-wrap__icon" aria-hidden="true"></i>
                    <input id="login" type="text" name="login" class="auth-input" value="{{ old('login') }}" required
                        autofocus autocomplete="username" placeholder="{{ __('ui.login_placeholder') }}">
                </div>
                @error('login')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password">{{ __('ui.password') }}</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock auth-input-wrap__icon" aria-hidden="true"></i>
                    <input id="password" type="password" name="password" class="auth-input" required
                        autocomplete="current-password" placeholder="{{ __('ui.password_placeholder') }}">
                </div>
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form__row">
                <label for="remember_me" class="auth-remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>{{ __('ui.remember_me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">{{ __('ui.forgot_password') }}</a>
                @endif
            </div>

            <button type="submit" class="auth-btn-primary auth-btn-primary--full">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                {{ __('ui.login') }}
            </button>
        </form>
    </div>
</x-guest-layout>

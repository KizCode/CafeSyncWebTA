<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="auth-card__header">
        <h2 class="auth-card__header-title">{{ __('Masuk') }}</h2>
        <p class="auth-card__header-desc">Masukkan email dan kata sandi untuk membuka kasir.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="auth-remember mt-4">
            <input id="remember_me" type="checkbox"
                class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
            <span>{{ __('Remember me') }}</span>
        </label>

        <div class="auth-form__actions">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="w-full sm:w-auto sm:ms-auto justify-center">
                <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

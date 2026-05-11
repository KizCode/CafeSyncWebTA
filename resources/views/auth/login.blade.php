<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">CafeSync</p>
        <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ __('Masuk') }}</h2>
        <p class="mt-2 text-sm text-slate-600">Masukkan kredensial Anda untuk mulai menggunakan kasir.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5 space-y-1">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3 mt-5">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input id="remember_me" type="checkbox" class="text-sky-600 border-slate-300 rounded shadow-sm focus:ring-sky-500" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-sky-600 underline rounded-md hover:text-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-300"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-auto w-full sm:w-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

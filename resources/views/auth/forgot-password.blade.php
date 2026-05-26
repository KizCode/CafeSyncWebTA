<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-auth-form-header title="{{ __('Forgot Password') }}"
        description="Masukkan email Anda. Kami akan mengirim tautan reset kata sandi." />

    <p class="text-sm text-slate-600 mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required
                autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="auth-form__actions mt-4">
            <x-primary-button class="w-full sm:w-auto justify-center">
                <i class="fas fa-paper-plane mr-2" aria-hidden="true"></i>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

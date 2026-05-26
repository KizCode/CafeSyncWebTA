<x-guest-layout>
    <x-auth-form-header title="{{ __('Verify Email') }}"
        description="Verifikasi alamat email Anda sebelum mulai menggunakan aplikasi." />

    <p class="text-sm text-slate-600 mb-4">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="auth-form__actions flex-column align-items-stretch">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center">
                <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center mt-3">
            @csrf
            <button type="submit" class="auth-link border-0 bg-transparent">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

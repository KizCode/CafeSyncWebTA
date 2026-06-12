<nav class="profile-nav" aria-label="{{ __('ui.settings') }}">
    <a class="profile-nav__link {{ ($activeTab ?? 'profile') === 'profile' ? 'active' : '' }}"
        href="{{ route('profile.edit') }}">
        <span class="profile-nav__icon"><i class="fas fa-user" aria-hidden="true"></i></span>
        <span class="profile-nav__text">
            <strong>{{ __('ui.profile') }}</strong>
            <small>{{ __('ui.name_email') }}</small>
        </span>
    </a>
    <a class="profile-nav__link {{ ($activeTab ?? '') === 'account' ? 'active' : '' }}"
        href="{{ route('profile.account') }}">
        <span class="profile-nav__icon"><i class="fas fa-lock" aria-hidden="true"></i></span>
        <span class="profile-nav__text">
            <strong>{{ __('ui.account_security_tab') }}</strong>
            <small>{{ __('ui.password_delete') }}</small>
        </span>
    </a>
    @if (Auth::user()->isAdministrator())
        <a class="profile-nav__link {{ ($activeTab ?? '') === 'queue' ? 'active' : '' }}"
            href="{{ route('settings.queue') }}">
            <span class="profile-nav__icon"><i class="fas fa-list-check" aria-hidden="true"></i></span>
            <span class="profile-nav__text">
                <strong>{{ __('ui.queue_production') }}</strong>
                <small>{{ __('ui.queue_status') }}</small>
            </span>
        </a>
    @endif
</nav>

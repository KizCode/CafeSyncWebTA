<nav class="mb-4">
    <div class="nav nav-pills">
        <a class="nav-link {{ ($activeTab ?? 'profile') === 'profile' ? 'active' : '' }}"
            href="{{ route('profile.edit') }}">
            Profile
        </a>
        <a class="nav-link {{ ($activeTab ?? '') === 'account' ? 'active' : '' }}" href="{{ route('profile.account') }}">
            Akun
        </a>
        <a class="nav-link {{ ($activeTab ?? '') === 'theme' ? 'active' : '' }}" href="{{ route('profile.theme') }}">
            Tema
        </a>
    </div>
</nav>

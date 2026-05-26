<nav class="profile-nav" aria-label="Menu pengaturan">
    <a class="profile-nav__link {{ ($activeTab ?? 'profile') === 'profile' ? 'active' : '' }}"
        href="{{ route('profile.edit') }}">
        <span class="profile-nav__icon"><i class="fas fa-user" aria-hidden="true"></i></span>
        <span class="profile-nav__text">
            <strong>Profil</strong>
            <small>Nama & email</small>
        </span>
    </a>
    <a class="profile-nav__link {{ ($activeTab ?? '') === 'account' ? 'active' : '' }}"
        href="{{ route('profile.account') }}">
        <span class="profile-nav__icon"><i class="fas fa-lock" aria-hidden="true"></i></span>
        <span class="profile-nav__text">
            <strong>Akun & Keamanan</strong>
            <small>Password & hapus akun</small>
        </span>
    </a>
</nav>

@props([
    'user',
    'activeTab' => 'profile',
    'title' => 'Pengaturan Profil',
    'description' => 'Kelola informasi dan keamanan akun Anda.',
    'icon' => 'fa-user-cog',
])

@php
    $user->loadMissing('role');
@endphp

<div class="container-fluid profile-page page-shell">
    <x-page-header :title="$title" :icon="$icon" badge="Pengaturan"
        :description="$description" class="mb-4" />

    <div class="profile-layout">
        <aside class="profile-sidebar">
            <div class="profile-hero">
                <div class="profile-hero__banner" aria-hidden="true"></div>
                <div class="profile-hero__body">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="profile-hero__name">{{ $user->name }}</h2>
                    <p class="profile-hero__email">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        {{ $user->email }}
                    </p>
                    @if ($user->role)
                        <span class="profile-hero__role">
                            <i class="fas fa-user-shield" aria-hidden="true"></i>
                            {{ $user->role->name }}
                        </span>
                    @endif
                    <p class="profile-hero__meta">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                        Bergabung {{ $user->created_at->translatedFormat('M Y') }}
                    </p>
                </div>
            </div>

            @include('profile.tabs', ['activeTab' => $activeTab])

            <div class="profile-tip">
                <i class="fas fa-seedling" aria-hidden="true"></i>
                <p>Tip: gunakan kata sandi kuat dan unik untuk menjaga keamanan kasir Anda.</p>
            </div>
        </aside>

        <div class="profile-content">
            {{ $slot }}
        </div>
    </div>
</div>

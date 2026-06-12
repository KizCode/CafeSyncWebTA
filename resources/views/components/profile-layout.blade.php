@props([
    'user',
    'activeTab' => 'profile',
    'title' => null,
    'description' => null,
    'icon' => 'fa-cog',
])

@php
    $title = $title ?? __('ui.profile_settings');
    $description = $description ?? __('ui.profile_desc');
    $user->loadMissing('role');
@endphp

<div class="container-fluid profile-page page-shell">
    <header class="profile-settings-header">
        <div class="profile-settings-header__icon">
            <i class="fas {{ $icon }}" aria-hidden="true"></i>
        </div>
        <div class="profile-settings-header__text">
            <span class="profile-settings-header__badge">
                <i class="fas fa-seedling" aria-hidden="true"></i> {{ __('ui.settings') }}
            </span>
            <h1 class="profile-settings-header__title">{{ $title }}</h1>
            <p class="profile-settings-header__desc">{{ $description }}</p>
        </div>
    </header>

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
                            {{ $user->translatedRoleName() }}
                        </span>
                    @endif
                    <p class="profile-hero__meta">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                        {{ __('ui.joined') }} {{ $user->created_at->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>

            @include('profile.tabs', ['activeTab' => $activeTab])

            <div class="profile-tip">
                <i class="fas fa-lightbulb" aria-hidden="true"></i>
                <p>{{ __('ui.password_tip') }}</p>
            </div>
        </aside>

        <div class="profile-content">
            <div class="profile-settings-main">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

@props([
    'title',
    'description' => null,
    'icon' => null,
    'badge' => null,
])

<header {{ $attributes->merge(['class' => 'page-header']) }}>
    <div class="page-header__main">
        @if ($badge)
            <span class="page-header__badge">
                @if ($icon)
                    <i class="fas {{ $icon }}" aria-hidden="true"></i>
                @endif
                {{ $badge }}
            </span>
        @endif
        <h1 class="page-header__title">
            @if ($icon && !$badge)
                <i class="fas {{ $icon }}" aria-hidden="true"></i>
            @endif
            {{ $title }}
        </h1>
        @if ($description)
            <p class="page-header__desc">{{ $description }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="page-header__actions">
            {{ $actions }}
        </div>
    @endif
</header>

@props([
    'showText' => true,
    'size' => 'lg',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'cafesync-brand--sm',
        'md' => 'cafesync-brand--md',
        default => 'cafesync-brand--lg',
    };
@endphp

<div {{ $attributes->merge(['class' => "cafesync-brand {$sizeClass}"]) }}>
    <div class="cafesync-brand__icon" aria-hidden="true">
        <i class="fas fa-mug-hot"></i>
    </div>
    @if ($showText)
        <div class="cafesync-brand__text">
            <span class="cafesync-brand__name">CafeSync</span>
            <span class="cafesync-brand__tagline">POS System</span>
        </div>
    @endif
</div>

@props([
    'title',
    'description' => null,
])

<div class="auth-card__header">
    <h2 class="auth-card__header-title">{{ $title }}</h2>
    @if ($description)
        <p class="auth-card__header-desc">{{ $description }}</p>
    @endif
</div>

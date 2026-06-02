@props([
    'name' => 'icon',
    'value' => 'fa-circle',
    'compact' => true,
])

@php
    $icons = config('queue.icons', ['fa-circle']);
    $value = in_array($value, $icons, true) ? $value : 'fa-circle';
    $pickerId = 'icon-picker-' . uniqid();
@endphp

<div class="icon-picker {{ $compact ? 'icon-picker--compact' : '' }}" data-icon-picker id="{{ $pickerId }}">
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-icon-input>
    <button type="button" class="icon-picker__trigger" data-icon-trigger aria-haspopup="listbox"
        aria-expanded="false" aria-label="Pilih ikon">
        <span class="icon-picker__preview" data-icon-preview>
            <i class="fas {{ $value }}" aria-hidden="true"></i>
        </span>
        <i class="fas fa-chevron-down icon-picker__caret" aria-hidden="true"></i>
    </button>
    <div class="icon-picker__dropdown" data-icon-dropdown hidden>
        <p class="icon-picker__dropdown-title">Pilih ikon</p>
        <div class="icon-picker__grid" role="listbox">
            @foreach ($icons as $icon)
                <button type="button" class="icon-picker__option {{ $icon === $value ? 'is-selected' : '' }}"
                    data-icon-value="{{ $icon }}" role="option"
                    aria-selected="{{ $icon === $value ? 'true' : 'false' }}"
                    title="{{ str_replace(['fa-', '-'], ['', ' '], $icon) }}">
                    <i class="fas {{ $icon }}" aria-hidden="true"></i>
                </button>
            @endforeach
        </div>
    </div>
</div>

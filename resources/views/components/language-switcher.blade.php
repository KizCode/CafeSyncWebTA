@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'lang-switcher ' . $class]) }} role="group" aria-label="{{ __('ui.language') }}">
    <a href="{{ route('locale.switch', 'id') }}"
        class="lang-switcher__btn {{ app()->getLocale() === 'id' ? 'is-active' : '' }}"
        @if (app()->getLocale() === 'id') aria-current="true" @endif>ID</a>
    <a href="{{ route('locale.switch', 'en') }}"
        class="lang-switcher__btn {{ app()->getLocale() === 'en' ? 'is-active' : '' }}"
        @if (app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
</div>

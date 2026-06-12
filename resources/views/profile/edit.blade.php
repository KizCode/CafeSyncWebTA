@extends($areaLayout ?? 'layouts.cashier')

@section('title', __('ui.profile_settings'))

@section('content')
    <x-profile-layout :user="$user" activeTab="profile" :title="__('ui.profile_info')" icon="fa-user-circle"
        :description="__('ui.profile_info_desc')">
        @include('profile.partials.update-profile-information-form')
    </x-profile-layout>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
    @include('profile.partials.edit-scripts')
@endpush

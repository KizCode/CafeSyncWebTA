@extends($areaLayout ?? 'layouts.cashier')

@section('title', 'Account - Pos System')

@section('content')
    <x-profile-layout :user="$user" activeTab="account" title="Akun & Keamanan" icon="fa-shield-halved"
        description="Amankan akun dengan kata sandi baru atau kelola penghapusan akun.">
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </x-profile-layout>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
    @include('profile.partials.edit-scripts')
@endpush

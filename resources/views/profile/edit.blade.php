@extends('layouts.cashier')

@section('title', 'Profile - Pos System')

@section('content')
    <x-profile-layout :user="$user" activeTab="profile" title="Informasi Profil" icon="fa-user"
        description="Perbarui nama dan alamat email yang terhubung ke akun kasir Anda.">
        @include('profile.partials.update-profile-information-form')
    </x-profile-layout>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
    @include('profile.partials.edit-scripts')
@endpush

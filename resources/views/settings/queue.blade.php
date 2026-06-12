@extends('layouts.admin')

@section('title', __('ui.queue_settings_page'))

@section('content')
    <x-profile-layout :user="$user" activeTab="queue" :title="__('ui.queue_settings_page')"
        :description="__('ui.queue_settings_desc')"
        icon="fa-list-check">

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('ui.close') }}"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('ui.close') }}"></button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('queue.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-columns me-1"></i> {{ __('ui.open_queue_board') }}
            </a>
        </div>

        @include('queue.partials.settings')
    </x-profile-layout>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/queue.css') }}">
@endpush

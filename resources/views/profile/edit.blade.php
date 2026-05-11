@extends('layouts.cashier')

@section('title', 'Profile - Pos System')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4 class="mb-4"><i class="fas fa-user-circle me-2"></i>Profile</h4>

                @include('profile.tabs', ['activeTab' => $activeTab ?? 'profile'])

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

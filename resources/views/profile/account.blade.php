@extends('layouts.cashier')

@section('title', 'Account - Pos System')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4 class="mb-4"><i class="fas fa-user-shield me-2"></i>Akun</h4>

                @include('profile.tabs', ['activeTab' => $activeTab ?? 'account'])

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

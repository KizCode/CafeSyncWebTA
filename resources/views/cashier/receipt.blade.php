@extends('layouts.standalone')

@section('title', __('ui.payment_receipt'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaction-receipt.css') }}">
@endpush

@section('content')
    <div class="card page-card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4 no-print">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h3>{{ __('ui.payment_success') }}</h3>
                <p class="text-muted">{{ __('ui.payment_success_desc') }}</p>
            </div>

            <x-transaction-receipt :transaction="$transaction" class="mb-4" />

            <div class="d-grid gap-2 no-print">
                <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-primary" target="_blank" rel="noopener">
                    <i class="fas fa-print"></i> {{ __('ui.print_receipt') }}
                </a>
                <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> {{ __('ui.download_pdf') }}
                </a>
                <a href="{{ route('cashier.index') }}" class="btn btn-success">
                    <i class="fas fa-shopping-cart"></i> {{ __('ui.new_transaction') }}
                </a>
                <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history"></i> {{ __('ui.view_history') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endpush

@extends('layouts.cashier')



@section('title', __('ui.transaction_receipt') . ' — ' . $transaction->invoice_number)



@push('styles')

    <link rel="stylesheet" href="{{ asset('css/transaction-receipt.css') }}">

@endpush



@section('content')

    <div class="container-fluid page-shell">

        <x-page-header :title="__('ui.transaction_receipt_title')" icon="fa-receipt" :badge="__('ui.transaction_receipt')"

            :description="__('ui.invoice_number') . ' ' . $transaction->invoice_number">

            <x-slot:actions>

                <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary btn-sm no-print">

                    <i class="fas fa-arrow-left me-1"></i> {{ __('ui.back_to_history') }}

                </a>

                <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-success btn-sm no-print"

                    target="_blank">

                    <i class="fas fa-print me-1"></i> {{ __('ui.print') }}

                </a>

            </x-slot:actions>

        </x-page-header>



        <div class="row justify-content-center no-print mb-3">

            <div class="col-auto">

                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-primary btn-sm">

                    <i class="fas fa-list me-1"></i> {{ __('ui.transaction_detail_link') }}

                </a>

            </div>

        </div>



        <x-transaction-receipt :transaction="$transaction" />

    </div>

@endsection



@push('styles')

    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

@endpush


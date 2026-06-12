@extends($layout ?? 'layouts.cashier')

@section('title', __('ui.transaction_receipt') . ' — ' . $transaction->invoice_number)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaction-receipt.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endpush

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header :title="__('ui.transaction_receipt_title')" icon="fa-receipt" :badge="__('ui.transaction_receipt')"
            :description="__('ui.invoice_number') . ' ' . $transaction->invoice_number">
            <x-slot:actions>
                <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary btn-sm no-print">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('ui.back_to_history') }}
                </a>
                <button type="button" class="btn btn-success btn-sm no-print"
                    onclick="window.ReceiptPopup ? window.ReceiptPopup.show({{ $transaction->id }}, { fromHistory: true }) : window.open('{{ route('transactions.print', $transaction->id) }}', '_blank')">
                    <i class="fas fa-print me-1"></i> {{ __('ui.print') }}
                </button>
            </x-slot:actions>
        </x-page-header>

        <div class="d-flex justify-content-center py-3">
            <x-transaction-receipt :transaction="$transaction" />
        </div>
    </div>
@endsection

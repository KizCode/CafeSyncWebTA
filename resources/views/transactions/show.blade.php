@extends('layouts.cashier')

@section('title', __('ui.transaction_detail'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaction-receipt.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endpush

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header :title="__('ui.transaction_detail')" icon="fa-receipt" :badge="__('ui.transactions')"
            :description="__('ui.invoice_number') . ' ' . $transaction->invoice_number">
            <x-slot:actions>
                <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary btn-sm no-print">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('ui.back_to_history') }}
                </a>
                <a href="{{ route('transactions.struk', $transaction->id) }}" class="btn btn-outline-success btn-sm no-print">
                    <i class="fas fa-receipt me-1"></i> {{ __('ui.transaction_receipt') }}
                </a>
                <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-success btn-sm no-print"
                    target="_blank">
                    <i class="fas fa-print me-1"></i> {{ __('ui.print') }}
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="row g-4">
            <div class="col-lg-5">
                <x-transaction-receipt :transaction="$transaction" class="h-100" />
            </div>
            <div class="col-lg-7">
                <div class="card page-card h-100">
                    <div class="card-header">
                        <h2 class="h6 mb-0"><i class="fas fa-list me-2 text-success"></i>{{ __('ui.transaction_breakdown') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <span class="text-muted small d-block">{{ __('ui.invoice_number') }}</span>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block">{{ __('ui.date') }}</span>
                                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block">{{ __('ui.payment_method') }}</span>
                                <span class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block">{{ __('ui.status') }}</span>
                                @if ($transaction->status == 'lunas')
                                    <span class="badge bg-success">{{ __('ui.paid') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('ui.unpaid') }}</span>
                                @endif
                            </div>
                            @if ($transaction->customer_name || $transaction->queue_number)
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">{{ __('ui.queue_name') }}</span>
                                    <strong class="text-success">{{ $transaction->customer_name ?? $transaction->queue_number }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('ui.product') }}</th>
                                        <th class="text-center">{{ __('ui.quantity') }}</th>
                                        <th class="text-end">{{ __('ui.price') }}</th>
                                        <th class="text-end">{{ __('ui.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? '—' }}</td>
                                            <td class="text-center fw-semibold">{{ $item->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">Rp
                                                {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="ms-auto" style="max-width: 320px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('ui.subtotal') }}</span>
                                <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if ($transaction->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-1 text-success">
                                    <span>{{ __('ui.discount') }}</span>
                                    <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($transaction->is_tax_enabled)
                                <div class="d-flex justify-content-between mb-1 text-info">
                                    <span>{{ __('ui.tax_ppn') }}</span>
                                    <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold fs-5 mt-2 pt-2 border-top">
                                <span>{{ __('ui.total') }}</span>
                                <span class="text-success">Rp
                                    {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

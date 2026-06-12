@extends($layout ?? 'layouts.admin')

@section('title', __('ui.transaction_history'))

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header :title="__('ui.transaction_history')" icon="fa-history" :badge="__('ui.transactions')"
            :description="__('ui.transaction_history_desc')">
            <x-slot:actions>
                @if (auth()->user()->role?->name === 'Administrator' && request('start_date') && request('end_date'))
                    <a href="{{ route('transactions.history.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-danger btn-sm" target="_blank" data-no-ajax>
                        <i class="fas fa-file-pdf me-1"></i> {{ __('ui.export_pdf') }}
                    </a>
                @endif
                @if (($layout ?? '') === 'layouts.cashier')
                    <a href="{{ route('cashier.index') }}" class="btn btn-outline-secondary btn-sm" data-no-ajax>
                        <i class="fas fa-cash-register me-1"></i> {{ __('ui.cashier') }}
                    </a>
                @endif
            </x-slot:actions>
        </x-page-header>

        <div class="card page-card">
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">{{ __('ui.transactions_on_page') }}</span>
                                    <h3 class="mb-0">{{ $transactions->count() }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-receipt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">{{ __('ui.total_payment') }}</span>
                                    <h3 class="mb-0">Rp
                                        {{ number_format($transactions->sum('grand_total'), 0, ',', '.') }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">{{ __('ui.paid_transactions') }}</span>
                                    <h3 class="mb-0">{{ $transactions->where('status', 'lunas')->count() }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="GET" class="mb-4">
                    <div class="card card-soft filter-panel shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ui.start_date') }}</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ui.end_date') }}</label>
                                    <input type="date" class="form-control" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary btn-sm me-2 mb-2">
                                        <i class="fas fa-filter"></i> {{ __('ui.filter') }}
                                    </button>
                                    <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary btn-sm mb-2">
                                        <i class="fas fa-redo"></i> {{ __('ui.reset') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive card table-card p-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('ui.invoice_number') }}</th>
                                <th>{{ __('ui.date') }}</th>
                                <th>{{ __('ui.items') }}</th>
                                <th>{{ __('ui.total') }}</th>
                                <th>{{ __('ui.method') }}</th>
                                <th>{{ __('ui.status') }}</th>
                                <th class="text-center" style="min-width: 140px;">{{ __('ui.receipt') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <button type="button"
                                            class="btn btn-link p-0 fw-bold text-decoration-none text-start"
                                            data-receipt-id="{{ $transaction->id }}" data-receipt-mode="history">
                                            {{ $transaction->invoice_number }}
                                        </button>
                                        @if ($transaction->queue_number)
                                            <br><small class="text-success">{{ __('ui.queue') }}
                                                {{ $transaction->queue_number }}</small>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->items->sum('quantity') }} {{ __('ui.item') }}</td>
                                    <td class="fw-bold text-nowrap">Rp
                                        {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                                    </td>
                                    <td>
                                        @if ($transaction->status == 'lunas')
                                            <span class="badge bg-success">{{ __('ui.paid') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ __('ui.unpaid') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="transaction-struk-actions justify-content-center">
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm"
                                                data-receipt-id="{{ $transaction->id }}"
                                                data-receipt-mode="history"
                                                title="{{ __('ui.view_receipt') }}">
                                                <i class="fas fa-receipt me-1"></i> {{ __('ui.receipt') }}
                                            </button>
                                            <button type="button"
                                                class="btn btn-success btn-sm"
                                                data-receipt-id="{{ $transaction->id }}"
                                                data-receipt-mode="history"
                                                title="{{ __('ui.print_receipt') }}">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox d-block"></i>
                                            {{ __('ui.no_transactions') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">{{ __('ui.showing_transactions', ['count' => $transactions->count(), 'total' => $transactions->total()]) }}</div>
                    <div>{{ $transactions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

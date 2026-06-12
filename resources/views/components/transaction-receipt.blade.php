@props(['transaction'])

@php
    $paymentLabels = [
        'cash' => __('ui.cash'),
        'tunai' => __('ui.cash'),
        'qris' => __('ui.qris'),
        'debit' => __('ui.debit_card'),
        'credit' => __('ui.credit_card'),
    ];
    $paymentLabel = $paymentLabels[strtolower($transaction->payment_method ?? '')] ?? strtoupper($transaction->payment_method ?? '-');
    $isCash = in_array(strtolower($transaction->payment_method ?? ''), ['cash', 'tunai'], true);
    $queueSettings = \App\Models\QueueSetting::current();
    $orderName = trim((string) ($transaction->customer_name ?? ''));
    if ($orderName === '' && $queueSettings->show_queue_on_receipt) {
        $orderName = trim((string) ($transaction->queue_number ?? ''));
    }
    $fmt = fn ($n) => number_format((float) $n, 0, ',', '.');
@endphp

<div {{ $attributes->merge(['class' => 'transaction-receipt', 'id' => 'receiptContent']) }}>
    <header class="transaction-receipt__brand">
        <div class="transaction-receipt__logo" aria-hidden="true">
            <i class="fas fa-mug-hot"></i>
        </div>
        <h2 class="transaction-receipt__title">{{ config('app.name', 'CafeSync') }}</h2>
        <p class="transaction-receipt__tagline">{{ __('ui.payment_receipt') }}</p>
        <div class="transaction-receipt__rule transaction-receipt__rule--double"></div>
    </header>

    @if ($orderName !== '')
        <div class="transaction-receipt__queue-ticket">
            <span class="transaction-receipt__queue-label">{{ __('ui.queue_name') }}</span>
            <strong class="transaction-receipt__queue-value">{{ $orderName }}</strong>
        </div>
    @endif

    <section class="transaction-receipt__meta">
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.invoice_number') }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--mono">{{ $transaction->invoice_number }}</span>
        </div>
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.date') }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--mono">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.status') }}</span>
            <span class="transaction-receipt__badge {{ $transaction->status === 'lunas' ? 'is-paid' : '' }}">
                {{ $transaction->status === 'lunas' ? __('ui.paid') : __('ui.unpaid') }}
            </span>
        </div>
    </section>

    <div class="transaction-receipt__rule"></div>

    <section class="transaction-receipt__items">
        <div class="transaction-receipt__items-head">
            <span>{{ __('ui.items') }}</span>
            <span>{{ __('ui.qty_short') }}</span>
            <span>{{ __('ui.total') }}</span>
        </div>
        @foreach ($transaction->items as $item)
            <article class="transaction-receipt__item">
                <div class="transaction-receipt__item-name">{{ $item->product->name ?? __('ui.product') }}</div>
                <div class="transaction-receipt__item-grid">
                    <span class="transaction-receipt__item-unit">@ Rp {{ $fmt($item->unit_price) }}</span>
                    <span class="transaction-receipt__item-qty">×{{ $item->quantity }}</span>
                    <span class="transaction-receipt__item-total">Rp {{ $fmt($item->total_price) }}</span>
                </div>
            </article>
        @endforeach
    </section>

    <div class="transaction-receipt__rule"></div>

    <section class="transaction-receipt__totals">
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.subtotal') }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--mono">Rp {{ $fmt($transaction->subtotal) }}</span>
        </div>
        @if ($transaction->discount_amount > 0)
            <div class="transaction-receipt__row is-discount">
                <span class="transaction-receipt__label">
                    {{ __('ui.discount') }}
                    @if ($transaction->discount_type === 'percent')
                        ({{ $transaction->discount_value }}%)
                    @endif
                </span>
                <span class="transaction-receipt__value transaction-receipt__value--mono">− Rp {{ $fmt($transaction->discount_amount) }}</span>
            </div>
        @endif
        @if ($transaction->is_tax_enabled)
            <div class="transaction-receipt__row is-tax">
                <span class="transaction-receipt__label">{{ __('ui.tax_ppn') }}</span>
                <span class="transaction-receipt__value transaction-receipt__value--mono">Rp {{ $fmt($transaction->tax_amount) }}</span>
            </div>
        @endif
        <div class="transaction-receipt__row transaction-receipt__row--grand">
            <span>{{ strtoupper(__('ui.total')) }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--mono">Rp {{ $fmt($transaction->grand_total) }}</span>
        </div>
    </section>

    <div class="transaction-receipt__rule transaction-receipt__rule--double"></div>

    <section class="transaction-receipt__payment">
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.payment_method') }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--strong">{{ $paymentLabel }}</span>
        </div>
        <div class="transaction-receipt__row">
            <span class="transaction-receipt__label">{{ __('ui.paid_amount') }}</span>
            <span class="transaction-receipt__value transaction-receipt__value--mono">Rp {{ $fmt($transaction->paid_amount) }}</span>
        </div>
        @if ($isCash && $transaction->change_amount > 0)
            <div class="transaction-receipt__row">
                <span class="transaction-receipt__label">{{ __('ui.change') }}</span>
                <span class="transaction-receipt__value transaction-receipt__value--mono">Rp {{ $fmt($transaction->change_amount) }}</span>
            </div>
        @endif
    </section>

    <footer class="transaction-receipt__footer">
        <div class="transaction-receipt__rule transaction-receipt__rule--double"></div>
        <p class="transaction-receipt__thanks">{{ __('ui.thank_you') }}</p>
        <p class="transaction-receipt__fine-print">{{ __('ui.no_returns') }}</p>
        <p class="transaction-receipt__timestamp">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
    </footer>
</div>

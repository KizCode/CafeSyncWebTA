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

@endphp



<div {{ $attributes->merge(['class' => 'transaction-receipt', 'id' => 'receiptContent']) }}>

    <div class="transaction-receipt__brand text-center">

        <div class="transaction-receipt__logo">

            <i class="fas fa-mug-hot" aria-hidden="true"></i>

        </div>

        <h2 class="transaction-receipt__title">CafeSync</h2>

        <p class="transaction-receipt__subtitle">{{ __('ui.payment_receipt') }}</p>

    </div>



    <div class="transaction-receipt__meta">

        <div class="transaction-receipt__row">

            <span>{{ __('ui.invoice_number') }}</span>

            <strong>{{ $transaction->invoice_number }}</strong>

        </div>

        @if ($queueSettings->show_queue_on_receipt && $transaction->queue_number)

            <div class="transaction-receipt__row transaction-receipt__row--queue">

                <span>{{ __('ui.queue_name') }}</span>

                <strong>{{ $transaction->queue_number }}</strong>

            </div>

        @endif

        <div class="transaction-receipt__row">

            <span>{{ __('ui.date') }}</span>

            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>

        </div>

        <div class="transaction-receipt__row">

            <span>{{ __('ui.status') }}</span>

            <span class="transaction-receipt__badge {{ $transaction->status === 'lunas' ? 'is-paid' : '' }}">

                {{ $transaction->status === 'lunas' ? __('ui.paid') : __('ui.unpaid') }}

            </span>

        </div>

    </div>



    <div class="transaction-receipt__items">

        @foreach ($transaction->items as $item)

            <div class="transaction-receipt__item">

                <div class="transaction-receipt__item-name">{{ $item->product->name ?? __('ui.product') }}</div>

                <div class="transaction-receipt__item-line">

                    <span>{{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>

                    <span>Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>

                </div>

            </div>

        @endforeach

    </div>



    <div class="transaction-receipt__totals">

        <div class="transaction-receipt__row">

            <span>{{ __('ui.subtotal') }}</span>

            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>

        </div>

        @if ($transaction->discount_amount > 0)

            <div class="transaction-receipt__row is-discount">

                <span>{{ __('ui.discount') }}

                    @if ($transaction->discount_type === 'percent')

                        ({{ $transaction->discount_value }}%)

                    @endif

                </span>

                <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>

            </div>

        @endif

        @if ($transaction->is_tax_enabled)

            <div class="transaction-receipt__row is-tax">

                <span>{{ __('ui.tax_ppn') }}</span>

                <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>

            </div>

        @endif

        <div class="transaction-receipt__row transaction-receipt__row--grand">

            <span>{{ strtoupper(__('ui.total')) }}</span>

            <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>

        </div>

    </div>



    <div class="transaction-receipt__payment">

        <div class="transaction-receipt__row">

            <span>{{ __('ui.payment_method') }}</span>

            <strong>{{ $paymentLabel }}</strong>

        </div>

        <div class="transaction-receipt__row">

            <span>{{ __('ui.paid_amount') }}</span>

            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>

        </div>

        @if ($isCash && $transaction->change_amount > 0)

            <div class="transaction-receipt__row">

                <span>{{ __('ui.change') }}</span>

                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>

            </div>

        @endif

    </div>



    <div class="transaction-receipt__footer text-center">

        <p>{{ __('ui.thank_you') }}</p>

        <small>{{ __('ui.no_returns') }}</small>

    </div>

</div>


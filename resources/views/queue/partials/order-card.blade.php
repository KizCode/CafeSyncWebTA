@php
    $itemsSummary = $order->items->map(fn ($i) => $i->quantity . '× ' . ($i->product->name ?? __('ui.item')))->take(3)->implode(', ');
    $moreCount = max(0, $order->items->count() - 3);
    $currentIndex = $statuses->search(fn ($s) => $s->id === $order->production_status_id);
    $nextStatus = $currentIndex !== false ? $statuses->get($currentIndex + 1) : null;
    $displayName = trim((string) ($order->customer_name ?: $order->queue_number));
@endphp

<article class="queue-card" data-order-id="{{ $order->id }}">
    <div class="queue-card__drag" title="{{ __('ui.drag_order') }}" aria-label="{{ __('ui.drag_order') }}">
        <i class="fas fa-grip-vertical" aria-hidden="true"></i>
    </div>
    <div class="queue-card__content">
        <div class="queue-card__head">
            <button type="button" class="queue-card__number queue-card__name-edit"
                data-name="{{ $displayName }}" title="{{ __('ui.click_rename') }}">
                {{ $displayName }}
            </button>
            <span class="queue-card__time">{{ $order->queued_at?->format('H:i') ?? $order->created_at->format('H:i') }}</span>
        </div>
        <p class="queue-card__invoice small text-muted mb-1">{{ $order->invoice_number }}</p>
        <p class="queue-card__items">{{ $itemsSummary }}@if ($moreCount > 0) {{ __('ui.more_items', ['count' => $moreCount]) }} @endif</p>
        <p class="queue-card__total fw-bold mb-2">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>

        @if ($nextStatus)
            <button type="button" class="btn btn-sm btn-success w-100 queue-card__advance"
                data-status-id="{{ $nextStatus->id }}">
                <i class="fas fa-arrow-right me-1"></i> {{ $nextStatus->name }}
            </button>
        @elseif ($doneStatus ?? null)
            <button type="button" class="btn btn-sm btn-outline-secondary w-100 queue-card__advance"
                data-status-id="{{ $doneStatus->id }}">
                <i class="fas fa-check me-1"></i> {{ __('ui.done') }}
            </button>
        @endif
    </div>
</article>

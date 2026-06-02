@php
    $itemsSummary = $order->items->map(fn ($i) => $i->quantity . '× ' . ($i->product->name ?? 'Item'))->take(3)->implode(', ');
    $moreCount = max(0, $order->items->count() - 3);
    $currentIndex = $statuses->search(fn ($s) => $s->id === $order->production_status_id);
    $nextStatus = $currentIndex !== false ? $statuses->get($currentIndex + 1) : null;
@endphp

<article class="queue-card" data-order-id="{{ $order->id }}">
    <div class="queue-card__head">
        <span class="queue-card__number">{{ $order->queue_number }}</span>
        <span class="queue-card__time">{{ $order->queued_at?->format('H:i') ?? $order->created_at->format('H:i') }}</span>
    </div>
    <p class="queue-card__invoice small text-muted mb-1">{{ $order->invoice_number }}</p>
    <p class="queue-card__items">{{ $itemsSummary }}@if ($moreCount > 0) +{{ $moreCount }} lainnya @endif</p>
    <p class="queue-card__total fw-bold mb-2">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>

    @if ($nextStatus)
        <button type="button" class="btn btn-sm btn-success w-100 queue-card__advance"
            data-status-id="{{ $nextStatus->id }}" data-status-name="{{ $nextStatus->name }}">
            <i class="fas fa-arrow-right me-1"></i> {{ $nextStatus->name }}
        </button>
    @else
        @if ($doneStatus ?? null)
            <button type="button" class="btn btn-sm btn-outline-secondary w-100 queue-card__advance"
                data-status-id="{{ $doneStatus->id }}" data-status-name="{{ $doneStatus->name }}">
                <i class="fas fa-check me-1"></i> Tandai Selesai
            </button>
        @endif
    @endif
</article>

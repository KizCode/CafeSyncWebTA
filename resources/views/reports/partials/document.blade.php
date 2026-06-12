<table class="report-header">
    <tr>
        <td style="width: 52px;">
            <div class="brand-logo">C</div>
        </td>
        <td style="padding-left: 10px;">
            <div class="brand-name">CafeSync</div>
            <div class="brand-tagline">Kopi &amp; Sawah — POS System</div>
            <div class="report-title">{{ __('ui.revenue_report') }}</div>
        </td>
        <td class="report-meta">
            <strong>{{ __('ui.printed_at') }}: {{ date('d/m/Y H:i') }}</strong>
            {{ __('ui.report_period_label') }}:<br>
            <span class="period-pill">
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                {{ __('ui.to_date_sep') }}
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </span>
        </td>
    </tr>
</table>

<div class="section-title">{{ __('ui.financial_summary') }}</div>

<table class="summary-table">
    <tr>
        <td>
            <div class="stat-box stat-box--green">
                <div class="stat-label">{{ __('ui.revenue') }}</div>
                <div class="stat-value green">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box stat-box--blue">
                <div class="stat-label">{{ __('ui.transactions') }}</div>
                <div class="stat-value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box">
                <div class="stat-label">{{ __('ui.items_sold') }}</div>
                <div class="stat-value">{{ number_format($totalItemsSold, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box stat-box--gold">
                <div class="stat-label">{{ __('ui.expenses') }}</div>
                <div class="stat-value red">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
        </td>
    </tr>
</table>

<table class="profit-banner">
    <tr>
        <td>
            <div class="label">{{ __('ui.gross_profit') }}</div>
            <div class="value">Rp {{ number_format($grossProfit, 0, ',', '.') }}</div>
        </td>
    </tr>
</table>

<div class="section-title">10 {{ __('ui.best_sellers') }}</div>

@php
    $topProductsTotal = $topProducts->sum(function ($item) {
        return ($item->product?->price ?? 0) * $item->total_qty;
    });
@endphp

<table class="data-table">
    <thead>
        <tr>
            <th style="width: 24px;">#</th>
            <th>{{ __('ui.product') }}</th>
            <th>{{ __('ui.category') }}</th>
            <th class="text-right">{{ __('ui.quantity') }}</th>
            <th class="text-right">{{ __('ui.price') }}</th>
            <th class="text-right">{{ __('ui.subtotal') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($topProducts as $index => $item)
            @php
                $price = $item->product?->price ?? 0;
                $lineTotal = $price * $item->total_qty;
            @endphp
            <tr class="{{ $index % 2 === 1 ? 'alt' : '' }}">
                <td class="rank">{{ $index + 1 }}</td>
                <td class="product-name">{{ $item->product?->name ?? '—' }}</td>
                <td class="muted">{{ $item->product?->category?->name ?? '—' }}</td>
                <td class="text-right">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($price, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($lineTotal, 0, ',', '.') }}</strong></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="empty-msg">{{ __('ui.no_sales_data') }}</td>
            </tr>
        @endforelse
    </tbody>
    @if ($topProducts->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">{{ __('ui.top_products_total') }}</td>
                <td class="text-right" style="color: #047857;">Rp {{ number_format($topProductsTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    @endif
</table>

<table class="report-footer-meta">
    <tr>
        <td>CafeSync POS — {{ __('ui.revenue_report') }}</td>
        <td style="text-align: right;">{{ __('ui.printed_at') }} {{ date('d/m/Y H:i') }}</td>
    </tr>
</table>

<div class="disclaimer">
    {{ __('ui.report_disclaimer') }}
</div>

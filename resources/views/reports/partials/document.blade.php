<table class="report-header">
    <tr>
        <td style="width: 52px;">
            <div class="brand-logo">C</div>
        </td>
        <td style="padding-left: 10px;">
            <div class="brand-name">CafeSync</div>
            <div class="brand-tagline">Kopi &amp; Sawah — POS System</div>
            <div class="report-title">Laporan Pendapatan</div>
        </td>
        <td class="report-meta">
            <strong>Dicetak: {{ date('d/m/Y H:i') }}</strong>
            Periode laporan:<br>
            <span class="period-pill">
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                s/d
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </span>
        </td>
    </tr>
</table>

<div class="section-title">Ringkasan Keuangan</div>

<table class="summary-table">
    <tr>
        <td>
            <div class="stat-box stat-box--green">
                <div class="stat-label">Pendapatan</div>
                <div class="stat-value green">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box stat-box--blue">
                <div class="stat-label">Transaksi</div>
                <div class="stat-value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box">
                <div class="stat-label">Item Terjual</div>
                <div class="stat-value">{{ number_format($totalItemsSold, 0, ',', '.') }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box stat-box--gold">
                <div class="stat-label">Pengeluaran</div>
                <div class="stat-value red">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
        </td>
    </tr>
</table>

<table class="profit-banner">
    <tr>
        <td>
            <div class="label">Laba Kotor (Pendapatan − Pengeluaran)</div>
            <div class="value">Rp {{ number_format($grossProfit, 0, ',', '.') }}</div>
        </td>
    </tr>
</table>

<div class="section-title">10 Produk Terlaris</div>

@php
    $topProductsTotal = $topProducts->sum(function ($item) {
        return ($item->product?->price ?? 0) * $item->total_qty;
    });
@endphp

<table class="data-table">
    <thead>
        <tr>
            <th style="width: 24px;">#</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Subtotal</th>
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
                <td colspan="6" class="empty-msg">Tidak ada data penjualan pada periode ini.</td>
            </tr>
        @endforelse
    </tbody>
    @if ($topProducts->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Total produk terlaris:</td>
                <td class="text-right" style="color: #047857;">Rp {{ number_format($topProductsTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    @endif
</table>

<table class="report-footer-meta">
    <tr>
        <td>CafeSync POS — Laporan Pendapatan</td>
        <td style="text-align: right;">Dicetak {{ date('d/m/Y H:i') }}</td>
    </tr>
</table>

<div class="disclaimer">
    Dokumen dihasilkan otomatis oleh CafeSync POS. Hanya transaksi berstatus <strong>LUNAS</strong> dalam periode
    terpilih yang dihitung.
</div>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #555;
        }

        .totals {
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 8px;
        }

        .payment {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }

        .text-success {
            color: #28a745;
        }

        .text-info {
            color: #17a2b8;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>STRUK PEMBAYARAN</h2>
        <p>{{ config('app.name', 'Sistem Kasir') }}</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span>No. Invoice:</span>
            <strong>{{ $transaction->invoice_number }}</strong>
        </div>
        <div class="info-row">
            <span>Tanggal:</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span>Kasir:</span>
            <span>Admin</span>
        </div>
    </div>

    <div class="items">
        @foreach($transaction->items as $item)
        <div class="item">
            <div class="item-header">
                <span>{{ $item->product->name }}</span>
            </div>
            <div class="item-detail">
                <span>{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>

        @if($transaction->discount_amount > 0)
        <div class="total-row text-success">
            <span>Diskon
                @if($transaction->discount_type == 'percent')
                    ({{ $transaction->discount_value }}%)
                @endif
            :</span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($transaction->is_tax_enabled)
        <div class="total-row text-info">
            <span>PPN 11%:</span>
            <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment">
        <div class="total-row">
            <span>Metode Bayar:</span>
            <strong style="text-transform: uppercase;">{{ $transaction->payment_method }}</strong>
        </div>
        <div class="total-row">
            <span>Dibayar:</span>
            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        @if($transaction->payment_method == 'tunai')
        <div class="total-row">
            <span>Kembalian:</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        <p style="margin-top: 10px;">{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

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
            line-height: 1.4;
            padding: 10px;
            max-width: 300px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 10px;
        }

        .totals {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row.grand {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
        }

        .payment {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>CAFESYNC</h2>
        <div>Jl. Contoh No. 123</div>
        <div>Telp: 0812-3456-7890</div>
    </div>

    <div class="info">
        <div class="info-row">
            <span>Invoice:</span>
            <span>{{ $transaction->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span>Tanggal:</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Kasir:</span>
            <span>Admin</span>
        </div>
    </div>

    <div class="items">
        @foreach($transaction->items as $item)
        <div class="item">
            <div class="item-name">{{ $item->product->name }}</div>
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
        <div class="total-row">
            <span>Diskon:</span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($transaction->tax_amount > 0)
        <div class="total-row">
            <span>Pajak (11%):</span>
            <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="total-row grand">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment">
        <div class="total-row">
            <span>Metode:</span>
            <span>{{ strtoupper($transaction->payment_method) }}</span>
        </div>
        @if($transaction->payment_method === 'cash')
        <div class="total-row">
            <span>Bayar:</span>
            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Kembalian:</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <div>Terima Kasih</div>
        <div>Selamat Datang Kembali</div>
    </div>
</body>
</html>

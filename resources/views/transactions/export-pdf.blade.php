<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 8px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .summary-row.total { font-size: 15px; font-weight: bold; border-top: 2px solid #333; padding-top: 4px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table th { background: #e3eafc; color: #333; padding: 5px; }
        table td { padding: 4px; border: 1px solid #ccc; }
        table tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #10B981; color: white; }
        .badge-secondary { background: #6B7280; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print { body { padding: 0; } @page { margin: 1cm; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>CAFESYNC</h1>
        <h2>Laporan Riwayat Transaksi</h2>
        <p>Periode: {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <span>Total Transaksi:</span>
            <span><strong>{{ $totalTransactions }} transaksi</strong></span>
        </div>
        <div class="summary-row total">
            <span>TOTAL PENDAPATAN:</span>
            <span>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Invoice</th>
                <th width="15%">Tanggal</th>
                <th width="10%">Metode</th>
                <th width="10%" class="text-center">Jumlah Item</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $transaction->invoice_number }}</strong></td>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                <td><span class="badge badge-secondary">{{ strtoupper($transaction->payment_method) }}</span></td>
                <td class="text-center">{{ $transaction->items->sum('quantity') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>CAFESYNC - Sistem Kasir</p>
    </div>

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                margin: 1cm;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>CAFESYNC</h1>
        <h2>Laporan Riwayat Transaksi</h2>
        <p>Periode: {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <span>Total Transaksi:</span>
            <span><strong>{{ $totalTransactions }} transaksi</strong></span>
        </div>
        <div class="summary-row total">
            <span>TOTAL PENDAPATAN:</span>
            <span>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Invoice</th>
                <th width="15%">Tanggal</th>
                <th width="10%">Metode</th>
                <th width="10%" class="text-center">Jumlah Item</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $transaction->invoice_number }}</strong></td>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                <td><span class="badge badge-secondary">{{ strtoupper($transaction->payment_method) }}</span></td>
                <td class="text-center">{{ $transaction->items->sum('quantity') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>CAFESYNC - Sistem Kasir</p>
    </div>
</body>
</html>

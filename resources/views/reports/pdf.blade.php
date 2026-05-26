<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Bitter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap");
        body { font-family: "Plus Jakarta Sans", Arial, sans-serif; font-size: 12px; }
        h1, h2, h3 { font-family: "Bitter", Georgia, serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary-table, .products-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table th, .summary-table td, .products-table th, .products-table td { border: 1px solid #ccc; padding: 6px; }
        .products-table th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pendapatan</h2>
        <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>
    <table class="summary-table">
        <tr>
            <th>Total Pendapatan</th>
            <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Transaksi</th>
            <td>{{ $totalTransactions }}</td>
        </tr>
        <tr>
            <th>Total Item Terjual</th>
            <td>{{ $totalItemsSold }}</td>
        </tr>
        <tr>
            <th>Total Pengeluaran</th>
            <td>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Laba Kotor</th>
            <td>Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
        </tr>
    </table>
    <h4>10 Produk Terlaris</h4>
    <table class="products-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $item)
            <tr>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td>{{ $item->total_qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="text-align:right;">Dicetak: {{ date('d/m/Y H:i') }}</p>
</body>
</html>

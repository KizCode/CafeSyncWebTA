<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    // ...existing code...

    public function downloadPdf(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $transactions = Transaction::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->where('status', 'lunas')->get();

        $totalRevenue = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();
        $totalItemsSold = TransactionItem::whereIn('transaction_id', $transactions->pluck('id'))
            ->sum('quantity');
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        $grossProfit = $totalRevenue - $totalExpenses;

        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereIn('transaction_id', $transactions->pluck('id'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->with('product')
            ->get();

        $pdf = PDF::loadView('reports.pdf', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalExpenses',
            'grossProfit',
            'totalTransactions',
            'totalItemsSold',
            'topProducts'
        ));
        return $pdf->download('laporan-pendapatan.pdf');
    }
}

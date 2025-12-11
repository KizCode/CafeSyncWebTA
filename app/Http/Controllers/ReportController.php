<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        // Get transactions in date range
        $transactions = Transaction::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->where('status', 'lunas')->get();

        // Calculate revenue
        $totalRevenue = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();

        // Calculate total items sold
        $totalItemsSold = TransactionItem::whereIn('transaction_id', $transactions->pluck('id'))
            ->sum('quantity');

        // Get expenses in date range
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate gross profit
        $grossProfit = $totalRevenue - $totalExpenses;

        // Get daily revenue for chart
        $dailyRevenue = Transaction::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])
        ->where('status', 'lunas')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Get top selling products
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereIn('transaction_id', $transactions->pluck('id'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->with('product')
            ->get();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalExpenses',
            'grossProfit',
            'totalTransactions',
            'totalItemsSold',
            'dailyRevenue',
            'topProducts'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('reports.index', $data);
    }

    public function preview(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('reports.preview', [
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
        ]);
    }

    public function streamPdf(Request $request)
    {
        return $this->makePdf($request)->stream('laporan-pendapatan.pdf');
    }

    public function downloadPdf(Request $request)
    {
        return $this->makePdf($request)->download('laporan-pendapatan.pdf');
    }

    private function makePdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('a4', 'portrait');
        $pdf->loadView('reports.pdf', $data);

        return $pdf;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildReportData(Request $request): array
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $timezone = config('app.timezone');
        $rangeStart = Carbon::parse($startDate, $timezone)->startOfDay();
        $rangeEnd = Carbon::parse($endDate, $timezone)->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$rangeStart, $rangeEnd])
            ->where('status', 'lunas')->get();

        $totalRevenue = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();

        $totalItemsSold = TransactionItem::whereIn('transaction_id', $transactions->pluck('id'))
            ->sum('quantity');

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $grossProfit = $totalRevenue - $totalExpenses;

        $dailyRevenue = Transaction::whereBetween('created_at', [$rangeStart, $rangeEnd])
            ->where('status', 'lunas')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereIn('transaction_id', $transactions->pluck('id'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->with(['product.category'])
            ->get();

        return compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalExpenses',
            'grossProfit',
            'totalTransactions',
            'totalItemsSold',
            'dailyRevenue',
            'topProducts'
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        // Get today's statistics
        $todayRevenue = Transaction::whereDate('created_at', today())
            ->where('status', 'lunas')
            ->sum('grand_total');

        $todayTransactions = Transaction::whereDate('created_at', today())
            ->where('status', 'lunas')
            ->count();

        // Get this week's revenue for chart
        $weeklyRevenue = Transaction::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->where('status', 'lunas')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('cashier.index', compact('categories', 'todayRevenue', 'todayTransactions', 'weeklyRevenue'));
    }

    public function getProducts()
    {
        $products = Product::with('category')->get();

        return response()->json($products);
    }
}

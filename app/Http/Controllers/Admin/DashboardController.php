<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'ingredients' => Ingredient::count(),
            'users' => User::count(),
            'today_revenue' => Transaction::whereDate('created_at', today())
                ->where('status', 'lunas')
                ->sum('grand_total'),
        ];

        $lowStockIngredients = Ingredient::query()
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('name')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'lowStockIngredients'));
    }
}

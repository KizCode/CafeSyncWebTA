<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $lowStockCount = $ingredients->filter->isLowStock()->count();
        $recentMovements = StockMovement::with(['ingredient', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('warehouse.dashboard', compact('ingredients', 'lowStockCount', 'recentMovements'));
    }
}

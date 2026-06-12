<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->get();

        return view('warehouse.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('warehouse.ingredients.form', ['ingredient' => new Ingredient()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'stock' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Ingredient::create([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'stock' => $validated['stock'] ?? 0,
            'min_stock' => $validated['min_stock'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('warehouse.ingredients.index')
            ->with('success', __('messages.ingredient_created'));
    }

    public function edit(Ingredient $ingredient)
    {
        return view('warehouse.ingredients.form', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'min_stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $ingredient->update([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'min_stock' => $validated['min_stock'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('warehouse.ingredients.index')
            ->with('success', __('messages.ingredient_updated'));
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()
            ->route('warehouse.ingredients.index')
            ->with('success', __('messages.ingredient_deleted'));
    }

    public function stockIn(Request $request, Ingredient $ingredient, StockService $stockService)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string|max:255',
        ]);

        $stockService->stockIn(
            $ingredient,
            (float) $validated['quantity'],
            $request->user()?->id,
            $validated['notes'] ?? null
        );

        return back()->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function adjust(Request $request, Ingredient $ingredient, StockService $stockService)
    {
        $validated = $request->validate([
            'stock' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $stockService->adjustStock(
            $ingredient,
            (float) $validated['stock'],
            $request->user()?->id,
            $validated['notes'] ?? null
        );

        return back()->with('success', __('messages.stock_adjusted'));
    }

    public function movements(Ingredient $ingredient)
    {
        $movements = StockMovement::with('user')
            ->where('ingredient_id', $ingredient->id)
            ->latest()
            ->paginate(20);

        return view('warehouse.ingredients.movements', compact('ingredient', 'movements'));
    }
}

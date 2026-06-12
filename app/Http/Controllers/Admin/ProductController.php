<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ManagesProductRecipes;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ManagesProductRecipes;
    public function index(StockService $stockService)
    {
        $products = Product::with(['category', 'ingredients'])->orderBy('name')->get();

        foreach ($products as $product) {
            $stockService->syncProductStock($product);
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
            'ingredients' => $ingredients,
            'recipe' => [],
        ]);
    }

    public function store(Request $request, StockService $stockService)
    {
        $validated = $this->validateProduct($request);

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
            'stock' => 0,
        ]);

        $this->syncRecipe($product, $validated['recipe'] ?? []);
        $stockService->syncProductStock($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_created'));
    }

    public function edit(Product $product)
    {
        $product->load('ingredients');
        $categories = Category::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();

        $recipe = $this->recipeFromProduct($product);

        return view('admin.products.form', compact('product', 'categories', 'ingredients', 'recipe'));
    }

    public function update(Request $request, Product $product, StockService $stockService)
    {
        $validated = $this->validateProduct($request);

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->syncRecipe($product, $validated['recipe'] ?? []);
        $stockService->syncProductStock($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_updated'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_deleted'));
    }
}

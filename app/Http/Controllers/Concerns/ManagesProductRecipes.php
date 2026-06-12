<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use Illuminate\Http\Request;

trait ManagesProductRecipes
{
    protected function productRules(bool $requireRecipe = false): array
    {
        $recipeRules = $requireRecipe
            ? ['required', 'array', 'min:1']
            : ['nullable', 'array'];

        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'recipe' => $recipeRules,
            'recipe.*.ingredient_id' => 'required|exists:ingredients,id',
            'recipe.*.quantity' => 'required|numeric|min:0.001',
        ];
    }

    protected function validateProduct(Request $request, bool $requireRecipe = false): array
    {
        return $request->validate($this->productRules($requireRecipe));
    }

    protected function syncRecipe(Product $product, array $recipe): void
    {
        $syncData = [];

        foreach ($recipe as $row) {
            if (empty($row['ingredient_id']) || empty($row['quantity'])) {
                continue;
            }

            $syncData[(int) $row['ingredient_id']] = [
                'quantity' => (float) $row['quantity'],
            ];
        }

        $product->ingredients()->sync($syncData);
    }

    protected function recipeFromProduct(Product $product): array
    {
        return $product->ingredients->map(fn ($ingredient) => [
            'ingredient_id' => $ingredient->id,
            'quantity' => $ingredient->pivot->quantity,
        ])->values()->all();
    }
}

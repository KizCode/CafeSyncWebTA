<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\StockMovement;
use InvalidArgumentException;

class StockService
{
    public function getAvailableStock(Product $product): int
    {
        $product->loadMissing('ingredients');

        if ($product->ingredients->isEmpty()) {
            return max(0, (int) $product->stock);
        }

        $maxUnits = PHP_INT_MAX;

        foreach ($product->ingredients as $ingredient) {
            $needed = (float) $ingredient->pivot->quantity;

            if ($needed <= 0) {
                continue;
            }

            $units = (int) floor((float) $ingredient->stock / $needed);
            $maxUnits = min($maxUnits, $units);
        }

        return $maxUnits === PHP_INT_MAX ? 0 : max(0, $maxUnits);
    }

    public function syncProductStock(Product $product): void
    {
        $product->update(['stock' => $this->getAvailableStock($product)]);
    }

    public function syncProductsForIngredient(Ingredient $ingredient): void
    {
        $ingredient->loadMissing('products');

        foreach ($ingredient->products as $product) {
            $this->syncProductStock($product);
        }
    }

    public function validateSale(array $items): void
    {
        foreach ($items as $item) {
            $productId = $item['product_id'] ?? $item['id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 0);

            if (! $productId || $quantity < 1) {
                throw new InvalidArgumentException('Item keranjang tidak valid.');
            }

            $product = Product::with('ingredients')->find($productId);

            if (! $product) {
                throw new InvalidArgumentException('Produk tidak ditemukan.');
            }

            $available = $this->getAvailableStock($product);

            if ($available < $quantity) {
                throw new InvalidArgumentException(
                    "Stok \"{$product->name}\" tidak mencukupi (tersedia: {$available})."
                );
            }
        }
    }

    public function deductForSale(array $items, ?int $userId = null, ?int $transactionId = null): void
    {
        foreach ($items as $item) {
            $productId = $item['product_id'] ?? $item['id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 0);
            $product = Product::with('ingredients')->find($productId);

            if (! $product || $quantity < 1) {
                continue;
            }

            if ($product->ingredients->isEmpty()) {
                $product->decrement('stock', $quantity);
                continue;
            }

            foreach ($product->ingredients as $ingredient) {
                $deduct = (float) $ingredient->pivot->quantity * $quantity;
                $ingredient->decrement('stock', $deduct);

                StockMovement::create([
                    'ingredient_id' => $ingredient->id,
                    'type' => 'out',
                    'quantity' => $deduct,
                    'reference' => $transactionId ? "transaksi:#{$transactionId}" : null,
                    'notes' => "Penjualan {$product->name}",
                    'user_id' => $userId,
                ]);
            }

            $this->syncProductStock($product);
        }
    }

    public function stockIn(Ingredient $ingredient, float $quantity, ?int $userId = null, ?string $notes = null): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Jumlah stok masuk harus lebih dari 0.');
        }

        $ingredient->increment('stock', $quantity);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'in',
            'quantity' => $quantity,
            'notes' => $notes ?: 'Stok masuk gudang',
            'user_id' => $userId,
        ]);

        $this->syncProductsForIngredient($ingredient);
    }

    public function adjustStock(Ingredient $ingredient, float $newStock, ?int $userId = null, ?string $notes = null): void
    {
        if ($newStock < 0) {
            throw new InvalidArgumentException('Stok tidak boleh negatif.');
        }

        $difference = $newStock - (float) $ingredient->stock;

        if ($difference == 0) {
            return;
        }

        $ingredient->update(['stock' => $newStock]);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'adjustment',
            'quantity' => abs($difference),
            'notes' => $notes ?: 'Penyesuaian stok gudang',
            'user_id' => $userId,
        ]);

        $this->syncProductsForIngredient($ingredient);
    }
}

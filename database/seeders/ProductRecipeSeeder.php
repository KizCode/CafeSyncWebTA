<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class ProductRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = Ingredient::pluck('id', 'name');
        $stockService = app(StockService::class);

        $recipes = [
            'Kopi Susu' => [
                'Biji Kopi' => 18,
                'Susu UHT' => 150,
                'Gula Pasir' => 10,
            ],
            'Es Teh Manis' => [
                'Teh Celup' => 1,
                'Gula Pasir' => 15,
                'Air Mineral' => 250,
            ],
            'Nasi Goreng' => [
                'Beras' => 200,
                'Bumbu Nasi Goreng' => 30,
                'Telur' => 1,
            ],
            'Mie Goreng' => [
                'Mie Kering' => 120,
                'Bumbu Nasi Goreng' => 25,
                'Telur' => 1,
            ],
            'Nasi Ayam Geprek' => [
                'Beras' => 200,
                'Ayam Fillet' => 120,
                'Bumbu Nasi Goreng' => 20,
            ],
            'Nasi Rendang' => [
                'Beras' => 200,
                'Daging Rendang' => 100,
            ],
            'Es Jeruk' => [
                'Jeruk' => 2,
                'Gula Pasir' => 20,
                'Air Mineral' => 200,
            ],
            'Jus Alpukat' => [
                'Alpukat' => 1,
                'Susu UHT' => 100,
                'Gula Pasir' => 15,
            ],
            'Air Mineral' => [
                'Air Mineral' => 600,
            ],
            'Kentang Goreng' => [
                'Kentang' => 150,
            ],
            'Pisang Goreng' => [
                'Pisang' => 2,
            ],
            'Tahu Isi' => [
                'Tahu' => 2,
            ],
            'Es Krim Vanilla' => [
                'Es Krim Base' => 80,
            ],
            'Pudding Coklat' => [
                'Coklat Bubuk' => 25,
                'Susu UHT' => 120,
            ],
            'Cake Slice' => [
                'Roti Cake' => 1,
            ],
        ];

        foreach ($recipes as $productName => $items) {
            $product = Product::where('name', $productName)->first();

            if (! $product) {
                continue;
            }

            $sync = [];

            foreach ($items as $ingredientName => $quantity) {
                $ingredientId = $ingredients[$ingredientName] ?? null;

                if ($ingredientId) {
                    $sync[$ingredientId] = ['quantity' => $quantity];
                }
            }

            $product->ingredients()->sync($sync);
            $stockService->syncProductStock($product);
        }
    }
}

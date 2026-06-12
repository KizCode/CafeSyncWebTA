<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Biji Kopi', 'unit' => 'gram', 'stock' => 5000, 'min_stock' => 500],
            ['name' => 'Susu UHT', 'unit' => 'ml', 'stock' => 20000, 'min_stock' => 2000],
            ['name' => 'Gula Pasir', 'unit' => 'gram', 'stock' => 8000, 'min_stock' => 800],
            ['name' => 'Teh Celup', 'unit' => 'pcs', 'stock' => 500, 'min_stock' => 50],
            ['name' => 'Beras', 'unit' => 'gram', 'stock' => 30000, 'min_stock' => 3000],
            ['name' => 'Bumbu Nasi Goreng', 'unit' => 'gram', 'stock' => 5000, 'min_stock' => 500],
            ['name' => 'Telur', 'unit' => 'pcs', 'stock' => 200, 'min_stock' => 20],
            ['name' => 'Mie Kering', 'unit' => 'gram', 'stock' => 10000, 'min_stock' => 1000],
            ['name' => 'Ayam Fillet', 'unit' => 'gram', 'stock' => 8000, 'min_stock' => 800],
            ['name' => 'Daging Rendang', 'unit' => 'gram', 'stock' => 6000, 'min_stock' => 600],
            ['name' => 'Kentang', 'unit' => 'gram', 'stock' => 10000, 'min_stock' => 1000],
            ['name' => 'Pisang', 'unit' => 'pcs', 'stock' => 150, 'min_stock' => 15],
            ['name' => 'Tahu', 'unit' => 'pcs', 'stock' => 120, 'min_stock' => 12],
            ['name' => 'Air Mineral', 'unit' => 'ml', 'stock' => 50000, 'min_stock' => 5000],
            ['name' => 'Jeruk', 'unit' => 'pcs', 'stock' => 100, 'min_stock' => 10],
            ['name' => 'Alpukat', 'unit' => 'pcs', 'stock' => 80, 'min_stock' => 8],
            ['name' => 'Es Krim Base', 'unit' => 'gram', 'stock' => 5000, 'min_stock' => 500],
            ['name' => 'Coklat Bubuk', 'unit' => 'gram', 'stock' => 3000, 'min_stock' => 300],
            ['name' => 'Roti Cake', 'unit' => 'pcs', 'stock' => 40, 'min_stock' => 4],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::firstOrCreate(
                ['name' => $ingredient['name']],
                $ingredient
            );
        }
    }
}

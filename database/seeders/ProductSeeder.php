<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Makanan (category_id: 1)
            ['name' => 'Nasi Goreng', 'price' => 25000, 'category_id' => 1, 'stock' => 0],
            ['name' => 'Mie Goreng', 'price' => 20000, 'category_id' => 1, 'stock' => 0],
            ['name' => 'Nasi Ayam Geprek', 'price' => 30000, 'category_id' => 1, 'stock' => 0],
            ['name' => 'Nasi Rendang', 'price' => 35000, 'category_id' => 1, 'stock' => 0],
            ['name' => 'Soto Ayam', 'price' => 22000, 'category_id' => 1, 'stock' => 0],

            // Minuman (category_id: 2)
            ['name' => 'Es Teh Manis', 'price' => 5000, 'category_id' => 2, 'stock' => 0],
            ['name' => 'Es Jeruk', 'price' => 8000, 'category_id' => 2, 'stock' => 0],
            ['name' => 'Kopi Susu', 'price' => 15000, 'category_id' => 2, 'stock' => 0],
            ['name' => 'Jus Alpukat', 'price' => 18000, 'category_id' => 2, 'stock' => 0],
            ['name' => 'Air Mineral', 'price' => 3000, 'category_id' => 2, 'stock' => 0],

            // Snack (category_id: 3)
            ['name' => 'Kentang Goreng', 'price' => 15000, 'category_id' => 3, 'stock' => 0],
            ['name' => 'Pisang Goreng', 'price' => 10000, 'category_id' => 3, 'stock' => 0],
            ['name' => 'Tahu Isi', 'price' => 12000, 'category_id' => 3, 'stock' => 0],
            ['name' => 'Risol Mayo', 'price' => 8000, 'category_id' => 3, 'stock' => 0],

            // Dessert (category_id: 4)
            ['name' => 'Es Krim Vanilla', 'price' => 12000, 'category_id' => 4, 'stock' => 0],
            ['name' => 'Pudding Coklat', 'price' => 10000, 'category_id' => 4, 'stock' => 0],
            ['name' => 'Cake Slice', 'price' => 18000, 'category_id' => 4, 'stock' => 0],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

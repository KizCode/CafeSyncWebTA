<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Berbagai jenis makanan'],
            ['name' => 'Minuman', 'description' => 'Berbagai jenis minuman'],
            ['name' => 'Snack', 'description' => 'Camilan dan makanan ringan'],
            ['name' => 'Dessert', 'description' => 'Makanan penutup'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

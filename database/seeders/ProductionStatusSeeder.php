<?php

namespace Database\Seeders;

use App\Models\ProductionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductionStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Menunggu', 'slug' => 'menunggu', 'color' => '#6b7280', 'icon' => 'fa-hourglass-half', 'sort_order' => 1, 'is_terminal' => false],
            ['name' => 'Sedang Diproses', 'slug' => 'diproses', 'color' => '#3b82f6', 'icon' => 'fa-fire-burner', 'sort_order' => 2, 'is_terminal' => false],
            ['name' => 'Siap Diambil', 'slug' => 'siap', 'color' => '#10b981', 'icon' => 'fa-bell', 'sort_order' => 3, 'is_terminal' => false],
            ['name' => 'Selesai', 'slug' => 'selesai', 'color' => '#5c4a32', 'icon' => 'fa-check-circle', 'sort_order' => 4, 'is_terminal' => true],
            ['name' => 'Dibatalkan', 'slug' => 'dibatalkan', 'color' => '#ef4444', 'icon' => 'fa-times-circle', 'sort_order' => 5, 'is_terminal' => true],
        ];

        foreach ($statuses as $status) {
            ProductionStatus::updateOrCreate(
                ['slug' => $status['slug']],
                array_merge($status, ['is_active' => true])
            );
        }
    }
}

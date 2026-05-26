<?php

namespace Database\Seeders;

use App\Models\AccessControl;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'cashier',
            'transactions',
            'reports',
            'profile',
        ];

        $defaults = [
            'Administrator' => $pages,
            'Kasir' => ['cashier', 'transactions', 'profile'],
            'Gudang' => ['reports', 'profile'],
            'CEO' => ['reports', 'profile'],
        ];

        foreach (Role::all() as $role) {
            foreach ($pages as $page) {
                AccessControl::updateOrCreate(
                    ['role_id' => $role->id, 'page' => $page],
                    ['allowed' => in_array($page, $defaults[$role->name] ?? [], true)]
                );
            }
        }
    }
}

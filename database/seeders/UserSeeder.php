<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::pluck('id', 'name');

        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@cafesync.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role_id' => $roles['Administrator'] ?? null,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'username' => 'kasir01',
            'email' => 'budi@cafesync.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role_id' => $roles['Kasir'] ?? null,
        ]);

        User::create([
            'name' => 'Siti Wulandari',
            'username' => 'gudang01',
            'email' => 'siti@cafesync.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role_id' => $roles['Gudang'] ?? null,
        ]);

        User::create([
            'name' => 'CEO Cafesync',
            'username' => 'ceo',
            'email' => 'ceo@cafesync.com',
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'role_id' => $roles['CEO'] ?? null,
        ]);
    }
}

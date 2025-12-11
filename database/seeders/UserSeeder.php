<?php

namespace Database\Seeders;

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
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@cafesync.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Create kasir users
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'kasir01',
            'email' => 'budi@cafesync.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
        ]);
    }
}

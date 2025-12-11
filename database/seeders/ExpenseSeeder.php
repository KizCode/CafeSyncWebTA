<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            [
                'description' => 'Belanja Bahan Baku',
                'amount' => 500000,
                'expense_date' => now()->subDays(5),
                'category' => 'Operasional',
            ],
            [
                'description' => 'Listrik Bulanan',
                'amount' => 300000,
                'expense_date' => now()->subDays(3),
                'category' => 'Utilitas',
            ],
            [
                'description' => 'Gaji Karyawan',
                'amount' => 2000000,
                'expense_date' => now()->subDays(1),
                'category' => 'SDM',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Transaction 1 - Tunai dengan diskon
        $transaction1 = Transaction::create([
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-0001',
            'subtotal' => 75000,
            'discount_type' => 'percent',
            'discount_value' => 10,
            'discount_amount' => 7500,
            'is_tax_enabled' => true,
            'tax_amount' => 7425, // (75000 - 7500) * 0.11
            'grand_total' => 74925,
            'payment_method' => 'tunai',
            'paid_amount' => 100000,
            'change_amount' => 25075,
            'status' => 'lunas',
            'created_at' => now()->subHours(3),
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction1->id,
            'product_id' => 1, // Nasi Goreng
            'quantity' => 2,
            'unit_price' => 25000,
            'total_price' => 50000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction1->id,
            'product_id' => 6, // Es Teh Manis
            'quantity' => 3,
            'unit_price' => 5000,
            'total_price' => 15000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction1->id,
            'product_id' => 8, // Kopi Susu
            'quantity' => 2,
            'unit_price' => 15000,
            'total_price' => 30000,
        ]);

        app(StockService::class)->deductForSale([
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 6, 'quantity' => 3],
            ['product_id' => 8, 'quantity' => 2],
        ], null, $transaction1->id);

        // Transaction 2 - QRIS tanpa diskon/pajak
        $transaction2 = Transaction::create([
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-0002',
            'subtotal' => 45000,
            'discount_amount' => 0,
            'is_tax_enabled' => false,
            'tax_amount' => 0,
            'grand_total' => 45000,
            'payment_method' => 'qris',
            'paid_amount' => 45000,
            'change_amount' => 0,
            'status' => 'lunas',
            'created_at' => now()->subHours(2),
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction2->id,
            'product_id' => 3, // Nasi Ayam Geprek
            'quantity' => 1,
            'unit_price' => 30000,
            'total_price' => 30000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction2->id,
            'product_id' => 11, // Kentang Goreng
            'quantity' => 1,
            'unit_price' => 15000,
            'total_price' => 15000,
        ]);

        app(StockService::class)->deductForSale([
            ['product_id' => 3, 'quantity' => 1],
            ['product_id' => 11, 'quantity' => 1],
        ], null, $transaction2->id);

        // Transaction 3 - Debit dengan pajak
        $transaction3 = Transaction::create([
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-0003',
            'subtotal' => 100000,
            'discount_type' => 'nominal',
            'discount_value' => 15000,
            'discount_amount' => 15000,
            'is_tax_enabled' => true,
            'tax_amount' => 9350, // (100000 - 15000) * 0.11
            'grand_total' => 94350,
            'payment_method' => 'debit',
            'paid_amount' => 94350,
            'change_amount' => 0,
            'status' => 'lunas',
            'created_at' => now()->subHour(),
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => 4, // Nasi Rendang
            'quantity' => 2,
            'unit_price' => 35000,
            'total_price' => 70000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => 9, // Jus Alpukat
            'quantity' => 1,
            'unit_price' => 18000,
            'total_price' => 18000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => 12, // Pisang Goreng
            'quantity' => 1,
            'unit_price' => 10000,
            'total_price' => 10000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => 15, // Es Krim Vanilla
            'quantity' => 1,
            'unit_price' => 12000,
            'total_price' => 12000,
        ]);

        app(StockService::class)->deductForSale([
            ['product_id' => 4, 'quantity' => 2],
            ['product_id' => 9, 'quantity' => 1],
            ['product_id' => 12, 'quantity' => 1],
            ['product_id' => 15, 'quantity' => 1],
        ], null, $transaction3->id);
    }
}

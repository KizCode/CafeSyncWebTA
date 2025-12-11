<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('discount_type')->nullable(); // 'percent' or 'nominal'
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->boolean('is_tax_enabled')->default(false);
            $table->decimal('grand_total', 12, 2);
            $table->string('payment_method'); // 'tunai', 'qris', 'debit'
            $table->decimal('paid_amount', 12, 2);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->enum('status', ['lunas', 'belum_lunas'])->default('lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

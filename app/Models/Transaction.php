<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'subtotal',
        'discount_amount',
        'discount_type',
        'discount_value',
        'tax_amount',
        'is_tax_enabled',
        'grand_total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'is_tax_enabled' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTransaction ? intval(substr($lastTransaction->invoice_number, -4)) + 1 : 1;

        return 'INV-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

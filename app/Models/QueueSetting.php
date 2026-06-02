<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'auto_enqueue_on_payment',
        'show_queue_on_receipt',
        'reset_queue_daily',
        'estimated_minutes',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'auto_enqueue_on_payment' => 'boolean',
        'show_queue_on_receipt' => 'boolean',
        'reset_queue_daily' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'is_enabled' => true,
            'auto_enqueue_on_payment' => true,
            'show_queue_on_receipt' => true,
            'reset_queue_daily' => true,
            'estimated_minutes' => 15,
        ]);
    }
}

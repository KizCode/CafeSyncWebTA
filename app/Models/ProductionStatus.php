<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'sort_order',
        'is_active',
        'is_terminal',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_terminal' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public static function defaultForQueue(): ?self
    {
        return static::query()
            ->where('is_active', true)
            ->where('is_terminal', false)
            ->orderBy('sort_order')
            ->first();
    }
}

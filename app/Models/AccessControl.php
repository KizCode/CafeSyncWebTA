<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'page',
        'allowed',
    ];

    protected $casts = [
        'allowed' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

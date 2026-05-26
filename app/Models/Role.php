<?php

namespace App\Models;

use App\Models\AccessControl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function accessControls()
    {
        return $this->hasMany(AccessControl::class);
    }
}

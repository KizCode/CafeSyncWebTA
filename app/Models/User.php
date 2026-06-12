<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role?->name === $roleName;
    }

    public function isAdministrator(): bool
    {
        return $this->hasRole('Administrator');
    }

    public function isKasir(): bool
    {
        return $this->hasRole('Kasir');
    }

    public function isGudang(): bool
    {
        return $this->hasRole('Gudang');
    }

    public function homeRoute(): string
    {
        return match ($this->role?->name) {
            'Kasir' => 'cashier.index',
            'Gudang' => 'warehouse.index',
            'Administrator' => 'admin.dashboard',
            default => 'cashier.index',
        };
    }

    public function translatedRoleName(): ?string
    {
        return match ($this->role?->name) {
            'Kasir' => __('ui.role_kasir'),
            'Gudang' => __('ui.role_gudang'),
            'Administrator' => __('ui.role_administrator'),
            default => $this->role?->name,
        };
    }
}

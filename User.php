<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
             'role' => Role::class,
        ];
    }

    
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    // public function isEmployee(): bool
    // {
    //     return $this->role === 'employee';
    // }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
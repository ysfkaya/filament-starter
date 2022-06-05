<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function canImpersonate()
    {
        return $this->isSuper();
    }

    public static function superRoles(): array
    {
        return [
            self::developerRole(),
            self::ownerRole(),
        ];
    }

    public static function developerRole(): string|null
    {
        return config('filament-shield.super_admin.role_name');
    }

    public static function ownerRole(): string|null
    {
        return config('filament-shield.filament_user.role_name');
    }

    public function scopeWithoutSupers($builder)
    {
        return $builder->whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', self::superRoles());
        });
    }

    public function isSuper()
    {
        return $this->hasAnyRole(self::superRoles());
    }
}

<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
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

    public static function superRoles(): array
    {
        return [
            self::developerRole(),
            self::ownerRole(),
        ];
    }

    public static function developerRole(): string|null
    {
        return config('filament-shield.super_admin.name');
    }

    public static function ownerRole(): string|null
    {
        return config('filament-shield.filament_user.name');
    }

    public function scopeWithoutSupers($builder)
    {
        return $builder->whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', self::superRoles());
        });
    }

    public static function notification(Notification $notification)
    {
        return self::each(fn (Admin $admin) => $admin->notify($notification), 5);
    }

    public function isSuper(): bool
    {
        return $this->hasAnyRole(self::superRoles());
    }

    public function isDeveloper(): bool
    {
        return $this->hasRole(self::developerRole());
    }

    public function isOwner(): bool
    {
        return $this->hasRole(self::ownerRole());
    }
}

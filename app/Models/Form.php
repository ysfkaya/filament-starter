<?php

namespace App\Models;

use App\Enums\FormType;
use App\Traits\HasMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory, HasMeta;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => FormType::class,
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->ip = request()->ip();
        });
    }
}

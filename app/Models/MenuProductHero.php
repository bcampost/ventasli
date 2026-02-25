<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProductHero extends Model
{
    protected $fillable = [
        'key',
        'title',
        'description',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
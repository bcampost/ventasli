<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'icon',
        'icon_path',
        'sort_order',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',

    ];
}
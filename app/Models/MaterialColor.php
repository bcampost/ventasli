<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialColor extends Model
{
    protected $fillable = [
        'type',
        'name',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(MenuProduct::class, 'menu_product_material_color')
            ->withTimestamps();
    }
}
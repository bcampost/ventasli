<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProductDetail extends Model
{
    protected $fillable = [
        'menu_product_id',
        'title',
        'description',
        'images',
        'largo',
        'ancho',
        'alto',
        'acero_colors',
        'melamina_colors',
    ];

    protected $casts = [
        'images' => 'array',
        'acero_colors' => 'array',
        'melamina_colors' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(MenuProduct::class, 'menu_product_id');
    }
}
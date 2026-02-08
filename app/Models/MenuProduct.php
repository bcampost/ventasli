<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProduct extends Model
{
    protected $table = 'menu_products';

    protected $fillable = [
        'menu_key',
        'title',
        'description',
        'image_path',
        'url',
        'sort',
    ];
}
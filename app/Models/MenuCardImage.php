<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCardImage extends Model
{
    protected $table = 'menu_card_images';

    protected $fillable = [
        'key',
        'title',
        'description',
        'path',
    ];

    public $timestamps = true;
}
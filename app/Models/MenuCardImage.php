<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCardImage extends Model
{
    protected $fillable = [
        'key',
        'image_path',
    ];
}
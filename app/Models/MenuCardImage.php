<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCardImage extends Model
{
    protected $table = 'menu_card_images';

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'title',
        'description',
        'path',
    ];
}
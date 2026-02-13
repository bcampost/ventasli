<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $table = 'footer_links';

    protected $fillable = [
        'group', 'key', 'label',
        'menu_path', 'external_url',
        'sort', 'is_active'
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];
}
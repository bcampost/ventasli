<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngenieriaPdf extends Model
{
    protected $connection = 'ingenieria';
    protected $table = 'pdf_files';

    public $timestamps = false;

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
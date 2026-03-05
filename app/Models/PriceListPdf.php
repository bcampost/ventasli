<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceListPdf extends Model
{
    protected $fillable = [
        'label','slug','file_path','sort_order','is_active'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'categoria',
        'nombre',
        'vigencia',
        'estatus',
        'periodo',
        'actualizado_en',
        'actualizado_por',
        'responsable',
        'archivo_path',
    ];

    protected $casts = [
        'actualizado_en' => 'datetime',
    ];
}
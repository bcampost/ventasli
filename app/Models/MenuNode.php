<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MenuNode extends Model
{
    protected $table = 'menu_nodes';

    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort'      => 'integer',
        'parent_id' => 'integer',
    ];

    // Para el menú superior (y cualquier consulta "solo activos")
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', 1);
    }

    public function parent()
    {
        return $this->belongsTo(MenuNode::class, 'parent_id');
    }

    // Hijos directos
    public function children()
    {
        return $this->hasMany(MenuNode::class, 'parent_id')
            ->orderBy('sort')
            ->orderBy('label');
    }

    // Hijos recursivos (esto es CLAVE para que el editor sí muestre todo)
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
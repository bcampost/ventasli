<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuNode extends Model
{
    protected $table = 'menu_nodes';

    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'sort',
        'is_active',

        // si existen en tu tabla, no estorban:
        'slug',
        'key',
        'title',
        'description',
        'image_path',
        'view_mode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(MenuNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuNode::class, 'parent_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
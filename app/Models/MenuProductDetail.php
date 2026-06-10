<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProductDetail extends Model
{
    protected $fillable = [
        'menu_product_id',

        'title',
        'description',
        'length',
        'width',
        'height',

        // ✅ nombres reales en tu DB
        'acero_colors',
        'melamina_colors',

        // ✅ imágenes con tags
        'images',
    ];

    protected $casts = [
        'acero_colors' => 'array',
        'melamina_colors' => 'array',
        'images' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\MenuProduct::class, 'menu_product_id');
    }

    // ✅ Safe accessor: siempre regresa array normalizado
    public function getImagesSafeAttribute(): array
    {
        $arr = $this->images;

        if (is_string($arr)) {
            $arr = json_decode($arr, true) ?: [];
        }

        if (!is_array($arr)) return [];

        $out = [];
        foreach ($arr as $it) {
            if (!is_array($it)) continue;
            $p = trim((string)($it['path'] ?? ''));
            if ($p === '') continue;

            $out[] = [
                'path' => ltrim($p, '/'),
                'acero' => trim((string)($it['acero'] ?? '')),
                'melamina' => trim((string)($it['melamina'] ?? '')),
            ];
        }

        return array_values($out);
    }
}
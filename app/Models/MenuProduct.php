<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProduct extends Model
{
    protected $fillable = [
        'menu_key',
        'title',
        'description',
        'url',
        'sort',
        'is_active',
        'image_path',
        'gallery_images',
        'specs',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gallery_images' => 'array', // ✅ slider / thumbnails
        'specs' => 'array',          // ✅ medidas, colores, etc
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // ✅ Detalle extendido (tabla menu_product_details)
    public function detail()
    {
        return $this->hasOne(\App\Models\MenuProductDetail::class, 'menu_product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (opcional pero útil)
    |--------------------------------------------------------------------------
    */

    // Normaliza menu_key para comparaciones/consultas
    public function normalizedMenuKey(): string
    {
        return trim((string) $this->menu_key, '/');
    }

    // URL pública al producto (si estás usando la ruta /producto/{menu_product})
    public function publicUrl(): string
    {
        return route('menu.product.show', ['menu_product' => $this->getKey()]);
    }

    // Imagen principal ya lista para <img src="">
    public function imageUrl(): ?string
    {
        if (!$this->image_path) return null;
        return asset('storage/' . ltrim($this->image_path, '/'));
    }

    // Gallery como URLs (storage)
    public function galleryUrls(): array
    {
        $imgs = is_array($this->gallery_images) ? $this->gallery_images : [];
        $imgs = array_values(array_filter($imgs, fn($p) => trim((string)$p) !== ''));

        return array_map(fn($p) => asset('storage/' . ltrim($p, '/')), $imgs);
    }
}
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
        'tech_pdf_path',
        'manual_pdf_path',
        'ingenieria_code',
        // ✅ ahora puede ser:
        // 1) array de strings (legacy): ["path1","path2"]
        // 2) array de objetos (nuevo): [{path, steel, melamine}, ...]
        'gallery_images',

        // ✅ specs: { steel_colors:[], melamine_colors:[], ... }
        'specs',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gallery_images' => 'array',
        'specs' => 'array',
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
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function normalizedMenuKey(): string
    {
        return trim((string) $this->menu_key, '/');
    }

    public function publicUrl(): string
    {
        return route('menu.product.show', ['menu_product' => $this->getKey()]);
    }

    public function imageUrl(): ?string
    {
        if (!$this->image_path)
            return null;
        return asset('storage/' . ltrim($this->image_path, '/'));
    }

    /**
     * ✅ Devuelve la galería normalizada como objetos:
     * [
     *   ['path'=>'...', 'steel'=>'Negro', 'melamine'=>'Encino'],
     *   ...
     * ]
     * Compatible con:
     * - legacy: ["a.jpg","b.jpg"]
     * - nuevo:  [{path, steel, melamine}]
     */
    public function galleryNormalized(): array
    {
        $imgs = is_array($this->gallery_images) ? $this->gallery_images : [];

        $out = [];
        foreach ($imgs as $it) {
            // legacy string
            if (is_string($it)) {
                $p = trim($it);
                if ($p === '')
                    continue;

                $out[] = [
                    'path' => ltrim($p, '/'),
                    'steel' => '',
                    'melamine' => '',
                ];
                continue;
            }

            // nuevo objeto
            if (is_array($it)) {
                $p = trim((string) ($it['path'] ?? ''));
                if ($p === '')
                    continue;

                $out[] = [
                    'path' => ltrim($p, '/'),
                    'steel' => trim((string) ($it['steel'] ?? '')),
                    'melamine' => trim((string) ($it['melamine'] ?? '')),
                ];
                continue;
            }
        }

        return array_values($out);
    }

    public function materialColors()
    {
        return $this->belongsToMany(\App\Models\MaterialColor::class, 'menu_product_material_color')
            ->withTimestamps();
    }

    /**
     * ✅ URLs de galería (storage)
     * (si usas thumbs sin filtrar)
     */
    public function galleryUrls(): array
    {
        $norm = $this->galleryNormalized();
        return array_map(
            fn($it) => asset('storage/' . ltrim($it['path'], '/')),
            $norm
        );
    }
    public function ingenieriaDocs(): array
    {
        return app(\App\Services\IngenieriaService::class)
            ->getDocs($this->ingenieria_code);
    }

    public function ingenieriaFichaUrl(): ?string
    {
        $docs = $this->ingenieriaDocs();

        return !empty($docs['ficha'])
            ? app(\App\Services\IngenieriaService::class)->url($docs['ficha'])
            : null;
    }

    public function ingenieriaInstructivoUrl(): ?string
    {
        $docs = $this->ingenieriaDocs();

        return !empty($docs['instructivo'])
            ? app(\App\Services\IngenieriaService::class)->url($docs['instructivo'])
            : null;
    }

}
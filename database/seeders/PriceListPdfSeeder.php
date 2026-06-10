<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNode;
use Illuminate\Support\Str;

class PriceListPdfSeeder extends Seeder
{
    public function run(): void
    {
        $rootLabel = 'Lista de precios';
        $rootSlug  = Str::slug($rootLabel, '-');
        $rootKey   = $rootSlug; // ejemplo: lista-de-precios

        // 1) Localiza root (parent_id null o 0)
        $root = MenuNode::query()
            ->where('label', $rootLabel)
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->first();

        // Si no existe, lo creamos con slug + key obligatorios
        if (!$root) {
            $root = MenuNode::create([
                'label'     => $rootLabel,
                'slug'      => $rootSlug,
                'key'       => $rootKey,
                'parent_id' => null,
                'url'       => '#',
                'sort'      => 0,
                'is_active' => true,
            ]);
        } else {
            // Si existe, aseguramos slug y key si están vacíos
            $dirty = false;

            if (empty($root->slug)) {
                $root->slug = $rootSlug;
                $dirty = true;
            }
            if (empty($root->key)) {
                $root->key = $rootKey;
                $dirty = true;
            }

            if ($dirty) $root->save();
        }

        // 2) Hijos con PDF
        $this->upsertChildPdf($root, 'Mobiliario', 'pdfs/lista-precios-mobiliario.pdf', 0);
        $this->upsertChildPdf($root, 'Silleria', 'pdfs/lista-precios-silleria.pdf', 1);

        // Si tienes también Comercializacion:
        // $this->upsertChildPdf($root, 'Comercializacion', 'pdfs/lista-precios-venta-directa-2025.pdf', 2);
    }

    private function upsertChildPdf(MenuNode $root, string $label, string $url, int $sort): void
    {
        $childSlug = Str::slug($label, '-');
        $childKey  = rtrim((string)$root->key, '/') . '/' . $childSlug;

        $node = MenuNode::query()
            ->where('parent_id', $root->id)
            ->where('label', $label)
            ->first();

        if (!$node) {
            MenuNode::create([
                'label'     => $label,
                'slug'      => $childSlug,
                'key'       => $childKey,
                'parent_id' => $root->id,
                'url'       => $url,
                'sort'      => $sort,
                'is_active' => true,
            ]);
            return;
        }

        // Existe: actualiza url + completa slug/key si faltan
        $dirty = false;

        if ($node->url !== $url) {
            $node->url = $url;
            $dirty = true;
        }
        if (empty($node->slug)) {
            $node->slug = $childSlug;
            $dirty = true;
        }
        if (empty($node->key)) {
            $node->key = $childKey;
            $dirty = true;
        }

        // No forzamos sort si ya está configurado (solo si viene null)
        if ($node->sort === null) {
            $node->sort = $sort;
            $dirty = true;
        }

        if ($node->is_active === null) {
            $node->is_active = true;
            $dirty = true;
        }

        if ($dirty) $node->save();
    }
}
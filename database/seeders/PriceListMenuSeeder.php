<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNode;

class PriceListMenuSeeder extends Seeder
{
    /**
     * Asegura que:
     * Lista de precios > (Mobiliario, Silleria, Comercializacion)
     * apunten directo a PDFs en /public/pdfs
     */
    public function run(): void
    {
        // Root: "Lista de precios"
        $root = MenuNode::updateOrCreate(
            [
                'label' => 'Lista de precios',
                'parent_id' => null,
            ],
            [
                'url' => '#',
                'is_active' => true,
                'sort' => 0,
            ]
        );

        // Hijos
        $this->upsertChild($root->id, 'Mobiliario', '/pdfs/lista-precios-mobiliario.pdf', 0);
        $this->upsertChild($root->id, 'Silleria', '/pdfs/lista-precios-silleria.pdf', 1);

        // Usamos el PDF que mencionaste:
        $this->upsertChild($root->id, 'Comercializacion', '/pdfs/lista-precios-venta-directa-2025.pdf', 2);
    }

    private function upsertChild(int $parentId, string $label, string $url, int $sort = 0): void
    {
        MenuNode::updateOrCreate(
            [
                'label' => $label,
                'parent_id' => $parentId,
            ],
            [
                'url' => $url,
                'is_active' => true,
                'sort' => $sort,
            ]
        );
    }
}
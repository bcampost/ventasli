<?php

namespace App\Support;

use App\Models\MenuNode;

class MenuTree
{
    /**
     * Devuelve el árbol listo para el nav:
     * [
     *   ['label'=>'...', 'url'=>..., 'children'=>[...]],
     * ]
     */
    public static function build(): array
    {
        // Si no existe tabla o modelo aún, regresa vacío sin romper
        if (!class_exists(MenuNode::class)) {
            return [];
        }

        $rows = MenuNode::query()
            ->where('is_active', 1)
            ->orderBy('sort')
            ->orderBy('label')
            ->get();

        // index por id
        $byId = [];
        foreach ($rows as $r) {
            $byId[$r->id] = [
                'id'       => $r->id,
                'label'    => $r->label,
                'url'      => $r->url,
                'sort'     => (int) $r->sort,
                'parent_id'=> $r->parent_id,
                'children' => [],
            ];
        }

        // arma árbol
        $tree = [];
        foreach ($byId as $id => $node) {
            $pid = $node['parent_id'];

            if ($pid && isset($byId[$pid])) {
                $byId[$pid]['children'][] = &$byId[$id];
            } else {
                $tree[] = &$byId[$id];
            }
        }

        // orden recursivo por sort/label
        $sortFn = function (&$nodes) use (&$sortFn) {
            usort($nodes, function ($a, $b) {
                if ($a['sort'] === $b['sort']) return strcmp($a['label'], $b['label']);
                return $a['sort'] <=> $b['sort'];
            });
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) $sortFn($n['children']);
            }
        };
        $sortFn($tree);

        return $tree;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\MenuNode;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function show(Request $request, string $sectionSlug, ?string $path = null)
    {
        $sectionSlug = trim($sectionSlug, '/');
        $path = $path ? trim($path, '/') : null;

        // Root (sección)
        $section = $this->resolveSectionRoot($sectionSlug);

        // Resolver nodo actual por la cadena de slugs
        [$currentNode, $chain] = $this->resolveCurrentNode($section, $path);
        $currentNodeId = $currentNode?->id;

        // Key del nivel actual (para productos)
        $currentMenuKey = $this->buildMenuKey($sectionSlug, $path);

        // Hijos (submenús)
        $children = collect();
        if ($currentNodeId) {
            $children = MenuNode::query()
                ->active()
                ->where('parent_id', $currentNodeId)
                ->orderBy('sort')
                ->orderBy('label')
                ->get();
        }

        // Cards para hijos
        $cards = $children->map(function ($n) use ($sectionSlug, $path) {
            $nodeSlug = Str::slug($n->label, '-');
            $childPath = trim(($path ? $path.'/' : '').$nodeSlug, '/');

            return [
                'id' => $n->id,
                'title' => $n->label,
                'key' => $this->buildMenuKey($sectionSlug, $childPath),
                'href' => route('menu.section', ['sectionSlug' => $sectionSlug, 'path' => $childPath]),
                'hasChildren' => MenuNode::query()->active()->where('parent_id', $n->id)->exists(),
                'description' => '',
                'customTitle' => null,
            ];
        })->values();

        // Productos del nivel actual
        $products = MenuProduct::query()
            ->where('menu_key', $currentMenuKey)
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        // Targets para agregar producto
        if ($children->count()) {
            $productTargets = $children->map(function ($n) use ($sectionSlug, $path) {
                $nodeSlug = Str::slug($n->label, '-');
                $childPath = trim(($path ? $path.'/' : '').$nodeSlug, '/');

                return [
                    'label' => $n->label,
                    'menu_key' => $this->buildMenuKey($sectionSlug, $childPath),
                ];
            })->values()->all();
        } else {
            $productTargets = [[
                'label' => $currentNode?->label ?? $section['label'] ?? 'Nivel actual',
                'menu_key' => $currentMenuKey,
            ]];
        }

        // Si ya estás usando menu_card_images, aquí deberías pasar tu colección real.
        // Para no romper tu vista, lo dejamos como colección vacía.
        $images = collect();

        return view('menu.show', [
            'section' => $section,
            'current' => [
                'label' => $currentNode?->label,
            ],
            'cards' => $cards,
            'images' => $images,

            'currentNodeId' => $currentNodeId,
            'currentMenuKey' => $currentMenuKey,

            // productos
            'products' => $products,
            'productTargets' => $productTargets,

            // redirect helper
            'redirectTo' => url()->current(),
        ]);
    }

    private function resolveSectionRoot(string $sectionSlug): array
    {
        $roots = MenuNode::query()
            ->active()
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->get();

        $found = $roots->first(function ($n) use ($sectionSlug) {
            return Str::slug($n->label, '-') === $sectionSlug;
        });

        return [
            'id' => $found?->id,
            'label' => $found?->label ?? Str::headline(str_replace('-', ' ', $sectionSlug)),
        ];
    }

    private function resolveCurrentNode(array $section, ?string $path): array
    {
        $rootId = $section['id'] ?? null;
        if (!$rootId) return [null, []];

        $current = MenuNode::find($rootId);
        if (!$current) return [null, []];

        $chain = [$current];

        $parts = array_values(array_filter(explode('/', trim((string)$path, '/'))));
        foreach ($parts as $slug) {
            $next = MenuNode::query()
                ->active()
                ->where('parent_id', $current->id)
                ->get()
                ->first(function ($n) use ($slug) {
                    return Str::slug($n->label, '-') === $slug;
                });

            if (!$next) break;

            $current = $next;
            $chain[] = $current;
        }

        return [$current, $chain];
    }

    private function buildMenuKey(string $sectionSlug, ?string $path): string
    {
        return trim($sectionSlug . '/' . trim((string)$path, '/'), '/');
    }
}
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
        $section = $this->resolveSectionRoot($sectionSlug);

        // ✅ Ruta completa tipo: "listas-de-precios/mobiliario"
        $fullPath = trim($sectionSlug . '/' . trim((string)$path, '/'), '/');

        // Resolver el nodo actual por el path (usando children por label)
        [$currentNode, $currentChain] = $this->resolveCurrentNode($section, $path);

        $currentNodeId = $currentNode?->id; // para “Agregar submenú”

        // Hijos del nodo actual (subopciones)
        $children = collect();
        if ($currentNodeId) {
            $children = MenuNode::query()
                ->active()
                ->where('parent_id', $currentNodeId)
                ->orderBy('sort')
                ->orderBy('label')
                ->get();
        }

        // Cards (submenús)
        $cards = $children->map(function ($n) use ($sectionSlug, $path) {
            $nodeSlug = Str::slug($n->label, '-');
            $newPath = trim(($path ? trim($path, '/') . '/' : '') . $nodeSlug, '/');

            $href = route('menu.section', [
                'sectionSlug' => $sectionSlug,
                'path' => $newPath
            ]);

            return [
                'id' => $n->id,
                'title' => $n->label,
                'key' => $this->buildMenuKey($sectionSlug, $newPath),
                'href' => $href,
                'hasChildren' => MenuNode::where('parent_id', $n->id)->where('is_active', 1)->exists(),
                'description' => '',
                'customTitle' => null,
            ];
        })->values();

        // Productos del nivel actual
        $products = MenuProduct::query()
            ->where('menu_key', $this->buildMenuKey($sectionSlug, $path))
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        // Targets para “Agregar producto”
        $productTargets = [];
        if ($children->count()) {
            $productTargets = $children->map(function ($n) use ($sectionSlug, $path) {
                $nodeSlug = Str::slug($n->label, '-');
                $childPath = trim(($path ? trim($path, '/') . '/' : '') . $nodeSlug, '/');

                return [
                    'label' => $n->label,
                    'menu_key' => $this->buildMenuKey($sectionSlug, $childPath),
                ];
            })->values()->all();
        } else {
            $productTargets = [[
                'label' => ($currentNode?->label ?? $section['label'] ?? 'Nivel actual'),
                'menu_key' => $this->buildMenuKey($sectionSlug, $path),
            ]];
        }

        // Si ya traes imágenes desde menu_card_images, aquí debes cargarlo como lo tenías.
        // De momento lo dejamos como coleccion vacía para no romper.
        $images = collect();

        return view('menu.show', [
            'section' => $section,
            'current' => [
                'label' => $currentNode?->label,
            ],
            'cards' => $cards,
            'images' => $images,
            'currentNodeId' => $currentNodeId,

            // ✅ FIX: se manda a la vista
            'fullPath' => $fullPath,

            // productos
            'products' => $products,
            'productTargets' => $productTargets,

            // redirect útil
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
<?php

namespace App\Http\Controllers;

use App\Models\MenuNode;
use App\Models\MenuProduct;
use App\Models\MenuCardImage;
use App\Models\MenuProductHero;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class MenuController extends Controller
{

        public function product(MenuProduct $menu_product)
    {
        // ✅ defaults seguros
        $gallery = is_array($menu_product->gallery_images) ? $menu_product->gallery_images : (json_decode((string)$menu_product->gallery_images, true) ?: []);
        $specs   = is_array($menu_product->specs) ? $menu_product->specs : (json_decode((string)$menu_product->specs, true) ?: []);

        // Si no hay galería, usa image_path como primera imagen (si existe)
        if (empty($gallery) && !empty($menu_product->image_path)) {
            $gallery = [$menu_product->image_path];
        }

        // Defaults del “boceto”
        $specs = array_merge([
            'measures' => [
                'largo' => '',
                'ancho' => '',
                'alto'  => '',
            ],
            'colors_acero'    => [],
            'colors_melamina' => [],
            'notes'           => '',
        ], $specs);

        return view('menu.product', [
            'p'       => $menu_product,
            'gallery' => $gallery,
            'specs'   => $specs,
            'backTo'  => url()->previous(),
        ]);
    }



    public function show(Request $request, string $sectionSlug, ?string $path = null)
    {
        $section = $this->resolveSectionRoot($sectionSlug);

        // ✅ Ruta completa tipo: "productos/detalles-de-productos/escritorios"
        $fullPath = $this->buildMenuKey($sectionSlug, $path);

        // Resolver el nodo actual por el path (ususing children por label)
        [$currentNode, $currentChain] = $this->resolveCurrentNode($section, $path);

        $currentNodeId = $currentNode?->id; // para “Agregar submenú”

        // Hijos del nodo actual (subopciones)
        $children = collect();
        if ($currentNodeId) {
            $children = MenuNode::query()
                ->when(method_exists(MenuNode::class, 'scopeActive'), fn($q) => $q->active(), fn($q) => $q->where('is_active', 1))
                ->where('parent_id', $currentNodeId)
                ->orderBy('sort')
                ->orderBy('label')
                ->get();
        }

        // Cards (submenús)
        $cards = $children->map(function ($n) use ($sectionSlug, $path) {
            $nodeSlug = Str::slug($n->label, '-');
            $newPath  = trim(($path ? trim($path, '/') . '/' : '') . $nodeSlug, '/');

            $href = route('menu.section', [
                'sectionSlug' => $sectionSlug,
                'path'        => $newPath
            ]);

            return [
                'id'          => $n->id,
                'title'       => $n->label,
                'key'         => $this->buildMenuKey($sectionSlug, $newPath), // 👈 clave usada por menu_card_images
                'href'        => $href,
                'hasChildren' => MenuNode::query()
                    ->when(method_exists(MenuNode::class, 'scopeActive'), fn($q) => $q->active(), fn($q) => $q->where('is_active', 1))
                    ->where('parent_id', $n->id)
                    ->exists(),
                'description' => '',
                'customTitle' => null,
            ];
        })->values();

        // Productos del nivel actual (MATCH EXACTO del fullPath)
        $products = MenuProduct::query()
            ->where('menu_key', $fullPath)
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        // Targets para modal "Agregar producto"
        $productTargets = [];

        if ($children->count()) {
            $basePath = trim((string)$path, '/'); // ej: "detalles-de-productos/escritorios"

            $productTargets = $children->map(function ($n) use ($sectionSlug, $basePath) {
                $nodeSlug  = Str::slug($n->label, '-');
                $childPath = trim($basePath . '/' . $nodeSlug, '/'); // ej: "detalles-de-productos/escritorios/anzio"

                return [
                    'label'    => $n->label,
                    'menu_key' => trim($sectionSlug . '/' . $childPath, '/'),
                ];
            })->values()->all();
        } else {
            $productTargets = [[
                'label'    => ($currentNode?->label ?? $section['label'] ?? 'Nivel actual'),
                'menu_key' => $fullPath,
            ]];
        }

        // ✅ IMÁGENES (menu_card_images) para cards
        $cardKeys = $cards->pluck('key')->filter()->values();

        $images = MenuCardImage::query()
            ->whereIn('key', $cardKeys)
            ->get()
            ->keyBy('key');

        /**
         * ✅ DETECTAR NIVEL "DETALLE" (leaf) para mostrar HERO gigante + productos
         * - Debe ser leaf (sin children)
         * - Debe tener path con profundidad (ej: detalles-de-productos/escritorios/anzio => 2 slashes o más)
         * - Y normalmente vive bajo "detalles-de-productos"
         */
        $trimPath = trim((string)$path, '/');
        $depth = $trimPath === '' ? 0 : (substr_count($trimPath, '/') + 1);

        $isDetailLevel =
            $children->isEmpty()
            && $depth >= 3
            && Str::contains($fullPath, 'detalles-de-productos/');

        if ($isDetailLevel) {
            // ✅ HERO: crea/obtiene por key=fullPath
            $hero = MenuProductHero::query()->firstOrCreate(
                ['key' => $fullPath],
                ['title' => null, 'description' => null, 'images' => []]
            );

            return view('menu.detail', [
                'section'        => $section,
                'current'        => ['label' => $currentNode?->label],
                'cards'          => $cards,          // en detalle normalmente vendrá vacío, pero lo pasamos
                'images'         => $images,         // idem
                'currentNodeId'  => $currentNodeId,
                'fullPath'       => $fullPath,
                'products'       => $products,
                'productTargets' => $productTargets, // en detalle: solo nivel actual
                'redirectTo'     => url()->current(),
                'hero'           => $hero,
            ]);
        }

        // ✅ NIVEL NORMAL (como lo tienes hoy)
        return view('menu.show', [
            'section'        => $section,
            'current'        => ['label' => $currentNode?->label],
            'cards'          => $cards,
            'images'         => $images,
            'currentNodeId'  => $currentNodeId,
            'fullPath'       => $fullPath,
            'products'       => $products,
            'productTargets' => $productTargets,
            'redirectTo'     => url()->current(),
        ]);
    }

    private function resolveSectionRoot(string $sectionSlug): array
    {
        $roots = MenuNode::query()
            ->when(method_exists(MenuNode::class, 'scopeActive'), fn($q) => $q->active(), fn($q) => $q->where('is_active', 1))
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->get();

        $found = $roots->first(function ($n) use ($sectionSlug) {
            return Str::slug($n->label, '-') === $sectionSlug;
        });

        return [
            'id'    => $found?->id,
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
                ->when(method_exists(MenuNode::class, 'scopeActive'), fn($q) => $q->active(), fn($q) => $q->where('is_active', 1))
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
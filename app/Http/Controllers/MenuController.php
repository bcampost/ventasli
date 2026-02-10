<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuCardImage;
use App\Models\MenuProduct;

class MenuController extends Controller
{
    /**
     * Slug seguro (incluye acentos) -> "sillería" => "sillería" -> regex -> "sillería" => "sillería" -> "siller-a" ??? (por eso usamos \p{L})
     * Con \p{L} conserva letras unicode y luego reemplaza separadores por '-'.
     */
    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'menu';
    }

    /**
     * Key canónica por partes (labels) => "productos/escritorios/anzio"
     */
    private function buildKey(array $parts): string
    {
        $parts = array_map(fn ($p) => $this->slugify($p), $parts);
        return implode('/', array_filter($parts));
    }

    /**
     * MAIN: /menu/{sectionSlug}/{path?}
     * - Muestra cards de navegación para children del nodo actual (desde config/menu.php)
     * - Además, SIEMPRE muestra productos asociados al "menu_key" actual (en cualquier nivel)
     * - Permite que un producto "simule" ser una categoría nueva al aparecer como card adicional
     */
    public function show(Request $request, string $sectionSlug, ?string $path = null)
    {
        $menu = config('menu', []);
        abort_if(empty($menu), 404);

        // Sección raíz
        $section = collect($menu)->first(function ($item) use ($sectionSlug) {
            return $this->slugify($item['label']) === $this->slugify($sectionSlug);
        });
        abort_if(!$section, 404);

        // Resolver nodo actual por path
        $segments = [];
        $current = $section;

        if (!empty($path)) {
            $segments = array_values(array_filter(explode('/', trim($path, '/'))));

            foreach ($segments as $seg) {
                $child = $this->findChildBySlug($current, $seg);
                abort_if(!$child, 404);
                $current = $child;
            }
        }

        // =========================
        // Breadcrumbs
        // =========================
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'label' => $section['label'],
            'url'   => route('menu.section', $sectionSlug),
        ];

        $runningPath = '';
        foreach ($segments as $seg) {
            $runningPath = $runningPath ? ($runningPath . '/' . $seg) : $seg;

            $node = $section;
            foreach (explode('/', $runningPath) as $p) {
                $node = $this->findChildBySlug($node, $p);
                abort_if(!$node, 404);
            }

            $breadcrumbs[] = [
                'label' => $node['label'] ?? $seg,
                'url'   => route('menu.section', [$sectionSlug, $runningPath]),
            ];
        }

        // =========================
        // MENU KEY actual (donde estoy parado)
        // =========================
        $currentKeyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments));
        $currentMenuKey = $this->buildKey($currentKeyParts);

        // =========================
        // Children (config)
        // =========================
        $children = (isset($current['children']) && is_array($current['children']))
            ? $current['children']
            : [];

        // =========================
        // Productos (BD) de este nivel
        // =========================
        $products = class_exists(MenuProduct::class)
            ? MenuProduct::where('menu_key', $currentMenuKey)->orderBy('sort')->orderBy('id', 'desc')->get()
            : collect();

        // =========================
        // Keys para imágenes/metadatos de cards
        // Incluye:
        // - cards de children (config)
        // - cards de productos (si quieres que se vean como "categoría nueva")
        // =========================
        $keys = [];
        // sección raíz
        $keys[] = $this->buildKey([$section['label']]);

        // children del config en este nivel
        foreach ($children as $child) {
            $keyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments), [$child['label']]);
            $keys[] = $this->buildKey($keyParts);
        }

        // productos como "sub-categorías" virtuales (misma jerarquía que un child)
        foreach ($products as $p) {
            // se crea una key consistente: currentMenuKey + "/" + slug(product_name)
            $keys[] = trim($currentMenuKey . '/' . $this->slugify((string) $p->name), '/');
        }

        $images = class_exists(MenuCardImage::class)
            ? MenuCardImage::whereIn('key', array_unique($keys))->get()->keyBy('key')
            : collect();

        // =========================
        // Construcción de cards:
        // 1) Cards del config (children)
        // 2) Cards de productos (virtuales) => se comportan como nuevas "categorías"
        // =========================
        $cards = [];

        // 1) Cards de children del config
        foreach ($children as $child) {
            $hasChildren = isset($child['children']) && is_array($child['children']) && count($child['children']) > 0;

            $childSlug = $this->slugify($child['label']);
            $childPath = trim(implode('/', array_filter(array_merge($segments, [$childSlug]))), '/');

            // Navegación:
            // - Si tiene children => navegar a siguiente nivel
            // - Si no => url del menú o '#'
            $href = $hasChildren
                ? route('menu.section', [$sectionSlug, $childPath])
                : ($child['url'] ?? '#');

            // Key para imagen/metadatos
            $keyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments), [$child['label']]);
            $imgKey = $this->buildKey($keyParts);

            $imgRow = $images->get($imgKey);

            $imgPath = null;
            if ($imgRow && !empty($imgRow->path)) {
                $imgPath = asset('storage/' . ltrim($imgRow->path, '/'));
            }

            $title = $imgRow->title ?? $child['label'];
            $description = $imgRow->description ?? ($hasChildren
                ? "Explora opciones dentro de “{$child['label']}”."
                : "Accede al recurso de “{$child['label']}”."
            );

            $cards[] = [
                'type'        => 'menu',
                'title'       => $title,
                'label'       => $child['label'],
                'description' => $description,
                'image'       => $imgPath,
                'href'        => $href,
                'hasChildren' => $hasChildren,
                'key'         => $imgKey,
                'customTitle' => $imgRow->title ?? null,
            ];
        }

        // 2) Cards de productos como "sub-categoría" virtual
        //    Esto hace que, aunque el nodo NO sea final, puedas agregar productos y verlos como opción (card).
        //    Si el producto tiene url -> abre url, si no -> se queda en '#'.
        foreach ($products as $p) {
            $productSlug = $this->slugify((string) $p->name);

            // Key del producto como si fuera un child
            $productKey = trim($currentMenuKey . '/' . $productSlug, '/');

            $imgRow = $images->get($productKey);

            // Imagen: prioridad BD de producto; si no, usa la de MenuCardImage
            $imgPath = null;
            if (!empty($p->image_path)) {
                $imgPath = asset('storage/' . ltrim($p->image_path, '/'));
            } elseif ($imgRow && !empty($imgRow->path)) {
                $imgPath = asset('storage/' . ltrim($imgRow->path, '/'));
            }

            $title = $imgRow->title ?? $p->name;
            $description = $imgRow->description ?? ($p->description ?? 'Producto agregado.');

            $cards[] = [
                'type'        => 'product',
                'product_id'  => $p->id,
                'title'       => $title,
                'label'       => $p->name,
                'description' => $description,
                'image'       => $imgPath,
                'href'        => $p->url ?? '#',
                'hasChildren' => false,
                'key'         => $productKey,
                'customTitle' => $imgRow->title ?? null,
            ];
        }

        return view('menu.show', [
            'section'        => $section,
            'current'        => $current,
            'sectionSlug'    => $sectionSlug,
            'path'           => $path,
            'breadcrumbs'    => $breadcrumbs,
            'cards'          => $cards,
            'images'         => $images,
            'currentMenuKey' => $currentMenuKey,
            'products'       => $products,
        ]);
    }

    public function section(Request $request, string $sectionSlug)
    {
        return $this->show($request, $sectionSlug, null);
    }

    // =========================
    // Helpers
    // =========================
    private function findChildBySlug(array $parent, string $slug): ?array
    {
        if (!isset($parent['children']) || !is_array($parent['children'])) {
            return null;
        }

        foreach ($parent['children'] as $child) {
            if ($this->slugify($child['label']) === $this->slugify($slug)) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Devuelve labels reales de los segments (para construir keys por labels)
     */
    private function segmentsLabelsFromPath(array $section, array $segments): array
    {
        if (empty($segments)) return [];

        $labels = [];
        $node = $section;

        foreach ($segments as $seg) {
            $child = $this->findChildBySlug($node, $seg);
            if (!$child) break;

            $labels[] = $child['label'];
            $node = $child;
        }

        return $labels;
    }
}
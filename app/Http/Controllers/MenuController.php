<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuCardImage;
use App\Models\MenuProduct;

class MenuController extends Controller
{
    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'menu';
    }

    private function buildKey(array $parts): string
    {
        $parts = array_map(fn ($p) => $this->slugify($p), $parts);
        return implode('/', $parts);
    }

    public function show(Request $request, string $sectionSlug, ?string $path = null)
    {
        $menu = config('menu', []);
        abort_if(empty($menu), 404);

        $section = collect($menu)->first(function ($item) use ($sectionSlug) {
            return $this->slugify($item['label']) === $sectionSlug;
        });
        abort_if(!$section, 404);

        $segments = [];
        $current = $section;

        if (!empty($path)) {
            $segments = array_values(array_filter(explode('/', $path)));

            foreach ($segments as $seg) {
                $child = $this->findChildBySlug($current, $seg);
                abort_if(!$child, 404);
                $current = $child;
            }
        }

        // Breadcrumbs
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
            }

            $breadcrumbs[] = [
                'label' => $node['label'] ?? $seg,
                'url'   => route('menu.section', [$sectionSlug, $runningPath]),
            ];
        }

        $children = (isset($current['children']) && is_array($current['children']))
            ? $current['children']
            : [];

        // Imágenes cards por key
        $keys = [];
        $keys[] = $this->buildKey([$section['label']]);

        foreach ($children as $child) {
            $keyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments), [$child['label']]);
            $keys[] = $this->buildKey($keyParts);
        }

        $images = class_exists(MenuCardImage::class)
            ? MenuCardImage::whereIn('key', $keys)->get()->keyBy('key')
            : collect();

        $cards = [];
        foreach ($children as $child) {
            $hasChildren = isset($child['children']) && is_array($child['children']) && count($child['children']) > 0;

            $childSlug = $this->slugify($child['label']);
            $childPath = trim(implode('/', array_filter(array_merge($segments, [$childSlug]))), '/');

            $href = '#';
            if ($hasChildren) {
                $href = route('menu.section', [$sectionSlug, $childPath]);
            } else {
                $href = $child['url'] ?? '#';
            }

            $keyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments), [$child['label']]);
            $imgKey = $this->buildKey($keyParts);

            $imgRow = $images->get($imgKey);
            $imgPath = null;
            if ($imgRow && !empty($imgRow->path)) {
                $imgPath = asset('storage/' . ltrim($imgRow->path, '/'));
            }

            $cards[] = [
                'title'       => $child['label'],
                'description' => $imgRow->description ?? ($hasChildren ? "Explora opciones dentro de “{$child['label']}”." : "Accede al recurso de “{$child['label']}”."),
                'image'       => $imgPath,
                'href'        => $href,
                'hasChildren' => $hasChildren,
                'key'         => $imgKey,
                'customTitle' => $imgRow->title ?? null,
            ];
        }

        // ✅ MENU KEY ACTUAL (donde estoy parado) para productos
        $currentKeyParts = array_merge([$section['label']], $this->segmentsLabelsFromPath($section, $segments));
        $currentMenuKey = $this->buildKey($currentKeyParts);

        // ✅ Productos de esta pantalla
        $products = class_exists(MenuProduct::class)
            ? MenuProduct::where('menu_key', $currentMenuKey)->orderBy('sort')->orderBy('id', 'desc')->get()
            : collect();

        return view('menu.show', [
            'section'      => $section,
            'current'      => $current,
            'sectionSlug'  => $sectionSlug,
            'path'         => $path,
            'breadcrumbs'  => $breadcrumbs,
            'cards'        => $cards,
            'images'       => $images,
            'currentMenuKey' => $currentMenuKey,
            'products'     => $products,
        ]);
    }

    public function section(Request $request, string $sectionSlug)
    {
        return $this->show($request, $sectionSlug, null);
    }

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
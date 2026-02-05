<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MenuCardImage;

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

    public function section(Request $request, string $sectionSlug)
    {
        // Si aún no quieres usar config/menu.php, aquí puedes seguir usando el array en el blade.
        // Pero para que el controller funcione, necesitamos el menú aquí:
        $menu = config('menu', []);

        // Si config('menu') no existe todavía, caería en [] y daría 404.
        // Si prefieres, luego lo cambiamos a un helper o a DB.
        $section = collect($menu)->first(function ($item) use ($sectionSlug) {
            return $this->slugify($item['label']) === $sectionSlug;
        });

        abort_if(!$section, 404);

        $open = (string) $request->query('open', '');
        $open = $open ? $this->slugify($open) : '';

        // Imágenes (si ya implementaste MenuCardImage). Si aún no, igual funciona aunque esté vacío
        $keys = [];
        $keys[] = $this->buildKey([$section['label']]);

        foreach (($section['children'] ?? []) as $child) {
            $keys[] = $this->buildKey([$section['label'], $child['label']]);

            foreach (($child['children'] ?? []) as $leaf) {
                $keys[] = $this->buildKey([$section['label'], $child['label'], $leaf['label']]);
            }
        }

        $images = class_exists(MenuCardImage::class)
            ? MenuCardImage::whereIn('key', $keys)->get()->keyBy('key')
            : collect();

        $openedChild = null;
        if ($open) {
            foreach (($section['children'] ?? []) as $child) {
                if ($this->slugify($child['label']) === $open) {
                    $openedChild = $child;
                    break;
                }
            }
        }

        return view('menu.section', [
            'section' => $section,
            'sectionSlug' => $sectionSlug,
            'open' => $open,
            'openedChild' => $openedChild,
            'images' => $images,
        ]);
    }
}
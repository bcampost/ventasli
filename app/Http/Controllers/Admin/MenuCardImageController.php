<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuCardImage;
use Illuminate\Support\Facades\Storage;

class MenuCardImageController extends Controller
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

    /**
     * Recorre config/menu.php y genera keys para:
     * - Sección
     * - Sección/Child
     * - Sección/Child/Leaf
     * (y así sucesivamente, recursivo)
     */
    private function collectKeysFromMenu(array $menu): array
    {
        $keys = [];

        $walk = function (array $node, array $trail) use (&$walk, &$keys) {
            if (!isset($node['label'])) return;

            $trail[] = $node['label'];
            $keys[] = $this->buildKey($trail);

            if (!empty($node['children']) && is_array($node['children'])) {
                foreach ($node['children'] as $child) {
                    $walk($child, $trail);
                }
            }
        };

        foreach ($menu as $section) {
            $walk($section, []);
        }

        // unique + reindex
        $keys = array_values(array_unique($keys));

        return $keys;
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $items = MenuCardImage::query()
            ->when($q !== '', fn ($query) => $query->where('key', 'like', "%{$q}%"))
            ->orderBy('key')
            ->paginate(30)
            ->withQueryString();

        return view('admin.menu-cards.index', [
            'items' => $items,
            'q' => $q,
        ]);
    }

    /**
     * ✅ NUEVO: Generar/Sync registros desde config/menu.php
     */
    public function sync()
    {
        $menu = config('menu', []);
        if (empty($menu)) {
            return redirect()
                ->route('admin.menu-cards.index')
                ->with('status', 'No se encontró config/menu.php o está vacío.');
        }

        $keys = $this->collectKeysFromMenu($menu);

        $created = 0;
        foreach ($keys as $key) {
            $row = MenuCardImage::firstOrCreate(
                ['key' => $key],
                ['path' => null, 'title' => null, 'description' => null]
            );

            if ($row->wasRecentlyCreated) $created++;
        }

        return redirect()
            ->route('admin.menu-cards.index')
            ->with('status', "Sincronizado. Keys detectadas: " . count($keys) . " | Nuevos registros: {$created}");
    }

    public function edit(string $key)
    {
        $item = MenuCardImage::where('key', $key)->first();

        if (!$item) {
            $item = MenuCardImage::create([
                'key' => $key,
                'path' => null,
                'title' => null,
                'description' => null,
            ]);
        }

        return view('admin.menu-cards.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'in:1'],
        ]);

        $item = MenuCardImage::where('key', $key)->first();

        if (!$item) {
            $item = MenuCardImage::create([
                'key' => $key,
                'path' => null,
                'title' => null,
                'description' => null,
            ]);
        }

        if ($request->input('remove_image') === '1') {
            if (!empty($item->path)) {
                Storage::disk('public')->delete($item->path);
            }
            $item->path = null;
        }

        if ($request->hasFile('image')) {
            if (!empty($item->path)) {
                Storage::disk('public')->delete($item->path);
            }

            $path = $request->file('image')->store('menu_cards', 'public');
            $item->path = $path;
        }

        $item->title = $request->input('title');
        $item->description = $request->input('description');
        $item->save();

        return redirect()
            ->route('admin.menu-cards.edit', $key)
            ->with('status', 'Guardado');
    }

    public function destroy(string $key)
    {
        $item = MenuCardImage::where('key', $key)->first();

        if ($item) {
            if (!empty($item->path)) {
                Storage::disk('public')->delete($item->path);
            }
            $item->delete();
        }

        return redirect()
            ->route('admin.menu-cards.index')
            ->with('status', 'Eliminado');
    }
}
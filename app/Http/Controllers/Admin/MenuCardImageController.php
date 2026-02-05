<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCardImage;
use Illuminate\Http\Request;
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

    private function keyOf(array $parts): string
    {
        return implode('/', array_map(fn($p) => $this->slugify($p), $parts));
    }

    public function index(Request $request)
    {
        $menu = config('menu', []);
        $focus = (string) $request->query('focus', '');

        // Generamos el catálogo de keys que existen en el menú
        $keys = [];
        foreach ($menu as $section) {
            $keys[] = $this->keyOf([$section['label']]);

            foreach (($section['children'] ?? []) as $child) {
                $keys[] = $this->keyOf([$section['label'], $child['label']]);

                foreach (($child['children'] ?? []) as $leaf) {
                    $keys[] = $this->keyOf([$section['label'], $child['label'], $leaf['label']]);
                }
            }
        }

        $keys = array_values(array_unique($keys));

        $images = MenuCardImage::whereIn('key', $keys)->get()->keyBy('key');

        return view('admin.menu-cards.index', [
            'keys' => $keys,
            'images' => $images,
            'focus' => $focus,
        ]);
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        $row = MenuCardImage::firstOrCreate(['key' => $key]);

        if ($request->hasFile('image')) {
            // borrar anterior si existe
            if ($row->image_path && Storage::disk('public')->exists($row->image_path)) {
                Storage::disk('public')->delete($row->image_path);
            }

            $path = $request->file('image')->store('menu-cards', 'public');
            $row->image_path = $path;
            $row->save();
        }

        return back()->with('status', 'Imagen actualizada.');
    }

    public function destroy(string $key)
    {
        $row = MenuCardImage::where('key', $key)->first();
        if ($row) {
            if ($row->image_path && Storage::disk('public')->exists($row->image_path)) {
                Storage::disk('public')->delete($row->image_path);
            }
            $row->image_path = null;
            $row->save();
        }

        return back()->with('status', 'Imagen eliminada.');
    }
}
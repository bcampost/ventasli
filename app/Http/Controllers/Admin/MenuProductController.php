<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuProductController extends Controller
{
    public function index()
    {
        $items = MenuProduct::query()
            ->orderBy('menu_key')
            ->orderBy('sort')
            ->orderByDesc('id')
            ->paginate(50);

        return view('admin.menu-products.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_key' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2000'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB

            // ✅ nuevos
            'steel_colors_json' => ['nullable', 'string'],
            'melamine_colors_json' => ['nullable', 'string'],

            'gallery_images.*' => ['nullable', 'image', 'max:6144'],
            'gallery_meta' => ['nullable', 'array'],
            'gallery_meta.*.steel' => ['nullable', 'string', 'max:80'],
            'gallery_meta.*.melamine' => ['nullable', 'string', 'max:80'],

            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $p = new MenuProduct();
        $p->menu_key = $data['menu_key'];
        $p->title = $data['title'];
        $p->description = $data['description'] ?? null;
        $p->url = $data['url'] ?? null;
        $p->sort = (int)($data['sort'] ?? 0);
        $p->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-products', 'public');
            $p->image_path = $path;
        }

        // specs: colores
        $specs = [];
        $steel = json_decode((string)($data['steel_colors_json'] ?? '[]'), true);
        $mel  = json_decode((string)($data['melamine_colors_json'] ?? '[]'), true);
        $specs['steel_colors'] = array_values(array_filter(array_map('trim', is_array($steel) ? $steel : [])));
        $specs['melamine_colors'] = array_values(array_filter(array_map('trim', is_array($mel) ? $mel : [])));
        $p->specs = $specs;

        // gallery_images con metadata
        $gallery = [];
        $files = $request->file('gallery_images', []);
        $meta  = $request->input('gallery_meta', []);

        foreach ($files as $idx => $file) {
            if (!$file) continue;
            $path = $file->store('menu-products/gallery', 'public');

            $steelV = trim((string)($meta[$idx]['steel'] ?? ''));
            $melV   = trim((string)($meta[$idx]['melamine'] ?? ''));

            $gallery[] = [
                'path' => $path,
                'steel' => $steelV,
                'melamine' => $melV,
            ];
        }

        $p->gallery_images = $gallery;
        $p->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto agregado.');

        return redirect()->back()->with('ok', 'Producto agregado.');
    }

    public function update(Request $request, MenuProduct $menu_product)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2000'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB

            // ✅ nuevos
            'steel_colors_json' => ['nullable', 'string'],
            'melamine_colors_json' => ['nullable', 'string'],

            'gallery_images.*' => ['nullable', 'image', 'max:6144'],
            'gallery_meta' => ['nullable', 'array'],
            'gallery_meta.*.steel' => ['nullable', 'string', 'max:80'],
            'gallery_meta.*.melamine' => ['nullable', 'string', 'max:80'],

            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['nullable', 'string'],

            // editar asignación existente
            'existing_meta' => ['nullable', 'array'],
            'existing_meta.*.steel' => ['nullable', 'string', 'max:80'],
            'existing_meta.*.melamine' => ['nullable', 'string', 'max:80'],

            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $menu_product->title = $data['title'];
        $menu_product->description = $data['description'] ?? null;
        $menu_product->url = $data['url'] ?? null;
        $menu_product->sort = (int)($data['sort'] ?? 0);
        $menu_product->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if (!empty($menu_product->image_path) && Storage::disk('public')->exists($menu_product->image_path)) {
                Storage::disk('public')->delete($menu_product->image_path);
            }
            $path = $request->file('image')->store('menu-products', 'public');
            $menu_product->image_path = $path;
        }

        // ✅ specs: colores
        $specs = is_array($menu_product->specs) ? $menu_product->specs : (json_decode((string)$menu_product->specs, true) ?: []);
        $steel = json_decode((string)($data['steel_colors_json'] ?? '[]'), true);
        $mel  = json_decode((string)($data['melamine_colors_json'] ?? '[]'), true);
        $specs['steel_colors'] = array_values(array_filter(array_map('trim', is_array($steel) ? $steel : [])));
        $specs['melamine_colors'] = array_values(array_filter(array_map('trim', is_array($mel) ? $mel : [])));
        $menu_product->specs = $specs;

        // ✅ gallery existente
        $gallery = is_array($menu_product->gallery_images) ? $menu_product->gallery_images : (json_decode((string)$menu_product->gallery_images, true) ?: []);
        $gallery = array_values(array_filter(array_map(function ($it) {
            if (!is_array($it)) return null;
            $path = trim((string)($it['path'] ?? ''));
            if ($path === '') return null;
            return [
                'path' => ltrim($path, '/'),
                'steel' => trim((string)($it['steel'] ?? '')),
                'melamine' => trim((string)($it['melamine'] ?? '')),
            ];
        }, $gallery)));

        // aplicar cambios de meta existente
        $existingMeta = $request->input('existing_meta', []);
        foreach ($gallery as $i => $it) {
            if (isset($existingMeta[$i])) {
                $gallery[$i]['steel'] = trim((string)($existingMeta[$i]['steel'] ?? $gallery[$i]['steel']));
                $gallery[$i]['melamine'] = trim((string)($existingMeta[$i]['melamine'] ?? $gallery[$i]['melamine']));
            }
        }

        // eliminar seleccionadas
        $remove = $request->input('remove_gallery', []);
        if (is_array($remove) && count($remove)) {
            $removeSet = array_flip(array_map(fn($p) => ltrim((string)$p, '/'), $remove));
            $keep = [];
            foreach ($gallery as $it) {
                if (isset($removeSet[ltrim($it['path'], '/')])) {
                    if (Storage::disk('public')->exists($it['path'])) {
                        Storage::disk('public')->delete($it['path']);
                    }
                    continue;
                }
                $keep[] = $it;
            }
            $gallery = $keep;
        }

        // agregar nuevos uploads con meta
        $files = $request->file('gallery_images', []);
        $meta  = $request->input('gallery_meta', []);

        foreach ($files as $idx => $file) {
            if (!$file) continue;
            $path = $file->store('menu-products/gallery', 'public');
            $gallery[] = [
                'path' => $path,
                'steel' => trim((string)($meta[$idx]['steel'] ?? '')),
                'melamine' => trim((string)($meta[$idx]['melamine'] ?? '')),
            ];
        }

        $menu_product->gallery_images = $gallery;

        $menu_product->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto actualizado.');

        return redirect()->back()->with('ok', 'Producto actualizado.');
    }

    public function destroy(Request $request, MenuProduct $menu_product)
    {
        $to = $request->input('redirect_to');

        if (!empty($menu_product->image_path) && Storage::disk('public')->exists($menu_product->image_path)) {
            Storage::disk('public')->delete($menu_product->image_path);
        }

        $gallery = is_array($menu_product->gallery_images) ? $menu_product->gallery_images : (json_decode((string)$menu_product->gallery_images, true) ?: []);
        foreach ($gallery as $it) {
            if (!is_array($it)) continue;
            $p = ltrim((string)($it['path'] ?? ''), '/');
            if ($p && Storage::disk('public')->exists($p)) {
                Storage::disk('public')->delete($p);
            }
        }

        $menu_product->delete();

        if ($to) return redirect($to)->with('ok', 'Producto eliminado.');
        return redirect()->back()->with('ok', 'Producto eliminado.');
    }
}
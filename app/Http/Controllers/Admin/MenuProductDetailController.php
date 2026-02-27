<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuProductDetailController extends Controller
{
    public function edit(MenuProduct $menu_product, Request $request)
    {
        $detail = $menu_product->detail()->firstOrCreate([]);
        $redirectTo = $request->get('redirect_to', url()->previous());

        return view('admin.menu-product-detail.edit', [
            'product' => $menu_product,
            'detail' => $detail,
            'redirectTo' => $redirectTo,
        ]);
    }

    public function update(MenuProduct $menu_product, Request $request)
    {
        $detail = $menu_product->detail()->firstOrCreate([]);

        $data = $request->validate([
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],

            'length' => ['nullable','string','max:50'],
            'width'  => ['nullable','string','max:50'],
            'height' => ['nullable','string','max:50'],

            // subir imágenes nuevas
            'gallery_images' => ['nullable','array'],
            'gallery_images.*' => ['nullable','image','max:5120'],

            'gallery_meta' => ['nullable','array'],
            'gallery_meta.*.acero' => ['nullable','string','max:60'],
            'gallery_meta.*.melamina' => ['nullable','string','max:60'],

            // reasignar existentes
            'existing_meta' => ['nullable','array'],
            'existing_meta.*.acero' => ['nullable','string','max:60'],
            'existing_meta.*.melamina' => ['nullable','string','max:60'],

            // remover existentes
            'remove_gallery' => ['nullable','array'],
            'remove_gallery.*' => ['nullable','string'],

            'redirect_to' => ['nullable','string'],
        ]);

        // base actual
        $imgs = $detail->images_safe; // accessor
        $imgs = is_array($imgs) ? $imgs : [];

        // 1) quitar marcadas
        $toRemove = (array)($data['remove_gallery'] ?? []);
        if (!empty($toRemove)) {
            $imgs = array_values(array_filter($imgs, function($row) use ($toRemove){
                return !in_array(($row['path'] ?? ''), $toRemove, true);
            }));

            foreach ($toRemove as $p) {
                $p = ltrim((string)$p,'/');
                if ($p !== '' && Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
        }

        // 2) reasignar meta existentes por índice
        $existingMeta = (array)($data['existing_meta'] ?? []);
        if (!empty($existingMeta)) {
            foreach ($imgs as $i => $row) {
                if (!array_key_exists($i, $existingMeta)) continue;
                $imgs[$i]['acero'] = trim((string)($existingMeta[$i]['acero'] ?? ''));
                $imgs[$i]['melamina'] = trim((string)($existingMeta[$i]['melamina'] ?? ''));
            }
        }

        // 3) agregar nuevos uploads
        $files = $request->file('gallery_images', []);
        $meta  = (array)($data['gallery_meta'] ?? []);

        if (is_array($files) && count($files)) {
            foreach ($files as $idx => $file) {
                if (!$file) continue;
                $path = $file->store('products', 'public');

                $imgs[] = [
                    'path' => $path,
                    'acero' => trim((string)($meta[$idx]['acero'] ?? '')),
                    'melamina' => trim((string)($meta[$idx]['melamina'] ?? '')),
                ];
            }
        }

        $detail->fill([
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'length' => $data['length'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'images' => $imgs,
        ])->save();

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('success', 'Detalle actualizado.');
    }
}
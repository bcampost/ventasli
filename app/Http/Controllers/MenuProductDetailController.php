<?php

namespace App\Http\Controllers;

use App\Models\MenuProduct;
use Illuminate\Http\Request;

class MenuProductDetailController extends Controller
{
    public function show(Request $request, MenuProduct $menu_product)
    {
        // Solo activos si no es admin
        $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin');
        if (!$isAdmin && !$menu_product->is_active) {
            abort(404);
        }

        // Normaliza estructura
        $specs = is_array($menu_product->specs) ? $menu_product->specs : (json_decode((string)$menu_product->specs, true) ?: []);
        $gallery = is_array($menu_product->gallery_images) ? $menu_product->gallery_images : (json_decode((string)$menu_product->gallery_images, true) ?: []);

        // Limpieza mínima
        $specs['steel_colors'] = array_values(array_filter(array_map('trim', $specs['steel_colors'] ?? [])));
        $specs['melamine_colors'] = array_values(array_filter(array_map('trim', $specs['melamine_colors'] ?? [])));

        // Asegura objetos con keys esperadas
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

        return view('menu.product', [
            'product' => $menu_product,
            'specs' => $specs,
            'gallery' => $gallery,
            'redirectTo' => url()->previous(),
            'isAdmin' => $isAdmin,
        ]);
    }
}
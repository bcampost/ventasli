<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;

class MenuProductVariantColorsController extends Controller
{
    public function edit(MenuProduct $menu_product, Request $request)
    {
        $detail = $menu_product->detail()->firstOrCreate([]);
        $redirectTo = $request->get('redirect_to', url()->previous());

        return view('admin.menu-product-detail.colors', [
            'product' => $menu_product,
            'detail' => $detail,
            'redirectTo' => $redirectTo,
        ]);
    }

    public function update(MenuProduct $menu_product, Request $request)
    {
        $detail = $menu_product->detail()->firstOrCreate([]);

        $data = $request->validate([
            // ✅ recibimos JSON desde la vista
            'acero_colors_json' => ['nullable','string'],
            'melamina_colors_json' => ['nullable','string'],
            'redirect_to' => ['nullable','string'],
        ]);

        $acero = json_decode((string)($data['acero_colors_json'] ?? '[]'), true);
        $mela  = json_decode((string)($data['melamina_colors_json'] ?? '[]'), true);

        $acero = is_array($acero) ? $acero : [];
        $mela  = is_array($mela) ? $mela : [];

        $acero = array_values(array_unique(array_filter(array_map('trim', $acero))));
        $mela  = array_values(array_unique(array_filter(array_map('trim', $mela))));

        $detail->fill([
            'acero_colors' => $acero,
            'melamina_colors' => $mela,
        ])->save();

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('success', 'Colores actualizados.');
    }
}
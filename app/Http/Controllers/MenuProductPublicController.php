<?php

namespace App\Http\Controllers;

use App\Models\MenuProduct;
use Illuminate\Http\Request;

class MenuProductPublicController extends Controller
{
    public function show(Request $request, MenuProduct $menu_product)
    {
        $user = auth()->user();
        $isAdmin = auth()->check() && method_exists($user, 'hasRole') && $user->hasRole('admin');

        if (!$isAdmin && !$menu_product->is_active) {
            abort(404);
        }

        $detail = $menu_product->detail()->firstOrCreate([]);

        $redirectTo = $request->get('redirect_to');
        if (!$redirectTo) {
            $redirectTo = route('home');
        }

        // Colores seleccionados para este producto
        $selectedColors = $menu_product->materialColors()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        $aceroColors = $selectedColors
            ->filter(fn($c) => $c->type === 'acero')
            ->pluck('name')
            ->map(fn($v) => trim((string)$v))
            ->filter()
            ->values()
            ->all();

        $laminadoColors = $selectedColors
            ->filter(fn($c) => $c->type === 'laminado')
            ->pluck('name')
            ->map(fn($v) => trim((string)$v))
            ->filter()
            ->values()
            ->all();

        return view('menu.product_show', [
            'product' => $menu_product,
            'detail' => $detail,
            'redirectTo' => $redirectTo,
            'aceroColors' => $aceroColors,
            'laminadoColors' => $laminadoColors,
        ]);
    }
}
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

        // Solo activos si no es admin
        if (!$isAdmin && !$menu_product->is_active) {
            abort(404);
        }

        // Asegura detail
        $detail = $menu_product->detail()->firstOrCreate([]);

        return view('menu.product_show', [
            'product' => $menu_product,               // aquí vienen tech_pdf_path/manual_pdf_path
            'detail' => $detail,
            'redirectTo' => $request->get('redirect_to', url()->previous()),
        ]);
    }
}
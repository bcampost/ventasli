<?php

namespace App\Http\Controllers;

use App\Models\MenuProduct;
use App\Models\MenuProductDetail;
use Illuminate\Http\Request;

class MenuProductPublicController extends Controller
{
    public function show(Request $request, MenuProduct $menu_product)
    {
        $detail = MenuProductDetail::firstOrCreate(
            ['menu_product_id' => $menu_product->id],
            ['images' => [], 'acero_colors' => [], 'melamina_colors' => []]
        );

        return view('menu.product_show', [
            'product' => $menu_product,
            'detail'  => $detail,
            'redirectTo' => url()->previous() ?: route('home'),
        ]);
    }
}
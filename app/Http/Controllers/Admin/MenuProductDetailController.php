<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use App\Models\MenuProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuProductDetailController extends Controller
{
    public function edit(Request $request, MenuProduct $menu_product)
    {
        $detail = MenuProductDetail::firstOrCreate(
            ['menu_product_id' => $menu_product->id],
            ['images' => [], 'acero_colors' => [], 'melamina_colors' => []]
        );

        return view('admin.product-details.edit', [
            'product' => $menu_product,
            'detail'  => $detail,
            'redirectTo' => $request->get('redirect_to', url()->previous() ?: route('menu.product.show', $menu_product)),
        ]);
    }

    public function update(Request $request, MenuProduct $menu_product)
    {
        $detail = MenuProductDetail::firstOrCreate(
            ['menu_product_id' => $menu_product->id],
            ['images' => [], 'acero_colors' => [], 'melamina_colors' => []]
        );

        $data = $request->validate([
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],

            'largo' => ['nullable','string','max:80'],
            'ancho' => ['nullable','string','max:80'],
            'alto'  => ['nullable','string','max:80'],

            'acero_colors' => ['nullable','string'],     // CSV
            'melamina_colors' => ['nullable','string'],  // CSV

            'images' => ['nullable','array'],
            'images.*' => ['image','max:5120'],

            'remove_images' => ['nullable','array'],
            'remove_images.*' => ['string'],

            'redirect_to' => ['nullable','string'],
        ]);

        // colores desde CSV -> array limpio
        $csvToArr = function(?string $csv){
            $csv = trim((string)$csv);
            if ($csv === '') return [];
            $parts = array_map('trim', explode(',', $csv));
            $parts = array_values(array_filter($parts, fn($v)=>$v!=='')); // sin vacíos
            // únicos (case-insensitive)
            $seen = [];
            $out = [];
            foreach ($parts as $p) {
                $k = mb_strtolower($p);
                if (isset($seen[$k])) continue;
                $seen[$k] = true;
                $out[] = $p;
            }
            return $out;
        };

        $detail->title = $data['title'] ?? $detail->title;
        $detail->description = $data['description'] ?? $detail->description;

        $detail->largo = $data['largo'] ?? $detail->largo;
        $detail->ancho = $data['ancho'] ?? $detail->ancho;
        $detail->alto  = $data['alto']  ?? $detail->alto;

        $detail->acero_colors = $csvToArr($data['acero_colors'] ?? null);
        $detail->melamina_colors = $csvToArr($data['melamina_colors'] ?? null);

        $images = is_array($detail->images) ? $detail->images : (json_decode((string)$detail->images, true) ?: []);

        // borrar imágenes marcadas
        $remove = $data['remove_images'] ?? [];
        if (!empty($remove)) {
            $remove = array_map(fn($p)=>ltrim($p,'/'), $remove);
            $images = array_values(array_filter($images, fn($p)=>!in_array(ltrim($p,'/'), $remove, true)));

            foreach ($remove as $p) {
                if ($p && Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
        }

        // subir nuevas
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file) continue;
                $path = $file->store('menu-products/details', 'public');
                $images[] = $path;
            }
        }

        $detail->images = array_values($images);
        $detail->save();

        $redirect = $data['redirect_to'] ?? route('menu.product.show', $menu_product);

        return redirect($redirect)->with('success', 'Detalle del producto actualizado.');
    }
}
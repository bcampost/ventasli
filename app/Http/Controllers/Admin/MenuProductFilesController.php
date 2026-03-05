<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuProductFilesController extends Controller
{
    /**
     * POST /admin/product-files/{menu_product}
     * Guarda (opcional) ficha técnica e instructivo en specs.files.*
     */
    public function update(Request $request, MenuProduct $menu_product)
    {
        $request->validate([
            'ficha_tecnica' => ['nullable', 'file', 'mimes:pdf', 'max:20480'], // 20MB
            'instructivo'   => ['nullable', 'file', 'mimes:pdf', 'max:20480'], // 20MB
            'redirect_to'   => ['nullable', 'string', 'max:2048'],
        ]);

        $specs = is_array($menu_product->specs)
            ? $menu_product->specs
            : (json_decode((string)$menu_product->specs, true) ?: []);

        $specs['files'] = is_array($specs['files'] ?? null) ? $specs['files'] : [];

        // Carpeta destino (public)
        $dir = public_path('pdfs/products');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $slugBase = Str::slug($menu_product->title ?: ('producto-'.$menu_product->id));

        if ($request->hasFile('ficha_tecnica')) {
            $filename = "ficha-tecnica-{$slugBase}-{$menu_product->id}.pdf";
            $request->file('ficha_tecnica')->move($dir, $filename);
            $specs['files']['ficha_tecnica'] = "pdfs/products/{$filename}";
        }

        if ($request->hasFile('instructivo')) {
            $filename = "instructivo-{$slugBase}-{$menu_product->id}.pdf";
            $request->file('instructivo')->move($dir, $filename);
            $specs['files']['instructivo'] = "pdfs/products/{$filename}";
        }

        $menu_product->specs = $specs;
        $menu_product->save();

        $redirect = $request->input('redirect_to') ?: url()->previous();

        return redirect($redirect)->with('status', 'Archivos del producto actualizados.');
    }
}
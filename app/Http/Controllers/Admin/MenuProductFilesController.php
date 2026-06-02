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
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $mimesRule = 'mimes:' . implode(',', $allowed);

        $request->validate([
            'ficha_tecnica' => ['nullable', 'file', $mimesRule, 'max:20480'], // 20MB
            'instructivo'   => ['nullable', 'file', $mimesRule, 'max:20480'], // 20MB
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

        $saveFile = function ($file, string $baseName) use ($dir, $allowed) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowed, true)) {
                $ext = 'pdf';
            }
            // Borra versiones previas con otra extensión.
            foreach ($allowed as $oldExt) {
                if ($oldExt === $ext) continue;
                $oldPath = $dir . DIRECTORY_SEPARATOR . "{$baseName}.{$oldExt}";
                if (is_file($oldPath)) @unlink($oldPath);
            }
            $filename = "{$baseName}.{$ext}";
            $file->move($dir, $filename);
            return "pdfs/products/{$filename}";
        };

        if ($request->hasFile('ficha_tecnica')) {
            $specs['files']['ficha_tecnica'] = $saveFile(
                $request->file('ficha_tecnica'),
                "ficha-tecnica-{$slugBase}-{$menu_product->id}"
            );
        }

        if ($request->hasFile('instructivo')) {
            $specs['files']['instructivo'] = $saveFile(
                $request->file('instructivo'),
                "instructivo-{$slugBase}-{$menu_product->id}"
            );
        }

        $menu_product->specs = $specs;
        $menu_product->save();

        $redirect = $request->input('redirect_to') ?: url()->previous();

        return redirect($redirect)->with('status', 'Archivos del producto actualizados.');
    }
}
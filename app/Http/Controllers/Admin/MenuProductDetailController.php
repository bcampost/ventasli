<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MaterialColor;

class MenuProductDetailController extends Controller
{
    public function edit(MenuProduct $menu_product, Request $request)
    {
        $detail = $menu_product->detail()->firstOrCreate([]);
        $redirectTo = $request->get('redirect_to', url()->previous());

        $globalAcero = MaterialColor::query()
            ->where('type', 'acero')
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        $globalLaminado = MaterialColor::query()
            ->where('type', 'laminado')
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        $selectedColors = $menu_product->materialColors()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        $selectedColorIds = $selectedColors
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $productAceroColors = $selectedColors
            ->filter(fn($c) => $c->type === 'acero')
            ->pluck('name')
            ->map(fn($v) => trim((string) $v))
            ->filter()
            ->values()
            ->all();

        $productLaminadoColors = $selectedColors
            ->filter(fn($c) => $c->type === 'laminado')
            ->pluck('name')
            ->map(fn($v) => trim((string) $v))
            ->filter()
            ->values()
            ->all();

        return view('admin.menu-product-detail.edit', [
            'product' => $menu_product,
            'detail' => $detail,
            'redirectTo' => $redirectTo,
            'globalAcero' => $globalAcero,
            'globalLaminado' => $globalLaminado,
            'selectedColorIds' => $selectedColorIds,
            'productAceroColors' => $productAceroColors,
            'productLaminadoColors' => $productLaminadoColors,
        ]);
    }

    public function update(MenuProduct $menu_product, Request $request)
    {

        $detail = $menu_product->detail()->firstOrCreate([]);

        $data = $request->validate([

            'product_title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
            'ingenieria_code' => ['nullable', 'string', 'max:100'],

            'selected_color_ids' => ['nullable', 'array'],
            'selected_color_ids.*' => ['nullable', 'integer', 'exists:material_colors,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'length' => ['nullable', 'string', 'max:50'],
            'width' => ['nullable', 'string', 'max:50'],
            'height' => ['nullable', 'string', 'max:50'],

            // subir imágenes nuevas
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],

            'gallery_meta' => ['nullable', 'array'],
            'gallery_meta.*.acero' => ['nullable', 'string', 'max:60'],
            'gallery_meta.*.melamina' => ['nullable', 'string', 'max:60'],

            // reasignar existentes
            'existing_meta' => ['nullable', 'array'],
            'existing_meta.*.acero' => ['nullable', 'string', 'max:60'],
            'existing_meta.*.melamina' => ['nullable', 'string', 'max:60'],
            'existing_meta.*.cover' => ['nullable', 'string', 'max:5'],
            // remover existentes
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['nullable', 'string'],

            // ✅ PDFs
            'tech_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],   // 50MB
            'manual_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'remove_tech_pdf' => ['nullable'],
            'remove_manual_pdf' => ['nullable'],

            'acero_colors_json' => ['nullable', 'string'],
            'melamina_colors_json' => ['nullable', 'string'],
            'redirect_to' => ['nullable', 'string'],
        ]);


        $menu_product->title = trim((string) $data['product_title']);
        $menu_product->ingenieria_code = strtoupper(trim((string) ($data['ingenieria_code'] ?? ''))) ?: null;
        // =========================
        // ✅ IMÁGENES (detail->images)
        // =========================
        $imgs = $detail->images_safe; // accessor
        $imgs = is_array($imgs) ? $imgs : [];

        // 1) quitar marcadas
        $toRemove = (array) ($data['remove_gallery'] ?? []);
        if (!empty($toRemove)) {
            $imgs = array_values(array_filter($imgs, function ($row) use ($toRemove) {
                return !in_array(($row['path'] ?? ''), $toRemove, true);
            }));

            foreach ($toRemove as $p) {
                $p = ltrim((string) $p, '/');
                if ($p !== '' && Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
        }

        // 2) reasignar meta existentes por índice
        $existingMeta = (array) ($data['existing_meta'] ?? []);
        if (!empty($existingMeta)) {
            foreach ($imgs as $i => $row) {
                foreach ($imgs as $i => $row) {
                    if (!array_key_exists($i, $existingMeta)) {
                        continue;
                    }

                    $imgs[$i]['acero'] = trim((string) ($existingMeta[$i]['acero'] ?? ''));
                    $imgs[$i]['melamina'] = trim((string) ($existingMeta[$i]['melamina'] ?? ''));

                    if (($existingMeta[$i]['cover'] ?? '0') === '1') {
                        $menu_product->image_path = $imgs[$i]['path'] ?? $menu_product->image_path;
                    }
                }
            }
        }

        // 3) agregar nuevos uploads
        $files = $request->file('gallery_images', []);
        $meta = (array) ($data['gallery_meta'] ?? []);

        if (is_array($files) && count($files)) {
            foreach ($files as $idx => $file) {
                if (!$file)
                    continue;
                $path = $file->store('products', 'public');

                $newAcero = trim((string) ($meta[$idx]['acero'] ?? ''));
                $newMela = trim((string) ($meta[$idx]['melamina'] ?? ''));

                $imgs[] = [
                    'path' => $path,
                    'acero' => $newAcero,
                    'melamina' => $newMela,
                ];
            }
        }

        // =========================
        // ✅ PDFs en BD (menu_products)
        // =========================

        // remover ficha técnica
        if ($request->boolean('remove_tech_pdf') && $menu_product->tech_pdf_path) {
            Storage::disk('public')->delete($menu_product->tech_pdf_path);
            $menu_product->tech_pdf_path = null;
        }

        // remover instructivo
        if ($request->boolean('remove_manual_pdf') && $menu_product->manual_pdf_path) {
            Storage::disk('public')->delete($menu_product->manual_pdf_path);
            $menu_product->manual_pdf_path = null;
        }

        // subir ficha técnica
        if ($request->hasFile('tech_pdf')) {
            if ($menu_product->tech_pdf_path) {
                Storage::disk('public')->delete($menu_product->tech_pdf_path);
            }

            $file = $request->file('tech_pdf');
            $name = 'ficha-tecnica-' . time() . '.pdf';
            $path = $file->storeAs("product-pdfs/{$menu_product->id}", $name, 'public');

            $menu_product->tech_pdf_path = $path;
        }

        // subir instructivo
        if ($request->hasFile('manual_pdf')) {
            if ($menu_product->manual_pdf_path) {
                Storage::disk('public')->delete($menu_product->manual_pdf_path);
            }

            $file = $request->file('manual_pdf');
            $name = 'instructivo-' . time() . '.pdf';
            $path = $file->storeAs("product-pdfs/{$menu_product->id}", $name, 'public');

            $menu_product->manual_pdf_path = $path;
        }


        $acero = json_decode((string) ($data['acero_colors_json'] ?? '[]'), true);
        $mela = json_decode((string) ($data['melamina_colors_json'] ?? '[]'), true);

        $acero = is_array($acero) ? $acero : [];
        $mela = is_array($mela) ? $mela : [];

        $acero = array_values(array_unique(array_filter(array_map('trim', $acero))));
        $mela = array_values(array_unique(array_filter(array_map('trim', $mela))));
        // =========================
        // ✅ Guardar todo
        // =========================
        $detail->fill([
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'length' => $data['length'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'acero_colors' => $acero,
            'melamina_colors' => $mela,
            'images' => $imgs,
        ])->save();

        $menu_product->title = $data['product_title'];

        if ($request->hasFile('image')) {
            if ($menu_product->image_path && Storage::disk('public')->exists($menu_product->image_path)) {
                Storage::disk('public')->delete($menu_product->image_path);
            }

            $menu_product->image_path = $request->file('image')->store('menu-products', 'public');
        }

        $menu_product->save();

        $selectedIds = collect((array) ($data['selected_color_ids'] ?? []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $menu_product->materialColors()->sync($selectedIds);

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('success', 'Detalle actualizado.');
    }
}
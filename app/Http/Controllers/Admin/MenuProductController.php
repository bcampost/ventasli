<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MenuProductController extends Controller
{
    public function index(Request $request)
    {
        $menuKey = (string) $request->query('key', '');
        abort_if($menuKey === '', 404);

        $items = MenuProduct::where('menu_key', $menuKey)
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        return view('admin.menu-products.index', [
            'menuKey' => $menuKey,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $hasIsActive = Schema::hasColumn('menu_products', 'is_active');

        $rules = [
            'menu_key' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'max:4096'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ];

        if ($hasIsActive) {
            $rules['is_active'] = ['nullable'];
        }

        $data = $request->validate($rules);

        $item = new MenuProduct();
        $item->menu_key = $data['menu_key'];
        $item->title = $data['title'];
        $item->description = $data['description'] ?? null;
        $item->url = $data['url'] ?? null;
        $item->sort = (int) ($data['sort'] ?? 0);

        if ($hasIsActive) {
            $item->is_active = $request->has('is_active') ? 1 : 0;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_products', 'public');
            $item->image_path = $path;
        }

        $item->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto agregado.');

        return redirect()->back()->with('ok', 'Producto agregado.');
    }

    public function edit(MenuProduct $menuProduct)
    {
        return view('admin.menu-products.edit', [
            'item' => $menuProduct,
            'menuKey' => $menuProduct->menu_key,
        ]);
    }

    public function update(Request $request, MenuProduct $menuProduct)
    {
        $hasIsActive = Schema::hasColumn('menu_products', 'is_active');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'in:1'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ];

        if ($hasIsActive) {
            $rules['is_active'] = ['nullable'];
        }

        $data = $request->validate($rules);

        $menuProduct->title = $data['title'];
        $menuProduct->description = $data['description'] ?? null;
        $menuProduct->url = $data['url'] ?? null;
        $menuProduct->sort = (int) ($data['sort'] ?? 0);

        if ($hasIsActive) {
            $menuProduct->is_active = $request->has('is_active') ? 1 : 0;
        }

        if ($request->input('remove_image') === '1') {
            if ($menuProduct->image_path) {
                Storage::disk('public')->delete($menuProduct->image_path);
            }
            $menuProduct->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($menuProduct->image_path) {
                Storage::disk('public')->delete($menuProduct->image_path);
            }
            $path = $request->file('image')->store('menu_products', 'public');
            $menuProduct->image_path = $path;
        }

        $menuProduct->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto actualizado.');

        return redirect()->back()->with('ok', 'Producto actualizado.');
    }

    public function destroy(Request $request, MenuProduct $menuProduct)
    {
        $to = (string) $request->input('redirect_to', '');

        if ($menuProduct->image_path) {
            Storage::disk('public')->delete($menuProduct->image_path);
        }

        $menuProduct->delete();

        if ($to !== '') return redirect($to)->with('ok', 'Producto eliminado.');
        return redirect()->back()->with('ok', 'Producto eliminado.');
    }
}
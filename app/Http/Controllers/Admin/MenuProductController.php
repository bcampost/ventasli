<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuProductController extends Controller
{
    public function index(Request $request)
    {
        $menuKey = (string) $request->query('key', '');
        abort_if($menuKey === '', 404);

        $items = MenuProduct::where('menu_key', $menuKey)
            ->orderBy('sort')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.menu-products.index', [
            'menuKey' => $menuKey,
            'items' => $items,
        ]);
    }

    public function create(Request $request)
    {
        $menuKey = (string) $request->query('key', '');
        abort_if($menuKey === '', 404);

        return view('admin.menu-products.create', [
            'menuKey' => $menuKey,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_key' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $item = new MenuProduct();
        $item->menu_key = $data['menu_key'];
        $item->title = $data['title'];
        $item->description = $data['description'] ?? null;
        $item->url = $data['url'] ?? null;
        $item->sort = (int) ($data['sort'] ?? 0);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_products', 'public');
            $item->image_path = $path;
        }

        $item->save();

        return redirect()
            ->route('admin.menu-products.index', ['key' => $item->menu_key])
            ->with('status', 'Producto creado');
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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'in:1'],
        ]);

        $menuProduct->title = $data['title'];
        $menuProduct->description = $data['description'] ?? null;
        $menuProduct->url = $data['url'] ?? null;
        $menuProduct->sort = (int) ($data['sort'] ?? 0);

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

        return redirect()
            ->route('admin.menu-products.index', ['key' => $menuProduct->menu_key])
            ->with('status', 'Producto actualizado');
    }

    public function destroy(MenuProduct $menuProduct)
    {
        $key = $menuProduct->menu_key;

        if ($menuProduct->image_path) {
            Storage::disk('public')->delete($menuProduct->image_path);
        }

        $menuProduct->delete();

        return redirect()
            ->route('admin.menu-products.index', ['key' => $key])
            ->with('status', 'Producto eliminado');
    }
}
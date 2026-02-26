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
        // viene desde /admin/menu-products?menu_key=...&redirect_to=...
        $menuKey    = (string) $request->query('menu_key', '');
        $redirectTo = (string) $request->query('redirect_to', url()->previous());

        $q = MenuProduct::query();

        // ✅ Si llega menu_key, filtramos SOLO ese nivel
        if (trim($menuKey) !== '') {
            $q->where('menu_key', trim($menuKey));
        }

        $items = $q->orderBy('sort')
            ->orderByDesc('id')
            ->paginate(50)
            ->appends($request->query()); // mantiene menu_key/redirect_to en paginación

        return view('admin.menu-products.index', compact('items', 'menuKey', 'redirectTo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_key'     => ['required', 'string', 'max:255'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'url'          => ['nullable', 'string', 'max:2000'],
            'sort'         => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['nullable'],
            'image'        => ['nullable', 'image', 'max:5120'], // 5MB
            'redirect_to'  => ['nullable', 'string', 'max:2000'],
        ]);

        $p = new MenuProduct();
        $p->menu_key    = trim($data['menu_key']);
        $p->title       = $data['title'];
        $p->description = $data['description'] ?? null;
        $p->url         = $data['url'] ?? null;
        $p->sort        = (int)($data['sort'] ?? 0);
        $p->is_active   = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-products', 'public');
            $p->image_path = $path;
        }

        $p->save();

        // ✅ siempre regresa a donde estabas (tu admin/menu-products con filtro)
        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto agregado.');

        return redirect()->back()->with('ok', 'Producto agregado.');
    }

    public function update(Request $request, MenuProduct $menu_product)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'url'          => ['nullable', 'string', 'max:2000'],
            'sort'         => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['nullable'],
            'image'        => ['nullable', 'image', 'max:5120'], // 5MB
            'redirect_to'  => ['nullable', 'string', 'max:2000'],
        ]);

        $menu_product->title       = $data['title'];
        $menu_product->description = $data['description'] ?? null;
        $menu_product->url         = $data['url'] ?? null;
        $menu_product->sort        = (int)($data['sort'] ?? 0);
        $menu_product->is_active   = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if (!empty($menu_product->image_path) && Storage::disk('public')->exists($menu_product->image_path)) {
                Storage::disk('public')->delete($menu_product->image_path);
            }
            $path = $request->file('image')->store('menu-products', 'public');
            $menu_product->image_path = $path;
        }

        $menu_product->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Producto actualizado.');

        return redirect()->back()->with('ok', 'Producto actualizado.');
    }

    public function destroy(Request $request, MenuProduct $menu_product)
    {
        $to = $request->input('redirect_to');

        if (!empty($menu_product->image_path) && Storage::disk('public')->exists($menu_product->image_path)) {
            Storage::disk('public')->delete($menu_product->image_path);
        }

        $menu_product->delete();

        if ($to) return redirect($to)->with('ok', 'Producto eliminado.');
        return redirect()->back()->with('ok', 'Producto eliminado.');
    }
}
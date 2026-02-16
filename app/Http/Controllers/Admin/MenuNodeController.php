<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuNodeController extends Controller
{
    /**
     * GET /admin/menu
     * Pantalla para administrar el menú
     */
    public function index()
    {
        $roots = MenuNode::query()
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->orderBy('sort')
            ->orderBy('label')
            ->with(['children' => function ($q) {
                $q->orderBy('sort')->orderBy('label');
            }])
            ->get();

        return view('admin.menu.index', [
            'roots' => $roots,
        ]);
    }

    /**
     * GET /admin/menu/children/{menu_node}
     * Devuelve hijos (JSON) por si lo usas con AJAX
     */
    public function children(MenuNode $menu_node)
    {
        $children = MenuNode::query()
            ->where('parent_id', $menu_node->id)
            ->orderBy('sort')
            ->orderBy('label')
            ->get();

        return response()->json($children);
    }

    /**
     * POST /admin/menu
     * Crea un nodo (root o hijo)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable'],
            'url'       => ['nullable', 'string', 'max:2048'],
            'sort'      => ['nullable', 'integer'],
            'is_active' => ['nullable'],
        ]);

        $label = trim($data['label']);
        $slug  = Str::slug($label, '-');

        $parentId = $data['parent_id'] ?? null;
        if ($parentId === '' || $parentId === '0') $parentId = null;

        // Calcula key requerido por tu DB
        $key = $slug;
        if ($parentId) {
            $parent = MenuNode::find($parentId);
            if ($parent && !empty($parent->key)) {
                $key = rtrim($parent->key, '/') . '/' . $slug;
            }
        }

        MenuNode::create([
            'label'     => $label,
            'slug'      => $slug,
            'key'       => $key,
            'parent_id' => $parentId,
            'url'       => $data['url'] ?? null,
            'sort'      => $data['sort'] ?? 0,
            'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
        ]);

        return redirect()
            ->route('admin.menu.index')
            ->with('status', 'Nodo creado.');
    }

    /**
     * PUT /admin/menu/{menu_node}
     * Actualiza un nodo
     */
    public function update(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:255'],
            'url'       => ['nullable', 'string', 'max:2048'],
            'sort'      => ['nullable', 'integer'],
            'is_active' => ['nullable'],
        ]);

        $label = trim($data['label']);
        $slug  = Str::slug($label, '-');

        // Recalcula key
        $key = $slug;
        if ($menu_node->parent_id) {
            $parent = MenuNode::find($menu_node->parent_id);
            if ($parent && !empty($parent->key)) {
                $key = rtrim($parent->key, '/') . '/' . $slug;
            }
        }

        $menu_node->label = $label;
        $menu_node->slug  = $slug;
        $menu_node->key   = $key;
        $menu_node->url   = $data['url'] ?? null;
        $menu_node->sort  = $data['sort'] ?? 0;
        $menu_node->is_active = isset($data['is_active']) ? (bool)$data['is_active'] : false;

        $menu_node->save();

        // También actualiza keys de hijos (por si cambiaste label/slug)
        $this->refreshChildrenKeys($menu_node);

        return redirect()
            ->route('admin.menu.index')
            ->with('status', 'Nodo actualizado.');
    }

    /**
     * DELETE /admin/menu/{menu_node}
     * Elimina un nodo (recursivo)
     */
    public function destroy(MenuNode $menu_node)
    {
        $this->deleteRecursively($menu_node);

        return redirect()
            ->route('admin.menu.index')
            ->with('status', 'Nodo eliminado.');
    }

    private function deleteRecursively(MenuNode $node): void
    {
        $children = MenuNode::query()->where('parent_id', $node->id)->get();
        foreach ($children as $child) {
            $this->deleteRecursively($child);
        }
        $node->delete();
    }

    private function refreshChildrenKeys(MenuNode $node): void
    {
        $children = MenuNode::query()->where('parent_id', $node->id)->get();
        foreach ($children as $child) {
            $child->key = rtrim($node->key, '/') . '/' . (Str::slug($child->label, '-'));
            if (empty($child->slug)) {
                $child->slug = Str::slug($child->label, '-');
            }
            $child->save();

            $this->refreshChildrenKeys($child);
        }
    }
}
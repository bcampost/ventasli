<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuNodeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id'   => ['nullable', 'integer'],
            'label'       => ['required', 'string', 'max:255'],
            'url'         => ['nullable', 'string', 'max:2048'],
            'sort'        => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $parentId = (int)($data['parent_id'] ?? 0);

        $parent = null;
        if ($parentId > 0) {
            $parent = MenuNode::find($parentId);
        }

        $baseSlug = Str::slug($data['label'], '-');
        if ($baseSlug === '') $baseSlug = 'item';

        // slug único por parent
        $slug = $baseSlug;
        $i = 2;
        while (
            MenuNode::query()
                ->where('parent_id', $parentId)
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        // key basado en parent.key + slug
        $baseKeyPrefix = ($parent && !empty($parent->key)) ? trim($parent->key, '/') : '';
        $keyBase = $baseKeyPrefix ? ($baseKeyPrefix . '/' . $slug) : $slug;

        // key único global
        $key = $keyBase;
        $k = 2;
        while (MenuNode::query()->where('key', $key)->exists()) {
            $key = $keyBase . '-' . $k;
            $k++;
        }

        MenuNode::create([
            'parent_id' => $parentId,
            'label'     => $data['label'],
            'slug'      => $slug,
            'key'       => $key,
            'url'       => $data['url'] ?? null,
            'sort'      => (int)($data['sort'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('success', 'Submenú creado correctamente.');
    }

    public function update(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'parent_id'   => ['nullable', 'integer'],
            'label'       => ['required', 'string', 'max:255'],
            'url'         => ['nullable', 'string', 'max:2048'],
            'sort'        => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $parentId = (int)($data['parent_id'] ?? ($menu_node->parent_id ?? 0));

        $parent = null;
        if ($parentId > 0) {
            $parent = MenuNode::find($parentId);
        }

        $baseSlug = Str::slug($data['label'], '-');
        if ($baseSlug === '') $baseSlug = 'item';

        // slug único por parent (excluye actual)
        $slug = $baseSlug;
        $i = 2;
        while (
            MenuNode::query()
                ->where('parent_id', $parentId)
                ->where('slug', $slug)
                ->where('id', '!=', $menu_node->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        // key basado en parent.key + slug
        $baseKeyPrefix = ($parent && !empty($parent->key)) ? trim($parent->key, '/') : '';
        $keyBase = $baseKeyPrefix ? ($baseKeyPrefix . '/' . $slug) : $slug;

        // key único global (excluye actual)
        $key = $keyBase;
        $k = 2;
        while (
            MenuNode::query()
                ->where('key', $key)
                ->where('id', '!=', $menu_node->id)
                ->exists()
        ) {
            $key = $keyBase . '-' . $k;
            $k++;
        }

        $menu_node->update([
            'parent_id' => $parentId,
            'label'     => $data['label'],
            'slug'      => $slug,
            'key'       => $key,
            'url'       => $data['url'] ?? null,
            'sort'      => (int)($data['sort'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('success', 'Opción actualizada correctamente.');
    }

    /**
     * DELETE /admin/menu/{menu_node}
     * route: admin.menu.destroy
     */
    public function destroy(Request $request, MenuNode $menu_node)
    {
        $redirectTo = $request->input('redirect_to') ?? url()->previous();

        // ✅ Borrado en cascada (hijos -> nietos -> ...)
        $this->deleteNodeRecursive($menu_node);

        return redirect($redirectTo)->with('success', 'Opción eliminada correctamente.');
    }

    /**
     * Borra un nodo y todos sus hijos de forma recursiva.
     * Además intenta borrar imagen asociada de "menu_card_images" si existe esa tabla.
     */
    private function deleteNodeRecursive(MenuNode $node): void
    {
        // 1) primero hijos
        $children = MenuNode::query()->where('parent_id', $node->id)->get();
        foreach ($children as $child) {
            $this->deleteNodeRecursive($child);
        }

        // 2) limpiar imagen asociada (si existe tabla y columna correcta)
        if (!empty($node->key) && Schema::hasTable('menu_card_images')) {

            // Detectar columna correcta para relacionar
            $linkCol = null;

            if (Schema::hasColumn('menu_card_images', 'menu_key')) $linkCol = 'menu_key';
            elseif (Schema::hasColumn('menu_card_images', 'key')) $linkCol = 'key';
            elseif (Schema::hasColumn('menu_card_images', 'token')) $linkCol = 'token';

            if ($linkCol) {
                $row = DB::table('menu_card_images')->where($linkCol, $node->key)->first();

                // Detectar columna del path de imagen
                $pathCol = null;
                if (Schema::hasColumn('menu_card_images', 'path')) $pathCol = 'path';
                elseif (Schema::hasColumn('menu_card_images', 'image_path')) $pathCol = 'image_path';
                elseif (Schema::hasColumn('menu_card_images', 'image')) $pathCol = 'image';

                if ($row && $pathCol && !empty($row->{$pathCol})) {
                    Storage::disk('public')->delete(ltrim($row->{$pathCol}, '/'));
                }

                DB::table('menu_card_images')->where($linkCol, $node->key)->delete();
            }
        }

        // 3) borrar el nodo
        $node->delete();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;

class MenuNodeController extends Controller
{
    /**
     * Editor del menú (vista principal)
     */
    public function index()
    {
        // Root = parent_id NULL o 0
        $roots = MenuNode::query()
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->orderBy('sort')
            ->orderBy('label')
            ->with('childrenRecursive') // tu relación
            ->get();

        return view('admin.menu-nodes.index', compact('roots'));
    }

    /**
     * Endpoint JSON para desplegar hijos (accordion)
     * GET /admin/menu/children/{menu_node}
     */
    public function children(MenuNode $menu_node)
    {
        $children = MenuNode::query()
            ->where('parent_id', $menu_node->id)
            // ->where('is_active', 1) // <- si quieres ocultar inactivos también en admin, descomenta
            ->orderBy('sort')
            ->orderBy('label')
            ->get();

        // Mapa rápido para "has_children" sin N+1
        $childIds = $children->pluck('id')->all();
        $counts = [];
        if (!empty($childIds)) {
            $counts = MenuNode::query()
                ->selectRaw('parent_id, COUNT(*) as c')
                ->whereIn('parent_id', $childIds)
                // ->where('is_active', 1) // <- igual aquí si filtras arriba
                ->groupBy('parent_id')
                ->pluck('c', 'parent_id')
                ->toArray();
        }

        $payload = $children->map(function ($n) use ($counts) {
            return [
                'id'           => $n->id,
                'label'        => $n->label,
                'url'          => $n->url,
                'sort'         => (int)($n->sort ?? 0),
                'is_active'    => (bool)$n->is_active,
                'has_children' => ((int)($counts[$n->id] ?? 0)) > 0,
                'href'         => $this->buildMenuHref($n),
            ];
        })->values();

        return response()->json($payload);
    }

    /**
     * Crear nodo
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => ['nullable'],
            'label'     => ['required', 'string', 'max:255'],
            'url'       => ['nullable', 'string', 'max:2048'],
            'sort'      => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Normaliza parent_id: '' -> null | '0' -> 0 | '5' -> 5
        $parentRaw = $request->input('parent_id', null);
        if ($parentRaw === '' || $parentRaw === null) {
            $data['parent_id'] = null;
        } else {
            $data['parent_id'] = (int)$parentRaw;
        }

        // Validación manual de exists cuando sí viene parent_id
        if (!is_null($data['parent_id']) && $data['parent_id'] !== 0) {
            abort_unless(MenuNode::where('id', $data['parent_id'])->exists(), 422);
        }

        $data['sort'] = $data['sort'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        MenuNode::create($data);

        return redirect()->route('admin.menu.index')->with('ok', 'Opción creada.');
    }

    /**
     * Actualizar nodo
     * PUT /admin/menu/{menu_node}
     */
    public function update(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:255'],
            'url'       => ['nullable', 'string', 'max:2048'],
            'sort'      => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort'] = $data['sort'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $menu_node->update($data);

        return redirect()->route('admin.menu.index')->with('ok', 'Opción actualizada.');
    }

    /**
     * Eliminar nodo y sus hijos
     * DELETE /admin/menu/{menu_node}
     */
    public function destroy(MenuNode $menu_node)
    {
        $this->deleteRecursive($menu_node);

        return redirect()->route('admin.menu.index')->with('ok', 'Opción eliminada.');
    }

    private function deleteRecursive(MenuNode $node): void
    {
        $node->load('children');
        foreach ($node->children as $child) {
            $this->deleteRecursive($child);
        }
        $node->delete();
    }

    /**
     * Helpers
     */

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'menu';
    }

    /**
     * Construye href navegable a tu MenuController:
     * /menu/{sectionSlug}/{path?}
     */
    private function buildMenuHref(MenuNode $node): string
    {
        // juntamos labels subiendo hasta root
        $labels = [];
        $current = $node;

        $guard = 0;
        while ($current && $guard < 60) {
            $labels[] = $current->label;
            $guard++;

            $pid = (int)($current->parent_id ?? 0);
            if ($pid === 0) break;

            $current = MenuNode::find($pid);
        }

        $labels = array_reverse($labels);
        if (empty($labels)) return '#';

        $sectionSlug = $this->slugify($labels[0]);
        $pathLabels = array_slice($labels, 1);
        $path = implode('/', array_map(fn($l) => $this->slugify($l), $pathLabels));

        if ($path === '') {
            return route('menu.section', $sectionSlug);
        }

        return route('menu.section', [$sectionSlug, $path]);
    }
}
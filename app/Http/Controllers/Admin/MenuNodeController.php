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
     */

    public function deletePdf(MenuNode $menu_node)
    {
        $currentUrl = trim((string) ($menu_node->url ?? ''));

        $isPdf = $currentUrl !== '' && (
            Str::endsWith(Str::lower($currentUrl), '.pdf') ||
            Str::contains(Str::lower($currentUrl), 'pdfs/')
        );

        if ($isPdf) {
            $absolute = public_path(ltrim($currentUrl, '/'));

            if (file_exists($absolute)) {
                @unlink($absolute);
            }

            $menu_node->update([
                'url' => null,
            ]);
        }

        return back()->with('status', "PDF eliminado de: {$menu_node->label}");
    }

    public function index()
    {
        $roots = MenuNode::query()
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->orderBy('sort')
            ->orderBy('label')
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort')->orderBy('label');
                }
            ])
            ->get();

        return view('admin.menu.index', [
            'roots' => $roots,
        ]);
    }

    /**
     * GET /admin/menu/children/{menu_node} (JSON)
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
     * ✅ GET /admin/menu/{menu_node}/manage
     * Pantalla para crear hijos + asignar PDF
     */
    public function manage(MenuNode $menu_node)
    {
        $children = MenuNode::query()
            ->where('parent_id', $menu_node->id)
            ->orderBy('sort')
            ->orderBy('label')
            ->get();

        return view('admin.menu.manage', [
            'node' => $menu_node,
            'children' => $children,
        ]);
    }

    /**
     * POST /admin/menu
     * Crea nodo (root o hijo, usado por tu modal general)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer'],
            'is_active' => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $label = trim($data['label']);
        $slug = Str::slug($label, '-');

        $parentId = $data['parent_id'] ?? null;
        if ($parentId === '' || $parentId === '0')
            $parentId = null;

        // ✅ key requerido por tu DB
        $key = $slug;
        if ($parentId) {
            $parent = MenuNode::find($parentId);
            if ($parent && !empty($parent->key)) {
                $key = rtrim($parent->key, '/') . '/' . $slug;
            }
        }

        MenuNode::create([
            'label' => $label,
            'slug' => $slug,
            'key' => $key,
            'parent_id' => $parentId,
            'url' => $data['url'] ?? null,
            'sort' => $data['sort'] ?? 0,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);

        $to = $data['redirect_to'] ?? null;

        return $to
            ? redirect($to)->with('status', 'Nodo creado.')
            : redirect()->route('admin.menu.index')->with('status', 'Nodo creado.');
    }

    /**
     * ✅ POST /admin/menu/{menu_node}/children
     * Crea un hijo bajo ese nodo (usado por manage.blade.php)
     */
    public function storeChild(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
        ]);

        $label = trim($data['label']);
        $slug = Str::slug($label, '-');

        // evita duplicado por slug dentro del mismo padre
        $exists = MenuNode::query()
            ->where('parent_id', $menu_node->id)
            ->where('slug', $slug)
            ->exists();

        if ($exists) {
            return back()->with('status', "Ya existe una opción con nombre similar: {$label}");
        }

        $key = rtrim((string) $menu_node->key, '/') . '/' . $slug;

        $maxSort = (int) MenuNode::query()
            ->where('parent_id', $menu_node->id)
            ->max('sort');

        MenuNode::create([
            'label' => $label,
            'slug' => $slug,
            'key' => $key,
            'parent_id' => $menu_node->id,
            'url' => null,
            'sort' => $maxSort + 1,
            'is_active' => 1,
        ]);

        return back()->with('status', "Opción creada: {$label}");
    }

    /**
     * ✅ POST /admin/menu/{menu_node}/upload
     * Sube PDF y lo asigna en url del nodo
     */
    public function uploadPdf(Request $request, MenuNode $menu_node)
    {
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:' . implode(',', $allowed),
                'max:20480',
            ],
        ]);

        $file = $request->file('pdf');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowed, true)) {
            $ext = 'pdf';
        }

        $slug = $menu_node->slug ?: Str::slug($menu_node->label, '-');
        $filename = "menu-{$slug}-{$menu_node->id}.{$ext}";
        $relativePath = "pdfs/{$filename}";

        // Borra cualquier archivo previo del nodo (con cualquier extensión).
        if ($menu_node->url && file_exists(public_path(ltrim($menu_node->url, '/')))) {
            @unlink(public_path(ltrim($menu_node->url, '/')));
        }
        foreach ($allowed as $oldExt) {
            if ($oldExt === $ext)
                continue;
            $oldPath = public_path("pdfs/menu-{$slug}-{$menu_node->id}.{$oldExt}");
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file->move(public_path('pdfs'), $filename);

        $menu_node->update([
            'url' => $relativePath,
        ]);

        $label = match ($ext) {
            'pdf' => 'PDF',
            'doc', 'docx' => 'Word',
            'xls', 'xlsx' => 'Excel',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => 'Imagen',
            default => 'Archivo',
        };

        return back()->with('status', "{$label} actualizado para: {$menu_node->label}");
    }

    /**
     * PUT /admin/menu/{menu_node}
     */
    public function update(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort' => ['nullable', 'integer'],
            'is_active' => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $label = trim($data['label']);
        $slug = Str::slug($label, '-');

        // Recalcula key
        $key = $slug;
        if ($menu_node->parent_id) {
            $parent = MenuNode::find($menu_node->parent_id);
            if ($parent && !empty($parent->key)) {
                $key = rtrim($parent->key, '/') . '/' . $slug;
            }
        }

        $menu_node->label = $label;
        $menu_node->slug = $slug;
        $menu_node->key = $key;
        $menu_node->url = $data['url'] ?? null;
        $menu_node->sort = $data['sort'] ?? 0;
        $menu_node->is_active = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        $menu_node->save();

        $this->refreshChildrenKeys($menu_node);

        $to = $data['redirect_to'] ?? null;

        return $to
            ? redirect($to)->with('status', 'Nodo actualizado.')
            : redirect()->route('admin.menu.index')->with('status', 'Nodo actualizado.');
    }

    /**
     * DELETE /admin/menu/{menu_node}
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
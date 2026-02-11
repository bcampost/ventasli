<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;

class MenuNodeController extends Controller
{
    public function index()
    {
        $roots = MenuNode::query()
            ->where(function($q){
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->orderBy('sort')->orderBy('label')
            ->get();

        return view('admin.menu.index', compact('roots'));
    }

    public function children(MenuNode $menu_node)
    {
        $children = MenuNode::query()
            ->where('parent_id', $menu_node->id)
            ->orderBy('sort')->orderBy('label')
            ->get()
            ->map(function ($n) use ($menu_node) {
                return [
                    'id' => $n->id,
                    'label' => $n->label,
                    'is_active' => (int)$n->is_active,
                ];
            });

        return response()->json($children);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2000'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $n = new MenuNode();
        $n->parent_id = $data['parent_id'] ?? null;
        $n->label = $data['label'];
        $n->url = $data['url'] ?? null;
        $n->sort = (int)($data['sort'] ?? 0);
        $n->is_active = $request->has('is_active') ? 1 : 0;
        $n->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Submenú creado.');

        return redirect()->route('admin.menu.index')->with('ok', 'Nodo creado.');
    }

    public function update(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2000'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $menu_node->label = $data['label'];
        $menu_node->url = $data['url'] ?? null;
        $menu_node->sort = (int)($data['sort'] ?? 0);
        $menu_node->is_active = $request->has('is_active') ? 1 : 0;
        $menu_node->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Submenú actualizado.');

        return redirect()->route('admin.menu.index')->with('ok', 'Nodo actualizado.');
    }

    public function destroy(Request $request, MenuNode $menu_node)
    {
        $to = (string) $request->input('redirect_to', '');

        $menu_node->deleteRecursive();

        if ($to !== '') return redirect($to)->with('ok', 'Nodo eliminado.');
        return redirect()->route('admin.menu.index')->with('ok', 'Nodo eliminado.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PriceListPdfController extends Controller
{
    public function index()
    {
        $root = MenuNode::query()
            ->where('is_active', 1)
            ->whereRaw('LOWER(label) = ?', ['lista de precios'])
            ->first();

        $children = collect();

        if ($root) {
            $children = MenuNode::query()
                ->where('parent_id', $root->id)
                ->where('is_active', 1)
                ->orderBy('sort')
                ->orderBy('label')
                ->get();
        }

        return view('admin.price-list-pdfs.index', compact('root', 'children'));
    }

    /**
     * ✅ Crear una nueva opción bajo "Lista de precios"
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', 'string', 'max:80'],
        ]);

        $root = MenuNode::query()
            ->where('is_active', 1)
            ->whereRaw('LOWER(label) = ?', ['lista de precios'])
            ->firstOrFail();

        $label = trim($request->input('label'));
        $slug  = Str::slug($label);

        // evitar duplicados por slug/key dentro de ese root
        $exists = MenuNode::query()
            ->where('parent_id', $root->id)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                ->orWhere('key', $slug);
            })
            ->exists();

        if ($exists) {
            return back()->with('status', "Ya existe una opción con nombre similar: {$label}");
        }

        $maxSort = (int) MenuNode::query()
            ->where('parent_id', $root->id)
            ->max('sort');

        MenuNode::create([
            'label'     => $label,
            'slug'      => $slug,
            'key'       => $slug,      // ✅ IMPORTANTÍSIMO para tu BD (NOT NULL)
            'url'       => null,       // se llenará al subir PDF
            'parent_id' => $root->id,
            'is_active' => 1,
            'sort'      => $maxSort + 1,
        ]);
        return back()->with('status', "Opción creada: {$label}");
    }

    /**
     * ✅ Subir / reemplazar PDF para un hijo
     */
    public function upload(Request $request, MenuNode $menu_node)
    {
        $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
        ]);

        $slug = $menu_node->slug ?: Str::slug($menu_node->label);
        $filename = "lista-precios-{$slug}.pdf";

        // guarda en public/pdfs
        $request->file('pdf')->move(public_path('pdfs'), $filename);

        // actualiza url del nodo: eso hace que MenuTree lo muestre en el menú
        $menu_node->update([
            'url' => "pdfs/{$filename}",
        ]);

        return back()->with('status', "PDF actualizado para: {$menu_node->label}");
    }
}
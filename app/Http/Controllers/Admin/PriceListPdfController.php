<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PriceListPdfController extends Controller
{
    /**
     * Pantalla: lista hijos de "Lista de precios" y permite subir PDF por cada opción
     */
    public function index()
    {
        // Busca el root por label. (Si cambia el label, ajusta aquí.)
        $root = MenuNode::query()
            ->where('label', 'Lista de precios')
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->first();

        $children = collect();

        if ($root) {
            $children = MenuNode::query()
                ->where('parent_id', $root->id)
                ->orderBy('sort')
                ->orderBy('label')
                ->get();
        }

        return view('admin.price-list-pdfs.index', [
            'root' => $root,
            'children' => $children,
        ]);
    }

    /**
     * Sube/Reemplaza PDF para un nodo hijo (Mobiliario/Silleria/Comercialización)
     * Guarda en public/pdfs y actualiza menu_nodes.url = "pdfs/<archivo>.pdf"
     */
    public function upload(Request $request, MenuNode $menu_node)
    {
        $data = $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:25600'], // 25MB
        ]);

        // Nombre del archivo: lista-precios-<slug>.pdf
        // Ej: "Silleria" -> lista-precios-silleria.pdf
        $safeSlug = $menu_node->slug ?: Str::slug($menu_node->label, '-');
        $filename = "lista-precios-{$safeSlug}.pdf";

        // Asegura carpeta public/pdfs
        $dir = public_path('pdfs');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // Mueve el archivo (reemplaza si existe)
        $file = $data['pdf'];
        $file->move($dir, $filename);

        // Actualiza URL del nodo (esto es lo que usa tu navbar)
        $menu_node->url = "pdfs/{$filename}";
        $menu_node->save();

        return redirect()
            ->route('admin.price-list-pdfs.index')
            ->with('status', "PDF actualizado para '{$menu_node->label}' → {$filename}");
    }
}
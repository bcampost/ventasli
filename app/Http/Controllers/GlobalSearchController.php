<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MenuNode;
use App\Models\MenuProduct;
// agrega aquí los modelos reales de tu proyecto:
use App\Models\Comunicado; // si existe
// use App\Models\Documento;
// use App\Models\MaterialVisual;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $q = mb_substr($q, 0, 120);

        if ($q === '') {
            return view('search.index', [
                'q' => $q,
                'results' => [],
            ]);
        }

        $like = '%' . str_replace(['%','_'], ['\%','\_'], $q) . '%';

        $results = [];

        // ✅ Menú (Opciones)
        if (class_exists(MenuNode::class)) {
            $items = MenuNode::query()
                ->where('label', 'like', $like)
                ->orWhere('url', 'like', $like)
                ->limit(12)
                ->get()
                ->map(fn($r) => [
                    'type' => 'Menú',
                    'title' => $r->label,
                    'snippet' => $r->url ?? '',
                    'url' => $r->url ?: null,
                ])
                ->toArray();
            $results = array_merge($results, $items);
        }

        // ✅ Productos
        if (class_exists(MenuProduct::class)) {
            $items = MenuProduct::query()
                ->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('menu_key', 'like', $like)
                ->limit(12)
                ->get()
                ->map(fn($r) => [
                    'type' => 'Producto',
                    'title' => $r->title,
                    'snippet' => Str::limit((string)$r->description, 160),
                    'url' => $r->url ?: null,
                ])
                ->toArray();
            $results = array_merge($results, $items);
        }

        // ✅ Comunicados (si tu modelo existe)
        if (class_exists(Comunicado::class)) {
            $items = Comunicado::query()
                ->where('title', 'like', $like)
                ->orWhere('body', 'like', $like)
                ->limit(12)
                ->get()
                ->map(fn($r) => [
                    'type' => 'Comunicado',
                    'title' => $r->title,
                    'snippet' => Str::limit(strip_tags((string)$r->body), 160),
                    'url' => method_exists($r, 'url') ? $r->url() : null,
                ])
                ->toArray();
            $results = array_merge($results, $items);
        }

        return view('search.index', [
            'q' => $q,
            'results' => $results,
        ]);
    }
}
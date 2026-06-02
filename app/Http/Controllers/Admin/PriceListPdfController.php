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
            'key'       => $slug,
            'url'       => null,
            'parent_id' => $root->id,
            'is_active' => 1,
            'sort'      => $maxSort + 1,
        ]);
        return back()->with('status', "Opción creada: {$label}");
    }

    /**
     * ✅ Subir / reemplazar archivo (PDF, Word, Excel o imagen) para un hijo
     */
    public function upload(Request $request, MenuNode $menu_node)
    {
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:' . implode(',', $allowed),
                'max:20480', // 20 MB
            ],
        ]);

        $file = $request->file('pdf');
        $ext  = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowed, true)) {
            $ext = 'pdf';
        }

        $slug = $menu_node->slug ?: Str::slug($menu_node->label);
        $filename = "lista-precios-{$slug}.{$ext}";

        foreach ($allowed as $oldExt) {
            if ($oldExt === $ext) continue;
            $oldPath = public_path("pdfs/lista-precios-{$slug}.{$oldExt}");
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file->move(public_path('pdfs'), $filename);

        $menu_node->update([
            'url' => "pdfs/{$filename}",
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
}
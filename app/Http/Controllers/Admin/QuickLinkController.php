<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuickLinkController extends Controller
{
    private function availableIcons(): array
    {
        $files = Storage::disk('public')->files('quicklink-icons');

        $allowed = ['svg','png','webp','jpg','jpeg'];
        $icons = [];

        foreach ($files as $f) {
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed, true)) {
                $icons[] = $f;
            }
        }

        sort($icons);
        return $icons;
    }

    public function index()
    {
        $links = QuickLink::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $icons = $this->availableIcons();

        return view('admin.quick_links.index', compact('links','icons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'url' => ['required','string','max:255'],

            // default icon key
            'icon' => ['nullable','string','max:40'],

            // uploaded icon chosen
            'icon_path' => ['nullable','string','max:255'],

            // new upload (CORREGIDO)
            'icon_upload' => ['nullable','file','max:2048','mimes:svg,png,webp,jpg,jpeg'],

            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        // Precedencia: upload > icon_path > icon (default)
        if ($request->hasFile('icon_upload')) {
            $file = $request->file('icon_upload');

            $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base) ?: 'icon';
            $ext  = strtolower($file->getClientOriginalExtension());

            $filename = $base.'_'.date('Ymd_His').'.'.$ext;
            $path = $file->storeAs('quicklink-icons', $filename, 'public');

            $data['icon_path'] = $path;
        } else {
            $chosenPath = trim((string)($data['icon_path'] ?? ''));
            $chosenIcon = trim((string)($data['icon'] ?? ''));

            $data['icon_path'] = $chosenPath !== '' ? $chosenPath : null;
            $data['icon'] = $chosenIcon !== '' ? $chosenIcon : 'dot';
        }

        QuickLink::create($data);

        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido creado.');
    }

    public function edit(QuickLink $quickLink)
    {
        $icons = $this->availableIcons();
        return view('admin.quick_links.edit', compact('quickLink','icons'));
    }

    public function update(Request $request, QuickLink $quickLink)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'url' => ['required','string','max:255'],

            'icon' => ['nullable','string','max:40'],
            'icon_path' => ['nullable','string','max:255'],

            // new upload (CORREGIDO)
            'icon_upload' => ['nullable','file','max:2048','mimes:svg,png,webp,jpg,jpeg'],

            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],

            'remove_icon' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        // Si marca quitar icono subido: borra archivo y limpia icon_path
        if ($request->boolean('remove_icon')) {
            if ($quickLink->icon_path && Storage::disk('public')->exists($quickLink->icon_path)) {
                Storage::disk('public')->delete($quickLink->icon_path);
            }
            $data['icon_path'] = null;
        }

        // Precedencia: upload > icon_path > icon
        if ($request->hasFile('icon_upload')) {
            $file = $request->file('icon_upload');

            $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base) ?: 'icon';
            $ext  = strtolower($file->getClientOriginalExtension());

            $filename = $base.'_'.date('Ymd_His').'.'.$ext;
            $path = $file->storeAs('quicklink-icons', $filename, 'public');

            // borra el anterior si existía
            if ($quickLink->icon_path && Storage::disk('public')->exists($quickLink->icon_path)) {
                Storage::disk('public')->delete($quickLink->icon_path);
            }

            $data['icon_path'] = $path;

            // deja icon default como fallback si quieres
            $data['icon'] = trim((string)($data['icon'] ?? '')) !== '' ? $data['icon'] : ($quickLink->icon ?? 'dot');
        } else {
            $chosenPath = trim((string)($data['icon_path'] ?? ''));
            $chosenIcon = trim((string)($data['icon'] ?? ''));

            // si eligió uno del grid subido
            if ($chosenPath !== '') {
                $data['icon_path'] = $chosenPath;
            } else {
                // si no eligió path y no pidió remove_icon, conserva el actual
                if (!$request->boolean('remove_icon')) {
                    $data['icon_path'] = $quickLink->icon_path;
                }
            }

            $data['icon'] = $chosenIcon !== '' ? $chosenIcon : ($quickLink->icon ?? 'dot');
        }

        $quickLink->update($data);

        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido actualizado.');
    }

    public function destroy(QuickLink $quickLink)
    {
        if ($quickLink->icon_path && Storage::disk('public')->exists($quickLink->icon_path)) {
            Storage::disk('public')->delete($quickLink->icon_path);
        }

        $quickLink->delete();

        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido eliminado.');
    }
}
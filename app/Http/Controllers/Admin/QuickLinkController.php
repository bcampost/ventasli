<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickLink;
use Illuminate\Http\Request;

class QuickLinkController extends Controller
{
    public function index()
    {
        $links = QuickLink::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.quick_links.index', compact('links'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'url' => ['required','string','max:255'],
            'icon' => ['required','string','max:40'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        QuickLink::create($data);

        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido creado.');
    }

    public function edit(QuickLink $quickLink)
    {
        return view('admin.quick_links.edit', compact('quickLink'));
    }

    public function update(Request $request, QuickLink $quickLink)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'url' => ['required','string','max:255'],
            'icon' => ['required','string','max:40'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        $quickLink->update($data);

        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido actualizado.');
    }

    public function destroy(QuickLink $quickLink)
    {
        $quickLink->delete();
        return redirect()->route('admin.quick-links.index')->with('ok', 'Acceso rápido eliminado.');
    }
}
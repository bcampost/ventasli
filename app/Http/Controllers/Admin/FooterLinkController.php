<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    public function update(Request $request, FooterLink $footer_link)
    {
        $data = $request->validate([
            'label' => ['required','string','max:120'],
            'link_mode' => ['required','in:menu,external'],
            'menu_path' => ['nullable','string','max:255'],
            'external_url' => ['nullable','string','max:2048'],
            'is_active' => ['nullable'],
            'sort' => ['nullable','integer','min:0'],
            'redirect_to' => ['nullable','string','max:2048'],
        ]);

        $mode = $data['link_mode'];

        // Normaliza
        $menuPath = trim((string)($data['menu_path'] ?? ''), '/');
        $extUrl   = trim((string)($data['external_url'] ?? ''));

        if ($mode === 'external') {
            // externo requiere URL
            if ($extUrl === '') {
                return back()->withErrors(['external_url' => 'Ingresa un link externo válido.'])->withInput();
            }
            $footer_link->external_url = $extUrl;
            $footer_link->menu_path = $menuPath ?: $footer_link->menu_path; // lo dejamos como fallback si ya existía
        } else {
            // menu requiere menu_path (si no, usa fallback por key)
            $footer_link->menu_path = $menuPath !== '' ? $menuPath : ($footer_link->menu_path ?: ('capacitaciones/'.$footer_link->key));
            $footer_link->external_url = null; // para forzar que vaya al menú
        }

        $footer_link->label = $data['label'];
        $footer_link->sort = isset($data['sort']) ? (int)$data['sort'] : $footer_link->sort;
        $footer_link->is_active = $request->has('is_active') ? (bool)$request->boolean('is_active') : $footer_link->is_active;

        $footer_link->save();

        $to = $data['redirect_to'] ?? url()->previous();
        return redirect($to)->with('status', 'Link actualizado.');
    }
}
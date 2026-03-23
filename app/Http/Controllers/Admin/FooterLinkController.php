<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class FooterLinkController extends Controller
{

private function footerLinksColumns(): array
{
    static $cols = null;

    if ($cols === null) {
        $cols = Schema::getColumnListing('footer_links');
    }

    return $cols;
}
    /**
     * Update de un solo link (pantallas clásicas).
     * Soporta menu|external|file (file NO sube archivo aquí; eso lo hace bulkUpdate o upload()).
     */
    public function update(Request $request, FooterLink $footer_link)
    {
        $data = $request->validate([
            'label'        => ['required', 'string', 'max:120'],
            'link_mode'    => ['required', 'in:menu,external,file'],
            'menu_path'    => ['nullable', 'string', 'max:255'],
            'external_url' => ['nullable', 'string', 'max:2048'],
            'is_active'    => ['nullable'],
            'sort'         => ['nullable', 'integer', 'min:0'],
            'redirect_to'  => ['nullable', 'string', 'max:2048'],
        ]);

        $mode = (string) $data['link_mode'];

        // Normaliza
        $menuPath = trim((string) ($data['menu_path'] ?? ''), '/');
        $extUrl   = trim((string) ($data['external_url'] ?? ''));

        $footer_link->label     = $data['label'];
        $footer_link->link_mode = $mode;
        $footer_link->sort      = isset($data['sort']) ? (int) $data['sort'] : $footer_link->sort;
        $footer_link->is_active = $request->has('is_active')
            ? (bool) $request->boolean('is_active')
            : $footer_link->is_active;

        if ($mode === 'external') {
            if ($extUrl === '') {
                return back()->withErrors(['external_url' => 'Ingresa un link externo válido.'])->withInput();
            }

            $footer_link->external_url = $extUrl;
            $footer_link->menu_path    = $menuPath ?: ($footer_link->menu_path ?: ('capacitaciones/' . $footer_link->key));

            // NO borramos file_path por si regresan a "file"
        } elseif ($mode === 'menu') {
            $footer_link->menu_path    = $menuPath !== '' ? $menuPath : ($footer_link->menu_path ?: ('capacitaciones/' . $footer_link->key));
            $footer_link->external_url = null;

            // NO borramos file_path por si regresan a "file"
        } else { // file
            // Aquí NO subimos archivo en update(). Solo dejamos el modo.
            // Si no hay archivo, menu_path queda como fallback
            $footer_link->external_url = null;
            $footer_link->menu_path    = $menuPath ?: ($footer_link->menu_path ?: ('capacitaciones/' . $footer_link->key));
        }

        $footer_link->save();

        $to = $data['redirect_to'] ?? url()->previous();
        return redirect($to)->with('status', 'Link actualizado.');
    }

    /**
     * Bulk update (usado por tu modal).
     * Soporta file upload por input name "file_{id}".
     *
     * IMPORTANTE:
     * - Debes enviar el request como multipart/form-data (FormData en JS)
     * - Debes tener: php artisan storage:link (para que /storage/... sea accesible)
     */
    public function bulkUpdate(Request $request)
    {
        // items viene como JSON string
        $items = json_decode((string) $request->input('items', '[]'), true);
        if (!is_array($items)) $items = [];

        foreach ($items as $row) {
            if (!isset($row['id'])) continue;

            $link = FooterLink::find($row['id']);
            if (!$link) continue;

            $label = trim((string) ($row['label'] ?? ''));
            if ($label === '') continue;

            $mode = (string) ($row['link_mode'] ?? 'menu');
            if (!in_array($mode, ['menu', 'external', 'file'], true)) {
                $mode = 'menu';
            }

            $fileKey = 'file_' . $link->id;
            $hasUploadedFile = $request->hasFile($fileKey) && $request->file($fileKey)->isValid();

            // Si llegó archivo nuevo, forzamos modo file aunque el front mande otra cosa
            if ($hasUploadedFile) {
                $mode = 'file';
            }

            $menuPath = trim((string) ($row['menu_path'] ?? ''), '/');
            $extUrl   = trim((string) ($row['external_url'] ?? ''));

            $link->label     = $label;
            $link->link_mode = $mode;
            $link->sort      = isset($row['sort']) ? (int) $row['sort'] : $link->sort;
            $link->is_active = array_key_exists('is_active', $row) ? (bool) $row['is_active'] : $link->is_active;

            if ($mode === 'external') {
                // requiere URL
                if ($extUrl !== '') {
                    $link->external_url = $extUrl;
                } else {
                    // si no mandan url, mantenemos la anterior (no rompemos)
                    $link->external_url = $link->external_url ?: null;
                }

                // fallback por si cambian a menu luego
                $link->menu_path = $menuPath ?: ($link->menu_path ?: ('capacitaciones/' . $link->key));

                // no borramos file_path
            } elseif ($mode === 'menu') {
                $link->menu_path    = $menuPath !== '' ? $menuPath : ($link->menu_path ?: ('capacitaciones/' . $link->key));
                $link->external_url = null;

                // no borramos file_path
            } else { // file

                if ($hasUploadedFile) {
                    $f = $request->file($fileKey);

                    // borra anterior si existe
                    if (!empty($link->file_path) && Storage::disk('public')->exists($link->file_path)) {
                        Storage::disk('public')->delete($link->file_path);
                    }

                    $safeBase = Str::slug(pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME));
                    $ext      = strtolower($f->getClientOriginalExtension() ?: 'bin');
                    $final    = $safeBase . '-' . time() . '.' . $ext;

                    $path = $f->storeAs('footer/capacitaciones', $final, 'public');

                    $link->file_path = $path;

                    // IMPORTANTE: en tu migración tú pusiste file_original/file_mime/file_size
                    // pero en tu código usas file_name. Ajusto a lo más común:
                    $cols = $this->footerLinksColumns();

                    if (in_array('file_original', $cols, true)) {
                        $link->file_original = $f->getClientOriginalName();
                    } elseif (in_array('file_name', $cols, true)) {
                        $link->file_name = $f->getClientOriginalName();
                    }

                    if (in_array('file_mime', $cols, true)) {
                        $link->file_mime = $f->getClientMimeType();
                    }

                    if (in_array('file_size', $cols, true)) {
                        $link->file_size = $f->getSize();
                    }

                    if (in_array('file_disk', $cols, true)) {
                        $link->file_disk = 'public';
                    }
                }

                // al estar en file, no quieres que navegue a externo
                $link->external_url = null;

                // menu_path lo dejamos como fallback si no hay file_path
                $link->menu_path = $menuPath ?: ($link->menu_path ?: ('capacitaciones/' . $link->key));
            }

            $link->save();
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Upload individual (opcional, si decides subir archivo por fila con botón separado)
     * Route: POST admin/footer-links/{footer_link}/upload
     */
    public function upload(Request $request, FooterLink $footer_link)
    {
        $data = $request->validate([
            'file'        => ['required', 'file', 'max:51200', 'mimes:pdf,mp4,webm,png,jpg,jpeg'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $f = $data['file'];

        if (!empty($footer_link->file_path) && Storage::disk('public')->exists($footer_link->file_path)) {
            Storage::disk('public')->delete($footer_link->file_path);
        }

        $safeBase = Str::slug(pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME));
        $ext      = strtolower($f->getClientOriginalExtension() ?: 'bin');
        $final    = $safeBase . '-' . time() . '.' . $ext;

        $path = $f->storeAs('footer/capacitaciones', $final, 'public');

        $footer_link->link_mode    = 'file';
        $footer_link->external_url = null;
        $footer_link->file_path    = $path;

        $cols = $this->footerLinksColumns();

        if (in_array('file_original', $cols, true)) {
            $footer_link->file_original = $f->getClientOriginalName();
        } elseif (in_array('file_name', $cols, true)) {
            $footer_link->file_name = $f->getClientOriginalName();
        }

        if (in_array('file_mime', $cols, true)) {
            $footer_link->file_mime = $f->getClientMimeType();
        }

        if (in_array('file_size', $cols, true)) {
            $footer_link->file_size = $f->getSize();
        }

        if (in_array('file_disk', $cols, true)) {
            $footer_link->file_disk = 'public';
        }

        $footer_link->save();

        return redirect($data['redirect_to'] ?? url()->previous())
            ->with('status', 'Archivo subido y guardado.');
    }
}
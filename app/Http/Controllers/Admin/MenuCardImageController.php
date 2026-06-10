<?php
// app/Http/Controllers/Admin/MenuCardImageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCardImage;
use App\Support\ImageStandardizer;
use Illuminate\Http\Request;

class MenuCardImageController extends Controller
{
    public function update(Request $request, string $token)
    {
        // ✅ token -> key (base64url)
        $key = $this->decodeToken($token);
        if (!$key) {
            return back()->with('error', 'Token inválido.');
        }

        $data = $request->validate([
            'title'       => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'image'       => ['nullable','image','max:5120'], // 5MB
            'redirect_to' => ['nullable','string'],
        ]);

        $row = MenuCardImage::query()->firstOrNew(['key' => $key]);

        $row->title = $data['title'] ?? null;
        $row->description = $data['description'] ?? null;

        // ✅ si sube imagen: recorta a estándar y guarda
        if ($request->hasFile('image')) {
            // borrar anterior
            ImageStandardizer::deleteIfExists($row->path);

            $path = ImageStandardizer::storeCover(
                $request->file('image'),
                'menu/cards',
                1600,
                900
            );

            $row->path = $path;
        }

        $row->save();

        $to = $data['redirect_to'] ?? url()->previous();
        return redirect($to)->with('success', 'Card actualizado.');
    }

    private function decodeToken(string $token): ?string
    {
        // base64url -> base64
        $b64 = strtr($token, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        $decoded = base64_decode($b64, true);
        return $decoded !== false ? $decoded : null;
    }
}
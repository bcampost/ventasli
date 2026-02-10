<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MenuCardImage;

class MenuCardController extends Controller
{
    /**
     * PUT /admin/menu-cards
     * Body:
     * - key (string) requerido
     * - title (nullable)
     * - description (nullable)
     * - image (nullable file)
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'key'         => ['required', 'string', 'max:255'],
            'title'       => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        $key = trim($data['key']);

        // Upsert por key
        $row = MenuCardImage::query()->firstOrNew(['key' => $key]);

        $row->title = $data['title'] ?? null;
        $row->description = $data['description'] ?? null;

        if ($request->hasFile('image')) {
            // borra anterior si existe
            if (!empty($row->path) && Storage::disk('public')->exists($row->path)) {
                Storage::disk('public')->delete($row->path);
            }

            $path = $request->file('image')->store('menu-cards', 'public');
            $row->path = $path;
        }

        $row->save();

        return back()->with('ok', 'Tarjeta actualizada.');
    }
}
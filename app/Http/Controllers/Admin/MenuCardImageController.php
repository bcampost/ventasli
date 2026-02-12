<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCardImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuCardImageController extends Controller
{
    private function decodeToken(string $token): string
    {
        $b64 = strtr($token, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        $decoded = base64_decode($b64, true);
        return is_string($decoded) ? $decoded : '';
    }

    public function index()
    {
        $items = MenuCardImage::query()
            ->orderBy('key')
            ->paginate(50);

        return view('admin.menu-cards.index', compact('items'));
    }

    public function sync()
    {
        // Si tú ya tenías lógica de sync, déjala.
        // Aquí lo dejo “no-op” para no romper.
        return redirect()->back()->with('ok', 'Sync ejecutado.');
    }

    public function edit(string $token)
    {
        $key = $this->decodeToken($token);
        abort_if(!$key, 404);

        $row = MenuCardImage::query()->where('key', $key)->first();
        if (!$row) {
            $row = MenuCardImage::create([
                'key' => $key,
                'title' => null,
                'description' => null,
                'path' => null,
            ]);
        }

        return view('admin.menu-cards.edit', compact('row', 'token', 'key'));
    }

    public function update(Request $request, string $token)
    {
        $key = $this->decodeToken($token);
        abort_if(!$key, 404);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $row = MenuCardImage::query()->where('key', $key)->first();
        if (!$row) {
            $row = new MenuCardImage();
            $row->key = $key;
        }

        $row->title = $data['title'] ?? null;
        $row->description = $data['description'] ?? null;

        if ($request->hasFile('image')) {
            // borra anterior si existe
            if ($row->path) {
                Storage::disk('public')->delete($row->path);
            }

            $path = $request->file('image')->store('menu_cards', 'public');
            $row->path = $path;
        }

        $row->save();

        $to = $data['redirect_to'] ?? null;
        if ($to) return redirect($to)->with('ok', 'Tarjeta actualizada.');

        return redirect()->back()->with('ok', 'Tarjeta actualizada.');
    }

    public function destroy(Request $request, string $token)
    {
        $key = $this->decodeToken($token);
        abort_if(!$key, 404);

        $to = $request->input('redirect_to');

        $row = MenuCardImage::query()->where('key', $key)->first();
        if ($row) {
            if ($row->path) Storage::disk('public')->delete($row->path);
            $row->delete();
        }

        if ($to) return redirect($to)->with('ok', 'Tarjeta eliminada.');
        return redirect()->back()->with('ok', 'Tarjeta eliminada.');
    }
}
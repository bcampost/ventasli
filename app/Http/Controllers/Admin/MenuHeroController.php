<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuProductHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuHeroController extends Controller
{
    public function edit(Request $request, string $token)
    {
        $key = $this->decodeToken($token);
        if (!$key) {
            return back()->with('error', 'Token inválido.');
        }

        $hero = MenuProductHero::query()->firstOrCreate(
            ['key' => $key],
            ['title' => null, 'description' => null, 'images' => []]
        );

        // normaliza images como array
        $images = is_array($hero->images) ? $hero->images : (json_decode((string)$hero->images, true) ?: []);
        $hero->images = $images;

        return view('admin.menu-hero.edit', [
            'hero'       => $hero,
            'key'        => $key,
            'token'      => $token,
            'redirectTo' => $request->query('redirect_to', url()->previous()),
        ]);
    }

    public function update(Request $request, string $token)
    {
        $key = $this->decodeToken($token);
        if (!$key) {
            return back()->with('error', 'Token inválido.');
        }

        $data = $request->validate([
            'title'       => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images'      => ['nullable'],
            'images.*'    => ['image', 'max:6144'], // 6MB c/u
            'remove'      => ['nullable', 'array'],
            'remove.*'    => ['string'],
            'redirect_to' => ['nullable', 'string'],
        ]);

        $hero = MenuProductHero::query()->firstOrCreate(
            ['key' => $key],
            ['title' => null, 'description' => null, 'images' => []]
        );

        $current = is_array($hero->images) ? $hero->images : (json_decode((string)$hero->images, true) ?: []);

        // 1) quitar imágenes marcadas
        $toRemove = collect($data['remove'] ?? [])->filter()->values()->all();
        if (!empty($toRemove)) {
            foreach ($toRemove as $p) {
                // seguridad: solo permite borrar dentro de public/
                $p = ltrim($p, '/');
                if (Str::startsWith($p, ['menu/heroes/'])) {
                    if (Storage::disk('public')->exists($p)) {
                        Storage::disk('public')->delete($p);
                    }
                }
            }
            $current = array_values(array_filter($current, fn($p) => !in_array($p, $toRemove, true)));
        }

        // 2) subir nuevas imágenes (sin webp, sin Intervention)
        if ($request->hasFile('images')) {
            foreach ((array)$request->file('images') as $file) {
                if (!$file) continue;

                $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                if (!in_array($ext, ['jpg','jpeg','png','webp','gif','bmp','avif'], true)) {
                    $ext = 'jpg';
                }

                $name = 'hero_' . now()->format('Ymd_His') . '_' . Str::random(10) . '.' . $ext;
                $path = 'menu/heroes/' . $name;

                Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
                $current[] = $path;
            }
        }

        // 3) guardar texto
        $hero->title = $data['title'] ?? null;
        $hero->description = $data['description'] ?? null;
        $hero->images = array_values($current);
        $hero->save();

        $to = $data['redirect_to'] ?? url()->previous();
        return redirect($to)->with('success', 'Hero actualizado.');
    }

    private function decodeToken(string $token): ?string
    {
        $b64 = strtr($token, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        $decoded = base64_decode($b64, true);
        return $decoded !== false ? $decoded : null;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCardImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuCardController extends Controller
{
    private function decodeToken(string $token): string
    {
        $b64 = strtr($token, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        return (string) base64_decode($b64);
    }

    public function update(Request $request, string $token)
    {
        $key = $this->decodeToken($token);

        $data = $request->validate([
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'image' => ['nullable','image','max:5120'],
        ]);

        $row = MenuCardImage::firstOrCreate(['key' => $key]);

        $row->title = $data['title'] ?? null;
        $row->description = $data['description'] ?? null;

        if ($request->hasFile('image')) {

            if ($row->path) {
                Storage::disk('public')->delete($row->path);
            }

            $path = $request->file('image')->store('menu-cards', 'public');
            $row->path = $path;
        }

        $row->save();

        return back()->with('ok', 'Imagen actualizada');
    }
}
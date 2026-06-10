<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TrainingMediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200', // 50MB
                'mimetypes:video/mp4,video/webm',
            ],
        ], [
            'file.required' => 'Selecciona un archivo.',
            'file.mimetypes' => 'Solo se permiten videos MP4 o WEBM.',
            'file.max' => 'El archivo es muy grande. Máximo 50MB.',
        ]);

        $path = $request->file('file')->store('capacitaciones', 'public');

        return response()->json([
            'ok' => true,
            // URL pública (storage)
            'url' => asset('storage/' . $path),
            // útil si quieres guardar solo relativo
            'relative' => '/storage/' . $path,
        ]);
    }
}
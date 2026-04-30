<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Slide::query()
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return view('comunicados.index', compact('comunicados'));
    }
}
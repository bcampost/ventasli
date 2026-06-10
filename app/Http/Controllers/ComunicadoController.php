<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Slide::query()
            ->orderByDesc('created_at')
            ->get();

        return view('comunicados.index', compact('comunicados'));
    }
}
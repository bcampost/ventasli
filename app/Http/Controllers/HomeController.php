<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class HomeController extends Controller
{
    public function index()
    {
        $nowMx = now('America/Mexico_City');

        Slide::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $nowMx)
            ->update(['is_active' => false]);

        $slides = Slide::where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('home', compact('slides'));
    }
}
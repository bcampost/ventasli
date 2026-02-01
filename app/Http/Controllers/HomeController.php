<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('slides'));
    }
}
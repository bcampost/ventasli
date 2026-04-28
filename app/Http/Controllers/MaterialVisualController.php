<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialVisualController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'renders');

        $allowedTabs = ['catalogos', 'renders', 'fotos', 'videos', 'historias'];

        if (!in_array($tab, $allowedTabs, true)) {
            $tab = 'renders';
        }

        return view('material-visual.index', [
            'initialTab' => $tab,
        ]);
    }
}
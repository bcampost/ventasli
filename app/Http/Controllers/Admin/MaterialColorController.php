<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialColor;
use Illuminate\Http\Request;

class MaterialColorController extends Controller
{

public function destroy(\App\Models\MaterialColor $material_color)
{
    \Illuminate\Support\Facades\DB::table('menu_product_material_color')
        ->where('material_color_id', $material_color->id)
        ->delete();

    $material_color->delete();

    return redirect()->back()->with('success', 'Color eliminado.');
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:acero,laminado'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $name = trim($data['name']);

        MaterialColor::firstOrCreate(
            [
                'type' => $data['type'],
                'name' => $name,
            ],
            [
                'sort' => 0,
                'is_active' => true,
            ]
        );

        return response()->json(['ok' => true]);
    }
}
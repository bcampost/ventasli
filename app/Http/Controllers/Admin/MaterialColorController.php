<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialColor;
use Illuminate\Http\Request;

class MaterialColorController extends Controller
{
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
<?php

namespace App\Http\Controllers;

use App\Models\MaterialVisualItem;
use Illuminate\Http\Request;

class MaterialVisualController extends Controller
{
    public function index(Request $request)
    {
        $initialTab = $request->get('tab', 'renders');

        $items = MaterialVisualItem::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('title')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'section' => $item->section,
                    'parent_key' => $item->parent_key,
                    'title' => $item->title,
                    'description' => $item->description,
                    'type' => $item->type,
                    'file_url' => $item->fileUrl(),
                    'thumb_url' => $item->thumbUrl(),
                    'sort' => $item->sort,
                ];
            })
            ->values();

        return view('material-visual.index', [
            'initialTab' => $initialTab,
            'items' => $items,
        ]);
    }
}
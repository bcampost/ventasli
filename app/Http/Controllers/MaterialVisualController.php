<?php

namespace App\Http\Controllers;

use App\Models\MaterialVisualItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialVisualController extends Controller
{

    private function itemPayload(MaterialVisualItem $item): array
    {
        return [
            'id' => $item->id,
            'section' => $item->section,
            'parent_key' => $item->parent_key,
            'title' => $item->title,
            'description' => $item->description,
            'type' => $item->type,
            'file_url' => $item->external_url ?: ($item->file_path ? asset('storage/' . ltrim($item->file_path, '/')) : null),
            'thumb_url' => $item->thumb_path ? asset('storage/' . ltrim($item->thumb_path, '/')) : null,
            'sort' => $item->sort,
        ];
    }
    public function index(Request $request)
    {
        $initialTab = $request->get('tab', 'renders');

        $items = MaterialVisualItem::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('title')
            ->get()
            ->map(function (MaterialVisualItem $item) {
                $fileUrl = null;

                if (!empty($item->external_url)) {
                    $fileUrl = $item->external_url;
                } elseif (!empty($item->file_path)) {
                    $fileUrl = asset('storage/' . ltrim($item->file_path, '/'));
                }

                $thumbUrl = null;

                if (!empty($item->thumb_path)) {
                    $thumbUrl = asset('storage/' . ltrim($item->thumb_path, '/'));
                } else {
                    $thumbUrl = $fileUrl;
                }

                return [
                    'id' => $item->id,
                    'section' => $item->section,
                    'parent_key' => $item->parent_key,
                    'title' => $item->title,
                    'description' => $item->description,
                    'type' => $item->type,
                    'file_url' => $fileUrl,
                    'thumb_url' => $thumbUrl,
                    'sort' => $item->sort,
                ];
            })
            ->values();

        return view('material-visual.index', [
            'initialTab' => $initialTab,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section' => ['required', 'string', 'max:50'],
            'parent_key' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:folder,image,video,pdf,link,file'],
            'external_url' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:51200'],      // 50 MB
            'thumbnail' => ['nullable', 'image', 'max:2048'], // 2 MB
            'sort' => ['nullable', 'integer', 'min:0'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $item = new MaterialVisualItem();
        $item->section = $data['section'];
        $item->parent_key = $data['parent_key'] ?? null;
        $item->title = $data['title'];
        $item->description = $data['description'] ?? null;
        $item->type = $data['type'];
        $item->external_url = $data['external_url'] ?? null;
        $item->sort = (int) ($data['sort'] ?? 0);
        $item->is_active = true;

        if ($request->hasFile('file')) {
            $item->file_path = $request->file('file')->store('material-visual/files', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $item->thumb_path = $request->file('thumbnail')->store('material-visual/thumbs', 'public');
        }

        $item->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Elemento agregado.',
                'item' => $this->itemPayload($item),
            ]);
        }

        return redirect($data['redirect_to'] ?? route('material-visual.index'))
            ->with('success', 'Elemento agregado.');
    }

    public function update(Request $request, MaterialVisualItem $item)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:folder,image,video,pdf,link,file'],
            'external_url' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:102400'],
            'thumbnail' => ['nullable', 'image', 'max:10240'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
        ]);

        $item->title = $data['title'];
        $item->description = $data['description'] ?? null;
        $item->type = $data['type'];
        $item->external_url = $data['external_url'] ?? null;
        $item->sort = (int) ($data['sort'] ?? 0);

        if ($request->hasFile('file')) {
            if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }

            $item->file_path = $request->file('file')->store('material-visual/files', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($item->thumb_path && Storage::disk('public')->exists($item->thumb_path)) {
                Storage::disk('public')->delete($item->thumb_path);
            }

            $item->thumb_path = $request->file('thumbnail')->store('material-visual/thumbs', 'public');
        }

        $item->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Elemento actualizado.',
                'item' => $this->itemPayload($item),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Elemento actualizado.',
                'item' => $this->itemPayload($item),
            ]);
        }

        return redirect($data['redirect_to'] ?? route('material-visual.index'))
            ->with('success', 'Elemento actualizado.');
    }

    public function destroy(Request $request, MaterialVisualItem $item)
    {
        if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
            Storage::disk('public')->delete($item->file_path);
        }

        if ($item->thumb_path && Storage::disk('public')->exists($item->thumb_path)) {
            Storage::disk('public')->delete($item->thumb_path);
        }

        $item->delete();

        return redirect($request->input('redirect_to', route('material-visual.index')))
            ->with('success', 'Elemento eliminado.');
    }
}
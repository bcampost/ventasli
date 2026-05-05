<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:4096'],
            'link'  => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $path = $request->file('image')->store('slides', 'public');

        Slide::create([
            'title' => $data['title'] ?? null,
            'image_path' => $path,
            'link' => $data['link'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ]);

        return redirect()->route('admin.slides.index')->with('ok', 'Slide creado');
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'link'  => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        if ($request->hasFile('image')) {
            if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }

            $slide->image_path = $request->file('image')->store('slides', 'public');
        }

        $slide->title = $data['title'] ?? null;
        $slide->link = $data['link'] ?? null;
        $slide->is_active = $request->boolean('is_active');
        $slide->expires_at = $request->filled('expires_at') ? $request->input('expires_at') : null;
        $slide->sort_order = (int)($data['sort_order'] ?? 0);
        $slide->save();

        return redirect()->route('admin.slides.index')->with('ok', 'Slide actualizado');
    }

    public function destroy(Slide $slide)
    {
        if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        return redirect()->route('admin.slides.index')->with('ok', 'Slide eliminado');
    }
}
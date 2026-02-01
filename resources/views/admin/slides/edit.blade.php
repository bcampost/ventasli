@extends('layouts.app')

@section('content')
  <div class="mb-4">
    <h1 class="text-xl font-semibold">Editar slide</h1>
    <p class="text-sm text-gray-500">Actualiza datos o reemplaza la imagen.</p>
  </div>

  <form method="POST" action="{{ route('admin.slides.update', $slide) }}" enctype="multipart/form-data"
        class="bg-white border rounded-2xl p-5 space-y-4 max-w-2xl">
    @csrf
    @method('PUT')

    <div>
      <label class="text-sm font-medium">Título (opcional)</label>
      <input name="title" value="{{ old('title', $slide->title) }}" class="w-full border rounded-lg p-2 mt-1" />
      @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="space-y-2">
      <label class="text-sm font-medium">Imagen (opcional)</label>
      <input type="file" name="image" class="w-full border rounded-lg p-2 mt-1" />
      @error('image') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror

      <div class="pt-2">
        <div class="text-xs text-gray-500 mb-2">Imagen actual:</div>
        <img class="w-full max-w-xl h-56 object-cover rounded-xl border" src="{{ asset('storage/'.$slide->image_path) }}" alt="preview">
      </div>
    </div>

    <div>
      <label class="text-sm font-medium">Link (opcional)</label>
      <input name="link" value="{{ old('link', $slide->link) }}" class="w-full border rounded-lg p-2 mt-1" placeholder="https://..." />
      @error('link') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-3">
      <input id="is_active" type="checkbox" name="is_active" value="1" {{ $slide->is_active ? 'checked' : '' }} class="rounded" />
      <label for="is_active" class="text-sm">Activo</label>
    </div>

    <div>
      <label class="text-sm font-medium">Orden</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}" class="w-full border rounded-lg p-2 mt-1" />
      @error('sort_order') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex gap-2">
      <button class="px-4 py-2 rounded bg-gray-900 text-white">Actualizar</button>
      <a href="{{ route('admin.slides.index') }}" class="px-4 py-2 rounded border">Volver</a>
    </div>
  </form>
@endsection
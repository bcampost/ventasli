@extends('layouts.app')

@section('content')
  <div class="mb-4">
    <h1 class="text-xl font-semibold">Nuevo slide</h1>
    <p class="text-sm text-gray-500">Sube una imagen para el slider de HOME.</p>
  </div>

  <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data"
        class="bg-white border rounded-2xl p-5 space-y-4 max-w-2xl">
    @csrf

    <div>
      <label class="text-sm font-medium">Título (opcional)</label>
      <input name="title" value="{{ old('title') }}" class="w-full border rounded-lg p-2 mt-1" />
      @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm font-medium">Imagen *</label>
      <input type="file" name="image" class="w-full border rounded-lg p-2 mt-1" required />
      <p class="text-xs text-gray-500 mt-1">Recomendado: 1600x600 aprox. Máx 4MB.</p>
      @error('image') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm font-medium">Link (opcional)</label>
      <input name="link" value="{{ old('link') }}" class="w-full border rounded-lg p-2 mt-1" placeholder="https://..." />
      @error('link') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-3">
      <input id="is_active" type="checkbox" name="is_active" value="1" checked class="rounded" />
      <label for="is_active" class="text-sm">Activo</label>
    </div>

    <div>
      <label class="text-sm font-medium">Orden</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border rounded-lg p-2 mt-1" />
      @error('sort_order') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex gap-2">
      <button class="px-4 py-2 rounded bg-gray-900 text-white">Guardar</button>
      <a href="{{ route('admin.slides.index') }}" class="px-4 py-2 rounded border">Cancelar</a>
    </div>
  </form>
@endsection
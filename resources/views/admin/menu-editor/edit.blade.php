@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-5">
    <div>
      <h1 class="text-xl font-semibold text-slate-900">Editar opción</h1>
      <p class="text-sm text-slate-600">{{ $node->key }}</p>
    </div>

    <a href="{{ route('admin.menu-editor.index') }}" class="text-sm px-3 py-2 rounded-lg border bg-white hover:bg-slate-50">
      Volver
    </a>
  </div>

  @if($errors->any())
    <div class="mb-4 rounded-lg border bg-rose-50 text-rose-800 px-4 py-3 text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-2xl border bg-white p-5 shadow-sm">
    <form method="POST" action="{{ route('admin.menu-editor.update', $node) }}" enctype="multipart/form-data" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="text-xs font-medium text-slate-700">Padre</label>
        <select name="parent_id" class="mt-1 w-full rounded-lg border-slate-200 text-sm">
          <option value="">— Nivel superior —</option>
          @foreach($allNodes as $n)
            @if($n->id !== $node->id)
              <option value="{{ $n->id }}" @selected($node->parent_id === $n->id)>{{ $n->key }} — {{ $n->label }}</option>
            @endif
          @endforeach
        </select>
      </div>

      <div>
        <label class="text-xs font-medium text-slate-700">Nombre</label>
        <input name="label" value="{{ old('label', $node->label) }}" class="mt-1 w-full rounded-lg border-slate-200 text-sm" required />
      </div>

      <div>
        <label class="text-xs font-medium text-slate-700">URL (si es recurso final)</label>
        <input name="url" value="{{ old('url', $node->url) }}" class="mt-1 w-full rounded-lg border-slate-200 text-sm" />
      </div>

      <div>
        <label class="text-xs font-medium text-slate-700">Título</label>
        <input name="title" value="{{ old('title', $node->title) }}" class="mt-1 w-full rounded-lg border-slate-200 text-sm" />
      </div>

      <div>
        <label class="text-xs font-medium text-slate-700">Descripción</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-slate-200 text-sm">{{ old('description', $node->description) }}</textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-slate-700">Orden</label>
          <input type="number" name="sort" value="{{ old('sort', $node->sort) }}" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm" />
        </div>

        <label class="inline-flex items-center gap-2 text-sm mt-6">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $node->is_active)) class="rounded border-slate-300">
          Activo
        </label>
      </div>

      <div>
        <label class="text-xs font-medium text-slate-700">Imagen</label>
        <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm" />

        @if($node->image_path)
          <div class="mt-2 flex items-center gap-3">
            <img src="{{ asset('storage/' . $node->image_path) }}" class="w-16 h-16 rounded-xl object-cover border" alt="">
            <label class="inline-flex items-center gap-2 text-sm">
              <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300">
              Quitar imagen actual
            </label>
          </div>
        @endif
      </div>

      <button class="w-full rounded-lg bg-slate-900 text-white text-sm py-2 hover:bg-slate-800">
        Guardar cambios
      </button>
    </form>
  </div>
</div>
@endsection
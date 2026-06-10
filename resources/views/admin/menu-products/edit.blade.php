@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto px-4 py-6">

    <div class="mb-5">
      <h1 class="text-2xl font-extrabold text-slate-900">Editar producto</h1>
      <p class="text-sm text-slate-600 mt-1">Sección: <span class="font-mono">{{ $menuKey }}</span></p>
    </div>

    @if(session('status'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.menu-products.update', $item->id) }}" enctype="multipart/form-data"
          class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
      @csrf
      @method('PUT')

      <div class="flex gap-4 items-center">
        <div class="w-24 h-24 rounded-2xl bg-slate-50 overflow-hidden">
          @if($item->image_path)
            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover" alt="">
          @endif
        </div>

        <div class="flex-1">
          <label class="text-sm font-semibold text-slate-900">Cambiar imagen</label>
          <input type="file" name="image"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />

          <label class="mt-2 inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300">
            Quitar imagen
          </label>
        </div>
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">Título</label>
        <input name="title" value="{{ old('title', $item->title) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required />
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">Descripción</label>
        <textarea name="description" rows="4"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description', $item->description) }}</textarea>
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">URL (opcional)</label>
        <input name="url" value="{{ old('url', $item->url) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">Orden</label>
        <input type="number" name="sort" value="{{ old('sort', $item->sort) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <a href="{{ route('admin.menu-products.index', ['key' => $menuKey]) }}"
           class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
          Volver
        </a>
        <button class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
          Guardar cambios
        </button>
      </div>
    </form>

  </div>
@endsection
@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto px-4 py-6">

    <div class="mb-5">
      <h1 class="text-2xl font-extrabold text-slate-900">Agregar producto</h1>
      <p class="text-sm text-slate-600 mt-1">Sección: <span class="font-mono">{{ $menuKey }}</span></p>
    </div>

    @if($errors->any())
      <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.menu-products.store') }}" enctype="multipart/form-data"
          class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
      @csrf

      <input type="hidden" name="menu_key" value="{{ $menuKey }}"/>

      <div>
        <label class="text-sm font-semibold text-slate-900">Título</label>
        <input name="title" value="{{ old('title') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required />
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">Descripción</label>
        <textarea name="description" rows="4"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description') }}</textarea>
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-900">URL (opcional)</label>
        <input name="url" value="{{ old('url') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-900">Orden</label>
          <input type="number" name="sort" value="{{ old('sort', 0) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
        </div>

        <div>
          <label class="text-sm font-semibold text-slate-900">Imagen (opcional)</label>
          <input type="file" name="image"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
        </div>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <a href="{{ route('admin.menu-products.index', ['key' => $menuKey]) }}"
           class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
          Cancelar
        </a>
        <button class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
          Guardar
        </button>
      </div>
    </form>

  </div>
@endsection
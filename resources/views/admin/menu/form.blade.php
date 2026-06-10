@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">

  <div class="flex items-start justify-between gap-4 mb-5">
    <div>
      <h1 class="text-xl font-semibold text-slate-900">
        {{ $mode === 'create' ? 'Crear opción' : 'Editar opción' }}
      </h1>
      <p class="text-sm text-slate-600 mt-1">
        Define etiqueta, URL (opcional), orden y si es sub-opción.
      </p>
    </div>

    <a href="{{ route('admin.menu.index') }}"
       class="px-4 py-2 rounded-xl border text-sm hover:bg-slate-50">
      Volver
    </a>
  </div>

  @if($errors->any())
    <div class="mb-4 p-3 rounded-xl bg-rose-50 text-rose-800 text-sm border border-rose-100">
      <div class="font-medium mb-1">Revisa estos campos:</div>
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white border rounded-2xl shadow-sm p-5">
    <form method="POST"
          action="{{ $mode === 'create' ? route('admin.menu.store') : route('admin.menu.update', $node) }}">
      @csrf
      @if($mode === 'edit') @method('PUT') @endif

      <div class="grid grid-cols-12 gap-4">

        <div class="col-span-12">
          <label class="text-xs font-medium text-slate-700">Nombre (label)</label>
          <input name="label" value="{{ old('label', $node->label) }}"
                 class="mt-1 w-full px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-200"
                 placeholder="Ej: Escritorios" required>
        </div>

        <div class="col-span-12 md:col-span-6">
          <label class="text-xs font-medium text-slate-700">Slug (opcional)</label>
          <input name="slug" value="{{ old('slug', $node->slug) }}"
                 class="mt-1 w-full px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-200"
                 placeholder="Se genera automático si lo dejas vacío">
        </div>

        <div class="col-span-12 md:col-span-6">
          <label class="text-xs font-medium text-slate-700">Orden (sort)</label>
          <input type="number" name="sort" value="{{ old('sort', $node->sort ?? 0) }}"
                 class="mt-1 w-full px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-200"
                 min="0" max="9999">
        </div>

        <div class="col-span-12">
          <label class="text-xs font-medium text-slate-700">URL (opcional, para opciones finales)</label>
          <input name="url" value="{{ old('url', $node->url) }}"
                 class="mt-1 w-full px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-200"
                 placeholder="https://... o /ruta">
          <div class="text-[11px] text-slate-500 mt-1">Si la opción tiene hijos, normalmente no requiere URL.</div>
        </div>

        <div class="col-span-12">
          <label class="text-xs font-medium text-slate-700">Descripción (opcional)</label>
          <textarea name="description" rows="3"
                    class="mt-1 w-full px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    placeholder="Texto breve que se puede usar en cards/vistas">{{ old('description', $node->description) }}</textarea>
        </div>

        <div class="col-span-12 md:col-span-7">
          <label class="text-xs font-medium text-slate-700">Padre (opcional)</label>
          <select name="parent_id"
                  class="mt-1 w-full px-3 py-2 rounded-xl border bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">— Raíz —</option>
            @foreach($flat as $p)
              <option value="{{ $p->id }}" @selected((string)old('parent_id', $node->parent_id) === (string)$p->id)>
                {{ $p->label }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-span-12 md:col-span-5 flex items-end">
          <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border w-full justify-center cursor-pointer hover:bg-slate-50">
            <input type="checkbox" name="is_active" value="1" class="rounded"
                   @checked(old('is_active', $node->is_active) ? true : false)>
            <span class="text-sm text-slate-800">Activa</span>
          </label>
        </div>

      </div>

      <div class="mt-5 flex items-center justify-end gap-2">
        <a href="{{ route('admin.menu.index') }}"
           class="px-4 py-2 rounded-xl border text-sm hover:bg-slate-50">
          Cancelar
        </a>

        <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm hover:bg-slate-800">
          Guardar
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
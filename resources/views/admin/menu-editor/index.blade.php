@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl font-semibold text-slate-900">Editar menú superior</h1>
      <p class="text-sm text-slate-600">Las opciones se reflejan automáticamente en el menú superior.</p>
    </div>

    <a href="{{ route('home') }}" class="text-sm px-3 py-2 rounded-lg border bg-white hover:bg-slate-50">
      Volver
    </a>
  </div>

  @if(session('status'))
    <div class="mb-4 rounded-lg border bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
      {{ session('status') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 rounded-lg border bg-rose-50 text-rose-800 px-4 py-3 text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Crear opción --}}
    <div class="lg:col-span-1">
      <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <h2 class="font-semibold text-slate-900 mb-3">Agregar opción</h2>

        <form method="POST" action="{{ route('admin.menu-editor.store') }}" enctype="multipart/form-data" class="space-y-3">
          @csrf

          <div>
            <label class="text-xs font-medium text-slate-700">Padre (opcional)</label>
            <select name="parent_id" class="mt-1 w-full rounded-lg border-slate-200 text-sm">
              <option value="">— Nivel superior —</option>
              @foreach($allNodes as $n)
                <option value="{{ $n->id }}">{{ $n->key }} — {{ $n->label }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-slate-700">Nombre</label>
            <input name="label" class="mt-1 w-full rounded-lg border-slate-200 text-sm" required />
          </div>

          <div>
            <label class="text-xs font-medium text-slate-700">URL (si es recurso final)</label>
            <input name="url" class="mt-1 w-full rounded-lg border-slate-200 text-sm" placeholder="https://..." />
          </div>

          <div>
            <label class="text-xs font-medium text-slate-700">Título (opcional)</label>
            <input name="title" class="mt-1 w-full rounded-lg border-slate-200 text-sm" />
          </div>

          <div>
            <label class="text-xs font-medium text-slate-700">Descripción (opcional)</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm"></textarea>
          </div>

          <div>
            <label class="text-xs font-medium text-slate-700">Imagen (opcional)</label>
            <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm" />
          </div>

          <div class="flex items-center gap-3">
            <div class="flex-1">
              <label class="text-xs font-medium text-slate-700">Orden</label>
              <input type="number" name="sort" value="0" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm" />
            </div>

            <label class="inline-flex items-center gap-2 text-sm mt-6">
              <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300">
              Activo
            </label>
          </div>

          <button class="w-full rounded-lg bg-slate-900 text-white text-sm py-2 hover:bg-slate-800">
            Crear
          </button>
        </form>
      </div>
    </div>

    {{-- Árbol --}}
    <div class="lg:col-span-2">
      <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <h2 class="font-semibold text-slate-900 mb-3">Estructura actual</h2>

        <div class="space-y-3">
          @foreach($roots as $root)
            @include('admin.menu-editor.partials.node-row', ['node' => $root, 'level' => 0])
          @endforeach
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
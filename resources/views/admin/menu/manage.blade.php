{{-- resources/views/admin/menu/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="w-full py-6">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="bg-white rounded-2xl shadow p-5">
      <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="min-w-0">
          <h1 class="text-2xl font-semibold truncate">Editar menú: {{ $node->label }}</h1>
          <p class="text-gray-600 mt-1">
            Aquí puedes crear sub-opciones y asignarles PDF (se reflejan en el menú automáticamente).
          </p>
          <div class="text-sm text-gray-500 mt-2">
            Nodo ID: <code>{{ $node->id }}</code>
            · Key: <code>{{ $node->key ?: '(vacío)' }}</code>
            · URL: <code>{{ $node->url ?: '(vacío)' }}</code>
          </div>
        </div>

        @if(session('status'))
          <div class="px-4 py-2 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('status') }}
          </div>
        @endif
      </div>

      {{-- ✅ Crear hijo --}}
      <div class="mt-6 border rounded-2xl p-4">
        <h2 class="font-semibold">Agregar nueva opción</h2>

        <form method="POST" action="{{ route('admin.menu.children.store', $node) }}" class="mt-3 flex gap-2 items-center flex-wrap">
          @csrf
          <input
            type="text"
            name="label"
            value="{{ old('label') }}"
            placeholder="Ej. Accesorios"
            class="w-full sm:flex-1 rounded-xl border-gray-300"
            required
          >
          <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold">
            Crear
          </button>
        </form>

        @error('label')
          <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- ✅ Hijos existentes --}}
      <div class="mt-6 space-y-4">
        @forelse($children as $child)
          @php
            $currentUrl = $child->url ? asset(ltrim($child->url, '/')) : null;
          @endphp

          <div class="border rounded-2xl p-4">
            <div class="flex items-start justify-between gap-4 flex-wrap">

              <div class="min-w-0">
                <div class="text-lg font-semibold truncate">{{ $child->label }}</div>

                <div class="text-sm text-gray-600 mt-1 break-words">
                  slug: <code>{{ $child->slug ?: '(vacío)' }}</code>
                  · key: <code>{{ $child->key ?: '(vacío)' }}</code>
                  · url: <code>{{ $child->url ?: '(vacío)' }}</code>
                </div>

                @if($currentUrl)
                  <div class="mt-2">
                    <a href="{{ $currentUrl }}" target="_blank" class="text-blue-700 underline text-sm">
                      Ver archivo actual
                    </a>
                  </div>
                @endif
              </div>

              <div class="flex-none w-full sm:w-auto space-y-3">

                {{-- ✅ Subir PDF --}}
                <form
                  method="POST"
                  action="{{ route('admin.menu.upload', $child) }}"
                  enctype="multipart/form-data"
                  class="flex items-center gap-2 flex-wrap"
                >
                  @csrf
                  <input type="file" name="pdf" accept="application/pdf" class="text-sm" required>
                  <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold">
                    Subir PDF
                  </button>
                </form>

                @error('pdf')
                  <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror

                {{-- ✅ Edit rápido (label/sort/url/is_active) --}}
                <form method="POST" action="{{ route('admin.menu.update', $child) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                  @csrf
                  @method('PUT')

                  <input
                    name="label"
                    value="{{ $child->label }}"
                    class="sm:col-span-4 rounded-xl border-gray-300 text-sm"
                    placeholder="Label"
                    required
                  />

                  <input
                    name="sort"
                    value="{{ (int)$child->sort }}"
                    class="sm:col-span-2 rounded-xl border-gray-300 text-sm"
                    placeholder="Sort"
                  />

                  <input
                    name="url"
                    value="{{ $child->url }}"
                    class="sm:col-span-4 rounded-xl border-gray-300 text-sm"
                    placeholder="url (opcional)"
                  />

                  <label class="sm:col-span-1 inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="rounded" {{ $child->is_active ? 'checked' : '' }}>
                    Activo
                  </label>

                  <button class="sm:col-span-1 px-3 py-2 rounded-xl bg-gray-100 border text-sm">
                    Guardar
                  </button>
                </form>

                {{-- ✅ Eliminar --}}
                <form method="POST" action="{{ route('admin.menu.destroy', $child) }}"
                      onsubmit="return confirm('¿Seguro que quieres eliminar esta opción y sus hijos?');">
                  @csrf
                  @method('DELETE')
                  <button class="px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    Eliminar
                  </button>
                </form>

              </div>
            </div>
          </div>

        @empty
          <div class="p-4 rounded-xl bg-gray-50 border text-gray-700">
            No hay opciones debajo de este nodo.
          </div>
        @endforelse
      </div>

      <div class="mt-6 text-sm text-gray-500">
        Tip: esta vista vive en <code>/admin/menu/{id}/manage</code> y está pensada para usarse desde el engrane del menú.
      </div>
    </div>

  </div>
</div>
@endsection
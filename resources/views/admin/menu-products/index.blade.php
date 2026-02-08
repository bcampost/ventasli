@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex items-start justify-between gap-4 mb-5">
      <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Productos de: <span class="font-mono text-sm">{{ $menuKey }}</span></h1>
        <p class="text-sm text-slate-600 mt-1">Crea, edita y elimina productos para esta sección del menú.</p>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('menu.section', $menuKey) }}"
           class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
          Ver sección
        </a>

        <a href="{{ route('admin.menu-products.create', ['key' => $menuKey]) }}"
           class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
          + Agregar
        </a>
      </div>
    </div>

    @if(session('status'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
        {{ session('status') }}
      </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-700">
          <tr>
            <th class="text-left px-4 py-3 font-semibold">Producto</th>
            <th class="text-left px-4 py-3 font-semibold">Orden</th>
            <th class="text-left px-4 py-3 font-semibold">URL</th>
            <th class="text-right px-4 py-3 font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($items as $item)
            <tr>
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-slate-50 overflow-hidden">
                    @if($item->image_path)
                      <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover" alt="">
                    @endif
                  </div>
                  <div>
                    <div class="font-semibold text-slate-900">{{ $item->title }}</div>
                    <div class="text-xs text-slate-600 line-clamp-1">{{ $item->description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 text-slate-700">{{ $item->sort }}</td>
              <td class="px-4 py-3 text-slate-700">
                @if($item->url)
                  <a href="{{ $item->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-700">Abrir ↗</a>
                @else
                  —
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.menu-products.edit', $item->id) }}"
                     class="text-xs px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
                    Editar
                  </a>
                  <form method="POST" action="{{ route('admin.menu-products.destroy', $item->id) }}"
                        onsubmit="return confirm('¿Eliminar este producto?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs px-3 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50">
                      Eliminar
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-10 text-center text-slate-600">
                No hay productos todavía.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
@endsection
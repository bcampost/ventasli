@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex items-end justify-between gap-4 mb-5">
      <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Cards del Menú</h1>
        <p class="text-sm text-slate-600 mt-1">Edita imagen, título y descripción por cada key del menú.</p>
      </div>

      <div class="flex items-center gap-2">
        {{-- ✅ Botón para generar/sincronizar registros --}}
        <form method="POST" action="{{ route('admin.menu-cards.sync') }}">
          @csrf
          <button type="submit"
                  class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
            Generar cards (sync)
          </button>
        </form>

        <form method="GET" class="flex gap-2 items-center">
          <input
            name="q"
            value="{{ $q }}"
            placeholder="Buscar key..."
            class="w-64 max-w-[60vw] rounded-xl border border-slate-200 px-3 py-2 text-sm"
          />
          <button class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
            Buscar
          </button>
        </form>
      </div>
    </div>

    @if(session('status'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
        {{ session('status') }}
      </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 border-b border-slate-200 text-slate-700">
            <tr>
              <th class="text-left px-4 py-3 font-semibold">Key</th>
              <th class="text-left px-4 py-3 font-semibold">Título</th>
              <th class="text-left px-4 py-3 font-semibold">Descripción</th>
              <th class="text-right px-4 py-3 font-semibold">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @forelse($items as $item)
              <tr>
                <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $item->key }}</td>
                <td class="px-4 py-3 text-slate-900">{{ $item->title ?? '—' }}</td>
                <td class="px-4 py-3 text-slate-600 max-w-[520px] truncate">{{ $item->description ?? '—' }}</td>
                <td class="px-4 py-3">
                  <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.menu-cards.edit', $item->key) }}"
                       class="text-xs px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
                      Editar
                    </a>

                    <form method="POST" action="{{ route('admin.menu-cards.destroy', $item->key) }}"
                          onsubmit="return confirm('¿Eliminar este registro?')">
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
                <td colspan="4" class="px-4 py-8 text-center text-slate-600">
                  No hay registros. Da click en <b>Generar cards (sync)</b> para crear las keys desde <code>config/menu.php</code>.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $items->links() }}
    </div>

  </div>
@endsection
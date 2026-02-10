@php
  // $node esperado como array:
  // ['id'=>, 'label'=>, 'url'=>, 'children'=>[]]
  $children = $node['children'] ?? [];
  $hasChildren = is_array($children) && count($children) > 0;
@endphp

<details class="group rounded-2xl border border-slate-200 bg-white/70 shadow-sm hover:shadow-md transition overflow-hidden">
  <summary class="cursor-pointer select-none list-none">
    <div class="flex items-center justify-between gap-4 px-4 py-3">
      <div class="flex items-center gap-3 min-w-0">
        <div class="h-9 w-9 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
          {{ mb_substr($node['label'] ?? '', 0, 2) }}
        </div>

        <div class="min-w-0">
          <div class="flex items-center gap-2">
            <span class="font-semibold text-slate-900 truncate">
              {{ $node['label'] ?? 'Sin título' }}
            </span>

            @if(!empty($node['url']))
              <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                link
              </span>
            @endif
          </div>

          <div class="text-xs text-slate-500 truncate">
            ID: {{ $node['id'] ?? '—' }}
            @if(!empty($node['url']))
              • {{ $node['url'] }}
            @endif
            @if($hasChildren)
              • {{ count($children) }} sub-opciones
            @endif
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2 shrink-0">
        <span class="text-xs text-slate-500 hidden sm:inline">
          {{ $hasChildren ? 'Desplegar' : 'Sin hijos' }}
        </span>

        <svg class="h-4 w-4 text-slate-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>
  </summary>

  <div class="px-4 pb-4 pt-2 bg-white">
    {{-- Acciones --}}
    <div class="flex flex-wrap items-center gap-2 pb-3 border-b border-slate-200">
      <a href="{{ route('admin.menu-nodes.edit', $node['id']) }}"
         class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-900">
        ✏️ Editar
      </a>

      <a href="{{ route('admin.menu-nodes.create', ['parent_id' => $node['id']]) }}"
         class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
        ➕ Agregar sub-opción
      </a>

      <form method="POST" action="{{ route('admin.menu-nodes.destroy', $node['id']) }}"
            onsubmit="return confirm('¿Seguro que quieres eliminar \"{{ $node['label'] ?? '' }}\" y sus sub-opciones?');">
        @csrf
        @method('DELETE')
        <button class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50">
          🗑️ Eliminar
        </button>
      </form>
    </div>

    {{-- Hijos --}}
    @if($hasChildren)
      <div class="mt-4 space-y-3 pl-3 border-l-2 border-slate-100">
        @foreach($children as $child)
          @include('admin.menu._node', ['node' => $child])
        @endforeach
      </div>
    @else
      <div class="mt-4 text-sm text-slate-600">
        No hay sub-opciones. Puedes agregar una con el botón de arriba.
      </div>
    @endif
  </div>
</details>
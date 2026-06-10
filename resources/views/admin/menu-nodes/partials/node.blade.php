@php
  $indent = min($level, 4);
  $padLeft = 12 + ($indent * 18);
  $isCategory = empty($node->url);
@endphp

<div class="pro-card rounded-2xl border border-slate-200 bg-white overflow-hidden">
  <div class="relative">
    <div class="flex items-stretch gap-4 px-5 py-4" style="padding-left: {{ $padLeft }}px;">
      <div class="w-1.5 rounded-full {{ $isCategory ? 'bg-indigo-500' : 'bg-emerald-500' }} self-stretch"></div>

      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-4">
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <div class="text-lg font-extrabold text-slate-900 truncate">{{ $node->label }}</div>

              @if($isCategory)
                <span class="chip inline-flex items-center rounded-full bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                  Categoría
                </span>
              @else
                <span class="chip inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                  Link
                </span>
              @endif

              @if(!$node->is_active)
                <span class="chip inline-flex items-center rounded-full bg-rose-50 border border-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">
                  Inactivo
                </span>
              @endif
            </div>

            <div class="text-sm text-slate-500 mt-1">
              Pasa el mouse para ver detalles • Orden: <span class="font-semibold text-slate-700">{{ (int)$node->sort }}</span>
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <button
              type="button"
              class="rounded-xl bg-slate-900 px-3.5 py-2 text-sm font-semibold text-white hover:opacity-95"
              onclick="openEditModal({{ $node->id }}, @js($node->label), @js($node->url), {{ (int)$node->sort }}, {{ $node->is_active ? 'true' : 'false' }})"
            >
              Editar
            </button>

            <button
              type="button"
              class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50"
              onclick="openCreateModal({{ $node->id }})"
            >
              + Submenú
            </button>

            {{-- ✅ FIX: PARAM CORRECTO menuNode --}}
            <form method="POST" action="{{ route('admin.menu.destroy', ['menuNode' => $node->id]) }}"
                  onsubmit="return confirm('¿Eliminar esta opción y todos sus submenús?');">
              @csrf
              @method('DELETE')
              <button class="rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                Eliminar
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- reveal panel --}}
    <div class="reveal absolute inset-y-0 right-0 w-[52%] bg-gradient-to-l from-slate-900 to-slate-800 text-white">
      <div class="h-full p-5 flex flex-col justify-center">
        <div class="text-xs font-semibold uppercase tracking-wider text-white/70">Detalles</div>

        <div class="mt-2 text-sm">
          <div class="flex items-center justify-between gap-3">
            <div class="text-white/80">URL</div>
            <div class="font-semibold text-right break-all">{{ $node->url ?: '— (categoría)' }}</div>
          </div>

          <div class="mt-2 flex items-center justify-between gap-3">
            <div class="text-white/80">Activo</div>
            <div class="font-semibold">{{ $node->is_active ? 'Sí' : 'No' }}</div>
          </div>

          <div class="mt-2 flex items-center justify-between gap-3">
            <div class="text-white/80">ID</div>
            <div class="font-semibold">{{ $node->id }}</div>
          </div>
        </div>

        <div class="mt-3 text-xs text-white/60">
          Tip: deja URL vacía para que funcione como categoría con submenús.
        </div>
      </div>
    </div>
  </div>

  @php $children = $node->childrenRecursive ?? collect(); @endphp
  @if($children->count())
    <div class="border-t border-slate-200 bg-slate-50/40 px-5 py-4">
      <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">
        Submenús ({{ $children->count() }})
      </div>

      <div class="space-y-3">
        @foreach($children as $child)
          @include('admin.menu-nodes.partials.node', ['node' => $child, 'level' => $level + 1])
        @endforeach
      </div>
    </div>
  @endif
</div>

<style>
  /* reveal effect */
  .pro-card { position: relative; }
  .pro-card .reveal{
    transform: translateX(102%);
    opacity: 0;
    transition: transform .35s ease, opacity .25s ease;
    pointer-events:none;
  }
  .pro-card:hover .reveal{
    transform: translateX(0);
    opacity: 1;
  }
  .pro-card .reveal::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(90deg, rgba(255,255,255,.0), rgba(255,255,255,.06));
    opacity:.6;
    pointer-events:none;
  }
</style>
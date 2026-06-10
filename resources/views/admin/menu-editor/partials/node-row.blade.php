@php
  $pad = $level * 18;
@endphp

<div class="flex items-start justify-between gap-4 rounded-xl border border-slate-100 px-3 py-2">
  <div class="min-w-0" style="padding-left: {{ $pad }}px;">
    <div class="flex items-center gap-2">
      <span class="font-medium text-slate-900 text-sm truncate">{{ $node->label }}</span>
      <span class="text-[11px] text-slate-500 truncate">({{ $node->key }})</span>
      @if(!$node->is_active)
        <span class="text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600">Inactivo</span>
      @endif
    </div>

    @if($node->url)
      <div class="text-xs text-slate-600 truncate">URL: {{ $node->url }}</div>
    @endif
  </div>

  <div class="flex items-center gap-2 shrink-0">
    <a href="{{ route('admin.menu-editor.edit', $node) }}" class="text-xs px-2 py-1 rounded-lg border bg-white hover:bg-slate-50">
      Editar
    </a>

    <form method="POST" action="{{ route('admin.menu-editor.destroy', $node) }}" onsubmit="return confirm('¿Eliminar esta opción y todos sus hijos?')">
      @csrf
      @method('DELETE')
      <button class="text-xs px-2 py-1 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100">
        Eliminar
      </button>
    </form>
  </div>
</div>

@if($node->children && $node->children->count())
  <div class="space-y-3">
    @foreach($node->children as $child)
      @include('admin.menu-editor.partials.node-row', ['node' => $child, 'level' => $level + 1])
    @endforeach
  </div>
@endif
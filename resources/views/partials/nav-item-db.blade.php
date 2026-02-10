@php
  $hasChildren = $node->children && $node->children->count() > 0;
  $href = $node->url ?: route('menu.section', [$node->key]);
@endphp

<div class="relative group/sub">
  <a
    href="{{ $href }}"
    class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 transition"
  >
    <span class="text-sm text-slate-900">{{ $node->label }}</span>

    @if($hasChildren)
      <span class="text-xs text-slate-500">▶</span>
    @endif
  </a>

  @if($hasChildren)
    <div class="absolute left-full top-0 hidden group-hover/sub:block min-w-[280px] bg-white border shadow-lg rounded-xl p-2 z-50">
      @foreach($node->children as $child)
        @include('partials.nav-item-db', ['node' => $child])
      @endforeach
    </div>
  @endif
</div>
@php
  $label = is_array($node) ? ($node['label'] ?? '') : ($node->label ?? '');
  $url   = is_array($node) ? ($node['url'] ?? null) : ($node->url ?? null);

  $children = is_array($node) ? ($node['children'] ?? []) : ($node->children ?? []);
  $hasChildren = is_array($children) && count($children) > 0;
@endphp

<div class="relative group/sub">
  <a
    href="{{ $url ?: '#' }}"
    class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-800"
  >
    <span class="text-sm">{{ $label }}</span>

    @if($hasChildren)
      <span class="text-xs text-slate-500">▶</span>
    @endif
  </a>

  @if($hasChildren)
    <div class="absolute left-full top-0 hidden group-hover/sub:block pl-2 z-50">
      <div class="min-w-[280px] bg-white border shadow-xl rounded-2xl p-2">
        @foreach($children as $child)
          @include('partials.nav-item', ['node' => $child])
        @endforeach
      </div>
    </div>
  @endif
</div>
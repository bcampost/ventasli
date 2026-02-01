@php
$hasChildren = isset($node['children']) && is_array($node['children']);
$url = $node['url'] ?? '#';
@endphp

<div class="relative group/sub">
  <a href="{{ $url }}"
     class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg hover:bg-gray-100">
    <span class="text-sm">{{ $node['label'] }}</span>
    @if($hasChildren)
      <span class="text-xs text-gray-500">▶</span>
    @endif
  </a>

  @if($hasChildren)
    <div class="absolute left-full top-0 hidden group-hover/sub:block min-w-[280px] bg-white border shadow-lg rounded-xl p-2 z-50">
      @foreach($node['children'] as $child)
        @include('partials.nav-item', ['node' => $child])
      @endforeach
    </div>
  @endif
</div>
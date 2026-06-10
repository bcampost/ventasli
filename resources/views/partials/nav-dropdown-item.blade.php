{{-- resources/views/partials/nav-dropdown-item.blade.php --}}
@php
  // Espera:
  // $node = ['label'=>..., 'url'=>..., 'children'=>[...] ]
  // $level = 0,1,2...
  $hasChildren = !empty($node['children']) && is_array($node['children']) && count($node['children']) > 0;

  $label = $node['label'] ?? 'Opción';
  $url   = $node['url'] ?? '#';
@endphp

<li class="li-dd-item group relative">
  <a
    href="{{ $url }}"
    class="li-dd-link {{ $hasChildren ? 'li-dd-link-has' : '' }}"
    @if($url === '#' && $hasChildren) onclick="event.preventDefault();" @endif
  >
    <span class="li-dd-text">{{ $label }}</span>

    @if($hasChildren)
      <span class="li-dd-caret">▸</span>
    @endif
  </a>

  @if($hasChildren)
    <ul class="li-dd-submenu">
      @foreach($node['children'] as $child)
        @include('partials.nav-dropdown-item', ['node' => $child, 'level' => ($level ?? 0) + 1])
      @endforeach
    </ul>
  @endif
</li>
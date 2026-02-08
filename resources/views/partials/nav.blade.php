@php
$menu = config('menu', []);

// slugify local para generar sectionSlug sin depender de helpers externos
$slugify = function (string $text): string {
  $text = mb_strtolower(trim($text));
  $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
  $text = trim($text, '-');
  return $text ?: 'menu';
};
@endphp

<header class="bg-white border-b sticky top-0 z-40">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
    <a href="{{ route('home') }}" class="font-semibold">ventasli</a>

    <nav class="flex gap-6 items-center">
      @foreach($menu as $item)
        @php $sectionSlug = $slugify($item['label']); @endphp

        <div class="relative group">
          {{-- CLICK -> /menu/{sectionSlug}  |  HOVER -> dropdown --}}
          <a
            href="{{ route('menu.section', $sectionSlug) }}"
            class="py-2 font-medium hover:text-indigo-600 inline-flex items-center gap-2"
          >
            {{ $item['label'] }}

            @if(!empty($item['children']))
              <svg class="w-4 h-4 opacity-60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            @endif
          </a>

          @if(!empty($item['children']))
            <div class="absolute left-0 top-full hidden group-hover:block min-w-[320px] bg-white border shadow-lg rounded-xl p-2 z-50">
              @foreach($item['children'] as $child)
                @include('partials.nav-item', ['node' => $child])
              @endforeach
            </div>
          @endif
        </div>
      @endforeach
    </nav>

    <div class="flex items-center gap-3">
      <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>

      {{-- OJO: el rol es admin en minúsculas (tu middleware lo usa así) --}}
      @role('admin')
        <a href="{{ route('admin.slides.index') }}" class="text-sm px-3 py-1.5 rounded bg-gray-900 text-white">
          Admin
        </a>
      @endrole

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="text-sm px-3 py-1.5 rounded border">Salir</button>
      </form>
    </div>
  </div>
</header>
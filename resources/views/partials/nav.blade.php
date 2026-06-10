@php
  // Menú superior desde DB (AppServiceProvider -> $menuTop)
  $menu = $menuTop ?? [];

  // slugify local para rutas /menu/{sectionSlug}
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

    {{-- MENU SUPERIOR --}}
    <nav class="flex gap-6 items-center">
      @foreach($menu as $item)
        @php
          $label = $item['label'] ?? '';
          $sectionSlug = $slugify($label);
          $children = $item['children'] ?? [];
          $hasChildren = is_array($children) && count($children) > 0;
        @endphp

        <div class="relative group">
          <a
            href="{{ route('menu.section', $sectionSlug) }}"
            class="py-2 font-medium hover:text-indigo-600 inline-flex items-center gap-2"
          >
            {{ $label }}

            @if($hasChildren)
              <svg class="w-4 h-4 opacity-60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            @endif
          </a>

          @if($hasChildren)
            <div class="absolute left-0 top-full hidden group-hover:block min-w-[320px] bg-white border shadow-lg rounded-xl p-2 z-50">
              @foreach($children as $child)
                @include('partials.nav-item', ['node' => $child, 'sectionSlug' => $sectionSlug, 'basePath' => ''])
              @endforeach
            </div>
          @endif
        </div>
      @endforeach
    </nav>

    {{-- PERFIL (dropdown por click en nombre) --}}
    <div class="relative">
      <button
        type="button"
        id="userMenuBtn"
        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 transition"
      >
        <span class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</span>
        <svg class="w-4 h-4 opacity-60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <div
        id="userMenu"
        class="hidden absolute right-0 mt-2 w-56 bg-white border shadow-xl rounded-2xl p-2 z-50"
      >
        @role('admin')
            @if (Route::has('admin.menu.index'))
              <a href="{{ route('admin.menu.index') }}" ...>Editar menú superior</a>
            @endif
             class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50 text-sm text-slate-800">
            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
            Editar menú superior
          </a>
          <div class="my-2 h-px bg-slate-100"></div>
        @endrole

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-rose-50 text-sm text-slate-800 hover:text-rose-700">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            Cerrar sesión
          </button>
        </form>
      </div>
    </div>
  </div>
</header>

<script>
  (function () {
    const btn = document.getElementById('userMenuBtn');
    const menu = document.getElementById('userMenu');
    if (!btn || !menu) return;

    const close = () => menu.classList.add('hidden');
    const toggle = () => menu.classList.toggle('hidden');

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggle();
    });

    // Click afuera cierra
    document.addEventListener('click', close);

    // Evita cerrar al click dentro del menú
    menu.addEventListener('click', (e) => e.stopPropagation());

    // ESC cierra
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
    });
  })();
</script>
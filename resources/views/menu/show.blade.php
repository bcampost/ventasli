@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Breadcrumbs --}}
    <div class="text-sm text-slate-500 mb-4">
      <a class="hover:text-slate-900" href="{{ route('home') }}">Inicio</a>
      <span class="mx-2">/</span>

      @foreach($breadcrumbs as $i => $bc)
        <a class="hover:text-slate-900" href="{{ $bc['url'] }}">{{ $bc['label'] }}</a>
        @if($i < count($breadcrumbs)-1)
          <span class="mx-2">/</span>
        @endif
      @endforeach
    </div>

    <div class="flex items-start justify-between gap-4 mb-5">
      <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
          {{ $current['label'] ?? $section['label'] }}
        </h1>
        <p class="text-sm text-slate-600 mt-1">
          Navega por categorías y subcategorías. Aquí también puedes gestionar productos por sección.
        </p>
      </div>

      @role('admin')
        <a href="{{ route('admin.menu-products.index', ['key' => $currentMenuKey]) }}"
           class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
          Administrar productos
        </a>
      @endrole
    </div>

    {{-- Cards de navegación (hijos del nodo actual) --}}
    @if(count($cards))
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($cards as $card)
          <a href="{{ $card['href'] }}"
             class="group rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-sm transition">

            <div class="aspect-[16/9] bg-slate-50 relative">
              @if($card['image'])
                <img src="{{ $card['image'] }}" class="w-full h-full object-cover" alt="">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-300 text-sm">
                  Sin imagen
                </div>
              @endif
            </div>

            <div class="p-4">
              <div class="flex items-center justify-between gap-2">
                <h3 class="font-semibold text-slate-900 leading-tight">
                  {{ $card['customTitle'] ?: $card['title'] }}
                </h3>

                <span class="text-slate-400 group-hover:text-slate-700 transition">→</span>
              </div>

              <p class="text-sm text-slate-600 mt-2 line-clamp-2">
                {{ $card['description'] }}
              </p>
            </div>
          </a>
        @endforeach
      </div>
    @endif

    {{-- ✅ Productos agregados por admin para esta pantalla --}}
    <div class="mt-10">
      <div class="flex items-center justify-between gap-3 mb-3">
        <div>
          <h2 class="text-xl font-extrabold text-slate-900">Productos</h2>
          <p class="text-sm text-slate-600">Elementos cargados específicamente para esta sección.</p>
        </div>

        @role('admin')
          <a href="{{ route('admin.menu-products.create', ['key' => $currentMenuKey]) }}"
             class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
            + Agregar producto
          </a>
        @endrole
      </div>

      @if($products->count())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          @foreach($products as $p)
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
              <div class="flex gap-4 p-4">
                <div class="w-28 h-28 rounded-xl bg-slate-50 overflow-hidden shrink-0">
                  @if($p->image_path)
                    <img src="{{ asset('storage/' . $p->image_path) }}" class="w-full h-full object-cover" alt="">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">
                      Sin imagen
                    </div>
                  @endif
                </div>

                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                      <div class="font-semibold text-slate-900 truncate">{{ $p->title }}</div>
                      <div class="text-sm text-slate-600 mt-1 line-clamp-3">
                        {{ $p->description }}
                      </div>
                    </div>

                    @role('admin')
                      <div class="flex gap-2">
                        <a href="{{ route('admin.menu-products.edit', $p->id) }}"
                           class="text-xs px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
                          Editar
                        </a>
                        <form method="POST" action="{{ route('admin.menu-products.destroy', $p->id) }}"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                          @csrf
                          @method('DELETE')
                          <button class="text-xs px-3 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50">
                            Eliminar
                          </button>
                        </form>
                      </div>
                    @endrole
                  </div>

                  @if($p->url)
                    <a href="{{ $p->url }}" target="_blank"
                       class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-700 mt-3">
                      Abrir recurso
                      <span>↗</span>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-slate-600">
          No hay productos cargados para esta sección.
          @role('admin')
            <div class="mt-3">
              <a href="{{ route('admin.menu-products.create', ['key' => $currentMenuKey]) }}"
                 class="inline-flex rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
                Agregar el primero
              </a>
            </div>
          @endrole
        </div>
      @endif
    </div>

  </div>
@endsection
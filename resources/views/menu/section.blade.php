@extends('layouts.app')

@php
  $slugify = function(string $text): string {
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'menu';
  };

  $keyOf = function(array $parts) use ($slugify): string {
    return implode('/', array_map(fn($p) => $slugify($p), $parts));
  };

  $imgUrl = function(string $key) use ($images) {
    $row = $images[$key] ?? null;
    if (!$row || !$row->image_path) return null;
    return asset('storage/' . ltrim($row->image_path, '/'));
  };

  $children = $section['children'] ?? [];
@endphp

@section('content')
  {{-- Top header (simple pero con presencia) --}}
  <div class="bg-white border rounded-2xl shadow-sm">
    <div class="p-6 sm:p-8">
      <div class="text-sm text-slate-500 mb-3">
        <a class="hover:text-slate-900" href="{{ route('home') }}">Inicio</a>
        <span class="mx-2">/</span>
        <span class="text-slate-900 font-semibold">{{ $section['label'] }}</span>
      </div>

      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
            {{ $section['label'] }}
          </h1>
          <p class="mt-2 text-slate-600 max-w-2xl">
            Selecciona una categoría para abrir recursos o desplegar sub-opciones.
          </p>

          <div class="mt-4 flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-50 border text-slate-700">
              Click → Cards
            </span>
            <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-50 border text-slate-700">
              Hover intacto
            </span>
            <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-50 border text-slate-700">
              Sin JS pesado
            </span>
          </div>
        </div>

        @role('admin')
          <a
            href="{{ route('admin.menu-cards.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition"
          >
            Administrar imágenes
            <svg class="w-4 h-4 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M4 4a2 2 0 012-2h5a1 1 0 010 2H6v12h8v-5a1 1 0 112 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
              <path d="M12 3a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 11-2 0V5.414l-6.293 6.293a1 1 0 01-1.414-1.414L14.586 4H13a1 1 0 01-1-1z"/>
            </svg>
          </a>
        @endrole
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- Main cards --}}
    <div class="lg:col-span-8">
      {{-- Grid “pro” (no lista eterna) --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @foreach($children as $child)
          @php
            $childHasChildren = !empty($child['children']);
            $childSlug = $slugify($child['label']);
            $childKey = $keyOf([$section['label'], $child['label']]);
            $src = $imgUrl($childKey);
            $isOpen = ($open === $childSlug);

            $href = $childHasChildren
              ? route('menu.section', $sectionSlug) . '?open=' . $childSlug
              : ($child['url'] ?? '#');
          @endphp

          <a
            href="{{ $href }}"
            class="group relative overflow-hidden rounded-2xl border bg-white
                   shadow-sm hover:shadow-md hover:-translate-y-[1px] transition"
          >
            {{-- Media strip --}}
            <div class="relative h-36 bg-slate-100">
              @if($src)
                <img src="{{ $src }}" alt="{{ $child['label'] }}" class="w-full h-full object-cover" />
              @else
                {{-- Placeholder elegante --}}
                <div class="w-full h-full bg-gradient-to-br from-slate-900 to-slate-800"></div>
                <div class="absolute inset-0 opacity-40 [background-image:radial-gradient(rgba(255,255,255,.18)_1px,transparent_1px)] [background-size:18px_18px]"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                  <div class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-white/15 bg-white/10 backdrop-blur">
                    <div class="h-10 w-10 rounded-xl border border-white/15 bg-white/10 flex items-center justify-center">
                      <svg class="w-5 h-5 text-white/85" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.828a2 2 0 00-.586-1.414l-2.828-2.828A2 2 0 0013.172 3H4z"/>
                      </svg>
                    </div>
                    <div class="text-white/90">
                      <div class="text-sm font-semibold">Sin imagen</div>
                      <div class="text-xs text-white/70">Puedes cargar una</div>
                    </div>
                  </div>
                </div>
              @endif

              <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-transparent"></div>

              {{-- Top chips --}}
              <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold
                             border border-white/20 bg-white/15 backdrop-blur text-white">
                  {{ $childHasChildren ? 'Categoría' : 'Recurso' }}
                </span>

                @if($isOpen)
                  <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold
                               border border-white/20 bg-white/15 backdrop-blur text-white">
                    Abierto
                  </span>
                @endif
              </div>
            </div>

            {{-- Body --}}
            <div class="p-5">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <div class="text-lg font-semibold tracking-tight text-slate-900">
                    {{ $child['label'] }}
                  </div>
                  <div class="mt-1 text-sm text-slate-600">
                    {{ $childHasChildren ? 'Despliega sub-opciones sin salir.' : 'Acceso directo al contenido.' }}
                  </div>
                </div>

                <div class="text-slate-300 group-hover:text-slate-800 transition mt-1">
                  <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>

              <div class="mt-4 flex flex-wrap gap-2">
                @if($childHasChildren)
                  <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700">
                    {{ count($child['children'] ?? []) }} sub-opciones
                  </span>
                @else
                  <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-50 border text-slate-700">
                    Acceso directo
                  </span>
                @endif

                @role('admin')
                  <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-amber-50 border border-amber-100 text-amber-800">
                    Editable
                  </span>
                @endrole
              </div>

              @role('admin')
                <div class="mt-4">
                  <a
                    class="text-sm font-semibold text-indigo-700 hover:text-indigo-900 hover:underline"
                    href="{{ route('admin.menu-cards.index', ['focus' => $childKey]) }}"
                    onclick="event.stopPropagation();"
                  >
                    Editar imagen
                  </a>
                </div>
              @endrole
            </div>
          </a>
        @endforeach
      </div>

      {{-- Subcards (cuadradas) --}}
      @if($open && !empty($openedChild) && !empty($openedChild['children']))
        <div class="mt-8 bg-white border rounded-2xl shadow-sm overflow-hidden">
          <div class="p-5 sm:p-6 border-b bg-slate-50">
            <div class="flex items-center justify-between gap-4">
              <div>
                <div class="text-sm font-semibold text-slate-900">Sub-opciones</div>
                <div class="text-sm text-slate-600">{{ $openedChild['label'] }}</div>
              </div>

              <a
                href="{{ route('menu.section', $sectionSlug) }}"
                class="text-sm font-semibold text-slate-600 hover:text-slate-900"
              >
                Cerrar
              </a>
            </div>
          </div>

          <div class="p-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              @foreach($openedChild['children'] as $leaf)
                @php
                  $leafKey = $keyOf([$section['label'], $openedChild['label'], $leaf['label']]);
                  $leafSrc = $imgUrl($leafKey);
                @endphp

                <a
                  href="{{ $leaf['url'] ?? '#' }}"
                  class="group overflow-hidden rounded-2xl border bg-white shadow-sm hover:shadow-md hover:-translate-y-[1px] transition"
                >
                  <div class="relative h-24 bg-slate-100">
                    @if($leafSrc)
                      <img src="{{ $leafSrc }}" alt="{{ $leaf['label'] }}" class="w-full h-full object-cover" />
                    @else
                      <div class="w-full h-full bg-gradient-to-br from-slate-900 to-slate-800"></div>
                      <div class="absolute inset-0 opacity-35 [background-image:radial-gradient(rgba(255,255,255,.18)_1px,transparent_1px)] [background-size:18px_18px]"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-transparent"></div>
                  </div>

                  <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                      <div class="font-semibold text-slate-900 leading-tight">
                        {{ $leaf['label'] }}
                      </div>

                      <div class="text-slate-300 group-hover:text-slate-800 transition mt-0.5">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                    </div>

                    <div class="text-sm text-slate-600 mt-1">Abrir</div>

                    @role('admin')
                      <div class="mt-3">
                        <a
                          class="text-sm font-semibold text-indigo-700 hover:text-indigo-900 hover:underline"
                          href="{{ route('admin.menu-cards.index', ['focus' => $leafKey]) }}"
                          onclick="event.stopPropagation();"
                        >
                          Editar imagen
                        </a>
                      </div>
                    @endrole
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>

    {{-- Aside (simple, útil, no estorba) --}}
    <aside class="lg:col-span-4">
      <div class="sticky top-24 space-y-4">
        <div class="bg-white border rounded-2xl shadow-sm p-5">
          <div class="text-sm font-semibold text-slate-900">Cómo usar</div>
          <div class="mt-2 text-sm text-slate-600 space-y-2">
            <div class="flex gap-2"><span class="mt-1 h-1.5 w-1.5 rounded-full bg-indigo-600"></span> Click en una card para abrir o desplegar</div>
            <div class="flex gap-2"><span class="mt-1 h-1.5 w-1.5 rounded-full bg-sky-600"></span> Sub-opciones aparecen abajo</div>
            @role('admin')
              <div class="flex gap-2"><span class="mt-1 h-1.5 w-1.5 rounded-full bg-amber-600"></span> Admin: puedes cargar imagen por card</div>
            @endrole
          </div>
        </div>

        <a href="{{ route('home') }}"
           class="block text-center px-4 py-3 rounded-2xl border bg-white hover:bg-slate-50 transition shadow-sm">
          Volver al Home
        </a>
      </div>
    </aside>
  </div>
@endsection
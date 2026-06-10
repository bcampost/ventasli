@php
  $href = $card['href'] ?? '#';
  $isExternal = !empty($card['is_external']);
  $hasChildren = !empty($card['hasChildren']);
  $title = $card['title'] ?? 'Sin título';
  $desc = $card['description'] ?? '';
  $img = $card['image'] ?? null;
@endphp

<a
  href="{{ $href }}"
  @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
  class="group block"
>
  <div class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm hover:shadow-lg transition-shadow">

    {{-- Imagen (cuadro izquierdo) --}}
    <div class="w-[92px] h-[92px] sm:w-[108px] sm:h-[108px] rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center shrink-0">
      @if($img)
        <img src="{{ $img }}" alt="{{ $title }}" class="w-full h-full object-cover">
      @else
        <div class="w-full h-full flex items-center justify-center">
          <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-extrabold text-sm tracking-wide">
            {{ mb_strtoupper(mb_substr($title, 0, 2)) }}
          </div>
        </div>
      @endif
    </div>

    {{-- Contenido derecho --}}
    <div class="flex-1 min-w-0">

      <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
          {{-- Nombre centrado arriba --}}
          <div class="text-center sm:text-left">
            <h3 class="text-lg font-extrabold tracking-tight text-slate-900 group-hover:text-slate-950">
              {{ $title }}
            </h3>
          </div>

          {{-- Descripción --}}
          <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            {{ $desc }}
          </p>
        </div>

        {{-- Indicador (si tiene sub-opciones) --}}
        <div class="shrink-0 flex items-center gap-2 pt-1">
          @if($hasChildren)
            <span class="text-xs px-2 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-semibold">
              Sub-opciones
            </span>
          @endif

          <span class="w-8 h-8 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-700 group-hover:bg-slate-900 group-hover:text-white transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
          </span>
        </div>
      </div>

    </div>
  </div>
</a>
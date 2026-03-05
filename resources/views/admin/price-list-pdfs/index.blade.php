{{-- resources/views/admin/price-list-pdfs/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  // ✅ Ajusta este valor si tu menú lateral es más ancho/angosto
  // (en tu captura parece ~90-110px)
  $rightSafe = 140; // px
@endphp

<style>
  /* ✅ “Área segura” para no quedar debajo del menú lateral derecho */
  .safe-area-right {
    width: calc(100vw - {{ $rightSafe }}px);
    max-width: 960px; /* más angosto */
    margin-left: auto;
    margin-right: auto;
  }
  @media (min-width: 1024px){
    .safe-area-right {
      max-width: 900px; /* aún más angosto en desktop */
    }
  }
</style>

<div class="w-full py-6">
  {{-- ✅ Contenedor centrado que NUNCA se mete debajo del menú lateral --}}
  <div class="safe-area-right px-4 sm:px-6 lg:px-8">

    <div class="bg-white rounded-2xl shadow p-5">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="text-2xl font-semibold">PDFs · Lista de precios</h1>
          <p class="text-gray-600 mt-1">
            Sube/reemplaza el PDF de cada opción (Mobiliario, Silleria, etc.). Se guardan en <code>public/pdfs</code>.
          </p>
        </div>

        @if(session('status'))
          <div class="px-4 py-2 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('status') }}
          </div>
        @endif
      </div>

      @if(!$root)
        <div class="mt-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-900">
          No se encontró el nodo raíz <b>"Lista de precios"</b> en <code>menu_nodes</code>.
          <div class="text-sm mt-2">
            Asegúrate de que exista en tu menú superior con ese mismo label.
          </div>
        </div>
      @else

        {{-- ✅ NUEVA OPCIÓN --}}
        <div class="mt-6 p-4 rounded-2xl border bg-gray-50">
          <div class="font-semibold mb-2">Agregar nueva opción</div>

          <form method="POST" action="{{ route('admin.price-list-pdfs.store') }}" class="flex items-center gap-2 flex-wrap">
            @csrf
            <input
              type="text"
              name="label"
              placeholder="Ej. Accesorios"
              class="w-full max-w-sm rounded-xl border-gray-300 text-sm"
              required
            >
            <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold">
              Crear
            </button>
          </form>

          @error('label')
            <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
          @enderror
        </div>

        {{-- ✅ LISTADO --}}
        <div class="mt-6 space-y-4">
          @forelse($children as $child)
            @php
              $currentUrl = $child->url ? asset(ltrim($child->url, '/')) : null;
              $suggestedName = 'lista-precios-' . ($child->slug ?: \Illuminate\Support\Str::slug($child->label, '-')) . '.pdf';
            @endphp

            <div class="border rounded-2xl p-4">
              <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0">
                  <div class="text-lg font-semibold truncate">{{ $child->label }}</div>
                  <div class="text-sm text-gray-600 mt-1">
                    Archivo sugerido: <code>{{ $suggestedName }}</code>
                  </div>
                  <div class="text-sm text-gray-600 mt-1">
                    URL en menú: <code>{{ $child->url ?: '(vacío)' }}</code>
                  </div>

                  @if($currentUrl)
                    <div class="mt-2">
                      <a href="{{ $currentUrl }}" target="_blank" class="text-blue-700 underline text-sm">
                        Ver PDF actual
                      </a>
                    </div>
                  @endif
                </div>

                <div class="flex-none">
                  <form
                    method="POST"
                    action="{{ route('admin.price-list-pdfs.upload', $child) }}"
                    enctype="multipart/form-data"
                    class="flex items-center gap-2 flex-wrap"
                  >
                    @csrf
                    <input
                      type="file"
                      name="pdf"
                      accept="application/pdf"
                      class="text-sm"
                      required
                    >
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold">
                      Subir
                    </button>
                  </form>

                  @error('pdf')
                    <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          @empty
            <div class="p-4 rounded-xl bg-gray-50 border text-gray-700">
              No hay hijos debajo de “Lista de precios”.
            </div>
          @endforelse
        </div>
      @endif
    </div>

    <div class="mt-6 bg-white rounded-2xl shadow p-5">
      <h2 class="font-semibold">Cómo usarlo</h2>
      <ol class="list-decimal pl-6 mt-2 text-gray-700 space-y-1">
        <li>Entra a <code>/admin/price-list-pdfs</code></li>
        <li>Agrega opciones nuevas con “Crear”</li>
        <li>En cada opción, selecciona el PDF y presiona <b>Subir</b></li>
        <li>Se guardará como <code>public/pdfs/lista-precios-&lt;slug&gt;.pdf</code></li>
        <li>El menú se actualiza solo (porque el <code>url</code> del nodo queda en <code>pdfs/...</code>)</li>
      </ol>
    </div>

  </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
  <div class="relative overflow-hidden rounded-3xl border bg-white shadow-sm">
    <div class="absolute inset-0">
      <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-amber-200/40 blur-3xl"></div>
      <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-white/70 to-white"></div>
    </div>

    <div class="relative p-6 sm:p-8">
      <div class="text-sm text-slate-500 mb-3">
        <a class="hover:text-slate-900" href="{{ route('home') }}">Inicio</a>
        <span class="mx-2">/</span>
        <span class="text-slate-900 font-medium">Admin</span>
        <span class="mx-2">/</span>
        <span class="text-slate-900 font-medium">Imágenes de Cards</span>
      </div>

      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
            Imágenes de Cards
          </h1>
          <p class="text-slate-600 mt-2 max-w-2xl">
            Sube o reemplaza imágenes por <span class="font-medium">key</span>. Esto alimenta las tarjetas del menú por click.
          </p>
        </div>

        <a
          href="{{ route('home') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border bg-white hover:bg-slate-50 shadow-sm active:scale-[0.99] transition"
        >
          Volver al Home
          <svg class="w-4 h-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M12.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
          </svg>
        </a>
      </div>

      @if(session('status'))
        <div class="mt-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl">
          {{ session('status') }}
        </div>
      @endif
    </div>
  </div>

  <div class="mt-6 bg-white border rounded-3xl shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-slate-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="text-sm font-semibold text-slate-900">Catálogo de keys</div>
        <div class="text-sm text-slate-600">
          Tip: usa el botón “Editar imagen” desde la vista de cards para traer foco a una key.
        </div>
      </div>
    </div>

    <div class="divide-y">
      @foreach($keys as $key)
        @php
          $row = $images[$key] ?? null;
          $url = ($row && $row->image_path) ? asset('storage/' . ltrim($row->image_path, '/')) : null;
          $isFocus = ($focus === $key);
        @endphp

        <div class="p-5 {{ $isFocus ? 'bg-indigo-50' : '' }}">
          <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="min-w-0">
              <div class="flex items-center gap-2">
                <div class="font-semibold text-slate-900 break-all">{{ $key }}</div>
                @if($url)
                  <span class="inline-flex items-center gap-2 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700">
                    Activa
                  </span>
                @else
                  <span class="inline-flex items-center gap-2 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-50 border text-slate-600">
                    Sin imagen
                  </span>
                @endif
              </div>

              <div class="text-sm text-slate-600 mt-1">
                Recomendación: 1200×600 (o similar). Peso máx 2MB.
              </div>
            </div>

            <div class="flex items-center gap-2">
              @if($url)
                <a class="text-sm px-3 py-2 rounded-xl border bg-white hover:bg-slate-50" href="{{ $url }}" target="_blank">
                  Ver
                </a>

                <form method="POST" action="{{ route('admin.menu-cards.destroy', $key) }}">
                  @csrf
                  @method('DELETE')
                  <button class="text-sm px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100">
                    Quitar
                  </button>
                </form>
              @endif
            </div>
          </div>

          <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            <div class="lg:col-span-4">
              <div class="w-full h-40 bg-slate-100 rounded-2xl overflow-hidden border">
                @if($url)
                  <img src="{{ $url }}" class="w-full h-full object-cover" alt="{{ $key }}">
                @else
                  <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">Sin imagen</div>
                @endif
              </div>
            </div>

            <div class="lg:col-span-8">
              <form method="POST" action="{{ route('admin.menu-cards.update', $key) }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('PUT')

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                  <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full text-sm text-slate-700
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-xl file:border-0
                           file:text-sm file:font-semibold
                           file:bg-slate-100 file:text-slate-700
                           hover:file:bg-slate-200"
                  />

                  <button class="inline-flex items-center justify-center gap-2 text-sm px-4 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 active:scale-[0.99] transition">
                    Guardar
                    <svg class="w-4 h-4 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path d="M3 6a2 2 0 012-2h8a2 2 0 012 2v11H5a2 2 0 01-2-2V6z"/>
                      <path d="M7 4h6v4H7V4z"/>
                    </svg>
                  </button>
                </div>

                @error('image')
                  <div class="text-sm text-rose-600">{{ $message }}</div>
                @enderror
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
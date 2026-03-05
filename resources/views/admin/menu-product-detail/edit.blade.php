@extends('layouts.app')

@section('content')
@php
  // URLs de PDFs (si existen)
  $techUrl   = $product->tech_pdf_path   ? asset('storage/'.ltrim($product->tech_pdf_path,'/'))   : null;
  $manualUrl = $product->manual_pdf_path ? asset('storage/'.ltrim($product->manual_pdf_path,'/')) : null;
@endphp

<style>
  @media (min-width: 1024px){
    .pdf-edit-wrap{ padding-right: 110px; }
  }
</style>

<div class="max-w-3xl mx-auto px-4 py-6 pdf-edit-wrap">
  <div class="mb-5">
    <h1 class="text-2xl font-extrabold text-slate-900">Editar detalle del producto</h1>
    <p class="text-sm text-slate-600 mt-1">
      Producto: <span class="font-mono">{{ $product->title }}</span>
    </p>
  </div>

  @if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
      {{ session('success') }}
    </div>
  @endif

  @if(session('status'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
      {{ session('status') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST"
        action="{{ route('admin.product-details.update', $product) }}"
        enctype="multipart/form-data"
        class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
    @csrf
    @method('PUT')

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    {{-- Datos base --}}
    <div>
      <label class="text-sm font-semibold text-slate-900">Título (opcional)</label>
      <input name="title" value="{{ old('title', $detail->title) }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      <p class="text-xs text-slate-500 mt-1">* Si no lo usas, el sistema muestra el título del producto.</p>
    </div>

    <div>
      <label class="text-sm font-semibold text-slate-900">Descripción (opcional)</label>
      <textarea name="description" rows="4"
                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description', $detail->description) }}</textarea>
    </div>

    {{-- Medidas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="text-sm font-semibold text-slate-900">Largo</label>
        <input name="length" value="{{ old('length', $detail->length) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="text-sm font-semibold text-slate-900">Ancho</label>
        <input name="width" value="{{ old('width', $detail->width) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="text-sm font-semibold text-slate-900">Alto</label>
        <input name="height" value="{{ old('height', $detail->height) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>
    </div>

    {{-- ✅ PDFs --}}
    <div class="border-t border-slate-200 pt-4">
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
          <div class="text-sm font-extrabold text-slate-900">PDFs del producto</div>
          <div class="text-xs text-slate-500">Ficha técnica e Instructivo se guardan en BD.</div>
        </div>

        <div class="flex gap-2 flex-wrap">
          <button type="button"
                  class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95 disabled:opacity-40"
                  @if(!$techUrl) disabled @endif
                  onclick="window.openPdfPreview(@js($techUrl), 'Ficha técnica')
            Ficha técnica
          </button>

          <button type="button"
                  class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95 disabled:opacity-40"
                  @if(!$manualUrl) disabled @endif
                  onclick="window.openPdfPreview(@js($manualUrl), 'Instructivo')">
            Instructivo
          </button>
        </div>
      </div>

      <div class="mt-3 grid grid-cols-1 gap-3">

        {{-- Ficha técnica --}}
        <div class="rounded-2xl border border-slate-200 p-4">
          <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="font-bold text-slate-900">Ficha técnica (PDF)</div>

            <div class="flex items-center gap-3 flex-wrap">
              @if($techUrl)
                <button type="button"
                        class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50"
                        onclick="window.openPdfPreview(@js($techUrl), 'Ficha técnica')
                  Ver preview
                </button>

                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                  <input type="checkbox" name="remove_tech_pdf" value="1" class="rounded border-slate-300">
                  Quitar PDF
                </label>
              @else
                <span class="text-sm text-slate-500 font-semibold">Sin PDF cargado</span>
              @endif
            </div>
          </div>

          <input type="file" name="tech_pdf" accept="application/pdf"
                 class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
          <p class="text-xs text-slate-500 mt-2">Al guardar, si seleccionas archivo se reemplaza el anterior.</p>
        </div>

        {{-- Instructivo --}}
        <div class="rounded-2xl border border-slate-200 p-4">
          <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="font-bold text-slate-900">Instructivo (PDF)</div>

            <div class="flex items-center gap-3 flex-wrap">
              @if($manualUrl)
                <button type="button"
                        class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50"
                        onclick="window.openPdfPreview(@js($manualUrl), 'Instructivo')">
                  Ver preview
                </button>

                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                  <input type="checkbox" name="remove_manual_pdf" value="1" class="rounded border-slate-300">
                  Quitar PDF
                </label>
              @else
                <span class="text-sm text-slate-500 font-semibold">Sin PDF cargado</span>
              @endif
            </div>
          </div>

          <input type="file" name="manual_pdf" accept="application/pdf"
                 class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
          <p class="text-xs text-slate-500 mt-2">Al guardar, si seleccionas archivo se reemplaza el anterior.</p>
        </div>

      </div>
    </div>

    {{-- Botones --}}
    <div class="flex justify-end gap-2 pt-2">
      <a href="{{ $redirectTo }}"
         class="rounded-xl border border-slate-200 text-slate-900 text-sm px-4 py-2 hover:bg-slate-50">
        Volver
      </a>

      <button class="rounded-xl bg-slate-900 text-white text-sm px-4 py-2 hover:opacity-95">
        Guardar cambios
      </button>
    </div>
  </form>

</div>
@endsection
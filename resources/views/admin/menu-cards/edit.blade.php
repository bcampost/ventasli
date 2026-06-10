@extends('layouts.app')

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-6">

    <div class="mb-5">
      <div class="text-sm text-slate-500">
        <a class="hover:text-slate-900" href="{{ route('admin.menu-cards.index') }}">Cards del Menú</a>
        <span class="mx-2">/</span>
        <span class="text-slate-900 font-semibold">Editar</span>
      </div>

      <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 mt-2">Editar Card</h1>
      <p class="text-sm text-slate-600 mt-1 font-mono">{{ $item->key }}</p>
    </div>

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

    <form method="POST" action="{{ route('admin.menu-cards.update', $item->key) }}" enctype="multipart/form-data"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="sm:col-span-1">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold text-slate-700 mb-2">Imagen</div>

            <div class="w-full aspect-square rounded-xl border border-slate-200 bg-white overflow-hidden flex items-center justify-center">
              @if($item->path)
                <img src="{{ asset('storage/'.$item->path) }}" class="w-full h-full object-cover" alt="Card image">
              @else
                <div class="w-16 h-16 rounded-full bg-slate-900 text-white flex items-center justify-center font-extrabold">
                  {{ mb_strtoupper(mb_substr($item->title ?? $item->key, 0, 2)) }}
                </div>
              @endif
            </div>

            <div class="mt-3">
              <input type="file" name="image" class="block w-full text-xs">
            </div>

            @if($item->path)
              <label class="mt-3 flex items-center gap-2 text-xs text-slate-700">
                <input type="checkbox" name="remove_image" value="1">
                Quitar imagen actual
              </label>
            @endif
          </div>
        </div>

        <div class="sm:col-span-2">
          <div class="mb-3">
            <label class="text-xs font-semibold text-slate-700">Título (opcional)</label>
            <input
              name="title"
              value="{{ old('title', $item->title) }}"
              class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
              placeholder="Si lo dejas vacío, usa el label del menú"
            />
          </div>

          <div class="mb-3">
            <label class="text-xs font-semibold text-slate-700">Descripción</label>
            <textarea
              name="description"
              rows="6"
              class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
              placeholder="Descripción que aparece debajo del título"
            >{{ old('description', $item->description) }}</textarea>
          </div>

          <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.menu-cards.index') }}"
               class="text-sm px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
              Volver
            </a>
            <button class="text-sm px-4 py-2 rounded-xl bg-slate-900 text-white hover:opacity-95">
              Guardar
            </button>
          </div>
        </div>

      </div>
    </form>

  </div>
@endsection
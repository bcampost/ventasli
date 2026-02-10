@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
  <div class="flex items-start justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Editar menú superior</h1>
      <p class="text-slate-600 mt-1">
        Despliega cada opción para editar, eliminar o agregar sub-opciones.
      </p>
    </div>

    <a href="{{ route('admin.menu-nodes.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 shadow-sm">
      ➕ Agregar opción raíz
    </a>
  </div>

  @if(session('ok'))
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 px-4 py-3">
      {{ session('ok') }}
    </div>
  @endif

  <div class="space-y-4">
    @forelse($tree as $node)
      @include('admin.menu._node', ['node' => $node])
    @empty
      <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-600">
        Aún no hay opciones en el menú.
      </div>
    @endforelse
  </div>
</div>
@endsection
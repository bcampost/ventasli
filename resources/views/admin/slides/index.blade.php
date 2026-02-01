@extends('layouts.app')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <div>
      <h1 class="text-xl font-semibold">Avisos (Slider)</h1>
      <p class="text-sm text-gray-500">Administra las imágenes del slider en HOME.</p>
    </div>
    <a href="{{ route('admin.slides.create') }}" class="px-3 py-2 rounded bg-gray-900 text-white">
      Nuevo slide
    </a>
  </div>

  @if(session('ok'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border text-green-800">
      {{ session('ok') }}
    </div>
  @endif

  <div class="bg-white border rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Preview</th>
          <th class="text-left p-3">Título</th>
          <th class="text-left p-3">Link</th>
          <th class="text-left p-3">Orden</th>
          <th class="text-left p-3">Activo</th>
          <th class="text-right p-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
      @forelse($slides as $s)
        <tr class="border-t align-top">
          <td class="p-3">
            <img class="w-40 h-20 object-cover rounded-lg border" src="{{ asset('storage/'.$s->image_path) }}" alt="slide">
          </td>
          <td class="p-3">{{ $s->title }}</td>
          <td class="p-3">
            @if($s->link)
              <a class="text-indigo-600 hover:underline break-all" href="{{ $s->link }}" target="_blank" rel="noopener">{{ $s->link }}</a>
            @else
              <span class="text-gray-400">—</span>
            @endif
          </td>
          <td class="p-3">{{ $s->sort_order }}</td>
          <td class="p-3">
            @if($s->is_active)
              <span class="inline-flex px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Sí</span>
            @else
              <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs">No</span>
            @endif
          </td>
          <td class="p-3 text-right">
            <div class="inline-flex gap-2">
              <a class="px-3 py-1.5 rounded border hover:bg-gray-50" href="{{ route('admin.slides.edit', $s) }}">Editar</a>
              <form method="POST" action="{{ route('admin.slides.destroy', $s) }}">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 rounded border hover:bg-gray-50" onclick="return confirm('¿Eliminar slide?')">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td class="p-6 text-gray-500" colspan="6">Aún no hay slides.</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>
@endsection
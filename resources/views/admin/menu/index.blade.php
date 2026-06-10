{{-- resources/views/admin/menu/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Administrador de Menú</h1>
                <p class="text-gray-600 mt-1">
                    Aquí puedes editar el campo <b>URL</b> para que “Lista de precios → Mobiliario/Silleria” apunte a PDFs y se abra en el modal.
                </p>
            </div>

            @if(session('status'))
                <div class="px-4 py-2 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Crear ROOT --}}
            <div class="border rounded-2xl p-4">
                <h2 class="font-semibold mb-3">Crear opción raíz</h2>

                <form method="POST" action="{{ route('admin.menu.store') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="text-sm font-medium">Nombre (label)</label>
                        <input name="label" class="mt-1 w-full rounded-xl border-gray-300" placeholder="Ej: Lista de precios" required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">URL (opcional)</label>
                        <input name="url" class="mt-1 w-full rounded-xl border-gray-300" placeholder="Ej: pdfs/lista-precios-silleria.pdf">
                        <p class="text-xs text-gray-500 mt-1">Para PDFs usa: <code>pdfs/archivo.pdf</code></p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Sort</label>
                            <input name="sort" type="number" class="mt-1 w-full rounded-xl border-gray-300" value="0">
                        </div>

                        <div class="flex items-end gap-2">
                            <input id="active_root" name="is_active" value="1" type="checkbox" class="rounded border-gray-300" checked>
                            <label for="active_root" class="text-sm">Activo</label>
                        </div>
                    </div>

                    <div>
                        <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">
                            Crear
                        </button>
                    </div>
                </form>
            </div>

            {{-- Lista Roots + Hijos --}}
            <div class="border rounded-2xl p-4">
                <h2 class="font-semibold mb-3">Estructura del menú</h2>

                <div class="space-y-4">
                    @foreach($roots as $root)
                        <details class="border rounded-2xl p-3">
                            <summary class="cursor-pointer flex items-center justify-between">
                                <div class="min-w-0">
                                    <div class="font-semibold truncate">{{ $root->label }}</div>
                                    <div class="text-xs text-gray-500">
                                        URL: <span class="font-mono">{{ $root->url ?: '(vacío)' }}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">Abrir</span>
                            </summary>

                            {{-- Edit root --}}
                            <div class="mt-4 border-t pt-4">
                                <form method="POST" action="{{ route('admin.menu.update', $root) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    @csrf
                                    @method('PUT')

                                    <div class="md:col-span-1">
                                        <label class="text-xs font-medium">Label</label>
                                        <input name="label" value="{{ $root->label }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium">URL</label>
                                        <input name="url" value="{{ $root->url }}" class="mt-1 w-full rounded-xl border-gray-300" placeholder="pdfs/archivo.pdf">
                                    </div>

                                    <div class="md:col-span-1 grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-xs font-medium">Sort</label>
                                            <input name="sort" type="number" value="{{ $root->sort ?? 0 }}" class="mt-1 w-full rounded-xl border-gray-300">
                                        </div>
                                        <div class="flex items-end gap-2">
                                            <input id="active_{{ $root->id }}" name="is_active" value="1" type="checkbox" class="rounded border-gray-300"
                                                @checked((bool)$root->is_active)>
                                            <label for="active_{{ $root->id }}" class="text-sm">Activo</label>
                                        </div>
                                    </div>

                                    <div class="md:col-span-4 flex items-center justify-between">
                                        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm">
                                            Guardar
                                        </button>

                                        <form method="POST" action="{{ route('admin.menu.destroy', $root) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm"
                                                onclick="return confirm('¿Eliminar este nodo y todos sus hijos?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </form>
                            </div>

                            {{-- Children list --}}
                            <div class="mt-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold">Hijos</h3>
                                </div>

                                <div class="space-y-3">
                                    @foreach($root->children as $child)
                                        <div class="border rounded-2xl p-3">
                                            <div class="text-sm font-semibold">{{ $child->label }}</div>
                                            <div class="text-xs text-gray-500">
                                                URL: <span class="font-mono">{{ $child->url ?: '(vacío)' }}</span>
                                            </div>

                                            <form method="POST" action="{{ route('admin.menu.update', $child) }}" class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                                                @csrf
                                                @method('PUT')

                                                <div class="md:col-span-1">
                                                    <label class="text-xs font-medium">Label</label>
                                                    <input name="label" value="{{ $child->label }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                                                </div>

                                                <div class="md:col-span-2">
                                                    <label class="text-xs font-medium">URL (PDF)</label>
                                                    <input name="url" value="{{ $child->url }}" class="mt-1 w-full rounded-xl border-gray-300" placeholder="pdfs/lista-precios-silleria.pdf">
                                                    <p class="text-xs text-gray-500 mt-1">Para tu caso: <code>pdfs/lista-precios-silleria.pdf</code></p>
                                                </div>

                                                <div class="md:col-span-1 grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="text-xs font-medium">Sort</label>
                                                        <input name="sort" type="number" value="{{ $child->sort ?? 0 }}" class="mt-1 w-full rounded-xl border-gray-300">
                                                    </div>
                                                    <div class="flex items-end gap-2">
                                                        <input id="active_{{ $child->id }}" name="is_active" value="1" type="checkbox" class="rounded border-gray-300"
                                                            @checked((bool)$child->is_active)>
                                                        <label for="active_{{ $child->id }}" class="text-sm">Activo</label>
                                                    </div>
                                                </div>

                                                <div class="md:col-span-4 flex items-center justify-between">
                                                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm">
                                                        Guardar
                                                    </button>

                                                    <form method="POST" action="{{ route('admin.menu.destroy', $child) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm"
                                                            onclick="return confirm('¿Eliminar este nodo y todos sus hijos?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Crear hijo --}}
                                <div class="mt-5 border-t pt-4">
                                    <h4 class="font-semibold mb-2">Crear hijo</h4>

                                    <form method="POST" action="{{ route('admin.menu.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $root->id }}">

                                        <div class="md:col-span-1">
                                            <label class="text-xs font-medium">Label</label>
                                            <input name="label" class="mt-1 w-full rounded-xl border-gray-300" placeholder="Ej: Silleria" required>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="text-xs font-medium">URL (opcional)</label>
                                            <input name="url" class="mt-1 w-full rounded-xl border-gray-300" placeholder="pdfs/lista-precios-silleria.pdf">
                                        </div>

                                        <div class="md:col-span-1 grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="text-xs font-medium">Sort</label>
                                                <input name="sort" type="number" class="mt-1 w-full rounded-xl border-gray-300" value="0">
                                            </div>
                                            <div class="flex items-end gap-2">
                                                <input id="active_new_{{ $root->id }}" name="is_active" value="1" type="checkbox" class="rounded border-gray-300" checked>
                                                <label for="active_new_{{ $root->id }}" class="text-sm">Activo</label>
                                            </div>
                                        </div>

                                        <div class="md:col-span-4">
                                            <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">
                                                Crear hijo
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </details>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-2xl shadow p-5">
        <h2 class="font-semibold">✅ Cómo dejar “Lista de precios” con PDFs (tu caso)</h2>
        <p class="text-gray-700 mt-2">
            En los hijos de “Lista de precios”, pon en URL:
        </p>

        <ul class="list-disc pl-6 mt-2 text-gray-700">
            <li><b>Mobiliario</b> → <code>pdfs/lista-precios-mobiliario.pdf</code></li>
            <li><b>Silleria</b> → <code>pdfs/lista-precios-silleria.pdf</code></li>
        </ul>

        <p class="text-gray-700 mt-3">
            Luego, al hacer click en el menú, el PDF se abre en la ventana emergente (modal) sin salir del Home.
        </p>
    </div>

</div>
@endsection
{{-- resources/views/admin/menu-products/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $menuKey = request('menu_key', '');     // 👈 ya no truena si no viene
  $redirectTo = request('redirect_to', url()->previous() ?: url()->current());
@endphp

<div style="max-width:1100px;margin:26px auto;padding:0 16px;">

  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:900;">Productos del menú</div>
      <div style="opacity:.7;font-weight:650;margin-top:4px;">
        Aquí agregas / editas productos por <code>menu_key</code>.
      </div>
    </div>

    <a href="{{ $redirectTo }}" class="btn btn-secondary" style="text-decoration:none;">
      ← Volver
    </a>
  </div>

  @if(session('ok'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(16,185,129,.3);background:rgba(16,185,129,.08);border-radius:12px;font-weight:800;">
      {{ session('ok') }}
    </div>
  @endif

  @if($errors->any())
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);border-radius:12px;">
      <div style="font-weight:900;margin-bottom:6px;">Corrige esto:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- =========================
       ✅ AGREGAR PRODUCTO
     ========================= --}}
  <form method="POST"
        action="{{ route('admin.menu-products.store') }}"
        enctype="multipart/form-data"
        style="margin-top:18px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    @csrf

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    <div style="padding:14px 16px;border-bottom:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Agregar producto
    </div>

    <div style="padding:16px;display:grid;grid-template-columns:1.2fr 1fr;gap:12px;">
      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">menu_key (ruta destino)</label>
        <input name="menu_key"
               value="{{ old('menu_key', $menuKey) }}"
               placeholder="productos/detalles-de-productos/escritorios/anzio"
               style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
        <div style="opacity:.7;font-weight:650;font-size:12px;margin-top:6px;">
          Tip: este es el nivel donde se mostrará el producto.
        </div>
      </div>

      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">Título</label>
        <input name="title"
               value="{{ old('title') }}"
               style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
      </div>

      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">Descripción (opcional)</label>
        <textarea name="description" rows="3"
                  style="width:100%;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:650;outline:none;">{{ old('description') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">URL (opcional)</label>
          <input name="url"
                 value="{{ old('url') }}"
                 placeholder="https://..."
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
        </div>

        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">Orden (sort)</label>
          <input type="number" name="sort"
                 value="{{ old('sort', 0) }}"
                 min="0"
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:10px;">
        <label style="display:flex;align-items:center;gap:10px;font-weight:900;">
          <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
          Activo
        </label>
      </div>

      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">Imagen (opcional)</label>
        <input type="file" name="image" accept="image/*">
      </div>
    </div>

    <div style="padding:14px 16px;border-top:1px solid rgba(15,23,42,.08);display:flex;justify-content:flex-end;gap:10px;background:rgba(15,23,42,.02);">
      <button type="submit"
              style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:900;cursor:pointer;">
        Guardar producto
      </button>
    </div>
  </form>


  {{-- =========================
       ✅ LISTADO + EDITAR / BORRAR
     ========================= --}}
  <div style="margin-top:18px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    <div style="padding:14px 16px;border-bottom:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Productos ({{ $items->total() }})
    </div>

    <div style="overflow:auto;">
      <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
          <tr style="background:rgba(15,23,42,.03);text-align:left;">
            <th style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.08);">ID</th>
            <th style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.08);">menu_key</th>
            <th style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.08);">Título</th>
            <th style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.08);">Activo</th>
            <th style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.08); text-align:right;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $p)
            <tr>
              <td style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.07);font-weight:900;">
                {{ $p->id }}
              </td>

              <td style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.07);">
                <code style="font-weight:800;">{{ $p->menu_key }}</code>
              </td>

              <td style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.07);font-weight:800;">
                {{ $p->title }}
              </td>

              <td style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.07);">
                {!! $p->is_active ? '✅' : '—' !!}
              </td>

              <td style="padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.07); text-align:right;">
                <details style="display:inline-block;text-align:left;">
                  <summary style="cursor:pointer;font-weight:900;opacity:.85;">Editar</summary>

                  <div style="margin-top:10px;min-width:360px;max-width:520px;padding:12px;border:1px solid rgba(15,23,42,.12);border-radius:12px;background:#fff;">
                    <form method="POST"
                          action="{{ route('admin.menu-products.update', $p) }}"
                          enctype="multipart/form-data">
                      @csrf
                      @method('PUT')

                      <input type="hidden" name="redirect_to" value="{{ url()->current() . (request()->getQueryString() ? ('?'.request()->getQueryString()) : '') }}">

                      <div style="display:grid;grid-template-columns:1fr;gap:10px;">
                        <div>
                          <label style="display:block;font-weight:900;margin-bottom:6px;">Título</label>
                          <input name="title" value="{{ $p->title }}"
                                 style="width:100%;height:40px;border-radius:10px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:700;outline:none;">
                        </div>

                        <div>
                          <label style="display:block;font-weight:900;margin-bottom:6px;">Descripción</label>
                          <textarea name="description" rows="3"
                                    style="width:100%;border-radius:10px;border:1px solid rgba(15,23,42,.16);padding:10px;font-weight:650;outline:none;">{{ $p->description }}</textarea>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                          <div>
                            <label style="display:block;font-weight:900;margin-bottom:6px;">URL</label>
                            <input name="url" value="{{ $p->url }}"
                                   style="width:100%;height:40px;border-radius:10px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:700;outline:none;">
                          </div>
                          <div>
                            <label style="display:block;font-weight:900;margin-bottom:6px;">Sort</label>
                            <input type="number" name="sort" min="0" value="{{ (int)$p->sort }}"
                                   style="width:100%;height:40px;border-radius:10px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:700;outline:none;">
                          </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;">
                          <label style="display:flex;align-items:center;gap:10px;font-weight:900;">
                            <input type="checkbox" name="is_active" {{ $p->is_active ? 'checked' : '' }}>
                            Activo
                          </label>
                        </div>

                        <div>
                          <label style="display:block;font-weight:900;margin-bottom:6px;">Cambiar imagen</label>
                          <input type="file" name="image" accept="image/*">
                          @if($p->image_path)
                            <div style="margin-top:8px;opacity:.8;font-weight:700;font-size:12px;">
                              Actual:
                              <a href="{{ asset('storage/'.$p->image_path) }}" target="_blank" style="text-decoration:none;font-weight:900;">
                                Ver ↗
                              </a>
                            </div>
                          @endif
                        </div>

                        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:6px;">
                          <button type="submit"
                                  style="height:40px;padding:0 12px;border-radius:10px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:900;cursor:pointer;">
                            Guardar
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </details>

                <form method="POST" action="{{ route('admin.menu-products.destroy', $p) }}"
                      style="display:inline-block;margin-left:8px;"
                      onsubmit="return confirm('¿Eliminar este producto?');">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="redirect_to" value="{{ url()->current() . (request()->getQueryString() ? ('?'.request()->getQueryString()) : '') }}">
                  <button type="submit"
                          style="height:34px;padding:0 10px;border-radius:10px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);font-weight:900;cursor:pointer;">
                    Eliminar
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding:14px;font-weight:800;opacity:.75;">
                No hay productos.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="padding:14px 16px;border-top:1px solid rgba(15,23,42,.08);">
      {{ $items->links() }}
    </div>
  </div>

</div>
@endsection
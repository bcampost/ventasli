@extends('layouts.app')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px;">
  <div style="display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;">
    <div style="flex:1;min-width:520px;background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:16px;">
      <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;">Accesos rápidos (panel derecho)</h2>

      @if(session('ok'))
        <div style="background:#ecfdf5;border:1px solid #bbf7d0;color:#065f46;padding:10px 12px;border-radius:12px;margin-bottom:12px;">
          {{ session('ok') }}
        </div>
      @endif

      <div style="overflow:auto;border-radius:12px;border:1px solid rgba(0,0,0,.06);">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
          <thead>
            <tr style="background:#f8fafc;text-align:left;">
              <th style="padding:10px 12px;">Orden</th>
              <th style="padding:10px 12px;">Nombre</th>
              <th style="padding:10px 12px;">URL</th>
              <th style="padding:10px 12px;">Icono</th>
              <th style="padding:10px 12px;">Activo</th>
              <th style="padding:10px 12px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($links as $l)
              <tr style="border-top:1px solid rgba(0,0,0,.06);">
                <td style="padding:10px 12px;">{{ $l->sort_order }}</td>
                <td style="padding:10px 12px;font-weight:600;">{{ $l->name }}</td>
                <td style="padding:10px 12px;color:#334155;">{{ $l->url }}</td>
                <td style="padding:10px 12px;color:#334155;">{{ $l->icon }}</td>
                <td style="padding:10px 12px;">
                  <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-size:12px;
                    background:{{ $l->is_active ? '#ecfdf5' : '#f1f5f9' }};
                    color:{{ $l->is_active ? '#065f46' : '#475569' }};
                    border:1px solid rgba(0,0,0,.06);">
                    {{ $l->is_active ? 'Sí' : 'No' }}
                  </span>
                </td>
                <td style="padding:10px 12px;white-space:nowrap;">
                  <a href="{{ route('admin.quick-links.edit',$l) }}" style="text-decoration:none;padding:6px 10px;border-radius:10px;border:1px solid rgba(0,0,0,.10);background:#fff;color:#0f172a;font-weight:600;">
                    Editar
                  </a>

                  <form method="POST" action="{{ route('admin.quick-links.destroy',$l) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      onclick="return confirm('¿Eliminar este acceso?')"
                      style="margin-left:6px;padding:6px 10px;border-radius:10px;border:1px solid rgba(239,68,68,.35);background:#fff;color:#b91c1c;font-weight:700;cursor:pointer;">
                      Eliminar
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div style="width:360px;max-width:100%;background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:16px;">
      <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;">
        Agregar acceso
      </h3>

      <form method="POST" action="{{ route('admin.quick-links.store') }}">
        @csrf

        <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Nombre</label>
        <input name="name" value="{{ old('name') }}"
               style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

        <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">URL</label>
        <input name="url" value="{{ old('url') }}"
               placeholder="/home o https://..."
               style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

        <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Icono</label>
        <select name="icon" style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;">
          @php
            $icons = ['home','folder','briefcase','users','calc','file','shield','dot','settings'];
            $selected = old('icon', 'dot');
          @endphp
          @foreach($icons as $ic)
            <option value="{{ $ic }}" @selected($selected === $ic)>{{ $ic }}</option>
          @endforeach
        </select>

        <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Orden</label>
        <input name="sort_order" type="number" value="{{ old('sort_order', 0) }}"
               style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

        <label style="display:flex;gap:8px;align-items:center;font-size:13px;color:#0f172a;margin-bottom:12px;">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) />
          Activo
        </label>

        <button type="submit"
          style="width:100%;padding:10px 12px;border-radius:12px;border:none;background:#0f172a;color:#fff;font-weight:800;cursor:pointer;">
          Crear acceso
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
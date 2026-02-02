@extends('layouts.app')

@section('content')
<div style="max-width:780px;margin:0 auto;padding:24px;">
  <div style="background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:16px;">
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:800;">Editar acceso rápido</h2>

    <form method="POST" action="{{ route('admin.quick-links.update',$quickLink) }}">
      @csrf
      @method('PUT')

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Nombre</label>
      <input name="name" value="{{ old('name', $quickLink->name) }}"
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">URL</label>
      <input name="url" value="{{ old('url', $quickLink->url) }}"
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Icono</label>
      <select name="icon" style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;">
        @php
          $icons = ['home','folder','briefcase','users','calc','file','shield','dot','settings'];
          $selected = old('icon', $quickLink->icon ?? 'dot');
        @endphp
        @foreach($icons as $ic)
          <option value="{{ $ic }}" @selected($selected === $ic)>{{ $ic }}</option>
        @endforeach
      </select>

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Orden</label>
      <input name="sort_order" type="number" value="{{ old('sort_order', $quickLink->sort_order ?? 0) }}"
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <label style="display:flex;gap:8px;align-items:center;font-size:13px;color:#0f172a;margin-bottom:12px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', (bool)$quickLink->is_active)) />
        Activo
      </label>

      <button type="submit"
        style="width:100%;padding:10px 12px;border-radius:12px;border:none;background:#0f172a;color:#fff;font-weight:800;cursor:pointer;">
        Guardar cambios
      </button>

      <a href="{{ route('admin.quick-links.index') }}"
         style="display:block;text-align:center;margin-top:10px;text-decoration:none;color:#334155;font-weight:700;">
        Cancelar
      </a>
    </form>
  </div>
</div>
@endsection
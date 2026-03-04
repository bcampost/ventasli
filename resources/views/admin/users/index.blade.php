@extends('layouts.app')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:18px;">
  <h2 style="font-weight:900;font-size:20px;margin-bottom:10px;">Usuarios (BD centralizada) · Roles (local)</h2>

  @if(session('status'))
    <div style="padding:10px 12px;border:1px solid #cfe6cf;background:#eef8ee;border-radius:10px;margin-bottom:12px;">
      {{ session('status') }}
    </div>
  @endif

  <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:10px;align-items:center;margin-bottom:12px;">
    <input name="q" value="{{ $q }}" placeholder="Buscar por correo o nombre..."
           style="flex:1;border:1px solid #ddd;border-radius:10px;padding:10px 12px;">
    <button style="border:1px solid #ddd;border-radius:10px;padding:10px 12px;font-weight:800;background:#fff;cursor:pointer;">
      Buscar
    </button>
  </form>

  <div style="overflow:auto;border:1px solid #eee;border-radius:12px;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="background:#fafafa;">
          <th style="text-align:left;padding:10px;border-bottom:1px solid #eee;">Nombre</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid #eee;">Email</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid #eee;">Rol actual</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid #eee;">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $r)
          @php
            $ru = $r['remote'];
            $local = $r['local'];
            $role = $r['role'];
          @endphp

          <tr>
            <td style="padding:10px;border-bottom:1px solid #f1f1f1;">
              {{ $ru->name ?? '—' }}
            </td>

            <td style="padding:10px;border-bottom:1px solid #f1f1f1;">
              {{ $ru->email ?? '—' }}
            </td>

            <td style="padding:10px;border-bottom:1px solid #f1f1f1;">
              @if($local)
                <strong>{{ $role === 'admin' ? 'admin' : 'user' }}</strong>
              @else
                <span style="opacity:.7;">No existe en local</span>
              @endif
            </td>

            <td style="padding:10px;border-bottom:1px solid #f1f1f1;">
              @if($local)
                <form method="POST" action="{{ route('admin.users.role', $local) }}" style="display:flex;gap:10px;align-items:center;">
                  @csrf
                  @method('PUT')

                  <select name="role" style="border:1px solid #ddd;border-radius:10px;padding:8px 10px;">
                    <option value="user"  @selected($role==='user')>Normal</option>
                    <option value="admin" @selected($role==='admin')>Admin</option>
                  </select>

                  <button style="border:1px solid #ddd;border-radius:10px;padding:8px 12px;font-weight:800;background:#fff;cursor:pointer;">
                    Guardar
                  </button>
                </form>
              @else
                <span style="opacity:.7;">(Se creará en local al listar / al iniciar sesión)</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:12px;">
    {{ $remoteUsers->links() }}
  </div>
</div>
@endsection
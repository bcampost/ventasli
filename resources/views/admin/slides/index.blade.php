{{-- resources/views/admin/slides/index.blade.php --}}
@extends('layouts.app')

@section('content')
  @php
    $items = $slides ?? $items ?? $data ?? null;
  @endphp

  <style>
    :root {
      --ink: #0b1220;
      --muted: rgba(15, 23, 42, .62);
      --line: rgba(15, 23, 42, .12);
      --line2: rgba(15, 23, 42, .18);
      --soft: rgba(248, 250, 252, .75);

      --shadowL: 0 18px 50px rgba(15, 23, 42, .12);
      --shadowM: 0 12px 30px rgba(15, 23, 42, .10);

      --primary: #2563eb;
      --primary2: #1d4ed8;

      --rXL: 24px;
      --rL: 18px;
      --rM: 14px;
    }

    .wrap {
      max-width: 1180px;
      margin: 0 auto;
      padding: 22px 18px 28px;
    }

    .topbar {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 14px;
    }

    .title {
      font-size: 1.55rem;
      font-weight: 950;
      letter-spacing: -.02em;
      color: var(--ink);
      line-height: 1.1;
    }

    .subtitle {
      margin-top: 8px;
      color: var(--muted);
      font-weight: 700;
      font-size: .95rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      font-weight: 300;
      border-radius: 16px;
      padding: .72rem .92rem;
      font-size: .86rem;
      border: 1px solid transparent;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
      user-select: none;
      white-space: nowrap;
    }

    .btn:active {
      transform: translateY(1px);
    }

    .btn-primary {
      background: linear-gradient(180deg, rgba(37, 99, 235, 1), rgba(29, 78, 216, 1));
      color: #fff;
      box-shadow: 0 14px 30px rgba(37, 99, 235, .22);
      text-decoration: none;
    }

    .btn-primary:hover {
      opacity: .96;
    }

    .panel {
      border: 1px solid var(--line);
      border-radius: var(--rXL);
      background:
        radial-gradient(900px 260px at 15% 0%, rgba(37, 99, 235, .06), transparent 55%),
        #fff;
      overflow: hidden;
      box-shadow: var(--shadowM);
    }

    .panel-head {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(255, 255, 255, .78);
      gap: 12px;
    }

    .panel-head .h {
      font-weight: 950;
      letter-spacing: -.01em;
      color: var(--ink);
    }

    .table-wrap {
      width: 100%;
      overflow: auto;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 940px;
    }

    thead th {
      text-align: left;
      padding: 12px 14px;
      font-size: .85rem;
      letter-spacing: .02em;
      color: rgba(15, 23, 42, .70);
      font-weight: 300;
      background: rgba(248, 250, 252, .80);
      border-bottom: 1px solid rgba(15, 23, 42, .10);
      position: sticky;
      top: 0;
      z-index: 1;
    }

    tbody td {
      padding: 12px 14px;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      vertical-align: middle;
      color: rgba(15, 23, 42, .86);
      font-weight: 750;
      font-size: .92rem;
      background: #fff;
    }

    tbody tr:hover td {
      background: rgba(248, 250, 252, .72);
    }

    .preview {
      width: 150px;
      height: 84px;
      border-radius: 14px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: rgba(15, 23, 42, .04);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: rgba(248, 250, 252, .75);
      font-weight: 300;
      font-size: .78rem;
      color: rgba(15, 23, 42, .72);
    }

    .badge.ok {
      border-color: rgba(16, 185, 129, .28);
      background: rgba(16, 185, 129, .10);
      color: rgba(5, 150, 105, .95);
    }

    .actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      align-items: center;
    }

    .a-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 12px;
      border-radius: 12px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: rgba(255, 255, 255, .92);
      font-weight: 300;
      font-size: .84rem;
      color: rgba(15, 23, 42, .78);
      text-decoration: none;
      box-shadow: 0 10px 22px rgba(2, 6, 23, .08);
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }

    .a-btn:hover {
      transform: translateY(-1px);
      background: #fff;
      border-color: rgba(15, 23, 42, .18);
    }

    .a-btn.danger {
      border-color: rgba(225, 29, 72, .25);
      background: rgba(225, 29, 72, .08);
      color: rgba(225, 29, 72, .92);
    }

    .a-btn.danger:hover {
      border-color: rgba(225, 29, 72, .38);
      background: rgba(225, 29, 72, .12);
    }

    .empty {
      padding: 18px;
      color: rgba(15, 23, 42, .65);
      text-align: center;
      font-weight: 800;
    }
  </style>

  <div class="wrap">
    <div class="topbar">
      <div>
        <div class="title">Avisos (Slider)</div>
        <div class="subtitle">Administra las imágenes del slider en HOME.</div>
      </div>

      <a class="btn btn-primary" href="{{ route('admin.slides.create') }}">
        + Nuevo slide
      </a>
    </div>

    <div class="panel">
      <div class="panel-head">
        <div class="h">Listado</div>
        <div style="color:rgba(15,23,42,.55); font-weight:800; font-size:.9rem;">
          {{ (is_object($items) && method_exists($items, 'total')) ? $items->total() . ' items' : '' }}
        </div>
      </div>

      @if($items && count($items))
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th style="width:190px;">Preview</th>
                <th>Título</th>
                <th>Link</th>
                <th style="width:120px;">Orden</th>
                <th style="width:120px;">Visible en Home</th>
                <th style="width:170px;">Vencimiento</th>
                <th style="width:220px; text-align:right;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($items as $slide)
                @php
                  $img = $slide->image_path ?? $slide->image ?? $slide->path ?? null;
                  $imgUrl = $img ? asset('storage/' . ltrim($img, '/')) : null;
                  $title = $slide->title ?? '—';
                  $link = $slide->link ?? $slide->url ?? '—';
                  $order = $slide->sort_order ?? $slide->sort ?? $slide->order ?? 0;
                  $active = (int) ($slide->is_active ?? 0) === 1;
                  $expiresAt = $slide->expires_at ?? null;
                  $isExpired = $expiresAt && \Carbon\Carbon::parse($expiresAt)->isPast();
                @endphp
                <tr>
                  <td>
                    <div class="preview">
                      @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="">
                      @else
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" style="opacity:.55;">
                          <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
                          <path d="M8 10h8M8 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                        </svg>
                      @endif
                    </div>
                  </td>
                  <td>{{ $title }}</td>
                  <td style="color:rgba(15,23,42,.65); font-weight:800;">
                    {{ $link }}
                  </td>
                  <td>{{ $order }}</td>
                  <td>
                    <span class="badge {{ ($active && !$isExpired) ? 'ok' : '' }}">
                      {{ ($active && !$isExpired) ? 'Sí' : 'No' }}
                    </span>
                  </td>

                  <td>
                    @if($expiresAt)
                      <span class="badge {{ $isExpired ? '' : 'ok' }}">
                        {{ \Carbon\Carbon::parse($expiresAt)->format('d/m/Y H:i') }}
                      </span>
                    @else
                      <span class="badge">Sin vencimiento</span>
                    @endif
                  </td>

                  <td>
                    <div class="actions">
                      <a class="a-btn" href="{{ route('admin.slides.edit', $slide->id) }}">Editar</a>

                      <form method="POST" action="{{ route('admin.slides.destroy', $slide->id) }}"
                        onsubmit="return confirm('¿Eliminar este slide?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="a-btn danger">Eliminar</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if(is_object($items) && method_exists($items, 'links'))
          <div style="padding: 14px 16px;">
            {{ $items->links() }}
          </div>
        @endif
      @else
        <div class="empty">No hay slides aún.</div>
      @endif
    </div>
  </div>
@endsection
@extends('layouts.app')

@php
  $slugify = function(string $text): string {
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'menu';
  };

  $keyOf = function(array $parts) use ($slugify): string {
    return implode('/', array_map(fn($p) => $slugify($p), $parts));
  };

  $images = $images ?? [];

  $imgUrl = function(string $key) use ($images) {
    $row = $images[$key] ?? null;
    if (!$row || !$row->image_path) return null;
    return asset('storage/' . ltrim($row->image_path, '/'));
  };

  $children = $section['children'] ?? [];
@endphp

@section('content')
  <style>
    .sec-wrap{ max-width:1450px; margin:0 auto; padding:22px 18px; }

    .sec-header{
      background:#fff;
      border:1px solid rgba(15,23,42,.08);
      border-radius:24px;
      padding:24px 28px;
      box-shadow: 0 2px 10px rgba(15,23,42,.03);
      margin-bottom:18px;
    }
    .sec-breadcrumb{
      display:flex; align-items:center; gap:8px;
      font-size:.88rem; color:#9aa4b2; margin-bottom:10px;
    }
    .sec-breadcrumb a{ color:#9aa4b2; text-decoration:none; }
    .sec-breadcrumb a:hover{ color:#0f172a; }
    .sec-breadcrumb b{ color:#0f172a; font-weight:700; }
    .sec-title{ font-size:1.65rem; font-weight:800; letter-spacing:-.02em; color:#0b1220; line-height:1.1; }
    .sec-subtitle{ margin-top:8px; color:#64748b; max-width:700px; line-height:1.5; font-size:.95rem; }
    .sec-admin-btn{
      display:inline-flex; align-items:center; gap:8px;
      padding:10px 16px; border-radius:14px;
      background:#111827; color:#fff; font-weight:700; text-decoration:none;
      box-shadow:0 10px 18px rgba(0,0,0,.10);
      transition:.15s ease;
    }
    .sec-admin-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 24px rgba(0,0,0,.14); }

    /* === Cards unificados (estilo Material Visual) === */
    .sec-grid{
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
      gap:22px;
      align-items:start;
    }

    .sec-card{
      position:relative;
      display:block;
      background:#fff;
      border:1px solid #e7eaf0;
      border-radius:22px;
      overflow:hidden;
      box-shadow: 0 2px 10px rgba(15,23,42,.03);
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      text-decoration:none; color:inherit;
    }
    .sec-card:hover{
      transform:translateY(-4px);
      box-shadow:0 22px 44px rgba(15,23,42,.10);
      border-color:#dbe3ee;
    }

    .sec-thumb{
      position:relative;
      aspect-ratio: 16/10;
      background:#f3f4f6;
      overflow:hidden;
      display:flex; align-items:center; justify-content:center;
    }
    .sec-thumb img{
      width:100%; height:100%; object-fit:cover; display:block;
      transition: transform .35s ease;
    }
    .sec-card:hover .sec-thumb img{ transform: scale(1.03); }

    .sec-thumb-empty{
      width:100%; height:100%;
      background: linear-gradient(135deg, #0b1220, #1e293b);
      display:flex; align-items:center; justify-content:center;
      color: rgba(255,255,255,.6);
      font-size:.85rem; font-weight:700;
    }

    .sec-chip{
      position:absolute; top:12px; left:12px;
      display:inline-flex; align-items:center;
      padding:5px 10px;
      border-radius:999px;
      font-size:11px; font-weight:700;
      background: rgba(255,255,255,.92);
      backdrop-filter: blur(8px);
      color:#0f172a;
      border:1px solid rgba(255,255,255,.7);
      box-shadow: 0 4px 12px rgba(15,23,42,.08);
    }

    .sec-body{
      padding:16px;
    }
    .sec-card-title{
      font-size:1rem; font-weight:700; color:#111827; line-height:1.35;
    }
    .sec-card-sub{
      margin-top:4px; font-size:.82rem; color:#64748b;
    }
    .sec-card-admin{
      margin-top:10px;
      font-size:.78rem; font-weight:700; color:#2563eb; text-decoration:none;
    }
    .sec-card-admin:hover{ text-decoration:underline; }

    /* Sub-opciones --> mismo card style */
    .sec-subpanel{
      margin-top:24px;
      background:#fff;
      border:1px solid rgba(15,23,42,.08);
      border-radius:24px;
      overflow:hidden;
      box-shadow: 0 2px 10px rgba(15,23,42,.03);
    }
    .sec-subpanel-head{
      padding:18px 22px;
      border-bottom:1px solid rgba(15,23,42,.06);
      background: rgba(248,250,252,.7);
      display:flex; align-items:center; justify-content:space-between; gap:12px;
    }
    .sec-subpanel-title{ font-weight:800; color:#0b1220; }
    .sec-subpanel-sub{ font-size:.85rem; color:#64748b; margin-top:2px; }
    .sec-subpanel-close{
      font-size:.85rem; font-weight:700; color:#64748b; text-decoration:none;
    }
    .sec-subpanel-close:hover{ color:#0b1220; }
    .sec-subpanel-body{ padding:22px; }

    /* Aside */
    .sec-aside{
      background:#fff;
      border:1px solid rgba(15,23,42,.08);
      border-radius:22px;
      box-shadow: 0 2px 10px rgba(15,23,42,.03);
      padding:18px;
    }
    .sec-aside h4{ font-size:.95rem; font-weight:800; color:#0b1220; margin-bottom:10px; }
    .sec-aside-item{
      display:flex; align-items:flex-start; gap:10px;
      font-size:.86rem; color:#64748b;
      margin-top:8px; line-height:1.45;
    }
    .sec-aside-dot{
      width:7px; height:7px; border-radius:999px; flex-shrink:0; margin-top:7px;
    }
    .sec-back-btn{
      display:block; margin-top:14px;
      text-align:center; padding:12px 16px;
      background:#fff; border:1px solid #e7eaf0; border-radius:16px;
      color:#0b1220; font-weight:700; text-decoration:none;
      transition:.15s ease;
    }
    .sec-back-btn:hover{ background:#f8fafc; }

    /* Layout 2 columnas */
    .sec-content{
      display:grid;
      grid-template-columns: 1fr 320px;
      gap:22px;
      align-items:start;
    }
    @media(max-width: 980px){
      .sec-content{ grid-template-columns: 1fr; }
    }
  </style>

  <div class="sec-wrap">

    {{-- HEADER --}}
    <div class="sec-header">
      <div class="sec-breadcrumb">
        <a href="{{ route('home') }}">Inicio</a>
        <span>›</span>
        <b>{{ $section['label'] }}</b>
      </div>

      <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:18px;">
        <div>
          <div class="sec-title">{{ $section['label'] }}</div>
          <div class="sec-subtitle">
            Selecciona una categoría para abrir recursos o desplegar sub-opciones.
          </div>
        </div>

        @role('admin')
          <a class="sec-admin-btn" href="{{ route('admin.menu-cards.index') }}">
            ⚙ Administrar imágenes
          </a>
        @endrole
      </div>
    </div>

    {{-- CONTENT --}}
    <div class="sec-content">

      {{-- Main cards --}}
      <div>
        <div class="sec-grid">
          @foreach($children as $child)
            @php
              $childHasChildren = !empty($child['children']);
              $childSlug = $slugify($child['label']);
              $childKey = $keyOf([$section['label'], $child['label']]);
              $src = $imgUrl($childKey);
              $isOpen = ($open === $childSlug);

              $href = $childHasChildren
                ? route('menu.section', $sectionSlug) . '?open=' . $childSlug
                : ($child['url'] ?? '#');
            @endphp

            <a class="sec-card" href="{{ $href }}">
              <div class="sec-thumb">
                @if($src)
                  <img src="{{ $src }}" alt="{{ $child['label'] }}">
                @else
                  <div class="sec-thumb-empty">Sin imagen</div>
                @endif

                <span class="sec-chip">{{ $childHasChildren ? 'Categoría' : 'Recurso' }}</span>
              </div>

              <div class="sec-body">
                <div class="sec-card-title">{{ $child['label'] }}</div>
                <div class="sec-card-sub">
                  {{ $childHasChildren
                      ? count($child['children'] ?? []) . ' sub-opciones'
                      : 'Acceso directo' }}
                </div>

                @role('admin')
                  <a
                    class="sec-card-admin"
                    href="{{ route('admin.menu-cards.index', ['focus' => $childKey]) }}"
                    onclick="event.stopPropagation();"
                  >Editar imagen</a>
                @endrole
              </div>
            </a>
          @endforeach
        </div>

        {{-- Subcards --}}
        @if($open && !empty($openedChild) && !empty($openedChild['children']))
          <div class="sec-subpanel">
            <div class="sec-subpanel-head">
              <div>
                <div class="sec-subpanel-title">Sub-opciones</div>
                <div class="sec-subpanel-sub">{{ $openedChild['label'] }}</div>
              </div>

              <a class="sec-subpanel-close" href="{{ route('menu.section', $sectionSlug) }}">Cerrar ✕</a>
            </div>

            <div class="sec-subpanel-body">
              <div class="sec-grid">
                @foreach($openedChild['children'] as $leaf)
                  @php
                    $leafKey = $keyOf([$section['label'], $openedChild['label'], $leaf['label']]);
                    $leafSrc = $imgUrl($leafKey);
                  @endphp

                  <a class="sec-card" href="{{ $leaf['url'] ?? '#' }}">
                    <div class="sec-thumb">
                      @if($leafSrc)
                        <img src="{{ $leafSrc }}" alt="{{ $leaf['label'] }}">
                      @else
                        <div class="sec-thumb-empty">Sin imagen</div>
                      @endif
                    </div>

                    <div class="sec-body">
                      <div class="sec-card-title">{{ $leaf['label'] }}</div>
                      <div class="sec-card-sub">Abrir</div>

                      @role('admin')
                        <a
                          class="sec-card-admin"
                          href="{{ route('admin.menu-cards.index', ['focus' => $leafKey]) }}"
                          onclick="event.stopPropagation();"
                        >Editar imagen</a>
                      @endrole
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>

      {{-- Aside --}}
      <aside>
        <div style="position:sticky; top:24px; display:flex; flex-direction:column; gap:12px;">
          <div class="sec-aside">
            <h4>Cómo usar</h4>
            <div class="sec-aside-item">
              <span class="sec-aside-dot" style="background:#2563eb;"></span>
              Click en una card para abrir o desplegar
            </div>
            <div class="sec-aside-item">
              <span class="sec-aside-dot" style="background:#0ea5e9;"></span>
              Sub-opciones aparecen abajo
            </div>
            @role('admin')
              <div class="sec-aside-item">
                <span class="sec-aside-dot" style="background:#f59e0b;"></span>
                Admin: puedes cargar imagen por card
              </div>
            @endrole
          </div>

          <a class="sec-back-btn" href="{{ route('home') }}">Volver al Home</a>
        </div>
      </aside>

    </div>
  </div>
@endsection
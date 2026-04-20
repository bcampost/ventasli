{{-- resources/views/menu/show.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;
  use App\Models\MenuProduct;

  // =========================
  // Fallbacks seguros
  // =========================
  $sectionSlug = $sectionSlug ?? (request()->route('sectionSlug') ?? '');
  $pathParam   = $pathParam   ?? (request()->route('path') ?? '');
  $fullPath    = $fullPath    ?? trim($sectionSlug.'/'.trim((string)$pathParam,'/'), '/');

  $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin');

  // token base64url (sin "/") para rutas admin de cards
  $tokenOf = function(string $key): string {
    return rtrim(strtr(base64_encode($key), '+/', '-_'), '=');
  };

  // =========================
  // ✅ PRODUCTOS "INFALIBLE"
  // =========================
  $fpTrim = trim((string)$fullPath, '/');

  $variants = collect([
    $fpTrim,
    '/'.$fpTrim,
    $fpTrim.'/',
    '/'.$fpTrim.'/',

    // quitar "detalles-de-productos" si en BD no existe ese tramo
    str_replace('/detalles-de-productos/', '/', $fpTrim),
    str_replace('/detalles-de-productos/', '/', '/'.$fpTrim),
    str_replace('/detalles-de-productos/', '/', $fpTrim.'/'),
    str_replace('/detalles-de-productos/', '/', '/'.$fpTrim.'/'),

    preg_replace('#/+#','/',$fpTrim),
  ])
  ->map(fn($v)=> trim((string)$v, '/'))
  ->filter()
  ->unique()
  ->values();

  $productsSafe = null;

  if (isset($products) && $products && method_exists($products,'count') && $products->count() > 0) {
    $productsSafe = $products;
    $productsMode = 'controller';
  } else {
    // 1) exact match por variantes
    $productsSafe = MenuProduct::query()
      ->where(function($q) use ($variants){
        foreach ($variants as $alt) {
          $q->orWhere('menu_key', $alt)
            ->orWhereRaw('LOWER(menu_key) = ?', [mb_strtolower($alt)]);
        }
      })
      ->orderBy('sort')
      ->orderByDesc('id')
      ->get();

    $productsMode = 'variants-exact';

    // 2) si sigue vacío: fallback por hoja (último segmento)
    if ($productsSafe->count() === 0) {
      $leaf = trim((string)Str::afterLast($fpTrim, '/'));
      $productsSafe = MenuProduct::query()
        ->where(function($q) use ($leaf){
          $q->where('menu_key', 'like', '%/'.$leaf)
            ->orWhere('menu_key', 'like', '%/'.$leaf.'/%');
        })
        ->orderBy('sort')
        ->orderByDesc('id')
        ->get();

      $productsMode = 'leaf-fallback';
    }

    // 3) si aún está vacío: fallback SOLO ADMIN para ver registros
    if ($isAdmin && $productsSafe->count() === 0) {
      $productsSafe = MenuProduct::query()
        ->where('menu_key', 'like', 'productos/%')
        ->orderByDesc('id')
        ->limit(48)
        ->get();

      $productsMode = 'admin-global-fallback';
    }
  }
@endphp

<style>
  :root{
    --ink:#0b1220;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);
    --primary:#2563eb;
    --primary2:#1d4ed8;
    --danger:#e11d48;
    --rXL: 24px;
    --tileR: 16px;

    /* ✅ “ventana” uniforme de imagen */
    --tileMediaH: 170px; /* ajusta a gusto (170–230) */
  }

/* 1) contenedor más ancho */
.wrap{
  max-width: 1360px;     /* antes 1120 */
  margin: 0 auto;
  padding: 22px 18px;
}

/* 1) contenedor más ancho */
.wrap{
  max-width: 1360px;     /* antes 1120 */
  margin: 0 auto;
  padding: 22px 18px;
}

/* 2) grid con cards más grandes */
.tile-grid{
  padding: 18px 16px 24px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); /* antes 220/4col */
  gap: 26px; /* más aire */
}

/* 3) opcional: un poquito más grande el título */
.tile-title{
  margin-top: 12px;
  font-size: 16px;
  font-weight: 750;
  opacity: .60;
}

/* 3) opcional: un poquito más grande el título */
.tile-title{
  margin-top: 12px;
  font-size: 16px;
  font-weight: 750;
  opacity: .60;
}
  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:.55rem; font-weight: 900; border-radius: 16px;
    padding: .72rem .92rem; font-size: .86rem;
    border: 1px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
    user-select:none; white-space:nowrap;
  }
  .btn:active{ transform: translateY(1px); }
  .btn-ghost{ background:#fff; border-color: var(--line); color: var(--ink); }
  .btn-ghost:hover{ background: rgba(248,250,252,.85); border-color: var(--line2); box-shadow: var(--shadowM); }
  .btn-primary{
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color:#fff; box-shadow: 0 14px 30px rgba(37,99,235,.22);
  }
  .btn-primary:hover{ opacity:.96; }

  .panel{
    border: 1px solid var(--line);
    border-radius: var(--rXL);
    background: radial-gradient(900px 260px at 15% 0%, rgba(37,99,235,.06), transparent 55%), #fff;
    overflow:hidden;
    margin-top: 14px;
  }
  .panel-head{
    padding: 14px 16px;
    border-bottom: 1px solid rgba(15,23,42,.08);
    display:flex; align-items:center; justify-content:space-between;
    background: rgba(255,255,255,.75);
    gap:12px;
  }
  .panel-head .h{ font-weight: 950; letter-spacing: -.01em; color: var(--ink); }

  .empty{ padding: 18px; color: rgba(15,23,42,.65); text-align:center; }

  .hint{ font-size: .86rem; color: rgba(15,23,42,.58); }
  .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

  /* =========================================================
     ✅ TILES PRO (imagen completa SIN recorte + tooltip encima)
     ========================================================= */
/* GRID como la foto 2 */
.tile-grid{
  padding: 18px 16px 24px;
  display:grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 22px;
}

@media(max-width: 1100px){
  .tile-grid{ grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 820px){
  .tile-grid{ grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 520px){
  .tile-grid{ grid-template-columns: 1fr; }
}

/* Card limpio */
.tile{
  position: relative;
  cursor: pointer;
}

/* Caja de imagen estilo foto 2 */
.tile-img{
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  border-radius: 14px;
  overflow: hidden;
  background: #e5e7eb; /* fallback suave */
}

/* ✅ Esto hace que se vea como la foto 2 */
.tile-img img{
  width: 100%;
  height: 100%;
  display: block;
  object-fit: cover;       /* <-- CLAVE */
  object-position: center;
}

/* Título abajo */
.tile-title{
  margin-top: 10px;
  font-size: 15px;
  font-weight: 700;
  opacity: .55;
  line-height: 1.15;
}

/* ====== ICONOS overlay (respeta tu tema) ====== */
.tile-actions{
  position:absolute;
  top:10px;
  right:10px;
  display:flex;
  gap:8px;
  z-index: 20;
}

.icon-btn{
  width: 38px;
  height: 38px;
  border-radius: 999px;
  border: 1px solid rgba(15,23,42,.14);
  background: rgba(255,255,255,.92);
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow: 0 12px 24px rgba(2,6,23,.10);
  cursor:pointer;
  transition: transform .15s ease, border-color .15s ease, background .15s ease;
}
.icon-btn:hover{
  transform: translateY(-1px);
  background:#fff;
  border-color: rgba(15,23,42,.22);
}
.icon-btn.danger{
  border-color: rgba(225,29,72,.28);
  background: rgba(225,29,72,.08);
}
.icon-btn.danger:hover{
  border-color: rgba(225,29,72,.38);
  background: rgba(225,29,72,.12);
}


  /* overlay sutil */
  .tile::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(180deg, rgba(2,6,23,.00) 55%, rgba(2,6,23,.10) 100%);
    opacity:0;
    transition: opacity .16s ease;
    pointer-events:none;
    z-index: 4;
  }
  .tile:hover::before{ opacity:1; }

  /* Tooltip encima (no empuja layout) */
  .tile-tooltip{
    position:absolute;
    left: 12px;
    right: 12px;
    bottom: 12px;
    z-index: 10;

    background: rgba(15,23,42,.92);
    color:#fff;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 12px;
    padding: 10px 10px;

    box-shadow: 0 22px 60px rgba(2,6,23,.30);
    backdrop-filter: blur(10px);

    opacity:0;
    transform: translateY(10px);
    pointer-events:none;
    transition: opacity .16s ease, transform .16s ease;
  }
  .tile:hover .tile-tooltip,
  .tile:focus-within .tile-tooltip{
    opacity:1;
    transform: translateY(0);
  }

  .tile-tooltip .tt-title{
    font-weight: 950;
    font-size: 13px;
    margin-bottom: 6px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
  }
  .tile-tooltip .tt-body{
    font-size: 12.5px;
    line-height: 1.25;
    color: rgba(255,255,255,.86);
    white-space: pre-line;
    display:-webkit-box;
    -webkit-line-clamp: 7;
    -webkit-box-orient: vertical;
    overflow:hidden;
  }
  .tile-tooltip .tt-meta{
    margin-top: 8px;
    font-size: 11.5px;
    color: rgba(255,255,255,.70);
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }
  .pill{
    display:inline-flex;
    align-items:center;
    padding: 4px 8px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.08);
    font-weight: 800;
    white-space:nowrap;
  }

  /* Acciones admin encima */
  .tile-actions{
    position:absolute;
    top:10px;
    right:10px;
    display:flex;
    gap:8px;
    z-index: 50;
  }

  .icon-btn{
    width: 38px; height: 38px; border-radius: 999px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    display:flex; align-items:center; justify-content:center;
    box-shadow: 0 12px 24px rgba(2,6,23,.10);
    cursor:pointer;
    transition: transform .15s ease, border-color .15s ease, background .15s ease;
  }
  .icon-btn:hover{ transform: translateY(-1px); background:#fff; border-color: rgba(15,23,42,.22); }
  .icon-btn.danger{ border-color: rgba(225,29,72,.28); background: rgba(225,29,72,.08); }
  .icon-btn.danger:hover{ border-color: rgba(225,29,72,.38); background: rgba(225,29,72,.12); }

  /* =========================================================
     ✅ MODALES
     ========================================================= */
  .modal-backdrop{ background: rgba(2,6,23,.72); backdrop-filter: blur(10px); }
  .modal-shell{
    border-radius: 26px; overflow:hidden;
    background: radial-gradient(900px 260px at 20% 0%, rgba(37,99,235,.12), transparent 55%), #fff;
    border: 1px solid rgba(15,23,42,.14);
    box-shadow: var(--shadowXL);
  }
  .modal-enter{ transform: translateY(12px) scale(.985); opacity:0; transition: transform .22s ease, opacity .22s ease; }
  .modal-open .modal-enter{ transform: translateY(0) scale(1); opacity:1; }
  .modal-header{
    padding: 18px 22px;
    border-bottom:1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.75);
    display:flex; align-items:flex-start; justify-content:space-between; gap:12px;
  }
  .modal-title{ font-size:1.10rem; font-weight:950; letter-spacing:-.02em; color:var(--ink); }
  .modal-sub{ margin-top:4px; font-size:.9rem; color: rgba(15,23,42,.58); }
  .modal-body{ padding: 18px 22px 22px; max-height: calc(100vh - 170px); overflow: auto; }

  .field-label{ font-size:.85rem; font-weight: 950; color: rgba(15,23,42,.78); }
  .input, .textarea, .select{
    width:100%;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    padding: .95rem 1rem;
    font-size:.95rem;
    color: var(--ink);
    outline:none;
    transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
  }
  .textarea{ padding:1rem; }
  .select{ padding:.90rem 1rem; }
  .input:focus, .textarea:focus, .select:focus{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 0 0 6px rgba(37,99,235,.14);
    background:#fff;
  }

  .no-scroll{ overflow: hidden !important; }
  body.modal-open .hide-when-modal{ opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; }

  #editCardBackdrop, #createModalBackdrop, #createProductBackdrop { z-index: 9998 !important; }
  #editCardModal, #createModal, #createProductModal { z-index: 9999 !important; }

  /* Debug admin */
  .dbg{
    padding: 12px 16px;
    font-size: 12px;
    color: rgba(15,23,42,.80);
    background: rgba(248,250,252,.85);
    border-top: 1px dashed rgba(15,23,42,.18);
  }
  .dbg b{ font-weight: 900; }
/* 1) EVITA que el grid estire las cards */
.tile-grid{
  align-items: start !important;   /* clave */
}

/* 2) QUE LA TILE NO TENGA “CAJA” (sin fondo/borde/sombra) */
.tile{
  background: transparent !important;
  border: 0 !important;
  box-shadow: none !important;
  padding: 0 !important;
  overflow: visible !important;    /* para que no “corte” nada */
  align-self: start !important;    /* no se estira */
}

/* 3) QUITA OVERLAYS QUE PUEDAN “pintar” abajo */
.tile::before{
  display: none !important;
}

/* 4) QUE SOLO SEA IMAGEN: el contenedor NO pinta fondo */
.tile-img{
  background: transparent !important;
  height: auto !important;
  min-height: 0 !important;
  overflow: hidden !important;     /* solo por bordes redondeados si quieres */
}

/* 5) IMAGEN MANDA: sin recorte, sin zoom, sin gap */
.tile-img img{
  display: block !important;
  width: 100% !important;
  height: auto !important;
  max-width: 100% !important;
  object-fit: unset !important;
}

/* ✅ imagen completa (NUNCA recorta) */
.tile-img{
  height: auto !important;
  min-height: 0 !important;
  background: transparent !important;
  overflow: visible !important; /* no cortes nada */
}

/* fuerza a que cualquier cover quede anulado */
.tile-img img{
  width: 100% !important;
  height: auto !important;
  max-width: 100% !important;
  max-height: none !important;
  object-fit: contain !important;     /* ✅ clave */
  object-position: center center !important;
  display: block !important;
}
/* rompe recortes de padres */
.tile, .tile-img{
  overflow: visible !important;
  height: auto !important;
}



</style>

<div class="wrap">

  {{-- OPCIONES (SUBMENÚS) --}}
  <div class="panel">
    <div class="panel-head">
      <div class="h">Opciones</div>

@if($isAdmin)
  <div class="head-actions" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <button type="button" class="btn btn-primary" onclick="openCreateNode(@js($currentNodeId ?? 0))">
      + Agregar submenú
    </button>
  </div>
@endif
    </div>

    @if(!empty($cards) && count($cards))
      <div class="tile-grid">
        @foreach($cards as $card)
          @php
            $key    = $card['key'] ?? '';
            $href   = $card['href'] ?? '#';
            $nodeId = $card['id'] ?? null;

            $imgRow = isset($images) ? ($images->get($key) ?? null) : null;

            $customTitle = $imgRow->title ?? ($card['customTitle'] ?? null);
            $desc        = $imgRow->description ?? ($card['description'] ?? '');
            $imgPath     = $imgRow->path ?? null;

            $imgUrl      = $imgPath ? asset('storage/' . ltrim($imgPath, '/')) : null;
            $title       = $customTitle ?: ($card['title'] ?? '—');
          @endphp

<div class="tile"
     onclick="
       if(@js($card['is_pdf'] ?? false)){
         event.preventDefault();
         event.stopPropagation();
         window.openPdfPreview(@js($href), @js($title));
       }else if(@js($href)!=='#'){
         window.location.href=@js($href);
       }
     ">

@if($isAdmin)
  <div class="tile-actions hide-when-modal">
    @if($nodeId)
      <a
        href="{{ route('admin.menu.manage', $nodeId) }}"
        class="icon-btn"
        title="Administrar opción"
        onclick="event.stopPropagation();"
      >
        ⚙️
      </a>

      <form method="POST"
            action="{{ route('admin.menu.destroy', ['menu_node' => $nodeId]) }}"
            onsubmit="event.stopPropagation(); return confirm('¿Eliminar esta opción del menú?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="icon-btn danger" title="Eliminar"
                onclick="event.preventDefault(); event.stopPropagation(); event.stopImmediatePropagation(); this.closest('form').submit();">
          🗑
        </button>
      </form>
    @endif
  </div>
@endif
              </div>
            @endif

              {{-- tooltip (SOLO hover): solo Título + Descripción --}}
              <div class="tile-tooltip" aria-hidden="true">
                <div class="tt-title">
                  <span>{{ $title }}</span>
                </div>
                <div class="tt-body">{{ trim((string)$desc) !== '' ? $desc : 'Sin descripción.' }}</div>
              </div>

            {{-- imagen (full-bleed) --}}
            <div class="tile-img">
              @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $title }}">
              @else
                <img
                  src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='700'%3E%3Crect width='100%25' height='100%25' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-size='34' font-family='Arial'%3ESin%20imagen%3C/text%3E%3C/svg%3E"
                  alt="Sin imagen"
                >
              @endif
            </div>

            {{-- título abajo como la foto 2 --}}
            <div class="tile-title">{{ $title }}</div>

          </div>
        @endforeach
      </div>
    @else
      <div class="empty">No hay opciones en este nivel.</div>
    @endif
  </div>



</div>

@if($isAdmin)


  {{-- =========================
      ✅ MODAL: CREAR SUBMENÚ
      ========================= --}}
  <div id="createModalBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeCreateNode()"></div>
  <div id="createModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Agregar submenú</div>
            <div class="modal-sub">Crea una opción dentro del nivel actual.</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeCreateNode()">Cerrar ✕</button>
        </div>

        <form method="POST" action="{{ route('admin.menu.store') }}">
          @csrf
          <input type="hidden" name="parent_id" id="create_parent_id" value="">
          <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? url()->current() }}">

          <div class="modal-body">
            <div class="space-y-4">
              <div>
                <label class="field-label">Nombre</label>
                <input name="label" type="text" required class="input" placeholder="Ej. Mobiliario">
              </div>

              <div>
                <label class="field-label">URL (opcional)</label>
                <input name="url" type="text" class="input" placeholder="#">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="field-label">Orden</label>
                  <input name="sort" type="number" min="0" value="0" class="input">
                </div>

                <div class="flex items-end gap-2 pb-1">
                  <input id="create_active" name="is_active" type="checkbox" class="rounded" checked>
                  <label for="create_active" class="field-label" style="margin:0;">Activo</label>
                </div>
              </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeCreateNode()">Cancelar</button>
              <button class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>


  <script>
    function lockBodyScroll(lock) {
      const b = document.body;
      if (lock) { b.classList.add('no-scroll'); b.classList.add('modal-open'); }
      else { b.classList.remove('no-scroll'); b.classList.remove('modal-open'); }
    }

    // CREATE SUBMENU
    function openCreateNode(parentId) {
      const input = document.getElementById('create_parent_id');
      if (input) input.value = parentId || 0;

      document.getElementById('createModalBackdrop')?.classList.remove('hidden');
      const modal = document.getElementById('createModal');
      modal?.classList.remove('hidden');
      modal?.classList.add('modal-open');
      lockBodyScroll(true);
    }
    function closeCreateNode() {
      document.getElementById('createModalBackdrop')?.classList.add('hidden');
      const modal = document.getElementById('createModal');
      modal?.classList.remove('modal-open');
      modal?.classList.add('hidden');
      lockBodyScroll(false);
    }

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeCreateNode();
  }
});

    // exponer por si lo llamas desde otros lados

    window.openCreateNode = openCreateNode;
    window.closeCreateNode = closeCreateNode;
  </script>
@endif

@role('admin')
  @php
    // ✅ usa el nodo si viene; si no, usa currentNodeId
    $manageId = $node->id ?? ($currentNodeId ?? null);
  @endphp

  @if($manageId)
    <a
      href="{{ route('admin.menu.manage', $manageId) }}"
      title="Editar este menú"
      style="
        position: fixed;
        right: 18px;
        bottom: 18px;
        width: 54px;
        height: 54px;
        border-radius: 999px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: #111827;
        color: #fff;
        box-shadow: 0 16px 28px rgba(0,0,0,.22);
        z-index: 9999;
        text-decoration: none;
      "
    >
      ⚙️
    </a>
  @endif
@endrole

@endsection
{{-- resources/views/menu/show.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  // ✅ fallback seguro para evitar "Undefined variable $fullPath"
  $sectionSlug = $sectionSlug ?? (request()->route('sectionSlug') ?? '');
  $pathParam   = $pathParam   ?? (request()->route('path') ?? '');
  $fullPath    = $fullPath    ?? trim($sectionSlug.'/'.trim((string)$pathParam,'/'), '/');

  $safeStr = function($v){
    return is_string($v) ? $v : '';
  };

  $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
@endphp

<style>
  :root{
    --ink:#0b1220;
    --muted:#5b6b84;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --soft: rgba(248,250,252,.70);

    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowL: 0 18px 50px rgba(15,23,42,.14);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);

    --primary:#2563eb;
    --primary2:#1d4ed8;
    --danger:#e11d48;

    --rXL: 24px;
    --rL: 18px;
    --rM: 14px;
  }

  .wrap{ max-width: 1120px; margin:0 auto; padding: 26px 18px; }

  .topbar{
    display:flex; align-items:flex-start; justify-content:space-between; gap:16px;
    margin-bottom: 14px;
  }
  .title{
    font-size: 1.55rem;
    font-weight: 950;
    letter-spacing: -.02em;
    color: var(--ink);
    line-height: 1.1;
  }
  .subtitle{
    margin-top: 8px;
    color: rgba(15,23,42,.60);
    font-size: .95rem;
    max-width: 68ch;
  }

  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:.55rem;
    font-weight: 900;
    border-radius: 16px;
    padding: .72rem .92rem;
    font-size: .86rem;
    border: 1px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
    user-select:none; white-space:nowrap;
  }
  .btn:active{ transform: translateY(1px); }

  .btn-ghost{
    background:#fff;
    border-color: var(--line);
    color: var(--ink);
  }
  .btn-ghost:hover{
    background: rgba(248,250,252,.85);
    border-color: var(--line2);
    box-shadow: var(--shadowM);
  }

  .btn-primary{
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color:#fff;
    box-shadow: 0 14px 30px rgba(37,99,235,.22);
  }
  .btn-primary:hover{ opacity:.96; }

  .btn-danger{
    background: rgba(225,29,72,.08);
    border-color: rgba(225,29,72,.25);
    color: var(--danger);
  }
  .btn-danger:hover{
    background: rgba(225,29,72,.12);
    border-color: rgba(225,29,72,.35);
  }

  .panel{
    border: 1px solid var(--line);
    border-radius: var(--rXL);
    background:
      radial-gradient(900px 260px at 15% 0%, rgba(37,99,235,.06), transparent 55%),
      #fff;
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
  .panel-head .h{
    font-weight: 950;
    letter-spacing: -.01em;
    color: var(--ink);
  }
  .panel-head .head-actions{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
  }

  /* ✅ Grid cards */
  .grid-cards{
    padding: 16px;
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
  }

  .card{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    background: #fff;
    overflow:hidden;
    box-shadow: 0 10px 24px rgba(2,6,23,.06);
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    cursor:pointer;
    position:relative;
  }
  .card:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(2,6,23,.10);
    border-color: rgba(15,23,42,.18);
  }

  .card-media{
    height: 120px;
    background: rgba(15,23,42,.04);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
  }
  .card-media img{
    width:100%;
    height:100%;
    object-fit: cover;
    display:block;
  }
  .card-body{
    padding: 12px 12px 14px;
  }
  .card-title{
    font-weight: 950;
    color: var(--ink);
    letter-spacing: -.01em;
    line-height: 1.1;
    font-size: 1rem;
    margin-bottom: 6px;
  }
  .card-desc{
    color: rgba(15,23,42,.62);
    font-size: .9rem;
    line-height: 1.25;
    min-height: 2.3em;
  }

  .card-actions{
    position:absolute;
    top:10px;
    right:10px;
    display:flex;
    gap:8px;
    z-index: 5;
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

  .empty{
    padding: 18px;
    color: rgba(15,23,42,.65);
    text-align:center;
  }

  /* Modals */
  .modal-backdrop{
    background: rgba(2,6,23,.72);
    backdrop-filter: blur(10px);
  }
  .modal-shell{
    border-radius: 26px;
    overflow:hidden;
    background:
      radial-gradient(900px 260px at 20% 0%, rgba(37,99,235,.12), transparent 55%),
      #fff;
    border: 1px solid rgba(15,23,42,.14);
    box-shadow: var(--shadowXL);
  }
  .modal-enter{
    transform: translateY(12px) scale(.985);
    opacity:0;
    transition: transform .22s ease, opacity .22s ease;
  }
  .modal-open .modal-enter{
    transform: translateY(0) scale(1);
    opacity:1;
  }
  .modal-header{
    padding: 18px 22px;
    border-bottom:1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.75);
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }
  .modal-title{ font-size:1.10rem; font-weight:950; letter-spacing:-.02em; color:var(--ink); }
  .modal-sub{ margin-top:4px; font-size:.9rem; color: rgba(15,23,42,.58); }
  .modal-body{
    padding: 18px 22px 22px;
    max-height: calc(100vh - 170px);
    overflow: auto;
  }

  .field-label{
    font-size:.85rem;
    font-weight: 950;
    color: rgba(15,23,42,.78);
  }
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

  .preview-card{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    overflow:hidden;
    background:#fff;
  }
  .preview-media{
    height: 140px;
    background: rgba(15,23,42,.04);
    display:flex; align-items:center; justify-content:center;
  }
  .preview-media img{
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    display:block;
  }

  .no-scroll{ overflow: hidden !important; }

  /* small info line */
  .hint{
    font-size: .86rem;
    color: rgba(15,23,42,.58);
  }
  .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

  /* ✅ Cuando un modal esté abierto, oculta botones flotantes de edición/eliminar del fondo */
  body.modal-open .hide-when-modal{
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
  }

  /* ✅ FIX “a prueba de balas”: modal siempre arriba de todo */
  #editModalBackdrop,
  #createModalBackdrop,
  #createProductBackdrop,
  #editProductBackdrop{ z-index: 9998 !important; }

  #editModal,
  #createModal,
  #createProductModal,
  #editProductModal{ z-index: 9999 !important; }
</style>

<div class="wrap">

  <div class="topbar">
    <div>
      <div class="title">{{ $current['label'] ?? $section['label'] ?? 'Menú' }}</div>
      <div class="subtitle">
        Opciones principales. Selecciona una opción para entrar.
      </div>
    </div>
  </div>

  {{-- =======================
       OPCIONES (SUBMENÚS)
       ======================= --}}
  <div class="panel">
    <div class="panel-head">
      <div class="h">Opciones</div>

      @if($isAdmin)
        <div class="head-actions">
          <button type="button" class="btn btn-primary" onclick="openCreateNode(@js($currentNodeId ?? 0))">
            + Agregar submenú
          </button>

          <button type="button" class="btn btn-primary" onclick="openCreateProduct()">
            + Agregar producto
          </button>
        </div>
      @endif
    </div>

    @if(!empty($cards) && count($cards))
      <div class="grid-cards">
        @foreach($cards as $i => $card)
          @php
            $key = $card['key'] ?? '';
            $href = $card['href'] ?? '#';
            $nodeId = $card['id'] ?? null;

            $imgRow = isset($images) ? ($images->get($key) ?? null) : null;
            $customTitle = $imgRow->title ?? ($card['customTitle'] ?? null);
            $desc = $imgRow->description ?? ($card['description'] ?? '');
            $imgUrl = null;
            if ($imgRow && !empty($imgRow->path)) $imgUrl = asset('storage/' . ltrim($imgRow->path, '/'));

            $title = $customTitle ?: ($card['title'] ?? '—');
          @endphp

          <div class="card" onclick="if(@js($href) !== '#') window.location.href = @js($href);">
            <div class="card-actions hide-when-modal" onclick="event.stopPropagation();">
              @if($isAdmin)
                {{-- ✏️ lápiz para editar card (imagen/título/desc) --}}
                <button type="button" class="icon-btn" title="Editar card"
                  onclick="openEditCardModal(@js($key), @js($customTitle ?: ''), @js($desc ?: ''), @js($imgUrl ?: ''), @js($card['title'] ?? ''))">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z"
                          stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                  </svg>
                </button>

                @if($nodeId)
                  {{-- 🗑️ eliminar nodo --}}
                  <form method="POST" action="{{ route('admin.menu.destroy', ['menu_node' => $nodeId]) }}"
                        onsubmit="return confirm('¿Eliminar esta opción del menú?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn danger" title="Eliminar">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M4 7h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        <path d="M6 7l1 14h10l1-14" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="M9 7V4h6v3" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </form>
                @endif
              @endif
            </div>

            <div class="card-media">
              @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="">
              @else
                <svg width="46" height="46" viewBox="0 0 24 24" fill="none" style="opacity:.55;">
                  <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6"/>
                  <path d="M8 10h8M8 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
              @endif
            </div>

            <div class="card-body">
              <div class="card-title" title="{{ $title }}">{{ $title }}</div>
              <div class="card-desc">{{ $desc ?: ' ' }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty">No hay opciones en este nivel.</div>
    @endif
  </div>

  {{-- =======================
       PRODUCTOS (DEL NIVEL)
       ======================= --}}
  <div class="panel">
    <div class="panel-head">
      <div class="h">Productos</div>
      <div class="hint">
        Se muestran los productos cuyo <span class="mono">menu_key</span> coincide con:
        <span class="mono">{{ $fullPath }}</span>
      </div>
    </div>

    @if(!empty($products) && $products->count())
      <div class="grid-cards">
        @foreach($products as $p)
          @php
            $pTitle = $p->title ?? '—';
            $pDesc  = $p->description ?? '';
            $pUrl   = $p->url ?? '';
          @endphp

          <div class="card" onclick="if(@js($pUrl) && @js($pUrl) !== '') window.open(@js($pUrl), '_blank');">
            <div class="card-actions hide-when-modal" onclick="event.stopPropagation();">
              @if($isAdmin)
                {{-- ✏️ editar producto (modal simple) --}}
                <button type="button" class="icon-btn" title="Editar producto"
                  onclick="openEditProduct(@js($p->id), @js($pTitle), @js($pDesc), @js($pUrl), @js((int)$p->sort), @js((int)($p->is_active ?? 1)))">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z"
                          stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                  </svg>
                </button>

                {{-- 🗑️ eliminar producto --}}
                <form method="POST" action="{{ route('admin.menu-products.destroy', ['menu_product' => $p->id]) }}"
                      onsubmit="return confirm('¿Eliminar este producto?');">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? url()->current() }}">
                  <button type="submit" class="icon-btn danger" title="Eliminar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                      <path d="M4 7h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                      <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                      <path d="M6 7l1 14h10l1-14" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                      <path d="M9 7V4h6v3" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    </svg>
                  </button>
                </form>
              @endif
            </div>

            <div class="card-media">
              <svg width="46" height="46" viewBox="0 0 24 24" fill="none" style="opacity:.55;">
                <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6"/>
                <path d="M7 9h10M7 12h7M7 15h9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>

            <div class="card-body">
              <div class="card-title" title="{{ $pTitle }}">{{ $pTitle }}</div>
              <div class="card-desc">{{ $pDesc ?: ' ' }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty">No hay productos en este nivel.</div>
    @endif
  </div>

</div>

{{-- =======================
     MODALES (ADMIN)
     ======================= --}}
@if($isAdmin)

  {{-- EDITAR CARD (menu_card_images) --}}
  <div id="editModalBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeEditCardModal()"></div>
  <div id="editModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-2xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Editar card</div>
            <div id="editModalSmall" class="modal-sub">—</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeEditCardModal()">
            Cerrar ✕
          </button>
        </div>

        <form id="editCardForm" method="POST" action="#" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="modal-body">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <div class="md:col-span-5">
                <div class="preview-card">
                  <div class="preview-media">
                    <img id="editPreviewImg" src="" alt="" class="hidden">
                    <div id="editNoImg" class="text-sm text-slate-400 font-semibold">Sin imagen</div>
                  </div>
                  <div class="p-3">
                    <div id="editPreviewTitle" class="font-extrabold text-slate-900 truncate">—</div>
                    <div id="editPreviewDesc" class="text-sm text-slate-600 mt-1 line-clamp-3">—</div>
                  </div>
                </div>
              </div>

              <div class="md:col-span-7 space-y-4">
                <div>
                  <label class="field-label">Título (opcional)</label>
                  <input id="edit_title" name="title" type="text" class="input" placeholder="Título personalizado">
                </div>

                <div>
                  <label class="field-label">Descripción</label>
                  <textarea id="edit_description" name="description" rows="4" class="textarea" placeholder="Descripción breve"></textarea>
                </div>

                <div>
                  <label class="field-label">Imagen</label>
                  <input id="edit_image" name="image" type="file" accept="image/*" class="input" style="padding:.75rem 1rem;">
                </div>
              </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeEditCardModal()">Cancelar</button>
              <button class="btn btn-primary">Guardar cambios</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  {{-- CREAR SUBMENÚ --}}
  <div id="createModalBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeCreateNode()"></div>
  <div id="createModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Agregar submenú</div>
            <div class="modal-sub">Crea una opción dentro del nivel actual.</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeCreateNode()">
            Cerrar ✕
          </button>
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

  {{-- CREAR PRODUCTO --}}
  <div id="createProductBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeCreateProduct()"></div>
  <div id="createProductModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-content-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Agregar producto</div>
            <div class="modal-sub">Asigna el producto a una opción (menu_key).</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeCreateProduct()">
            Cerrar ✕
          </button>
        </div>

        <form method="POST" action="{{ route('admin.menu-products.store') }}">
          @csrf
          <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? url()->current() }}">

          <div class="modal-body">
            <div class="space-y-4">
              <div>
                <label class="field-label">Asignar a</label>
                <select name="menu_key" class="select" required>
                  @foreach(($productTargets ?? []) as $t)
                    <option value="{{ $t['menu_key'] }}">{{ $t['label'] }} — {{ $t['menu_key'] }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="field-label">Título</label>
                <input name="title" type="text" required class="input" placeholder="Ej. Bench">
              </div>

              <div>
                <label class="field-label">Descripción</label>
                <textarea name="description" rows="4" class="textarea" placeholder="Descripción breve"></textarea>
              </div>

              <div>
                <label class="field-label">URL (opcional)</label>
                <input name="url" type="text" class="input" placeholder="https://...">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="field-label">Orden</label>
                  <input name="sort" type="number" min="0" value="0" class="input">
                </div>

                <div class="flex items-end gap-2 pb-1">
                  <input id="prod_active" name="is_active" type="checkbox" class="rounded" checked>
                  <label for="prod_active" class="field-label" style="margin:0;">Activo</label>
                </div>
              </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeCreateProduct()">Cancelar</button>
              <button class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  {{-- EDITAR PRODUCTO (modal simple) --}}
  <div id="editProductBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeEditProduct()"></div>
  <div id="editProductModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Editar producto</div>
            <div class="modal-sub" id="editProductSmall">—</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeEditProduct()">
            Cerrar ✕
          </button>
        </div>

        <form id="editProductForm" method="POST" action="#">
          @csrf
          @method('PUT')
          <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? url()->current() }}">

          <div class="modal-body">
            <div class="space-y-4">
              <div>
                <label class="field-label">Título</label>
                <input id="ep_title" name="title" type="text" required class="input">
              </div>

              <div>
                <label class="field-label">Descripción</label>
                <textarea id="ep_description" name="description" rows="4" class="textarea"></textarea>
              </div>

              <div>
                <label class="field-label">URL (opcional)</label>
                <input id="ep_url" name="url" type="text" class="input">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="field-label">Orden</label>
                  <input id="ep_sort" name="sort" type="number" min="0" value="0" class="input">
                </div>

                <div class="flex items-end gap-2 pb-1">
                  <input id="ep_active" name="is_active" type="checkbox" class="rounded">
                  <label for="ep_active" class="field-label" style="margin:0;">Activo</label>
                </div>
              </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeEditProduct()">Cancelar</button>
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
      if (lock) {
        b.classList.add('no-scroll');
        b.classList.add('modal-open'); // ✅ IMPORTANTÍSIMO
      } else {
        b.classList.remove('no-scroll');
        b.classList.remove('modal-open'); // ✅ IMPORTANTÍSIMO
      }
    }

    // =========================
    // EDIT CARD MODAL
    // =========================
    function openEditCardModal(key, title, description, imgUrl, fallbackTitle) {
      const backdrop = document.getElementById('editModalBackdrop');
      const modal = document.getElementById('editModal');
      const form = document.getElementById('editCardForm');

      // ✅ IMPORTANTE: hacemos template y reemplazamos con key SIN encode para permitir "/"
      const actionTpl = @js(route('admin.menu-cards.update', ['token' => '__TOKEN__']));
      form.action = actionTpl.replace('__TOKEN__', key);

      document.getElementById('editModalSmall').textContent = `Key: ${key}`;
      document.getElementById('edit_title').value = title || '';
      document.getElementById('edit_description').value = description || '';

      document.getElementById('editPreviewTitle').textContent = (title || fallbackTitle || '—');
      document.getElementById('editPreviewDesc').textContent = (description || '—');

      const img = document.getElementById('editPreviewImg');
      const no = document.getElementById('editNoImg');

      if (imgUrl) {
        img.src = imgUrl;
        img.classList.remove('hidden');
        no.classList.add('hidden');
      } else {
        img.src = '';
        img.classList.add('hidden');
        no.classList.remove('hidden');
      }

      const fileInput = document.getElementById('edit_image');
      fileInput.value = '';
      fileInput.onchange = (e) => {
        const f = e.target.files && e.target.files[0];
        if (!f) return;
        const url = URL.createObjectURL(f);
        img.src = url;
        img.classList.remove('hidden');
        no.classList.add('hidden');
      };

      backdrop.classList.remove('hidden');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeEditCardModal() {
      const backdrop = document.getElementById('editModalBackdrop');
      const modal = document.getElementById('editModal');
      modal.classList.remove('modal-open');
      backdrop.classList.add('hidden');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    document.addEventListener('input', (e) => {
      if (e.target && e.target.id === 'edit_title') {
        document.getElementById('editPreviewTitle').textContent = e.target.value || '—';
      }
      if (e.target && e.target.id === 'edit_description') {
        document.getElementById('editPreviewDesc').textContent = e.target.value || '—';
      }
    });

    // =========================
    // CREATE SUBMENU
    // =========================
    function openCreateNode(parentId) {
      document.getElementById('create_parent_id').value = parentId || 0;

      document.getElementById('createModalBackdrop').classList.remove('hidden');
      const modal = document.getElementById('createModal');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeCreateNode() {
      document.getElementById('createModalBackdrop').classList.add('hidden');
      const modal = document.getElementById('createModal');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    // =========================
    // CREATE PRODUCT
    // =========================
    function openCreateProduct(){
      document.getElementById('createProductBackdrop').classList.remove('hidden');
      const modal = document.getElementById('createProductModal');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeCreateProduct(){
      document.getElementById('createProductBackdrop').classList.add('hidden');
      const modal = document.getElementById('createProductModal');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    // =========================
    // EDIT PRODUCT
    // =========================
    function openEditProduct(id, title, desc, url, sort, active){
      const backdrop = document.getElementById('editProductBackdrop');
      const modal = document.getElementById('editProductModal');
      const form = document.getElementById('editProductForm');

      const actionTpl = @js(route('admin.menu-products.update', ['menu_product' => '__ID__']));
      form.action = actionTpl.replace('__ID__', String(id));

      document.getElementById('editProductSmall').textContent = `ID: ${id}`;
      document.getElementById('ep_title').value = title || '';
      document.getElementById('ep_description').value = desc || '';
      document.getElementById('ep_url').value = url || '';
      document.getElementById('ep_sort').value = (sort ?? 0);
      document.getElementById('ep_active').checked = (Number(active) === 1);

      backdrop.classList.remove('hidden');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeEditProduct(){
      document.getElementById('editProductBackdrop').classList.add('hidden');
      const modal = document.getElementById('editProductModal');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeEditCardModal();
        closeCreateNode();
        closeCreateProduct();
        closeEditProduct();
      }
    });

    // Exponer global por si lo llamas desde layout
    window.openCreateNode = openCreateNode;
    window.closeCreateNode = closeCreateNode;
  </script>
@endif

@endsection
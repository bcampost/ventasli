@extends('layouts.app')

@section('content')
@php
  $safe = fn($v) => is_string($v) ? $v : '';
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
  }
  .panel-head{
    padding: 14px 16px;
    border-bottom: 1px solid rgba(15,23,42,.08);
    display:flex; align-items:center; justify-content:space-between;
    background: rgba(255,255,255,.75);
  }
  .panel-head .h{
    font-weight: 950;
    letter-spacing: -.01em;
    color: var(--ink);
  }
  .head-actions{
    display:flex;
    gap:10px;
    align-items:center;
  }

  /* ✅ Cards Grid */
  .card-grid{
    display:grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 14px;
    padding: 14px;
  }
  @media (min-width: 768px){
    .card-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
  }
  @media (min-width: 1100px){
    .card-grid{ grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
  }

  .mcard{
    position: relative;
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    background: #fff;
    overflow:hidden;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    box-shadow: 0 10px 22px rgba(2,6,23,.06);
  }
  .mcard:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 50px rgba(2,6,23,.12);
    border-color: rgba(15,23,42,.18);
  }

  .mcard-media{
    height: 140px;
    background: rgba(15,23,42,.04);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
  }
  .mcard-media img{
    width:100%;
    height:100%;
    object-fit: cover;
    display:block;
  }
  .mcard-body{
    padding: 14px 14px 12px;
  }
  .mcard-title{
    font-weight: 950;
    color: #0b1220;
    letter-spacing: -.01em;
    font-size: 1.02rem;
    line-height: 1.2;
  }
  .mcard-desc{
    margin-top: 6px;
    color: rgba(15,23,42,.62);
    font-weight: 700;
    font-size: .88rem;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow:hidden;
    min-height: calc(1.35em * 2);
  }

  .mcard-actions{
    padding: 0 14px 14px;
    display:flex;
    gap:10px;
    align-items:center;
    justify-content: space-between;
  }

  .mcard-open{
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-weight: 900;
    font-size: .86rem;
    border-radius: 999px;
    padding: .55rem .75rem;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(248,250,252,.75);
    color: rgba(15,23,42,.88);
    text-decoration:none;
  }
  .mcard-open:hover{
    background:#fff;
    border-color: rgba(15,23,42,.20);
  }

  .mcard-admin{
    display:flex;
    align-items:center;
    gap:8px;
  }

  .icon-btn{
    width: 40px; height: 40px;
    border-radius: 14px;
    display:flex; align-items:center; justify-content:center;
    border: 1px solid rgba(15,23,42,.12);
    background: #fff;
    cursor:pointer;
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease, background .15s ease;
  }
  .icon-btn:hover{
    transform: translateY(-1px);
    box-shadow: 0 12px 26px rgba(2,6,23,.10);
    border-color: rgba(15,23,42,.20);
    background: rgba(248,250,252,.85);
  }
  .icon-btn svg{
    width: 18px;
    height: 18px;
    color: rgba(15,23,42,.78);
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
  .select{ padding: .9rem 1rem; }
  .textarea{ padding:1rem; }
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

  {{-- PANEL SUBMENUS --}}
  <div class="panel">
    <div class="panel-head">
      <div class="h">Opciones</div>

      @role('admin')
        <div class="head-actions">
          <button type="button" class="btn btn-primary" onclick="openCreateNode(@js($currentNodeId))">
            + Agregar submenú
          </button>

          <button type="button" class="btn btn-primary" onclick="openCreateProduct()">
            + Agregar producto
          </button>
        </div>
      @endrole
    </div>

    @if(count($cards))
      <div class="card-grid">
        @foreach($cards as $card)
          @php
            $nodeId = $card['id'] ?? null;

            $imgRow = $images->get($card['key']) ?? null;
            $customTitle = $imgRow->title ?? ($card['customTitle'] ?? null);
            $desc = $imgRow->description ?? ($card['description'] ?? '');
            $imgUrl = null;
            if ($imgRow && !empty($imgRow->path)) $imgUrl = asset('storage/' . ltrim($imgRow->path, '/'));

            $title = $customTitle ?: ($card['title'] ?? '—');
            $href  = $card['href'] ?? '#';
          @endphp

          <div class="mcard">
            <div class="mcard-media"
                 onclick="if(@js($href) !== '#') window.location.href = @js($href);"
                 style="cursor:pointer;">
              @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $title }}">
              @else
                <div class="text-sm font-extrabold text-slate-400">Sin imagen</div>
              @endif
            </div>

            <div class="mcard-body"
                 onclick="if(@js($href) !== '#') window.location.href = @js($href);"
                 style="cursor:pointer;">
              <div class="mcard-title" title="{{ $title }}">{{ $title }}</div>
              <div class="mcard-desc">{{ $desc ?: '—' }}</div>
            </div>

            <div class="mcard-actions">
              <a class="mcard-open" href="{{ $href }}">Abrir ↗</a>

              @role('admin')
                <div class="mcard-admin">
                  {{-- lápiz (editar card: title/desc/image desde menu_card_images) --}}
                  <button type="button"
                          class="icon-btn"
                          title="Editar card"
                          onclick="openEditCardModal(@js($card['key']), @js($customTitle ?: ''), @js($desc ?: ''), @js($imgUrl ?: ''), @js($card['title'] ?? ''))">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M4 20h4l10.5-10.5a2 2 0 0 0 0-2.8l-1.2-1.2a2 2 0 0 0-2.8 0L4 16v4Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                      <path d="M13.5 6.5l4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                  </button>

                  @if($nodeId)
                    <form method="POST" action="{{ route('admin.menu.destroy', ['menu_node' => $nodeId]) }}"
                          onsubmit="return confirm('¿Eliminar esta opción del menú?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger" style="padding:.62rem .85rem; border-radius:14px;">
                        Eliminar
                      </button>
                    </form>
                  @endif
                </div>
              @endrole
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="p-8 text-center text-slate-600">
        No hay opciones en este nivel.
      </div>
    @endif
  </div>

  {{-- PANEL PRODUCTOS --}}
  <div class="panel" style="margin-top:16px;">
    <div class="panel-head">
      <div class="h">Productos</div>
      <div class="text-sm font-bold text-slate-500">
        Se muestran los productos cuyo <code>menu_key</code> coincide con: <code>{{ $fullPath ?? '' }}</code>
      </div>
    </div>

    @if(($products ?? collect())->count())
      <div class="card-grid">
        @foreach($products as $p)
          @php
            $pImg = !empty($p->image_path) ? asset('storage/'.ltrim($p->image_path,'/')) : null;
          @endphp

          <div class="mcard">
            <div class="mcard-media">
              @if($pImg)
                <img src="{{ $pImg }}" alt="{{ $p->title }}">
              @else
                <div class="text-sm font-extrabold text-slate-400">Sin imagen</div>
              @endif
            </div>

            <div class="mcard-body">
              <div class="mcard-title">{{ $p->title }}</div>
              <div class="mcard-desc">{{ $p->description ?: '—' }}</div>
            </div>

            <div class="mcard-actions">
              @if($p->url)
                <a class="mcard-open" href="{{ $p->url }}" target="_blank" rel="noopener">Abrir ↗</a>
              @else
                <span class="text-xs font-bold text-slate-400">Sin link</span>
              @endif

              @role('admin')
                <div class="mcard-admin">
                  {{-- lápiz (editar producto) --}}
                  <button type="button"
                          class="icon-btn"
                          title="Editar producto"
                          onclick="openEditProduct(
                            @js($p->id),
                            @js($p->menu_key),
                            @js($p->title),
                            @js($p->description ?? ''),
                            @js($p->url ?? ''),
                            @js((int)($p->sort ?? 0)),
                            @js((int)($p->is_active ?? 1)),
                            @js($pImg ?? '')
                          )">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M4 20h4l10.5-10.5a2 2 0 0 0 0-2.8l-1.2-1.2a2 2 0 0 0-2.8 0L4 16v4Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                      <path d="M13.5 6.5l4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                  </button>

                  <form method="POST" action="{{ route('admin.menu-products.destroy', ['menu_product' => $p->id]) }}"
                        onsubmit="return confirm('¿Eliminar este producto?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                    <button type="submit" class="btn btn-danger" style="padding:.62rem .85rem; border-radius:14px;">
                      Eliminar
                    </button>
                  </form>
                </div>
              @endrole
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="p-8 text-center text-slate-600">
        No hay productos en este nivel.
      </div>
    @endif
  </div>

</div>

@role('admin')
  {{-- MODAL EDITAR CARD (SUBMENUS) --}}
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

  {{-- MODAL CREAR SUBMENÚ --}}
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

  {{-- MODAL CREAR PRODUCTO --}}
  <div id="createProductBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeCreateProduct()"></div>
  <div id="createProductModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Agregar producto</div>
            <div class="modal-sub">Crea un producto y asígnalo a una opción.</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeCreateProduct()">
            Cerrar ✕
          </button>
        </div>

        <form method="POST" action="{{ route('admin.menu-products.store') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

          <div class="modal-body">
            <div class="space-y-4">
              <div>
                <label class="field-label">Asignar a</label>
                <select class="select" name="menu_key" id="create_product_menu_key" required>
                  @foreach(($productTargets ?? []) as $t)
                    <option value="{{ $t['menu_key'] }}">{{ $t['label'] }} — ({{ $t['menu_key'] }})</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="field-label">Título</label>
                <input name="title" type="text" required class="input" placeholder="Ej. Bench">
              </div>

              <div>
                <label class="field-label">Descripción</label>
                <textarea name="description" rows="4" class="textarea" placeholder="Descripción del producto"></textarea>
              </div>

              <div>
                <label class="field-label">Link (opcional)</label>
                <input name="url" type="text" class="input" placeholder="https://...">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="field-label">Orden</label>
                  <input name="sort" type="number" min="0" value="0" class="input">
                </div>

                <div class="flex items-end gap-2 pb-1">
                  <input id="create_product_active" name="is_active" type="checkbox" class="rounded" checked>
                  <label for="create_product_active" class="field-label" style="margin:0;">Activo</label>
                </div>
              </div>

              <div>
                <label class="field-label">Imagen (opcional)</label>
                <input name="image" type="file" accept="image/*" class="input" style="padding:.75rem 1rem;">
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

  {{-- MODAL EDITAR PRODUCTO --}}
  <div id="editProductBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeEditProduct()"></div>
  <div id="editProductModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Editar producto</div>
            <div id="editProductSmall" class="modal-sub">—</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeEditProduct()">
            Cerrar ✕
          </button>
        </div>

        <form id="editProductForm" method="POST" action="#" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

          <div class="modal-body">
            <div class="space-y-4">
              <div>
                <label class="field-label">Asignado a (menu_key)</label>
                <input id="edit_product_menu_key" type="text" class="input" disabled>
              </div>

              <div>
                <label class="field-label">Título</label>
                <input id="edit_product_title" name="title" type="text" required class="input">
              </div>

              <div>
                <label class="field-label">Descripción</label>
                <textarea id="edit_product_description" name="description" rows="4" class="textarea"></textarea>
              </div>

              <div>
                <label class="field-label">Link (opcional)</label>
                <input id="edit_product_url" name="url" type="text" class="input">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="field-label">Orden</label>
                  <input id="edit_product_sort" name="sort" type="number" min="0" class="input">
                </div>

                <div class="flex items-end gap-2 pb-1">
                  <input id="edit_product_active" name="is_active" type="checkbox" class="rounded">
                  <label for="edit_product_active" class="field-label" style="margin:0;">Activo</label>
                </div>
              </div>

              <div>
                <label class="field-label">Imagen (opcional)</label>
                <input id="edit_product_image" name="image" type="file" accept="image/*" class="input" style="padding:.75rem 1rem;">
                <div id="edit_product_img_hint" class="text-xs font-bold text-slate-500 mt-2">—</div>
              </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeEditProduct()">Cancelar</button>
              <button class="btn btn-primary">Guardar cambios</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script>
    function lockBodyScroll(lock) {
      const b = document.body;
      if (lock) b.classList.add('no-scroll');
      else b.classList.remove('no-scroll');
    }

    // ====== Edit Card Modal (SUBMENUS) ======
    function openEditCardModal(key, title, description, imgUrl, fallbackTitle) {
      const backdrop = document.getElementById('editModalBackdrop');
      const modal = document.getElementById('editModal');
      const form = document.getElementById('editCardForm');

      const actionTpl = @js(route('admin.menu-cards.update', ['key' => '___KEY___']));
      form.action = actionTpl.replace('___KEY___', encodeURIComponent(key));

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

    // ====== Create Submenu Modal ======
    function openCreateNode(parentId) {
      if (!parentId) {
        alert('No se pudo determinar el nivel actual en BD (currentNodeId).');
        return;
      }
      document.getElementById('create_parent_id').value = parentId;

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

    // ====== Create Product Modal ======
    function openCreateProduct() {
      document.getElementById('createProductBackdrop').classList.remove('hidden');
      const modal = document.getElementById('createProductModal');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeCreateProduct() {
      document.getElementById('createProductBackdrop').classList.add('hidden');
      const modal = document.getElementById('createProductModal');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    // ====== Edit Product Modal ======
    function openEditProduct(id, menuKey, title, description, url, sort, isActive, imgUrl) {
      const backdrop = document.getElementById('editProductBackdrop');
      const modal = document.getElementById('editProductModal');
      const form = document.getElementById('editProductForm');

      const actionTpl = @js(route('admin.menu-products.update', ['menu_product' => '___ID___']));
      form.action = actionTpl.replace('___ID___', encodeURIComponent(id));

      document.getElementById('editProductSmall').textContent = `ID: ${id}`;
      document.getElementById('edit_product_menu_key').value = menuKey || '';
      document.getElementById('edit_product_title').value = title || '';
      document.getElementById('edit_product_description').value = description || '';
      document.getElementById('edit_product_url').value = url || '';
      document.getElementById('edit_product_sort').value = sort || 0;
      document.getElementById('edit_product_active').checked = (parseInt(isActive,10) === 1);

      document.getElementById('edit_product_image').value = '';
      document.getElementById('edit_product_img_hint').textContent =
        imgUrl ? 'Imagen actual: OK (si subes otra, reemplaza).' : 'Sin imagen actual.';

      backdrop.classList.remove('hidden');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }

    function closeEditProduct() {
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
  </script>
@endrole

@endsection
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

    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);

    --primary:#2563eb;
    --primary2:#1d4ed8;
    --danger:#e11d48;

    --rXL: 24px;
    --rL: 18px;
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
    background: linear-gradient(180deg, var(--primary), var(--primary2));
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
    gap: 10px;
  }
  .panel-head .h{
    font-weight: 950;
    letter-spacing: -.01em;
    color: var(--ink);
  }
  .panel-actions{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap: wrap;
  }

  .rows{ display:flex; flex-direction:column; }

  .row{
    display:flex;
    align-items:center;
    gap:12px;
    padding: 16px 16px;
    border-top: 1px solid rgba(15,23,42,.06);
    transition: background .2s ease;
    cursor: pointer;
  }
  .row:first-child{ border-top:0; }
  .row:hover{ background: rgba(248,250,252,.55); }

  .row-title{
    font-weight: 950;
    font-size: 1.05rem;
    color: var(--ink);
    letter-spacing: -.01em;
    min-width: 0;
    overflow:hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .actions{
    margin-left:auto;
    display:flex; align-items:center; gap:10px;
    flex:0 0 auto;
  }

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
  .input:focus, .textarea:focus, .select:focus{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 0 0 6px rgba(37,99,235,.14);
    background:#fff;
  }

  .no-scroll{ overflow: hidden !important; }
</style>

<div class="wrap">

  <div class="topbar">
    <div>
      <div class="title">{{ $current['label'] ?? $section['label'] ?? 'Menú' }}</div>
      <div class="subtitle">
        @if(count($cards))
          Opciones principales. Selecciona una opción para entrar.
        @else
          No hay submenús. Aquí se listan los productos de este nivel.
        @endif
      </div>
    </div>

    @role('admin')
      <a href="{{ route('admin.menu.index') }}" class="btn btn-ghost">Administrar menú</a>
    @endrole
  </div>

  <div class="panel">
    <div class="panel-head">
      <div class="h">Opciones</div>

      @role('admin')
        <div class="panel-actions">
          <button type="button" class="btn btn-primary"
                  onclick="openCreateNode(@js($currentNodeId))">
            + Agregar submenú
          </button>

          <button type="button" class="btn btn-primary"
                  onclick="openCreateProduct()">
            + Agregar producto
          </button>
        </div>
      @endrole
    </div>

    {{-- SUBMENÚS (CARDS EN LISTA) --}}
    @if(count($cards))
      <div class="rows">
        @foreach($cards as $card)
          @php
            $nodeId = $card['id'] ?? null;
            $title  = $card['title'] ?? '—';
            $href   = $card['href'] ?? '#';
          @endphp

          <div class="row" onclick="if(@js($href) !== '#') window.location.href = @js($href);">
            <div class="row-title" title="{{ $title }}">{{ $title }}</div>

            @role('admin')
              <div class="actions" onclick="event.stopPropagation();">
                @if($nodeId)
                  <form method="POST" action="{{ route('admin.menu.destroy', ['menu_node' => $nodeId]) }}"
                        onsubmit="return confirm('¿Eliminar esta opción del menú?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                    <button type="submit" class="btn btn-danger" style="padding:.62rem .85rem; border-radius:14px;">
                      Eliminar
                    </button>
                  </form>
                @endif
              </div>
            @endrole
          </div>
        @endforeach
      </div>
    @else
      <div class="p-6 text-slate-700 font-semibold">
        No hay submenús en este nivel.
      </div>
    @endif

    {{-- PRODUCTOS DEL NIVEL ACTUAL --}}
    <div style="border-top:1px solid rgba(15,23,42,.08); background:rgba(248,250,252,.55); padding:12px 16px;">
      <div style="font-weight:950; color:var(--ink);">Productos</div>
      <div style="color:rgba(15,23,42,.55); font-size:.9rem; margin-top:2px;">
        Se muestran los productos cuyo <b>menu_key</b> coincide con: <code>{{ $currentMenuKey }}</code>
      </div>
    </div>

    @if(isset($products) && count($products))
      <div class="rows">
        @foreach($products as $p)
          <div class="row" style="cursor:default;">
            <div class="row-title" title="{{ $p->title }}">{{ $p->title }}</div>

            @role('admin')
              <div class="actions">
                <form method="POST" action="{{ route('admin.menu-products.destroy', ['menuProduct' => $p->id]) }}"
                      onsubmit="return confirm('¿Eliminar producto?');">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                  <button class="btn btn-danger" style="padding:.62rem .85rem; border-radius:14px;">Eliminar</button>
                </form>
              </div>
            @endrole
          </div>
        @endforeach
      </div>
    @else
      <div class="p-6 text-slate-600">
        No hay productos en este nivel.
      </div>
    @endif
  </div>
</div>

@role('admin')
  {{-- MODAL CREAR SUBMENÚ --}}
  <div id="createNodeBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeCreateNode()"></div>
  <div id="createNodeModal" class="fixed inset-0 hidden z-[90]">
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
          <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

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
            <div class="modal-sub">
              @if(count($cards))
                Debes asignarlo a una opción (submenú).
              @else
                Se agregará al nivel actual.
              @endif
            </div>
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
                <select name="menu_key" class="select" required>
                  @foreach($productTargets as $t)
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
                <textarea name="description" rows="4" class="textarea" placeholder="Descripción..."></textarea>
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
                  <input id="prod_active" name="is_active" type="checkbox" class="rounded" checked>
                  <label for="prod_active" class="field-label" style="margin:0;">Activo</label>
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

  <script>
    function lockBodyScroll(lock) {
      const b = document.body;
      if (lock) b.classList.add('no-scroll');
      else b.classList.remove('no-scroll');
    }

    // Submenu modal
    function openCreateNode(parentId) {
      if (!parentId) {
        alert('No se pudo determinar el nivel actual. Revisa que exista el root/ramas en menu_nodes.');
        return;
      }
      document.getElementById('create_parent_id').value = parentId;

      document.getElementById('createNodeBackdrop').classList.remove('hidden');
      const modal = document.getElementById('createNodeModal');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      lockBodyScroll(true);
    }
    function closeCreateNode() {
      document.getElementById('createNodeBackdrop').classList.add('hidden');
      const modal = document.getElementById('createNodeModal');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      lockBodyScroll(false);
    }

    // Product modal
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

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeCreateNode();
        closeCreateProduct();
      }
    });
  </script>
@endrole

@endsection
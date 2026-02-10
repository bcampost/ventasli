@extends('layouts.app')

@section('content')
@php
  $safe = fn($v) => is_string($v) ? $v : '';

  // ✅ Top-level: cuando NO hay path (estás en la página base del menú superior)
  $isTopLevel = empty($current['path'] ?? '') && empty(request()->route('path'));
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

  /* Layout */
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

  /* Clean list container */
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

  .rows{ display:flex; flex-direction:column; }

  /* Main card row */
  .row{
    display:flex;
    align-items:center;
    gap:12px;
    padding: 16px 16px;
    border-top: 1px solid rgba(15,23,42,.06);
    transition: background .2s ease;
  }
  .row:first-child{ border-top:0; }
  .row:hover{ background: rgba(248,250,252,.55); }

  .twisty{
    width: 46px; height:46px;
    border-radius: 16px;
    border:1px solid var(--line);
    background:#fff;
    display:flex; align-items:center; justify-content:center;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
    user-select:none;
    flex:0 0 auto;
  }
  .twisty:hover{
    background: rgba(248,250,252,.85);
    border-color: var(--line2);
    box-shadow: var(--shadowM);
  }
  .twisty[disabled]{ opacity:.45; cursor:not-allowed; }

  .chev{
    transition: transform .25s ease;
    color: rgba(15,23,42,.72);
    font-weight: 950;
    font-size: 18px;
    line-height: 1;
  }

  .row-title{
    font-weight: 950;
    font-size: 1.10rem;
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

  /* Children */
  .children-wrap{
    padding: 0 16px 16px 74px;
  }
  .children-box{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    background: rgba(248,250,252,.65);
    overflow:hidden;
  }
  .children-list{
    padding: 12px;
    display:grid;
    gap:10px;
  }
  .child{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 16px;
    background: #fff;
    padding: 12px 12px;
    display:flex;
    align-items:center;
    gap:10px;
    transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
  }
  .child:hover{
    box-shadow: var(--shadowM);
    transform: translateY(-1px);
    border-color: rgba(15,23,42,.18);
  }
  .child .name{
    font-weight: 900;
    color: var(--ink);
    min-width:0;
    overflow:hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .child a{
    margin-left:auto;
    font-weight: 900;
    font-size: .85rem;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 999px;
    padding: .45rem .65rem;
    background: #fff;
  }
  .child a:hover{
    background: rgba(248,250,252,.85);
    border-color: rgba(15,23,42,.20);
  }

  /* Modal */
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
  .input, .textarea{
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
  .input:focus, .textarea:focus{
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
        Opciones principales. Despliega para ver subopciones.
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
        <button type="button" class="btn btn-primary" onclick="openCreateNode('{{ $currentMenuKey }}')">
          + Agregar submenú
        </button>
      @endrole
    </div>

    @if(count($cards))
      <div class="rows">
        @foreach($cards as $i => $card)
          @php
            $nodeId = $card['id'] ?? null;
            $hasChildren = (bool)($card['hasChildren'] ?? false);

            $imgRow = $images->get($card['key']) ?? null;
            $customTitle = $imgRow->title ?? ($card['customTitle'] ?? null);
            $desc = $imgRow->description ?? ($card['description'] ?? '');
            $imgUrl = null;
            if ($imgRow && !empty($imgRow->path)) $imgUrl = asset('storage/' . ltrim($imgRow->path, '/'));

            $title = ($customTitle ?: ($card['title'] ?? '—'));
          @endphp

          <div class="row">
            <button type="button"
                    class="twisty"
                    onclick="toggleNode({{ $i }}, '{{ $nodeId }}')"
                    @if(!$hasChildren || !$nodeId) disabled @endif>
              <span id="chev{{ $i }}" class="chev">▸</span>
            </button>

            <div class="row-title" title="{{ $title }}">{{ $title }}</div>

            @role('admin')
              <div class="actions">
                {{-- ✅ Si es top-level (menú superior), NO mostramos editar aquí --}}
                @if(!$isTopLevel)
                  <button type="button"
                          class="btn btn-ghost"
                          style="padding:.62rem .85rem; border-radius:14px;"
                          onclick="openEditCardModal(@js($card['key']), @js($customTitle ?: ''), @js($desc ?: ''), @js($imgUrl ?: ''), @js($card['title'] ?? ''))">
                    Editar
                  </button>
                @endif

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

          <div id="childrenWrap{{ $i }}" class="children-wrap hidden">
            <div class="children-box">
              <div id="childrenList{{ $i }}" class="children-list">
                <div class="text-sm text-slate-600">Cargando…</div>
              </div>
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
</div>

{{-- ======================
  MODAL EDITAR CARD
====================== --}}
@role('admin')
  <div id="editModalBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeEditCardModal()"></div>

  <div id="editModal" class="fixed inset-0 hidden z-[90]">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-2xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Editar tarjeta</div>
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
                  <input id="edit_image" name="image" type="file" accept="image/*"
                         class="input" style="padding:.75rem 1rem;">
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
          <input type="hidden" name="menu_key" id="create_menu_key" value="{{ $currentMenuKey }}">

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
      if (lock) b.classList.add('no-scroll');
      else b.classList.remove('no-scroll');
    }

    // -------------------------
    // Edit Card Modal
    // -------------------------
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

    // -------------------------
    // Create sub-menu modal
    // -------------------------
    function openCreateNode(menuKey) {
      document.getElementById('create_menu_key').value = menuKey || '';
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

    // -------------------------
    // Accordion / children AJAX
    // -------------------------
    async function toggleNode(idx, nodeId) {
      if (!nodeId) return;

      const wrap = document.getElementById('childrenWrap' + idx);
      const chev = document.getElementById('chev' + idx);
      const list = document.getElementById('childrenList' + idx);

      const isOpen = !wrap.classList.contains('hidden');
      if (isOpen) {
        wrap.classList.add('hidden');
        chev.style.transform = 'rotate(0deg)';
        return;
      }

      wrap.classList.remove('hidden');
      chev.style.transform = 'rotate(90deg)';

      if (wrap.dataset.loaded === '1') return;

      const urlTpl = @js(route('admin.menu.children', ['menu_node' => '___ID___']));
      const fetchUrl = urlTpl.replace('___ID___', encodeURIComponent(nodeId));

      try {
        const res = await fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        if (!Array.isArray(data) || data.length === 0) {
          list.innerHTML = `<div class="text-sm text-slate-600 p-2">No hay subopciones.</div>`;
          wrap.dataset.loaded = '1';
          return;
        }

        list.innerHTML = data.map(n => {
          const openLink = n.href ? `<a href="${n.href}">Abrir ↗</a>` : '';
          return `
            <div class="child">
              <div class="name" title="${escapeHtml(n.label || '')}">${escapeHtml(n.label || '')}</div>
              ${openLink}
            </div>
          `;
        }).join('');

        wrap.dataset.loaded = '1';
      } catch (e) {
        list.innerHTML = `<div class="text-sm text-rose-700 p-2">Error al cargar subopciones.</div>`;
      }
    }

    function escapeHtml(str) {
      return String(str)
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'","&#039;");
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeEditCardModal();
        closeCreateNode();
      }
    });
  </script>
@endrole

@endsection
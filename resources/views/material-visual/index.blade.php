@extends('layouts.app')

@section('content')

  <div class="mv-page">

    {{-- HEADER --}}
    <div class="mv-header">
      <h1>Material Visual</h1>

      <input id="mvSearch" type="text" placeholder="Buscar..." />
    </div>

    <div class="mv-tabs">
      <button class="mv-tab {{ $initialTab === 'renders' ? 'active' : '' }}" data-tab="renders">Renders</button>
      <button class="mv-tab {{ $initialTab === 'fotos' ? 'active' : '' }}" data-tab="fotos">Fotos</button>
      <button class="mv-tab {{ $initialTab === 'videos' ? 'active' : '' }}" data-tab="videos">Videos</button>
      <button class="mv-tab {{ $initialTab === 'proyectos' ? 'active' : '' }}" data-tab="proyectos">Proyectos</button>
    </div>
    @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
      <div class="mv-toolbar">
        <button type="button" class="mv-add-level-btn" onclick="openMVModal()">
          + Agregar opción aquí
        </button>

        <span id="mvLevelLabel" class="mv-level-label"></span>
      </div>
    @endif


    {{-- GRID --}}
    <div id="mvGrid" class="mv-grid"></div>

  </div>

  @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
    <button type="button" onclick="openMVModal()" class="mv-float-btn">
      +
    </button>

    <div id="mvModal" class="mv-modal-admin">
      <div class="mv-modal-box">
        <div class="mv-modal-head">
          <div>
            <div class="mv-modal-title">Crear elemento</div>
            <div class="mv-modal-sub">Agrega carpetas, archivos, imágenes, videos, PDFs o links.</div>
          </div>

          <button type="button" class="mv-btn-soft" onclick="closeMVModal()">Cerrar ✕</button>
        </div>

        <form method="POST" action="{{ route('admin.material-visual.items.store') }}" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="section" id="mv_section">
          <input type="hidden" name="parent_key" id="mv_parent">
          <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
          <div class="mv-modal-body">
            <div class="mv-field">
              <label>Nombre</label>
              <input name="title" required class="mv-input" placeholder="Ej. Escritorios">
            </div>

            <div class="mv-field">
              <label>Descripción</label>
              <textarea name="description" rows="3" class="mv-input" placeholder="Opcional"></textarea>
            </div>

            <div class="mv-field">
              <label>Tipo</label>
              <select name="type" class="mv-input">
                <option value="folder">Carpeta</option>
                <option value="file">Archivo</option>
                <option value="image">Imagen</option>
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
                <option value="link">Link externo</option>
              </select>
            </div>

            <div class="mv-field">
              <label>Link externo</label>
              <input name="external_url" class="mv-input" placeholder="https://...">
            </div>

            <div class="mv-field">
              <label>Archivo</label>
              <input type="file" name="file" class="mv-input">
            </div>

            <div class="mv-field">
              <label>Thumbnail / portada</label>
              <input type="file" name="thumbnail" accept="image/*" class="mv-input">
            </div>

            <div class="mv-field">
              <label>Orden</label>
              <input type="number" name="sort" value="0" min="0" class="mv-input">
            </div>

            <div class="mv-actions">
              <button type="button" class="mv-btn-soft" onclick="closeMVModal()">Cancelar</button>
              <button type="submit" class="mv-btn-primary">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  @endif


  <style>
    .mv-page {
      max-width: 1400px;
      margin: auto;
      padding: 24px;
    }

    .mv-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .mv-header input {
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #ccc;
      width: 260px;
    }

    .mv-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .mv-tab {
      padding: 10px 16px;
      border-radius: 999px;
      border: 1px solid #ddd;
      background: #fff;
      cursor: pointer;
      font-weight: 600;
    }

    .mv-tab.active {
      background: #111827;
      color: #fff;
    }

    .mv-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 20px;
    }

    .mv-card {
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
      border: 1px solid #eee;
      cursor: pointer;
      transition: .2s;
    }

    .mv-card:hover {
      transform: translateY(-4px);
    }

    .mv-thumb {
      aspect-ratio: 16/10;
      background: #f3f4f6;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
    }

    .mv-body {
      padding: 12px;
    }

    .mv-title {
      font-weight: 700;
    }

    .mv-float-btn {
      position: fixed;
      right: 22px;
      bottom: 22px;
      width: 62px;
      height: 62px;
      border-radius: 999px;
      border: 0;
      background: #111827;
      color: #fff;
      font-size: 32px;
      font-weight: 300;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 18px 38px rgba(0, 0, 0, .28);
      z-index: 9999;
      cursor: pointer;
    }

    .mv-modal-admin {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(2, 6, 23, .72);
      backdrop-filter: blur(10px);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      padding: 18px;
    }

    .mv-modal-box {
      width: 100%;
      max-width: 620px;
      background: #fff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 30px 90px rgba(2, 6, 23, .30);
    }

    .mv-modal-head {
      padding: 18px 22px;
      border-bottom: 1px solid rgba(15, 23, 42, .10);
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 14px;
      background: #f8fafc;
    }

    .mv-modal-title {
      font-size: 1.15rem;
      font-weight: 300;
      color: #111827;
    }

    .mv-modal-sub {
      margin-top: 4px;
      color: #64748b;
      font-size: .9rem;
    }

    .mv-modal-body {
      padding: 20px 22px 22px;
      max-height: calc(100vh - 150px);
      overflow: auto;
    }

    .mv-field {
      display: grid;
      gap: 7px;
      margin-bottom: 14px;
    }

    .mv-field label {
      font-size: .86rem;
      font-weight: 800;
      color: #334155;
    }

    .mv-input {
      width: 100%;
      border: 1px solid rgba(15, 23, 42, .14);
      border-radius: 14px;
      padding: 11px 13px;
      outline: none;
    }

    .mv-input:focus {
      border-color: rgba(37, 99, 235, .45);
      box-shadow: 0 0 0 5px rgba(37, 99, 235, .10);
    }

    .mv-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 18px;
    }

    .mv-btn-soft,
    .mv-btn-primary {
      border-radius: 14px;
      padding: 10px 14px;
      font-weight: 800;
      cursor: pointer;
      border: 1px solid transparent;
    }

    .mv-btn-soft {
      background: #fff;
      color: #111827;
      border-color: rgba(15, 23, 42, .14);
    }

    .mv-btn-primary {
      background: #111827;
      color: #fff;
    }

    .mv-card-actions {
      display: flex;
      gap: 8px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .mv-card-actions button {
      border: 1px solid rgba(15, 23, 42, .12);
      background: #fff;
      border-radius: 10px;
      padding: 7px 10px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
    }

    .mv-card-actions button:hover {
      background: #f8fafc;
    }

    .mv-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 18px;
    }

    .mv-add-level-btn {
      border: 0;
      background: #111827;
      color: #fff;
      border-radius: 14px;
      padding: 10px 14px;
      font-weight: 700;
      cursor: pointer;
    }

    .mv-add-level-btn:hover {
      opacity: .92;
    }

    .mv-level-label {
      color: #64748b;
      font-size: 13px;
    }
  </style>


  <script>
    const DB_ITEMS = @json($items ?? []);
    const MV_STORE_URL = @json(route('admin.material-visual.items.store'));
    const MV_ITEMS_BASE_URL = @json(url('/admin/material-visual/items'));
    const DB_MAP = {};
    DB_ITEMS.forEach(i => DB_MAP[i.id] = i);

    const MV = {
      tab: @json($initialTab ?? 'renders'),
      search: '',
      parentKey: null
    };

    function normalize(text) {
      return String(text || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
    }

    function makeKey(item) {
      return item.section + '/' + normalize(item.title).replace(/\s+/g, '-');
    }

    function iconFor(type, section) {
      if (type === 'folder') return '📁';
      if (section === 'fotos') return '📷';
      if (section === 'videos') return '🎬';
      if (section === 'proyectos') return '🏆';
      if (section === 'catalogos') return '📄';
      return '🖼️';
    }

    function render() {
      const grid = document.getElementById('mvGrid');
      if (!grid) return;
      const levelLabel = document.getElementById('mvLevelLabel');
      if (levelLabel) {
        levelLabel.textContent = MV.parentKey
          ? 'Agregando dentro de esta carpeta'
          : 'Agregando en la raíz de esta sección';
      }


      grid.innerHTML = '';

      if (MV.parentKey !== null) {
        const back = document.createElement('div');
        back.style.gridColumn = '1 / -1';
        back.innerHTML = `
                <button type="button" class="mv-tab active" style="margin-bottom:10px;">
                  ← Volver
                </button>
              `;

        back.querySelector('button').addEventListener('click', () => {
          MV.parentKey = null;
          render();
        });

        grid.appendChild(back);
      }

      let items = DB_ITEMS.filter(item => {
        if (item.section !== MV.tab) return false;

        if (MV.parentKey === null) {
          return !item.parent_key;
        }

        return item.parent_key === MV.parentKey;
      });

      if (MV.search) {
        items = items.filter(item =>
          normalize(item.title).includes(normalize(MV.search)) ||
          normalize(item.description).includes(normalize(MV.search))
        );
      }

      if (!items.length) {
        const empty = document.createElement('div');
        empty.style.gridColumn = '1 / -1';
        empty.style.padding = '34px';
        empty.style.textAlign = 'center';
        empty.style.color = '#64748b';
        empty.style.fontWeight = '700';
        empty.innerText = 'No hay elementos en esta sección.';

        grid.appendChild(empty);
        return;
      }

      items.forEach(item => {
        const el = document.createElement('div');
        el.className = 'mv-card';

        el.innerHTML = `
        <div class="mv-thumb">
          ${item.thumb_url
            ? `<img src="${item.thumb_url}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover;">`
            : `<span>${iconFor(item.type, item.section)}</span>`
          }
        </div>

        <div class="mv-body">
          <div class="mv-title">${item.title}</div>
          <div style="font-size:12px;color:#64748b;margin-top:4px;">
            ${item.type === 'folder' ? 'Carpeta' : 'Archivo'}
          </div>

          @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
                            <div class="mv-card-actions">
                              <button type="button" onclick="event.stopPropagation(); openEditMVModal(${item.id})">
                                ✏️ Editar
                              </button>

                      <form method="POST"
                            action="${MV_ITEMS_BASE_URL}/${item.id}"                           
                            onclick="event.stopPropagation();"
                            onsubmit="event.stopPropagation(); return confirm('¿Eliminar este elemento?');">          @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                      <button type="submit" onclick="event.stopPropagation();">
                        🗑 Eliminar
                      </button>
                              </form>
                            </div>
          @endif
        </div>
      `;

        if (item.type === 'folder') {
          el.addEventListener('click', () => {
            MV.parentKey = makeKey(item);
            render();
          });
        } else {
          el.addEventListener('click', () => {
            if (item.file_url) {
              window.open(item.file_url, '_blank');
            }
          });
        }

        grid.appendChild(el);
      });
    }

    document.querySelectorAll('.mv-tab').forEach(tab => {
      const tabName = tab.dataset.tab;

      if (tabName === MV.tab) {
        tab.classList.add('active');
      } else {
        tab.classList.remove('active');
      }

      tab.addEventListener('click', () => {
        document.querySelectorAll('.mv-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        MV.tab = tab.dataset.tab;
        MV.parentKey = null;

        const url = new URL(window.location.href);
        url.searchParams.set('tab', MV.tab);
        window.history.replaceState({}, '', url);

        render();
      });
    });

    document.getElementById('mvSearch')?.addEventListener('input', e => {
      MV.search = e.target.value;
      render();
    });
    function openMVModal() {
      const modal = document.getElementById('mvModal');
      const section = document.getElementById('mv_section');
      const parent = document.getElementById('mv_parent');
      const form = document.querySelector('#mvModal form');

      // RESET FORM primero
      form.reset();
      form.action = MV_STORE_URL;
      let method = form.querySelector('input[name="_method"]');
      if (method) method.remove();

      // Luego asignar valores actuales
      if (section) section.value = MV.tab;
      if (parent) parent.value = MV.parentKey || '';

      if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    }


    function openEditMVModal(id) {
      const item = DB_MAP[id];
      if (!item) return;

      openMVModal();

      document.getElementById('mv_section').value = item.section;
      document.getElementById('mv_parent').value = item.parent_key || '';

      document.querySelector('[name="title"]').value = item.title || '';
      document.querySelector('[name="description"]').value = item.description || '';
      document.querySelector('[name="type"]').value = item.type || 'folder';
      document.querySelector('[name="external_url"]').value = item.file_url || '';

      // 🔥 CAMBIAR ACTION DEL FORM
      const form = document.querySelector('#mvModal form');

      form.action = `${MV_ITEMS_BASE_URL}/${id}`;
      // si no existe _method lo crea
      let method = form.querySelector('input[name="_method"]');
      if (!method) {
        method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        form.appendChild(method);
      }

      method.value = 'PUT';
    }

    function closeMVModal() {
      const modal = document.getElementById('mvModal');

      if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      }
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeMVModal();
      }
    });
    render();
  </script>

@endsection
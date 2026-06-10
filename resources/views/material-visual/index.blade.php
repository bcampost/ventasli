@extends('layouts.app')

@section('content')

  <div class="mv-page">

    {{-- HEADER --}}
    <div class="mv-hero">
      <div>
        <div class="mv-breadcrumb">
          Material Visual
          <span>›</span>
          <span id="mvCurrentSection">{{ ucfirst($initialTab ?? 'renders') }}</span>
        </div>

        <h1 id="mvPageTitle">Material Visual</h1>

        <p id="mvSubtitle" class="mv-subtitle">
          Explora renders, fotografías, videos y proyectos comerciales.
        </p>
      </div>

      <div class="mv-hero-count">
        <span id="mvCounter">0 archivos</span>
      </div>
    </div>

    <div class="mv-tabs-wrap">
      <div class="mv-tabs">
        <button class="mv-tab {{ $initialTab === 'renders' ? 'active' : '' }}" data-tab="renders">Renders</button>
        <button class="mv-tab {{ $initialTab === 'fotos' ? 'active' : '' }}" data-tab="fotos">Fotos</button>
        <button class="mv-tab {{ $initialTab === 'redes' ? 'active' : '' }}" data-tab="redes">Redes Sociales</button>
      </div>
    </div>
    <div id="mvSubtabs" class="mv-subtabs" style="display:none;"></div>

    <div class="mv-panel">
      <div class="mv-tools">
        <div class="mv-searchbox">
          <span>⌕</span>
          <input id="mvSearch" type="text" placeholder="Buscar por modelo, código o nombre..." />
        </div>

        <select id="mvFormatFilter" class="mv-select">
          <option value="">Todos los formatos</option>
          <option value="folder">Carpetas</option>
          <option value="image">Imágenes</option>
          <option value="video">Videos</option>
          <option value="pdf">PDF</option>
          <option value="file">Archivos</option>
          <option value="link">Links</option>
        </select>
      </div>

      <div class="mv-sort-row">
        <button type="button" class="mv-sort active">Más recientes</button>
        <button type="button" class="mv-sort">Más descargados</button>
        <button type="button" class="mv-sort">A–Z</button>
      </div>

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

        <form id="mvItemForm" method="POST" action="{{ route('admin.material-visual.items.store') }}"
          enctype="multipart/form-data">
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
              <button type="submit" class="mv-btn-primary" id="mvSaveBtn">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  @endif


  <style>
    :root {
      --mv-bg: #f5f6f8;
      --mv-card: #ffffff;
      --mv-border: #e7eaf0;
      --mv-text: #111827;
      --mv-muted: #7b8794;
      --mv-soft: #f3f4f6;
      --mv-soft-2: #eef2f7;
      --mv-dark: #171717;
      --mv-shadow: 0 12px 30px rgba(15, 23, 42, .05);
      --mv-shadow-hover: 0 22px 44px rgba(15, 23, 42, .10);
    }

    .mv-page {
      max-width: 1450px;
      margin: auto;
      padding: 26px;
    }

    /* HERO */

    .mv-hero {
      border: 1px solid var(--mv-border);
      background: linear-gradient(180deg,
          rgba(255, 255, 255, 1),
          rgba(249, 250, 251, .96));
      border-radius: 28px;
      padding: 34px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      margin-bottom: 20px;
      box-shadow: var(--mv-shadow);
    }

    .mv-breadcrumb {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: .88rem;
      color: #9aa4b2;
      margin-bottom: 16px;
    }

    .mv-breadcrumb span {
      color: #64748b;
    }

    #mvPageTitle {
      margin: 0;
      font-size: 2.15rem;
      line-height: 1.05;
      letter-spacing: -.03em;
      color: var(--mv-text);
      font-weight: 700;
    }

    .mv-subtitle {
      margin-top: 12px;
      color: #64748b;
      max-width: 700px;
      line-height: 1.7;
      font-size: .96rem;
    }

    .mv-hero-count {
      white-space: nowrap;
      color: #94a3b8;
      font-size: 1rem;
      padding-top: 10px;
    }

    /* TABS */

    .mv-tabs-wrap {
      margin-bottom: 18px;
    }

    .mv-tabs {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .mv-subtabs {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 22px;
    }

    .mv-subtab {
      border: 1px solid rgba(15, 23, 42, .08);
      background: #f8fafc;
      color: #475569;

      height: 42px;
      padding: 0 16px;

      border-radius: 12px;

      display: flex;
      align-items: center;
      justify-content: center;

      font-size: .92rem;
      font-weight: 500;

      cursor: pointer;

      transition: .18s ease;
    }

    .mv-subtab:hover {
      background: #f1f5f9;
    }

    .mv-subtab.active {
      background: #111827;
      color: #fff;
      border-color: #111827;
    }

    .mv-tab {
      height: 46px;
      padding: 0 18px;
      border-radius: 14px;
      border: 1px solid transparent;
      background: #fff;
      color: #475569;
      cursor: pointer;
      font-weight: 600;
      font-size: .92rem;
      transition: .18s ease;
      box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }

    .mv-tab:hover {
      background: #fff;
      border-color: #e2e8f0;
    }

    .mv-tab.active {
      background: #171717;
      color: #fff;
      border-color: #171717;
    }

    /* PANEL */

    .mv-panel {
      border: 1px solid var(--mv-border);
      border-radius: 24px;
      background: #fff;
      padding: 22px;
      margin-bottom: 22px;
      box-shadow: var(--mv-shadow);
    }

    .mv-tools {
      display: grid;
      grid-template-columns: 1fr 240px;
      gap: 14px;
      margin-bottom: 18px;
    }

    .mv-searchbox {
      height: 52px;
      border-radius: 16px;
      border: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0 16px;
      background: #fff;
    }

    .mv-searchbox span {
      color: #9ca3af;
      font-size: 20px;
    }

    .mv-searchbox input {
      flex: 1;
      border: 0;
      outline: none;
      background: transparent;
      font-size: .95rem;
      color: #111827;
    }

    .mv-searchbox input::placeholder {
      color: #9ca3af;
    }

    .mv-select {
      height: 52px;
      border-radius: 16px;
      border: 1px solid #e5e7eb;
      padding: 0 14px;
      background: #fff;
      font-size: .92rem;
      color: #111827;
      outline: none;
    }

    /* SORTS */

    .mv-sort-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .mv-sort {
      height: 38px;
      padding: 0 14px;
      border-radius: 12px;
      border: 1px solid transparent;
      background: #f8fafc;
      color: #64748b;
      cursor: pointer;
      font-size: .84rem;
      font-weight: 600;
      transition: .18s ease;
    }

    .mv-sort:hover {
      background: #eef2f7;
    }

    .mv-sort.active {
      background: #e8f0ff;
      color: #2563eb;
    }

    /* TOOLBAR */

    .mv-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }

    .mv-add-level-btn {
      height: 46px;
      padding: 0 18px;
      border-radius: 14px;
      border: 0;
      background: #111827;
      color: #fff;
      font-weight: 700;
      cursor: pointer;
      transition: .18s ease;
    }

    .mv-add-level-btn:hover {
      transform: translateY(-1px);
    }

    .mv-level-label {
      color: #94a3b8;
      font-size: .85rem;
    }

    /* GRID */

    .mv-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
      gap: 22px;
    }

    /* CARD */

    .mv-card {
      background: #fff;
      border-radius: 22px;
      overflow: hidden;
      border: 1px solid var(--mv-border);
      cursor: pointer;
      transition:
        transform .18s ease,
        box-shadow .18s ease,
        border-color .18s ease;
      box-shadow: 0 2px 10px rgba(15, 23, 42, .03);
    }

    .mv-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--mv-shadow-hover);
      border-color: #dbe3ee;
    }

    .mv-thumb {
      aspect-ratio: 16/10;
      background: #f3f4f6;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    .mv-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .35s ease;
    }

    .mv-card:hover .mv-thumb img {
      transform: scale(1.03);
    }

    .mv-thumb span {
      font-size: 42px;
      opacity: .45;
    }

    .mv-body {
      padding: 16px;
    }

    .mv-thumb-actions {
      position: absolute;
      top: 14px;
      right: 14px;

      display: flex;
      gap: 10px;

      opacity: 0;
      transform: translateY(-8px);

      transition: .22s ease;

      z-index: 20;
    }

    .mv-card:hover .mv-thumb-actions {
      opacity: 1;
      transform: translateY(0);
    }

    .mv-thumb-icon {
      width: 42px;
      height: 42px;

      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, .7);

      background: rgba(255, 255, 255, .92);

      backdrop-filter: blur(12px);

      display: flex;
      align-items: center;
      justify-content: center;

      font-size: 16px;

      cursor: pointer;

      box-shadow:
        0 10px 30px rgba(15, 23, 42, .14);

      transition: .18s ease;

      padding: 0;
    }

    .mv-thumb-icon:hover {
      transform: scale(1.06);
      background: #fff;
    }

    .mv-thumb-icon.danger:hover {
      background: #fee2e2;
    }

    .mv-title {
      font-size: 1rem;
      font-weight: 700;
      color: #111827;
      line-height: 1.35;
    }

    .mv-card-actions {
      display: flex;
      gap: 8px;
      margin-top: 14px;
      flex-wrap: wrap;
    }

    .mv-card-actions button {
      height: 34px;
      padding: 0 12px;
      border-radius: 10px;
      border: 1px solid rgba(15, 23, 42, .10);
      background: #fff;
      color: #334155;
      cursor: pointer;
      font-size: .78rem;
      font-weight: 700;
    }

    .mv-card-actions button:hover {
      background: #f8fafc;
    }

    /* FLOAT */

    .mv-float-btn {
      position: fixed;
      right: 24px;
      bottom: 24px;
      width: 64px;
      height: 64px;
      border-radius: 999px;
      border: 0;
      background: #111827;
      color: #fff;
      font-size: 34px;
      font-weight: 300;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow:
        0 24px 44px rgba(15, 23, 42, .24);
      z-index: 9999;
      cursor: pointer;
    }

    /* MODAL */

    .mv-modal-admin {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, .55);
      backdrop-filter: blur(10px);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .mv-modal-box {
      width: 100%;
      max-width: 680px;
      background: #fff;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 30px 90px rgba(15, 23, 42, .28);
    }

    .mv-modal-head {
      padding: 22px 24px;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      background: #f8fafc;
      display: flex;
      justify-content: space-between;
      gap: 16px;
    }

    .mv-modal-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #111827;
    }

    .mv-modal-sub {
      margin-top: 5px;
      color: #64748b;
      font-size: .92rem;
    }

    .mv-modal-body {
      padding: 24px;
      max-height: calc(100vh - 150px);
      overflow: auto;
    }

    .mv-field {
      display: grid;
      gap: 8px;
      margin-bottom: 16px;
    }

    .mv-field label {
      font-size: .85rem;
      font-weight: 700;
      color: #334155;
    }

    .mv-input {
      width: 100%;
      border: 1px solid rgba(15, 23, 42, .12);
      border-radius: 16px;
      padding: 13px 14px;
      outline: none;
      transition: .18s ease;
    }

    .mv-input:focus {
      border-color: rgba(37, 99, 235, .4);
      box-shadow: 0 0 0 5px rgba(37, 99, 235, .08);
    }

    .mv-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 22px;
    }

    .mv-btn-soft,
    .mv-btn-primary {
      height: 44px;
      padding: 0 18px;
      border-radius: 14px;
      font-weight: 700;
      cursor: pointer;
      border: 1px solid transparent;
    }

    .mv-btn-soft {
      background: #fff;
      border-color: rgba(15, 23, 42, .10);
    }

    .mv-btn-primary {
      background: #111827;
      color: #fff;
    }

    /* REDES SOCIALES */

    .mv-thumb.mv-thumb-16x9 {
      aspect-ratio: 16 / 9;
    }

    .mv-thumb.mv-thumb-1x1 {
      aspect-ratio: 1 / 1;
    }

    .mv-play-overlay {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color .18s ease;
      pointer-events: none;
      z-index: 5;
    }

    .mv-card:hover .mv-play-overlay {
      background: rgba(0, 0, 0, .10);
    }

    .mv-play-overlay-circle {
      width: 56px;
      height: 56px;
      border-radius: 999px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 16px rgba(0, 0, 0, .18);
    }

    .mv-play-overlay-circle::before {
      content: '';
      display: block;
      width: 0;
      height: 0;
      margin-left: 4px;
      border-left: 14px solid #111827;
      border-top: 9px solid transparent;
      border-bottom: 9px solid transparent;
    }

    .mv-duration-badge {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: rgba(0, 0, 0, .72);
      color: #fff;
      font-size: .72rem;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 6px;
      letter-spacing: .02em;
      z-index: 6;
    }

    .mv-grid.mv-grid-2col {
      grid-template-columns: repeat(2, 1fr);
      gap: 22px;
    }

    .mv-redes-section-label {
      grid-column: 1 / -1;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .14em;
      color: #94a3b8;
      text-transform: uppercase;
      margin: 0 0 6px;
    }

    .mv-card-category .mv-thumb {
      aspect-ratio: 16 / 9;
    }

    .mv-card-category .mv-body {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      gap: 12px;
    }

    .mv-card-category .mv-card-meta {
      font-size: .85rem;
      color: #94a3b8;
      margin-top: 4px;
    }

    .mv-card-category-chevron {
      font-size: 22px;
      color: #cbd5e1;
      flex-shrink: 0;
      line-height: 1;
    }

    .mv-back-link {
      background: none;
      border: 0;
      font-size: .85rem;
      color: #64748b;
      cursor: pointer;
      padding: 6px 0;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-weight: 600;
    }

    .mv-back-link:hover {
      color: #111827;
    }

    /* MOBILE */

    @media(max-width:900px) {

      .mv-hero {
        flex-direction: column;
      }

      .mv-tools {
        grid-template-columns: 1fr;
      }

      .mv-grid {
        grid-template-columns: 1fr;
      }

      .mv-grid.mv-grid-2col {
        grid-template-columns: 1fr;
      }

    }

    @media (max-width: 768px) {
      .mv-thumb-actions {
        opacity: 1;
        transform: none;
      }
    }
  </style>


  <script>
    let DB_ITEMS = @json($items ?? []);
    const MV_STORE_URL = @json(route('admin.material-visual.items.store'));
    const MV_ITEMS_BASE_URL = @json(url('/admin/material-visual/items'));
    const DB_MAP = {};
    DB_ITEMS.forEach(i => DB_MAP[i.id] = i);

    const MV = {
      tab: @json($initialTab ?? 'renders'),
      search: '',
      format: '',
      subfilter: '',
      parentKey: null
    };

    const REDES_SUBSECTIONS = {
      'redes/historias': {
        name: 'Historias de éxito',
        description: 'Videos de proyectos reales con clientes.',
        aspectClass: 'mv-thumb-16x9',
        isVideo: true,
      },
      'redes/posts': {
        name: 'Posts',
        description: 'Publicaciones gráficas para redes sociales.',
        aspectClass: 'mv-thumb-1x1',
        isVideo: false,
      },
    };
    const REDES_CATEGORY_ORDER = ['redes/historias', 'redes/posts'];

    const HERO_DEFAULTS = {
      title: 'Material Visual',
      subtitle: 'Explora renders, fotografías, videos y proyectos comerciales.',
    };

    function updateHero(title, subtitle) {
      const titleEl = document.getElementById('mvPageTitle');
      const subEl = document.getElementById('mvSubtitle');
      if (titleEl) titleEl.textContent = title;
      if (subEl) subEl.textContent = subtitle;
    }

    function resetHero() {
      updateHero(HERO_DEFAULTS.title, HERO_DEFAULTS.subtitle);
    }

    function renderRedesCategoryPicker() {
      const grid = document.getElementById('mvGrid');
      const panel = document.querySelector('.mv-panel');
      const toolbar = document.querySelector('.mv-toolbar');
      if (panel) panel.style.display = 'none';
      if (toolbar) toolbar.style.display = 'none';

      updateHero('Redes Sociales', 'Material visual publicado en nuestras redes, listo para compartir o reutilizar.');

      grid.classList.add('mv-grid-2col');

      const header = document.createElement('p');
      header.className = 'mv-redes-section-label';
      header.textContent = 'Selecciona una categoría';
      grid.appendChild(header);

      REDES_CATEGORY_ORDER.forEach(key => {
        const sub = REDES_SUBSECTIONS[key];
        const count = DB_ITEMS.filter(i => i.section === 'redes' && i.parent_key === key).length;

        const card = document.createElement('div');
        card.className = 'mv-card mv-card-category';
        card.innerHTML = `
          <div class="mv-thumb">
            <span>🖼️</span>
          </div>
          <div class="mv-body">
            <div>
              <div class="mv-title">${sub.name}</div>
              <div class="mv-card-meta">${count} archivo${count === 1 ? '' : 's'}</div>
            </div>
            <span class="mv-card-category-chevron">›</span>
          </div>
        `;
        card.addEventListener('click', () => {
          MV.parentKey = key;
          render();
        });
        grid.appendChild(card);
      });

      const counter = document.getElementById('mvCounter');
      if (counter) {
        const total = DB_ITEMS.filter(i => i.section === 'redes').length;
        counter.textContent = `${total} archivo${total === 1 ? '' : 's'}`;
      }

      const currentSection = document.getElementById('mvCurrentSection');
      if (currentSection) currentSection.textContent = 'Redes Sociales';
    }

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

    function renderSubfilters(items) {

      const wrap = document.getElementById('mvSubtabs');

      if (!wrap) return;

      wrap.innerHTML = '';

      const folders = items.filter(i => i.type === 'folder');

      if (!folders.length) {
        wrap.style.display = 'none';
        return;
      }

      wrap.style.display = 'flex';

      const allBtn = document.createElement('button');
      allBtn.className = `mv-subtab ${MV.subfilter === '' ? 'active' : ''}`;
      allBtn.innerText = 'Todas';

      allBtn.addEventListener('click', () => {
        MV.subfilter = '';
        render();
      });

      wrap.appendChild(allBtn);

      folders.forEach(folder => {

        const btn = document.createElement('button');

        btn.className = `
                              mv-subtab
                              ${MV.subfilter === folder.title ? 'active' : ''}
                            `;

        btn.innerText = folder.title;

        btn.addEventListener('click', () => {
          MV.subfilter = folder.title;
          render();
        });

        wrap.appendChild(btn);
      });
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

      // Restablecer estado visual heredado del render anterior
      const panel = document.querySelector('.mv-panel');
      const toolbar = document.querySelector('.mv-toolbar');
      if (panel) panel.style.display = '';
      if (toolbar) toolbar.style.display = '';
      grid.classList.remove('mv-grid-2col');
      resetHero();

      // Redes Sociales raíz → selector de subcategorías y salir
      if (MV.tab === 'redes' && !MV.parentKey) {
        renderRedesCategoryPicker();
        return;
      }

      // Detectar subsección de Redes Sociales para estilos especiales
      const redesSubConfig = (MV.tab === 'redes' && MV.parentKey) ? REDES_SUBSECTIONS[MV.parentKey] : null;
      if (redesSubConfig) {
        updateHero(redesSubConfig.name, redesSubConfig.description);
      }

      if (MV.parentKey !== null) {
        const back = document.createElement('div');
        back.style.gridColumn = '1 / -1';

        if (redesSubConfig) {
          back.innerHTML = `
            <button type="button" class="mv-back-link" style="margin-bottom:8px;">
              ← Volver a Redes Sociales
            </button>
            `;
        } else {
          back.innerHTML = `
            <button type="button" class="mv-tab active" style="margin-bottom:10px;">
              ← Volver
            </button>
            `;
        }

        back.querySelector('button').addEventListener('click', () => {
          MV.parentKey = null;
          MV.subfilter = '';
          render();
        });

        grid.appendChild(back);
      }

      if (MV.parentKey) {
        const childItems = DB_ITEMS.filter(item => {
          return item.section === MV.tab && item.parent_key === MV.parentKey;
        });

        renderSubfilters(childItems);
      } else {
        const wrap = document.getElementById('mvSubtabs');
        if (wrap) {
          wrap.innerHTML = '';
          wrap.style.display = 'none';
        }

        MV.subfilter = '';
      }

      let items = DB_ITEMS.filter(item => {
        if (item.section !== MV.tab) return false;

        if (MV.format && item.type !== MV.format) return false;

        if (MV.parentKey === null) {
          return !item.parent_key;
        }

        if (item.parent_key !== MV.parentKey) {
          return false;
        }

        if (MV.subfilter) {
          return normalize(item.title).includes(normalize(MV.subfilter));
        }

        return true;
      });

      if (MV.search) {
        items = items.filter(item =>
          normalize(item.title).includes(normalize(MV.search)) ||
          normalize(item.description).includes(normalize(MV.search))
        );
      }

      const counter = document.getElementById('mvCounter');
      if (counter) {
        counter.textContent = `${items.length} archivo${items.length === 1 ? '' : 's'}`;
      }

      const currentSection = document.getElementById('mvCurrentSection');
      if (currentSection) {
        if (MV.tab === 'proyectos') {
          currentSection.textContent = 'Historias de éxito';
        } else if (MV.tab === 'redes') {
          if (redesSubConfig) {
            currentSection.innerHTML = `Redes Sociales <span>›</span> ${redesSubConfig.name}`;
          } else {
            currentSection.textContent = 'Redes Sociales';
          }
        } else {
          currentSection.textContent = MV.tab.charAt(0).toUpperCase() + MV.tab.slice(1);
        }
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

      const thumbExtraClass = redesSubConfig ? redesSubConfig.aspectClass : '';
      const showPlayOverlay = !!(redesSubConfig && redesSubConfig.isVideo);
      const durationRegex = /^\d{1,2}:\d{2}$/;

      items.forEach(item => {
        const el = document.createElement('div');
        el.className = 'mv-card';

        const duration = (showPlayOverlay && item.description && durationRegex.test(item.description.trim()))
          ? item.description.trim()
          : null;

        el.innerHTML = `
          <div class="mv-thumb ${thumbExtraClass}">
            ${item.thumb_url
            ? `<img src="${item.thumb_url}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover;">`
            : `<span>${iconFor(item.type, item.section)}</span>`
          }
            ${showPlayOverlay ? `<div class="mv-play-overlay"><div class="mv-play-overlay-circle"></div></div>` : ''}
            ${duration ? `<div class="mv-duration-badge">${duration}</div>` : ''}

            @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
              <div class="mv-thumb-actions">
                <button
                  type="button"
                  class="mv-thumb-icon"
                  onclick="event.stopPropagation(); openEditMVModal(${item.id})"
                  title="Editar"
                >
                  ✏️
                </button>

                <form
                  method="POST"
                  action="${MV_ITEMS_BASE_URL}/${item.id}"
                  onclick="event.stopPropagation();"
                  onsubmit="event.stopPropagation(); return confirm('¿Eliminar este elemento?');"
                >
                  @csrf
                  @method('DELETE')

                  <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                  <button
                    type="submit"
                    class="mv-thumb-icon danger"
                    onclick="event.stopPropagation();"
                    title="Eliminar"
                  >
                    🗑
                  </button>
                </form>
              </div>
            @endif
          </div>

          <div class="mv-body">
            <div class="mv-title">${item.title}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;">
              ${item.type === 'folder' ? 'Carpeta' : 'Archivo'}
            </div>
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

    document.getElementById('mvFormatFilter')?.addEventListener('change', e => {
      MV.format = e.target.value;
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



    let MV_IS_SAVING = false;

    document.getElementById('mvItemForm')?.addEventListener('submit', async function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (MV_IS_SAVING) return;
      MV_IS_SAVING = true;

      const form = this;
      const btn = document.getElementById('mvSaveBtn');

      if (btn) {
        btn.disabled = true;
        btn.innerText = 'Guardando...';
      }

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          }
        });

        const data = await res.json();

        if (!res.ok || !data.ok) {
          alert(data.message || 'No se pudo guardar.');
          return;
        }

        if (data.item) {
          const existingIndex = DB_ITEMS.findIndex(i => Number(i.id) === Number(data.item.id));

          if (existingIndex >= 0) {
            DB_ITEMS[existingIndex] = data.item;
          } else {
            DB_ITEMS.push(data.item);
          }

          DB_MAP[data.item.id] = data.item;

          DB_ITEMS.sort((a, b) => {
            const sortA = Number(a.sort || 0);
            const sortB = Number(b.sort || 0);

            if (sortA !== sortB) return sortA - sortB;

            return String(a.title || '').localeCompare(String(b.title || ''));
          });
        }

        closeMVModal();
        render();

      } catch (error) {
        alert('Error de conexión al guardar.');
      } finally {
        MV_IS_SAVING = false;

        if (btn) {
          btn.disabled = false;
          btn.innerText = 'Guardar';
        }
      }
    });

    render();
  </script>

@endsection
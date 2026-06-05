{{-- resources/views/menu/detail.blade.php --}}
@extends('layouts.app')

@section('content')
  @php
    use Illuminate\Support\Str;

    // Imágenes del hero (array)
    $images = is_array($hero->images ?? null)
      ? ($hero->images ?? [])
      : (json_decode((string) ($hero->images ?? '[]'), true) ?: []);

    // token base64url para la key (sin "/")
    $encode = function ($raw) {
      $b64 = base64_encode($raw);
      return rtrim(strtr($b64, '+/', '-_'), '=');
    };
    $token = $encode($fullPath);

    $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin');

    // Primera imagen como "cover" base
    $firstImg = !empty($images[0]) ? asset('storage/' . ltrim($images[0], '/')) : null;

    // Texto opcional (si no hay, no se pinta)
    $heroTitle = trim((string) ($hero->title ?? ''));
    $heroDesc = trim((string) ($hero->description ?? ''));

    $fullPathLower = Str::lower((string) $fullPath);

    $isHerramientasVenta =
      Str::contains($fullPathLower, 'herramientas-de-venta')
      || Str::contains($fullPathLower, 'herramientas de venta');

    $productsCount = isset($products) ? $products->count() : 0;

    $useWideHerramientasLayout = $isHerramientasVenta && $productsCount === 2;
  @endphp

  <div class="pd">

    {{-- ✅ HERO FULL WIDTH (como en tu screenshot 1) --}}
    <section class="pd-hero" aria-label="Hero del producto">
      <div class="pd-hero-shell">

        {{-- Slider --}}
        <div class="pd-slider" data-pd-slider>
          <div class="pd-track">
            @forelse($images as $img)
              @php $url = asset('storage/' . ltrim($img, '/')); @endphp
              <div class="pd-slide">
                <img src="{{ $url }}" alt="">
              </div>
            @empty
              <div class="pd-slide pd-empty">
                <div class="pd-empty-inner">
                  <div class="pd-empty-title">Sin imágenes</div>
                  <div class="pd-empty-sub">Cárgalas desde el botón de edición.</div>
                </div>
              </div>
            @endforelse
          </div>

          {{-- Flechas (solo si hay más de 1) --}}
          @if(count($images) > 1)
            <button type="button" class="pd-nav pd-prev" data-pd-prev aria-label="Anterior">‹</button>
            <button type="button" class="pd-nav pd-next" data-pd-next aria-label="Siguiente">›</button>
          @endif

          {{-- Dots --}}
          @if(count($images) > 1)
            <div class="pd-dots" data-pd-dots aria-hidden="true"></div>
          @endif
        </div>

        {{-- Overlay contenido (centrado, como screenshot 1) --}}
        <div class="pd-overlay">
          <div class="pd-overlay-inner">
            @if($heroTitle !== '' || $heroDesc !== '')
              <div class="pd-brand">
                @if($heroTitle !== '')
                  <div class="pd-title">{{ $heroTitle }}</div>
                @endif
                @if($heroDesc !== '')
                  <div class="pd-subtitle">{!! nl2br(e($heroDesc)) !!}</div>
                @endif
              </div>
            @endif
          </div>
        </div>

        {{-- Botón editar (respetando icono) --}}
        @if($isAdmin && \Illuminate\Support\Facades\Route::has('admin.menu-hero.edit'))
          <a class="pd-edit" href="{{ route('admin.menu-hero.edit', $token) }}" title="Editar HERO / Slider"
            aria-label="Editar">
            ✎
          </a>
        @endif

      </div>
    </section>

    {{-- ✅ CONTENIDO NORMAL (productos debajo, como en “Escritorios”) --}}
    <section class="pd-body">
      <div class="pd-wrap">

        @role('admin')
        <div style="display:flex; justify-content:flex-end; gap:10px; margin:0 0 18px;">
          <button type="button" class="pd-add-product-btn" onclick="openCreateProductModal()">
            + Agregar producto
          </button>
        </div>
        @endrole


        @if(isset($products) && $products->count())
          <div class="pd-grid {{ $useWideHerramientasLayout ? 'pd-grid--wide-two' : '' }}">
            @foreach($products as $prod)
              @php
                $prodUrl = route('menu.product.show', [
                  'menu_product' => $prod->id,
                  'redirect_to' => url()->current()
                ]);

                $prodImgUrl = $prod->image_path
                  ? asset('storage/' . ltrim($prod->image_path, '/'))
                  : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='700'%3E%3Crect width='100%25' height='100%25' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-size='34' font-family='Arial'%3ESin%20imagen%3C/text%3E%3C/svg%3E";
              @endphp

              <a class="pd-tile {{ $useWideHerramientasLayout ? 'pd-tile--wide' : '' }}" href="{{ $prodUrl }}"
                style="text-decoration:none; color:inherit;">

                <div class="pd-img {{ $useWideHerramientasLayout ? 'pd-img--wide' : '' }}">
                  <img src="{{ $prodImgUrl }}" alt="{{ $prod->title }}">

                  @if($useWideHerramientasLayout && $prod->image_path)
                    <button type="button" class="pd-zoom-btn"
                      onclick="event.preventDefault(); event.stopPropagation(); window.openMediaPreview(@js($prodImgUrl), @js($prod->title));">
                      🔍 Zoom
                    </button>
                  @endif

                  @role('admin')
                    <button type="button" class="pd-cover-btn"
                      title="Cambiar portada"
                      onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('pdCoverInput_{{ $prod->id }}').click();">
                      📷 Portada
                    </button>
                  @endrole
                </div>

                <div class="pd-tt {{ $useWideHerramientasLayout ? 'pd-tt--wide' : '' }}">
                  {{ $prod->title }}
                </div>
              </a>

              @role('admin')
                <form method="POST"
                      action="{{ route('admin.product-details.set-cover', $prod) }}"
                      enctype="multipart/form-data"
                      id="pdCoverForm_{{ $prod->id }}"
                      style="display:none;">
                  @csrf
                  <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                  <input type="file"
                         name="cover_image"
                         id="pdCoverInput_{{ $prod->id }}"
                         accept="image/*"
                         onchange="if(this.files.length){ document.getElementById('pdCoverForm_{{ $prod->id }}').submit(); }">
                </form>
              @endrole
            @endforeach
          </div>
        @else
          <div class="pd-empty-products">No hay productos en este nivel.</div>
        @endif

      </div>
    </section>

  </div>

  <style>
    :root {
      --pd-ink: #0b1220;
      --pd-line: rgba(15, 23, 42, .12);
      --pd-shadow: 0 18px 48px rgba(2, 6, 23, .12);
      --pd-r: 22px;
      --pd-r2: 18px;
      --pd-blue: #2563eb;
    }

    .pd {
      width: 100%;
    }

    /* =========================
         HERO FULL WIDTH
         ========================= */
    .pd-hero {
      width: 100%;
      padding: 0;
      /* full bleed */
      margin: 0;
    }

    .pd-hero-shell {
      position: relative;
      width: min(1480px, calc(100% - 44px));
      /* como sitio (con margen) */
      margin: 22px auto 0;
      border-radius: 26px;
      overflow: hidden;
      box-shadow: var(--pd-shadow);
      background: #0b1220;
    }

    /* Slider (banner tipo web) */
    .pd-slider {
      position: relative;
      width: 100%;
      height: clamp(320px, 42vw, 520px);
      /* ✅ look tipo screenshot 1 */
      overflow: hidden;
    }

    .pd-track {
      display: flex;
      height: 100%;
      transition: transform .35s ease;
      will-change: transform;
    }

    .pd-slide {
      min-width: 100%;
      height: 100%;
      background: #0b1220;
      position: relative;
    }

    .pd-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* ✅ como web: llena el banner */
      object-position: center;
      display: block;
    }

    /* Empty */
    .pd-empty {
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(180deg, rgba(15, 23, 42, .96), rgba(15, 23, 42, .86));
    }

    .pd-empty-inner {
      text-align: center;
      color: #fff;
      opacity: .9;
      padding: 22px;
    }

    .pd-empty-title {
      font-weight: 950;
      font-size: 22px;
    }

    .pd-empty-sub {
      margin-top: 6px;
      opacity: .8;
      font-weight: 650;
    }

    /* Overlay text (centrado) */
    .pd-overlay {
      pointer-events: none;
      position: absolute;
      inset: 0;
      background:
        radial-gradient(900px 420px at 50% 45%, rgba(0, 0, 0, .32), rgba(0, 0, 0, 0) 65%),
        linear-gradient(180deg, rgba(0, 0, 0, .18), rgba(0, 0, 0, .10));
    }

    .pd-overlay-inner {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 22px;
    }

    .pd-brand {
      text-align: center;
      max-width: 820px;
      color: #fff;
      text-shadow: 0 20px 60px rgba(0, 0, 0, .40);
    }

    .pd-title {
      font-weight: 950;
      letter-spacing: .06em;
      text-transform: uppercase;
      font-size: clamp(34px, 6vw, 86px);
      line-height: 1.02;
    }

    .pd-subtitle {
      margin-top: 10px;
      font-size: clamp(14px, 2.1vw, 22px);
      font-weight: 650;
      opacity: .92;
      line-height: 1.2;
    }

    /* Flechas */
    .pd-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 52px;
      height: 52px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, .22);
      background: rgba(255, 255, 255, .92);
      color: rgba(2, 6, 23, .92);
      font-size: 30px;
      font-weight: 300;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 30;
      box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
      transition: transform .12s ease, background .12s ease;
    }

    .pd-nav:hover {
      background: #fff;
      transform: translateY(-50%) scale(1.02);
    }

    .pd-prev {
      left: 14px;
    }

    .pd-next {
      right: 14px;
    }

    /* Dots */
    .pd-dots {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 14px;
      display: flex;
      justify-content: center;
      gap: 8px;
      z-index: 35;
    }

    .pd-dot {
      width: 9px;
      height: 9px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .55);
      border: 1px solid rgba(0, 0, 0, .14);
      box-shadow: 0 10px 24px rgba(0, 0, 0, .20);
    }

    .pd-dot.is-active {
      background: rgba(255, 255, 255, .95);
      transform: scale(1.12);
    }

    /* Edit icon top-right (como tu estilo) */
    .pd-edit {
      position: absolute;
      top: 14px;
      right: 14px;
      z-index: 40;
      width: 42px;
      height: 42px;
      border-radius: 999px;
      border: 1px solid rgba(15, 23, 42, .14);
      background: rgba(255, 255, 255, .92);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 950;
      text-decoration: none;
      color: rgba(2, 6, 23, .92);
      box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .pd-edit:hover {
      background: #fff;
    }

    /* =========================
         BODY / PRODUCTS
         ========================= */
    .pd-body {
      padding: 18px 0 34px;
    }

    .pd-wrap {
      width: min(1480px, calc(100% - 44px));
      margin: 0 auto;
    }

    .pd-actions {
      display: flex;
      justify-content: flex-end;
      margin: 10px 0 14px;
    }

    .pd-btn {
      border: 1px solid rgba(15, 23, 42, .12);
      background: rgba(255, 255, 255, .92);
      padding: 10px 14px;
      border-radius: 999px;
      font-weight: 950;
      cursor: pointer;
      box-shadow: 0 12px 26px rgba(2, 6, 23, .08);
    }

    .pd-btn:hover {
      background: #fff;
    }

    /* === Cards unificados (estilo Material Visual) === */
    .pd-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
      gap: 22px;
      align-items: start;
    }

    @media(max-width: 640px) {
      .pd-hero-shell {
        width: calc(100% - 26px);
      }

      .pd-wrap {
        width: calc(100% - 26px);
      }
    }

    .pd-grid.pd-grid--wide-two {
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 28px;
      align-items: stretch;
    }

    .pd-tile--wide {
      border-radius: 22px;
      overflow: hidden;
    }

    .pd-img--wide {
      aspect-ratio: 16 / 8.8;
      position: relative;
      background: #eef2f7;
    }

    .pd-tt--wide {
      padding: 16px 14px 18px;
      font-weight: 300;
      font-size: 30px;
      line-height: 1.05;
      color: rgba(15, 23, 42, .88);
      opacity: 1;
    }

    .pd-zoom-btn {
      position: absolute;
      right: 14px;
      top: 14px;
      z-index: 5;
      border: 1px solid rgba(255, 255, 255, .7);
      background: rgba(255, 255, 255, .92);
      backdrop-filter: blur(12px);
      color: #0b1220;
      padding: 10px 14px;
      border-radius: 14px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 10px 30px rgba(15, 23, 42, .14);
      opacity: 0;
      transition: .22s ease;
    }

    .pd-tile--wide:hover .pd-zoom-btn {
      opacity: 1;
    }

    .pd-zoom-btn:hover {
      background: #fff;
      transform: scale(1.04);
    }

    .pd-cover-btn {
      position: absolute;
      left: 14px;
      top: 14px;
      z-index: 5;
      border: 1px solid rgba(37, 99, 235, .35);
      background: rgba(255, 255, 255, .92);
      backdrop-filter: blur(12px);
      color: #1d4ed8;
      padding: 8px 12px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      box-shadow: 0 8px 22px rgba(15, 23, 42, .14);
      opacity: 0;
      transition: .22s ease;
    }

    .pd-tile:hover .pd-cover-btn {
      opacity: 1;
    }

    .pd-cover-btn:hover {
      background: #fff;
      transform: scale(1.04);
    }

    @media(max-width: 980px) {
      .pd-grid.pd-grid--wide-two {
        grid-template-columns: 1fr;
      }

      .pd-tt--wide {
        font-size: 24px;
      }
    }

    .pd-tile {
      background: #fff;
      border-radius: 22px;
      border: 1px solid #e7eaf0;
      box-shadow: 0 2px 10px rgba(15, 23, 42, .03);
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .pd-tile:hover {
      transform: translateY(-4px);
      box-shadow: 0 22px 44px rgba(15, 23, 42, .10);
      border-color: #dbe3ee;
    }

    .pd-img {
      aspect-ratio: 16/10;
      background: #f3f4f6;
      overflow: hidden;
    }

    .pd-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .35s ease;
    }

    .pd-tile:hover .pd-img img {
      transform: scale(1.03);
    }

    .pd-tt {
      padding: 16px;
      font-size: 1rem;
      font-weight: 700;
      color: #111827;
      line-height: 1.35;
      opacity: 1;
    }

    .pd-empty-products {
      padding: 18px 2px;
      color: rgba(15, 23, 42, .65);
      font-weight: 700;
    }

    .pd-add-product-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border-radius: 16px;
      padding: 12px 16px;
      border: 1px solid rgba(37, 99, 235, .18);
      background: linear-gradient(180deg, #2563eb, #1d4ed8);
      color: #fff;
      font-weight: 800;
      cursor: pointer;
      box-shadow: 0 14px 28px rgba(37, 99, 235, .22);
    }

    .pd-add-product-btn:hover {
      transform: translateY(-1px);
    }

    .pd-modal-backdrop {
      position: fixed;
      inset: 0;
      z-index: 9998;
      background: rgba(2, 6, 23, .72);
      backdrop-filter: blur(10px);
    }

    .pd-modal {
      position: fixed;
      inset: 0;
      z-index: 9999;
    }

    .pd-modal-box {
      width: 100%;
      max-width: 720px;
      border-radius: 26px;
      overflow: hidden;
      background: #fff;
      border: 1px solid rgba(15, 23, 42, .14);
      box-shadow: 0 30px 90px rgba(2, 6, 23, .22);
    }

    .pd-modal-head {
      padding: 18px 22px;
      border-bottom: 1px solid rgba(15, 23, 42, .10);
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      background: rgba(248, 250, 252, .9);
    }

    .pd-modal-title {
      font-size: 1.1rem;
      font-weight: 300;
      color: #0b1220;
    }

    .pd-modal-sub {
      margin-top: 4px;
      font-size: .9rem;
      color: rgba(15, 23, 42, .58);
    }

    .pd-modal-body {
      padding: 20px 22px 22px;
      max-height: calc(100vh - 160px);
      overflow: auto;
    }

    .pd-field {
      display: grid;
      gap: 7px;
      margin-bottom: 14px;
    }

    .pd-label {
      font-size: .86rem;
      font-weight: 800;
      color: #334155;
    }

    .pd-input,
    .pd-textarea {
      width: 100%;
      border-radius: 16px;
      border: 1px solid rgba(15, 23, 42, .14);
      background: #fff;
      padding: 12px 14px;
      font-size: .95rem;
      outline: none;
    }

    .pd-input:focus,
    .pd-textarea:focus {
      border-color: rgba(37, 99, 235, .45);
      box-shadow: 0 0 0 5px rgba(37, 99, 235, .10);
    }

    .pd-help {
      font-size: .8rem;
      color: #64748b;
    }

    .pd-modal-actions {
      margin-top: 18px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      flex-wrap: wrap;
    }

    .pd-btn-soft,
    .pd-btn-primary {
      border-radius: 16px;
      padding: 11px 15px;
      font-weight: 800;
      border: 1px solid transparent;
      cursor: pointer;
    }

    .pd-btn-soft {
      background: #fff;
      color: #0f172a;
      border-color: rgba(15, 23, 42, .12);
    }

    .pd-btn-primary {
      background: #0f172a;
      color: #fff;
    }

    .hidden {
      display: none !important;
    }

    .no-scroll {
      overflow: hidden !important;
    }
  </style>

  <script>
    (function () {
      const root = document.querySelector('[data-pd-slider]');
      if (!root) return;

      const track = root.querySelector('.pd-track');
      const slides = Array.from(root.querySelectorAll('.pd-slide'));
      const prev = root.querySelector('[data-pd-prev]');
      const next = root.querySelector('[data-pd-next]');
      const dotsWrap = root.querySelector('[data-pd-dots]');

      if (slides.length <= 1) return;

      let i = 0;

      // dots
      const dots = slides.map((_, idx) => {
        const d = document.createElement('span');
        d.className = 'pd-dot' + (idx === 0 ? ' is-active' : '');
        dotsWrap?.appendChild(d);
        d.addEventListener('click', () => { i = idx; render(); });
        return d;
      });

      function render() {
        track.style.transform = `translateX(${i * -100}%)`;
        dots.forEach((d, idx) => d.classList.toggle('is-active', idx === i));
      }

      prev?.addEventListener('click', () => { i = Math.max(0, i - 1); render(); });
      next?.addEventListener('click', () => { i = Math.min(slides.length - 1, i + 1); render(); });

      // swipe (móvil)
      let startX = null;
      root.addEventListener('touchstart', (e) => {
        startX = e.touches?.[0]?.clientX ?? null;
      }, { passive: true });
      root.addEventListener('touchend', (e) => {
        if (startX === null) return;
        const endX = e.changedTouches?.[0]?.clientX ?? startX;
        const dx = endX - startX;
        if (Math.abs(dx) > 45) {
          if (dx < 0) i = Math.min(slides.length - 1, i + 1);
          else i = Math.max(0, i - 1);
          render();
        }
        startX = null;
      }, { passive: true });

    })();
  </script>

  @role('admin')
  <div id="createProductBackdrop" class="pd-modal-backdrop hidden" onclick="closeCreateProductModal()"></div>

  <div id="createProductModal" class="pd-modal hidden">
    <div style="min-height:100%; display:flex; align-items:center; justify-content:center; padding:18px;">
      <div class="pd-modal-box">
        <div class="pd-modal-head">
          <div>
            <div class="pd-modal-title">Agregar producto</div>
            <div class="pd-modal-sub">Alta rápida dentro de este nivel.</div>
          </div>

          <button type="button" class="pd-btn-soft" onclick="closeCreateProductModal()">
            Cerrar ✕
          </button>
        </div>

        <form method="POST" action="{{ route('admin.menu-products.store') }}" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="menu_key" value="{{ $fullPath }}">
          <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

          <div class="pd-modal-body">
            <div class="pd-field">
              <label class="pd-label">Título del producto</label>
              <input name="title" type="text" required class="pd-input" placeholder="Ej. Escritorio 1000">
            </div>

            <div class="pd-field">
              <label class="pd-label">Código ingeniería</label>
              <input name="ingenieria_code" type="text" class="pd-input" placeholder="Ej. 1000, 106, 1214">
              <div class="pd-help">
                Opcional. Si lo dejas vacío, el sistema intentará detectarlo desde el título.
              </div>
            </div>

            <div class="pd-field">
              <label class="pd-label">Descripción</label>
              <textarea name="description" rows="4" class="pd-textarea"
                placeholder="Descripción comercial básica del producto"></textarea>
            </div>

            <div class="pd-field">
              <label class="pd-label">Imagen principal</label>
              <input name="image" type="file" accept="image/*" class="pd-input" style="padding:.75rem 1rem;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div class="pd-field">
                <label class="pd-label">Orden</label>
                <input name="sort" type="number" min="0" value="0" class="pd-input">
              </div>

              <div class="pd-field" style="align-content:end;">
                <label style="display:flex; align-items:center; gap:8px; font-weight:800; color:#334155;">
                  <input name="is_active" type="checkbox" checked>
                  Activo
                </label>
              </div>
            </div>

            <div class="pd-modal-actions">
              <button type="button" class="pd-btn-soft" onclick="closeCreateProductModal()">
                Cancelar
              </button>

              <button type="submit" class="pd-btn-primary">
                Guardar producto
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openCreateProductModal() {
      document.getElementById('createProductBackdrop')?.classList.remove('hidden');
      document.getElementById('createProductModal')?.classList.remove('hidden');
      document.body.classList.add('no-scroll');
    }

    function closeCreateProductModal() {
      document.getElementById('createProductBackdrop')?.classList.add('hidden');
      document.getElementById('createProductModal')?.classList.add('hidden');
      document.body.classList.remove('no-scroll');
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeCreateProductModal();
      }
    });
  </script>
  @endrole

@endsection
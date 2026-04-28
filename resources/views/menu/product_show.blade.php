@extends('layouts.app')

@section('content')
  @php
    $user = auth()->user();
    $isAdmin = auth()->check() && method_exists($user, 'hasRole') && $user->hasRole('admin');

    $mainImg = $product->image_path ? asset('storage/' . ltrim($product->image_path, '/')) : null;

    $aceroColors = isset($aceroColors) && is_array($aceroColors)
      ? array_values(array_filter(array_map('trim', $aceroColors)))
      : [];

    $laminadoColors = isset($laminadoColors) && is_array($laminadoColors)
      ? array_values(array_filter(array_map('trim', $laminadoColors)))
      : [];

    $galleryArr = $detail->images_safe ?? [];
    $galleryArr = is_array($galleryArr) ? $galleryArr : [];

    $techUrl = $product->tech_pdf_path ? asset('storage/' . ltrim($product->tech_pdf_path, '/')) : null;
    $manualUrl = $product->manual_pdf_path ? asset('storage/' . ltrim($product->manual_pdf_path, '/')) : null;

    $payload = [
      'id' => $product->id,
      'title' => $product->title,
      'main' => $mainImg,
      'acero' => $aceroColors,
      'melamina' => $laminadoColors,
      'gallery' => array_values(array_filter(array_map(function ($it) {
        if (!is_array($it))
          return null;
        $p = trim((string) ($it['path'] ?? ''));
        if ($p === '')
          return null;

        return [
          'url' => asset('storage/' . ltrim($p, '/')),
          'path' => ltrim($p, '/'),
          'acero' => trim((string) ($it['acero'] ?? '')),
          'melamina' => trim((string) ($it['melamina'] ?? '')),
        ];
      }, $galleryArr))),
    ];
  @endphp

  <style>
    :root {
      --ink: #0b1220;
      --line: rgba(15, 23, 42, .12);
      --line2: rgba(15, 23, 42, .18);
      --shadowM: 0 12px 26px rgba(2, 6, 23, .08);
      --rXL: 22px;
      --primary: #2563eb;
    }

    .pd-wrap {
      max-width: 1280px;
      margin: 22px auto;
      padding: 0 18px;
    }

    .pd-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 14px;
    }

    .pd-title {
      font-weight: 950;
      font-size: 22px;
      color: var(--ink);
      letter-spacing: -.02em;
    }

    .pd-sub {
      margin-top: 4px;
      font-weight: 750;
      color: rgba(15, 23, 42, .62);
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
      text-decoration: none;
      cursor: pointer;
    }

    .btn-ghost {
      background: #fff;
      border-color: var(--line);
      color: var(--ink);
    }

    .btn-primary {
      background: linear-gradient(180deg, rgba(37, 99, 235, 1), rgba(29, 78, 216, 1));
      color: #fff;
      box-shadow: 0 14px 30px rgba(37, 99, 235, .22);
    }

    .btn:disabled,
    .btn[disabled] {
      opacity: .45;
      cursor: not-allowed;
    }

    .layout {
      display: grid;
      grid-template-columns: 1.9fr 1fr;
      gap: 16px;
      align-items: start;
    }

    @media(max-width:980px) {
      .layout {
        grid-template-columns: 1fr;
      }
    }

    .card {
      border: 1px solid var(--line);
      border-radius: var(--rXL);
      background: #fff;
      box-shadow: var(--shadowM);
      overflow: hidden;
    }

    .card-head {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      background: rgba(255, 255, 255, .78);
    }

    .h {
      font-weight: 950;
      color: var(--ink);
    }

    .muted {
      font-weight: 300;
      color: rgba(15, 23, 42, .6);
    }

    .media {
      position: relative;
      background: #f3f4f6;
      overflow: hidden;
    }

    .media-frame {
      width: 100%;
      aspect-ratio: 21/10;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
    }

    .media-frame img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
      background: #fff;
    }

    .media-empty {
      padding: 52px 18px;
      color: #94a3b8;
      font-weight: 950;
    }

    .nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 44px;
      height: 44px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .92);
      border: 1px solid rgba(15, 23, 42, .18);
      font-size: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 16px 34px rgba(2, 6, 23, .16);
      user-select: none;
    }

    .nav.prev {
      left: 12px;
    }

    .nav.next {
      right: 12px;
    }

    .thumbs {
      display: flex;
      gap: 10px;
      padding: 12px 14px;
      border-top: 1px solid rgba(15, 23, 42, .08);
      overflow: auto;
      background: rgba(15, 23, 42, .015);
    }

    .th {
      width: 98px;
      aspect-ratio: 16/10;
      border-radius: 12px;
      border: 1px solid rgba(15, 23, 42, .14);
      overflow: hidden;
      background: #fff;
      cursor: pointer;
      flex: 0 0 auto;
    }

    .th img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
    }

    .th.is-active {
      outline: 4px solid rgba(37, 99, 235, .22);
      border-color: rgba(37, 99, 235, .45);
    }

    .info {
      padding: 14px 16px;
    }

    .k {
      font-weight: 950;
      color: var(--ink);
    }

    .chips {
      margin-top: 10px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .chip {
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid rgba(15, 23, 42, .14);
      background: rgba(255, 255, 255, .92);
      font-weight: 300;
      font-size: 12.5px;
      cursor: pointer;
      user-select: none;
    }

    .chip.is-on {
      border-color: rgba(37, 99, 235, .55);
      box-shadow: 0 0 0 6px rgba(37, 99, 235, .14);
    }

    .hint {
      margin-top: 10px;
      font-weight: 750;
      color: rgba(15, 23, 42, .62);
      font-size: 12.5px;
      line-height: 1.35;
    }

    .desc {
      padding: 14px 16px;
      color: rgba(15, 23, 42, .78);
      font-weight: 650;
      line-height: 1.35;
    }


    .pdf-shell {
      width: min(1100px, 100%);
      background: #fff;
      border-radius: 22px;
      overflow: hidden;
      border: 1px solid rgba(15, 23, 42, .14);
      box-shadow: 0 30px 90px rgba(2, 6, 23, .22);
    }

    .pdf-head {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(15, 23, 42, .10);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      background: rgba(255, 255, 255, .92);
    }

    .pdf-title {
      font-weight: 300;
      color: #0b1220;
    }

    .pdf-close {
      border: 1px solid rgba(15, 23, 42, .14);
      background: #fff;
      border-radius: 14px;
      padding: .55rem .8rem;
      font-weight: 300;
      cursor: pointer;
    }

    .pdf-close:hover {
      background: rgba(248, 250, 252, .9);
    }

    .no-scroll {
      overflow: hidden !important;
    }
  </style>

  <div class="pd-wrap">
    <div class="pd-top">
      <div>
        <div class="pd-title">{{ $product->title }}</div>
        <div class="pd-sub">Detalle del producto</div>
      </div>

      <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <a class="btn btn-ghost" href="{{ $redirectTo }}">← Volver</a>

        @if($isAdmin)
          <a class="btn btn-primary"
            href="{{ route('admin.product-details.edit', ['menu_product' => $product->id, 'redirect_to' => $redirectTo]) }}">
            ✎ Editar
          </a>
        @endif
      </div>
    </div>

    <div class="layout">
      <div class="card">
        <div class="card-head">
          <div class="h">Galería</div>
          <div class="muted" id="pdFilterLabel">Sin filtros</div>
        </div>

        <div class="media">
          <div class="media-frame">
            @if($mainImg)
              <img id="pdMainImg" src="{{ $mainImg }}" alt="{{ $product->title }}">
            @else
              <div class="media-empty">Sin imagen principal</div>
            @endif
          </div>

          <button type="button" class="nav prev" id="pdPrev" style="display:none;">‹</button>
          <button type="button" class="nav next" id="pdNext" style="display:none;">›</button>
        </div>

        <div class="thumbs" id="pdThumbs"></div>
      </div>

      <div class="card">
        <div class="card-head">
          <div class="h">Variantes</div>
          <div class="muted">Estructura + Laminado</div>
        </div>

        <div class="info">
          <div class="k">Descripción</div>
          <div class="hint" style="margin-top:6px;">
            {{ $detail->description ?: ($product->description ?: 'Sin descripción.') }}
          </div>
        </div>

        <div style="border-top:1px solid rgba(15,23,42,.08);"></div>

        <div class="info">
          <div class="k">Estructura</div>
          <div class="chips" id="chipsA"></div>

          <div class="k" style="margin-top:14px;">Laminado</div>
          <div class="chips" id="chipsM"></div>

          <div class="hint">
            Si eliges <b>Estructura</b> + <b>Laminado</b>, se mostrarán solo las imágenes asignadas a esa combinación.
          </div>


          @php
            $svc = new \App\Services\IngenieriaService();

            $code = $product->ingenieria_code ?: $product->title;

            $docs = $svc->getDocs($code);
            $fallbackDocs = $svc->guessDocsByCode($code);

            $fichaIngenieria = $docs['ficha']
              ? $svc->url($docs['ficha'])
              : $svc->url($fallbackDocs['ficha']);

            $instIngenieria = $docs['instructivo']
              ? $svc->url($docs['instructivo'])
              : $svc->url($fallbackDocs['instructivo']);

            $fichaLocal = $techUrl ?? null;
            $instLocal = $manualUrl ?? null;

            $fichaUrl = $fichaIngenieria ?: $fichaLocal;
            $instUrl = $instIngenieria ?: $instLocal;
          @endphp

          <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">

            @if($fichaUrl)
              <button type="button" class="btn btn-ghost" onclick="window.openPdfPreview(@js($fichaUrl), 'Ficha técnica')">
                📄 Ficha técnica
              </button>
            @endif

            @if($instUrl)
              <button type="button" class="btn btn-ghost" onclick="window.openPdfPreview(@js($instUrl), 'Instructivo')">
                📘 Instructivo
              </button>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>



  <script>
    window.PROD = @json($payload);

    (function () {
      const acero = window.PROD.acero || [];
      const melamina = window.PROD.melamina || [];
      const gallery = window.PROD.gallery || [];

      let selA = '';
      let selM = '';
      let base = [];
      let idx = 0;

      const chipsA = document.getElementById('chipsA');
      const chipsM = document.getElementById('chipsM');
      const thumbs = document.getElementById('pdThumbs');
      const mainImg = document.getElementById('pdMainImg');
      const prev = document.getElementById('pdPrev');
      const next = document.getElementById('pdNext');
      const filterLabel = document.getElementById('pdFilterLabel');

      function chip(label, on) {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'chip' + (on ? ' is-on' : '');
        b.textContent = label;
        return b;
      }

      function renderChips() {
        chipsA.innerHTML = '';
        chipsM.innerHTML = '';

        const allA = chip('Todos', selA === '');
        allA.onclick = () => { selA = ''; apply(); renderChips(); };
        chipsA.appendChild(allA);

        acero.forEach(c => {
          const b = chip(c, selA === c);
          b.onclick = () => { selA = (selA === c) ? '' : c; apply(); renderChips(); };
          chipsA.appendChild(b);
        });

        const allM = chip('Todos', selM === '');
        allM.onclick = () => { selM = ''; apply(); renderChips(); };
        chipsM.appendChild(allM);

        melamina.forEach(c => {
          const b = chip(c, selM === c);
          b.onclick = () => { selM = (selM === c) ? '' : c; apply(); renderChips(); };
          chipsM.appendChild(b);
        });
      }

      function apply() {
        const filtered = gallery.filter(it => {
          const okA = selA ? (it.acero === selA) : true;
          const okM = selM ? (it.melamina === selM) : true;
          return okA && okM;
        });

        base = filtered.length ? filtered : (gallery.length ? gallery : []);
        idx = 0;

        const a = selA ? `Acero: ${selA}` : '';
        const m = selM ? `Laminado: ${selM}` : '';
        filterLabel.textContent = (a || m) ? [a, m].filter(Boolean).join(' · ') : 'Sin filtros';

        renderGallery();
      }

      function renderGallery() {
        if (!mainImg) return;

        thumbs.innerHTML = '';
        const firstUrl = base[0]?.url || window.PROD.main || '';
        if (firstUrl) mainImg.src = firstUrl;

        base.forEach((it, i) => {
          const d = document.createElement('div');
          d.className = 'th' + (i === 0 ? ' is-active' : '');
          const im = document.createElement('img');
          im.src = it.url;
          d.appendChild(im);

          d.onclick = () => {
            idx = i;
            mainImg.src = base[idx].url;
            [...thumbs.querySelectorAll('.th')].forEach((x, k) => x.classList.toggle('is-active', k === idx));
            updateNav(base.length);
          };

          thumbs.appendChild(d);
        });

        updateNav(base.length);
        prev.onclick = () => go(-1);
        next.onclick = () => go(+1);
      }

      function updateNav(len) {
        if (len > 1) { prev.style.display = 'flex'; next.style.display = 'flex'; }
        else { prev.style.display = 'none'; next.style.display = 'none'; }
      }

      function go(step) {
        if (!base.length) return;
        idx = Math.max(0, Math.min(base.length - 1, idx + step));
        mainImg.src = base[idx].url;
        [...thumbs.querySelectorAll('.th')].forEach((x, k) => x.classList.toggle('is-active', k === idx));
        updateNav(base.length);
      }

      renderChips();
      apply();
    })();


  </script>
@endsection
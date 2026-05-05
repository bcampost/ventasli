{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Venta Directa | LI</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ url('/favicon.ico') }}?v=2">

  <style>
    .no-scroll {
      overflow: hidden !important;
    }

    /* Modal PDF GLOBAL (único) */
    #pdfBackdrop {
      position: fixed;
      inset: 0;
      background: rgba(2, 6, 23, .72);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      z-index: 2147483000;
      display: none;
      /* ← a prueba, no depende de "hidden" */
    }

    #pdfModal {
      position: fixed;
      inset: 0;
      z-index: 2147483001;
      display: none;
      /* ← a prueba */
      align-items: center;
      justify-content: center;
      padding: 18px;
    }

    #pdfShell {
      width: min(1100px, 96vw);
      height: min(85vh, 900px);
      background: #fff;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid rgba(15, 23, 42, .14);
      box-shadow: 0 30px 90px rgba(2, 6, 23, .25);
      display: flex;
      flex-direction: column;
    }

    #pdfHead {
      padding: 12px 14px;
      border-bottom: 1px solid rgba(15, 23, 42, .10);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      background: rgba(255, 255, 255, .92);
    }

    #pdfTitle {
      font-weight: 300;
      color: #0b1220;
    }

    .pdfBtn {
      padding: 8px 10px;
      border-radius: 12px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: rgba(248, 250, 252, .9);
      font-weight: 300;
      cursor: pointer;
      text-decoration: none;
      color: #0b1220;
      user-select: none;
    }

    .pdfBtn:hover {
      background: rgba(248, 250, 252, 1);
    }

    #pdfTools {
      display: flex;
      gap: 8px;
      align-items: center;
      flex-wrap: wrap;
    }

    #mediaZoomBar {
      display: none;
      align-items: center;
      gap: 8px;
      padding: 6px 8px;
      border: 1px solid rgba(15, 23, 42, .12);
      border-radius: 12px;
      background: rgba(248, 250, 252, .92);
    }

    .zoomBtn {
      min-width: 38px;
      height: 36px;
      padding: 0 10px;
      border-radius: 10px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: #fff;
      color: #0b1220;
      font-weight: 300;
      cursor: pointer;
    }

    .zoomBtn:hover {
      background: rgba(248, 250, 252, 1);
    }

    #zoomLabel {
      min-width: 58px;
      text-align: center;
      font-weight: 800;
      color: #0b1220;
      font-size: 13px;
    }

    #pdfBody {
      flex: 1;
      background: #0b1220;
      position: relative;
      overflow: auto;
    }

    #mediaImageWrap {
      display: none;
      width: 100%;
      height: 100%;
      overflow: auto;
      align-items: center;
      justify-content: center;
      background: #0b1220;
      padding: 14px;
    }

    #mediaImage {
      display: none;
      max-width: 100%;
      max-height: 100%;
      width: auto;
      height: auto;
      object-fit: contain;
    }

    #pdfBody {
      flex: 1;
      background: #0b1220;
    }

    #pdfFrame {
      width: 100%;
      height: 100%;
      border: 0;
      background: #fff;
    }
  </style>
</head>

<body class="antialiased">
  <div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    @isset($header)
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endisset

    <main>
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>

  {{-- ✅ SIEMPRE visible (iconos laterales) --}}
  @include('partials.right-quick-menu')

  {{-- ✅ FOOTER SIEMPRE visible --}}
  @include('layouts.footer')

  {{-- =========================================================
  ✅ MODAL UNIVERSAL PREVIEW PDF (GLOBAL ÚNICO)
  Se abre SOLO cuando llamas: openPdfPreview(url, title)
  ========================================================= --}}
  <div id="pdfBackdrop" onclick="window.closePdfPreview && window.closePdfPreview()"></div>

  <div id="pdfModal" aria-hidden="true" onclick="window.closePdfPreview && window.closePdfPreview()">
    <div id="pdfShell" onclick="event.stopPropagation()">
      <div id="pdfHead">
        <div id="pdfTitle">Preview</div>

        <div id="pdfTools">
          <div id="mediaZoomBar">
            <button type="button" class="zoomBtn" onclick="window.mediaZoomOut && window.mediaZoomOut()">−</button>
            <div id="zoomLabel">100%</div>
            <button type="button" class="zoomBtn" onclick="window.mediaZoomIn && window.mediaZoomIn()">+</button>
            <button type="button" class="zoomBtn"
              onclick="window.mediaZoomReset && window.mediaZoomReset()">Reset</button>
          </div>

          <a id="downloadMediaBtn" class="pdfBtn" href="#" download onclick="event.stopPropagation();">
            Descargar ⬇️
          </a>

          <button type="button" class="pdfBtn" onclick="window.closePdfPreview && window.closePdfPreview()">
            Cerrar ✕
          </button>
        </div>
      </div>

      <div id="pdfBody" tabindex="-1">
        <iframe id="pdfFrame" src="" loading="lazy"
          style="display:none;width:100%;height:100%;border:0;background:#fff;"></iframe>

        <div id="mediaImageWrap" tabindex="-1">
          <img id="mediaImage" src="" alt="">
        </div>

        <video id="mediaVideo" controls playsinline
          style="display:none;width:100%;height:100%;background:#0b1220;"></video>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const backdrop = () => document.getElementById('pdfBackdrop');
      const modal = () => document.getElementById('pdfModal');
      const frame = () => document.getElementById('pdfFrame');
      const imageEl = () => document.getElementById('mediaImage');
      const imageWrap = () => document.getElementById('mediaImageWrap');
      const videoEl = () => document.getElementById('mediaVideo');
      const titleEl = () => document.getElementById('pdfTitle');
      const openNew = () => document.getElementById('pdfOpenNewTab');
      const zoomBar = () => document.getElementById('mediaZoomBar');
      const zoomLabel = () => document.getElementById('zoomLabel');

      let currentZoom = 1;
      const MIN_ZOOM = 0.25;
      const MAX_ZOOM = 3;
      const STEP_ZOOM = 0.10;

      function updateZoomUI() {
        if (zoomLabel()) {
          zoomLabel().textContent = Math.round(currentZoom * 100) + '%';
        }
        if (imageEl()) {
          imageEl().style.transform = `scale(${currentZoom})`;
        }
      }

      function showZoomBar(show) {
        if (zoomBar()) {
          zoomBar().style.display = show ? 'inline-flex' : 'none';
        }
      }

      function resetZoom() {
        currentZoom = 1;
        updateZoomUI();
        if (imageWrap()) {
          imageWrap().scrollTop = 0;
          imageWrap().scrollLeft = 0;
        }
      }

      function resetMedia() {
        if (frame()) {
          frame().src = '';
          frame().style.display = 'none';
        }

        if (imageEl()) {
          imageEl().src = '';
          imageEl().style.display = 'none';
          imageEl().style.transform = 'scale(1)';
        }

        if (imageWrap()) {
          imageWrap().style.display = 'none';
          imageWrap().scrollTop = 0;
          imageWrap().scrollLeft = 0;
        }

        if (videoEl()) {
          videoEl().pause();
          videoEl().src = '';
          videoEl().style.display = 'none';
        }

        showZoomBar(false);
        resetZoom();
      }

      function openModal(url, title) {
        if (!url) return;

        if (titleEl()) titleEl().textContent = title || 'Documento';
        if (openNew()) openNew().href = url;

        const downloadBtn = document.getElementById('downloadMediaBtn');
        if (downloadBtn) {
          downloadBtn.href = url;
        }

        if (backdrop()) backdrop().style.display = 'block';
        if (modal()) {
          modal().style.display = 'flex';
          modal().setAttribute('aria-hidden', 'false');
        }

        document.body.classList.add('no-scroll');
        setTimeout(() => {
          const focusTarget =
            (imageWrap() && imageWrap().style.display !== 'none')
              ? imageWrap()
              : document.getElementById('pdfBody');

          if (focusTarget) {
            focusTarget.focus({ preventScroll: true });
          }
        }, 50);
      }

      window.mediaZoomIn = function () {
        currentZoom = Math.min(MAX_ZOOM, currentZoom + STEP_ZOOM);
        updateZoomUI();
      };

      window.mediaZoomOut = function () {
        currentZoom = Math.max(MIN_ZOOM, currentZoom - STEP_ZOOM);
        updateZoomUI();
      };

      window.mediaZoomReset = function () {
        resetZoom();
      };

      window.openPdfPreview = function (url, title) {
        if (!url) return;

        resetMedia();

        if (frame()) {
          frame().src = url;
          frame().style.display = 'block';
        }

        openModal(url, title || 'Documento');
      };

      window.openMediaPreview = function (url, title) {
        if (!url) return;

        resetMedia();

        const lower = String(url).toLowerCase();

        if (lower.match(/\.(png|jpg|jpeg|gif|webp|svg)(\?.*)?$/)) {
          if (imageWrap()) imageWrap().style.display = 'flex';
          if (imageEl()) {
            imageEl().src = url;
            imageEl().style.display = 'block';
          }
          showZoomBar(true);
          resetZoom();
        }
        else if (lower.match(/\.(mp4|webm|ogg)(\?.*)?$/)) {
          if (videoEl()) {
            videoEl().src = url;
            videoEl().style.display = 'block';
          }
        }
        else {
          if (frame()) {
            frame().src = url;
            frame().style.display = 'block';
          }
        }

        openModal(url, title || 'Vista previa');
      };

      window.closePdfPreview = function () {
        resetMedia();

        if (backdrop()) backdrop().style.display = 'none';
        if (modal()) {
          modal().style.display = 'none';
          modal().setAttribute('aria-hidden', 'true');
        }

        document.body.classList.remove('no-scroll');
      };

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') window.closePdfPreview();

        const tag = (e.target?.tagName || '').toLowerCase();
        const isTyping =
          tag === 'input' ||
          tag === 'textarea' ||
          e.target?.isContentEditable;

        if (isTyping) return;

        const wrap = imageWrap();
        const isImagePreviewOpen =
          wrap &&
          window.getComputedStyle(wrap).display !== 'none';

        if (isImagePreviewOpen) {
          if (e.key === '+' || e.key === '=') {
            e.preventDefault();
            window.mediaZoomIn();
          }

          if (e.key === '-') {
            e.preventDefault();
            window.mediaZoomOut();
          }

          if (e.key === '0') {
            e.preventDefault();
            window.mediaZoomReset();
          }
        }
      });
    })();

    window.openCreateNode = function (parentId) {
      const input = document.getElementById('create_parent_id');
      const backdrop = document.getElementById('createModalBackdrop');
      const modal = document.getElementById('createModal');

      if (!input || !backdrop || !modal) return;

      input.value = (parentId ?? 0);
      backdrop.classList.remove('hidden');
      modal.classList.remove('hidden');
      modal.classList.add('modal-open');
      document.body.classList.add('no-scroll');
    }

    window.closeCreateNode = function () {
      const backdrop = document.getElementById('createModalBackdrop');
      const modal = document.getElementById('createModal');
      if (!backdrop || !modal) return;

      backdrop.classList.add('hidden');
      modal.classList.remove('modal-open');
      modal.classList.add('hidden');
      document.body.classList.remove('no-scroll');
    }
  </script>
</body>

</html>
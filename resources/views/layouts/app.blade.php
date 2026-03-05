{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
      .no-scroll{ overflow: hidden !important; }

      /* Modal PDF GLOBAL (único) */
      #pdfBackdrop{
        position: fixed;
        inset: 0;
        background: rgba(2,6,23,.72);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 2147483000;
        display: none; /* ← a prueba, no depende de "hidden" */
      }
      #pdfModal{
        position: fixed;
        inset: 0;
        z-index: 2147483001;
        display: none; /* ← a prueba */
        align-items: center;
        justify-content: center;
        padding: 18px;
      }
      #pdfShell{
        width: min(1100px, 96vw);
        height: min(85vh, 900px);
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(15,23,42,.14);
        box-shadow: 0 30px 90px rgba(2,6,23,.25);
        display: flex;
        flex-direction: column;
      }
      #pdfHead{
        padding: 12px 14px;
        border-bottom: 1px solid rgba(15,23,42,.10);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: rgba(255,255,255,.92);
      }
      #pdfTitle{
        font-weight: 900;
        color: #0b1220;
      }
      .pdfBtn{
        padding: 8px 10px;
        border-radius: 12px;
        border: 1px solid rgba(15,23,42,.12);
        background: rgba(248,250,252,.9);
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        color: #0b1220;
        user-select:none;
      }
      .pdfBtn:hover{ background: rgba(248,250,252,1); }

      #pdfBody{ flex: 1; background:#0b1220; }
      #pdfFrame{ width: 100%; height: 100%; border: 0; background: #fff; }
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
          <div id="pdfTitle">Preview PDF</div>

          <div style="display:flex; gap:10px; align-items:center;">
            <a id="pdfOpenNewTab" href="#" target="_blank" rel="noopener" class="pdfBtn"
               onclick="event.stopPropagation();">
              Abrir aparte ↗
            </a>

            <button type="button" class="pdfBtn" onclick="window.closePdfPreview && window.closePdfPreview()">
              Cerrar ✕
            </button>
          </div>
        </div>

        <div id="pdfBody">
          <iframe id="pdfFrame" src="" loading="lazy"></iframe>
        </div>
      </div>
    </div>

    <script>
      (function(){
        const backdrop = () => document.getElementById('pdfBackdrop');
        const modal    = () => document.getElementById('pdfModal');
        const frame    = () => document.getElementById('pdfFrame');
        const titleEl  = () => document.getElementById('pdfTitle');
        const openNew  = () => document.getElementById('pdfOpenNewTab');

        window.openPdfPreview = function(url, title){
          if(!url) return;

          if(titleEl()) titleEl().textContent = title || 'Documento';
          if(frame()) frame().src = url;
          if(openNew()) openNew().href = url;

          if(backdrop()) backdrop().style.display = 'block';
          if(modal()) {
            modal().style.display = 'flex';
            modal().setAttribute('aria-hidden', 'false');
          }

          document.body.classList.add('no-scroll');
        };

        window.closePdfPreview = function(){
          if(frame()) frame().src = '';
          if(backdrop()) backdrop().style.display = 'none';
          if(modal()) {
            modal().style.display = 'none';
            modal().setAttribute('aria-hidden', 'true');
          }
          document.body.classList.remove('no-scroll');
        };

        // Cierra con ESC
        document.addEventListener('keydown', (e)=>{
          if(e.key === 'Escape') window.closePdfPreview();
        });

        // ✅ OJO: quitamos AUTO-OPEN por query para que NO se abra solo en otras páginas.
        // Si lo quieres, se hace solo en rutas específicas.
      })();

      // =========================================================
      // ✅ Tus helpers existentes (crear nodo modal)
      // =========================================================
      window.openCreateNode = function(parentId){
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

      window.closeCreateNode = function(){
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
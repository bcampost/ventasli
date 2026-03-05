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
</head>

<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        {{-- Page Heading --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    {{-- ✅ SIEMPRE visible (iconos laterales) --}}
    @include('partials.right-quick-menu')

    {{-- ✅ FOOTER SIEMPRE visible --}}
    @include('layouts.footer')

    {{-- Tu modal existente (lo dejamos) --}}
    <x-pdf-preview-modal />

    {{-- =========================================================
       ✅ MODAL UNIVERSAL PREVIEW PDF (global)
       Se abre si existe ?pdf_preview=URL
       ========================================================= --}}
    <div id="pdfPreviewBackdrop" class="fixed inset-0 hidden" style="background: rgba(2,6,23,.72); backdrop-filter: blur(10px); z-index: 9998;" onclick="closePdfPreview()"></div>

    <div id="pdfPreviewModal" class="fixed inset-0 hidden" style="z-index: 9999;">
        <div class="min-h-full flex items-center justify-center p-4">
            <div style="
                width: min(1100px, 96vw);
                height: min(85vh, 900px);
                background: white;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 30px 90px rgba(2,6,23,.25);
                border: 1px solid rgba(15,23,42,.14);
                display:flex;
                flex-direction: column;
            ">
                <div style="
                    padding: 12px 14px;
                    border-bottom: 1px solid rgba(15,23,42,.10);
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap: 12px;
                    background: rgba(255,255,255,.9);
                ">
                    <div style="font-weight: 800; color:#0b1220;">
                        Preview PDF
                    </div>

                    <div style="display:flex; gap:10px; align-items:center;">
                        <a id="pdfPreviewOpenNewTab" href="#" target="_blank" rel="noopener"
                           style="
                             padding: 8px 10px;
                             border-radius: 12px;
                             border: 1px solid rgba(15,23,42,.12);
                             text-decoration:none;
                             color:#0b1220;
                             font-weight: 700;
                             background: rgba(248,250,252,.85);
                           "
                        >Abrir aparte ↗</a>

                        <button type="button" onclick="closePdfPreview()"
                                style="
                                  padding: 8px 10px;
                                  border-radius: 12px;
                                  border: 1px solid rgba(15,23,42,.12);
                                  background: rgba(248,250,252,.85);
                                  font-weight: 800;
                                  cursor:pointer;
                                "
                        >Cerrar ✕</button>
                    </div>
                </div>

                <div style="flex:1; background:#0b1220;">
                    <iframe
                        id="pdfPreviewFrame"
                        src=""
                        style="width:100%; height:100%; border:0; background:white;"
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>

<script>
  // =========================================================
  // ✅ PDF Preview Universal (global)
  // =========================================================
  function openPdfPreview(url){
    if (!url) return;
    const backdrop = document.getElementById('pdfPreviewBackdrop');
    const modal    = document.getElementById('pdfPreviewModal');
    const frame    = document.getElementById('pdfPreviewFrame');
    const openNew  = document.getElementById('pdfPreviewOpenNewTab');

    // Asegura que el iframe cargue PDF (si ya trae query, respeta)
    frame.src = url;
    openNew.href = url;

    backdrop.classList.remove('hidden');
    modal.classList.remove('hidden');
    document.body.classList.add('no-scroll');
  }

  function closePdfPreview(){
    const backdrop = document.getElementById('pdfPreviewBackdrop');
    const modal    = document.getElementById('pdfPreviewModal');
    const frame    = document.getElementById('pdfPreviewFrame');

    backdrop.classList.add('hidden');
    modal.classList.add('hidden');
    document.body.classList.remove('no-scroll');

    // libera el iframe
    frame.src = '';
  }

  // auto-open si viene por query
  document.addEventListener('DOMContentLoaded', () => {
    try {
      const params = new URLSearchParams(window.location.search);
      const u = params.get('pdf_preview');
      if (u) openPdfPreview(u);
    } catch (e) {}
  });

  // Cierra con ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closePdfPreview();
  });

  // expón global
  window.openPdfPreview = openPdfPreview;
  window.closePdfPreview = closePdfPreview;

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

<style>
  .no-scroll{ overflow: hidden !important; }
</style>

</body>
</html>
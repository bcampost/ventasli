{{-- resources/views/components/pdf-preview-modal.blade.php --}}
<div
    id="mediaPreviewModal"
    class="fixed inset-0 hidden"
    style="z-index:2147483647;"
    aria-hidden="true"
>
    <div
        id="mediaPreviewOverlay"
        class="absolute inset-0 bg-black/85 backdrop-blur-sm"
    ></div>

    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl shadow-2xl overflow-hidden"
            style="
                width: min(1200px, 94vw);
                height: min(92vh, 980px);
                display: grid;
                grid-template-rows: auto 1fr;
            "
        >
            <div class="px-4 py-3 border-b bg-white flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Vista previa</p>
                    <h3 id="mediaPreviewTitle" class="text-lg font-semibold truncate">Documento</h3>
                </div>

                <div class="flex items-center gap-2">
                    {{-- ✅ Zoom controls --}}
                    <div class="flex items-center gap-2 mr-2">
                        <button
                            id="mediaZoomOut"
                            class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                            type="button"
                            title="Zoom -"
                        >−</button>

                        <button
                            id="mediaZoomReset"
                            class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                            type="button"
                            title="100%"
                        >100%</button>

                        <button
                            id="mediaZoomFit"
                            class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                            type="button"
                            title="Ajustar"
                        >Ajustar</button>

                        <button
                            id="mediaZoomIn"
                            class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                            type="button"
                            title="Zoom +"
                        >+</button>

                        <div class="hidden sm:flex items-center gap-2 ml-2">
                            <input id="mediaZoomRange" type="range" min="50" max="200" value="100" />
                            <span id="mediaZoomLabel" class="text-xs font-semibold text-gray-600 w-12 text-right">100%</span>
                        </div>
                    </div>

                    {{-- ✅ IMPORTANTE: data-modal-ignore para que NO lo intercepte el listener --}}
                    <a
                        id="mediaPreviewDownload"
                        data-modal-ignore="1"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        href="#"
                        download
                    >Descargar</a>

                    <button
                        id="mediaPreviewPrint"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        type="button"
                    >Imprimir</button>

                    <button
                        id="mediaPreviewClose"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        type="button"
                    >Cerrar</button>
                </div>
            </div>

            <div style="min-height:0; overflow:hidden; position:relative; background:#111;">
                {{-- ✅ Overlay suave para evitar parpadeo al recargar PDF --}}
                <div
                    id="mediaPreviewBusy"
                    aria-hidden="true"
                    style="
                        position:absolute;
                        inset:0;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background: rgba(0,0,0,.40);
                        backdrop-filter: blur(6px);
                        opacity: 0;
                        pointer-events:none;
                        transition: opacity 160ms ease;
                        z-index: 5;
                    "
                >
                    <div style="
                        display:flex;
                        align-items:center;
                        gap:12px;
                        padding: 12px 14px;
                        border-radius: 14px;
                        background: rgba(255,255,255,.10);
                        border: 1px solid rgba(255,255,255,.14);
                        color:#fff;
                        font-weight:700;
                        font-size: 13px;
                        box-shadow: 0 18px 60px rgba(0,0,0,.35);
                    ">
                        <span style="
                            width:10px;height:10px;border-radius:999px;
                            background:#fff;opacity:.9;
                            display:inline-block;
                            animation: mpPulse 900ms ease-in-out infinite;
                        "></span>
                        Aplicando zoom…
                    </div>
                </div>

                <iframe
                    id="mediaPreviewIframe"
                    src="about:blank"
                    title="PDF Preview"
                    style="display:none; width:100%; height:100%; border:0; background:#fff; position:relative; z-index:1;"
                ></iframe>

                <div
                    id="mediaPreviewImageWrap"
                    style="display:none; width:100%; height:100%; overflow:auto; background:#111; position:relative; z-index:1;"
                >
                    <div
                        id="mediaPreviewImageStage"
                        style="
                            width:100%;
                            height:100%;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            padding: 18px;
                            transform-origin: center center;
                            transition: transform 140ms ease;
                        "
                    >
                        <img
                            id="mediaPreviewImage"
                            alt="Vista previa"
                            style="
                                display:block;
                                max-width: 100%;
                                max-height: 100%;
                                width: auto;
                                height: auto;
                                object-fit: contain;
                                border-radius: 10px;
                                box-shadow: 0 20px 60px rgba(0,0,0,.35);
                            "
                        />
                    </div>
                </div>

                <style>
                  @keyframes mpPulse{
                    0%,100%{ transform: scale(1); opacity:.75; }
                    50%{ transform: scale(1.45); opacity:1; }
                  }
                </style>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    function $(id){ return document.getElementById(id); }

    const modal   = $('mediaPreviewModal');
    const overlay = $('mediaPreviewOverlay');
    const btnClose= $('mediaPreviewClose');
    const btnPrint= $('mediaPreviewPrint');
    const aDown   = $('mediaPreviewDownload');
    const titleEl = $('mediaPreviewTitle');

    const iframe  = $('mediaPreviewIframe');
    const imgWrap = $('mediaPreviewImageWrap');
    const imgEl   = $('mediaPreviewImage');
    const imgStage= $('mediaPreviewImageStage');
    const busy    = $('mediaPreviewBusy');

    // zoom controls
    const zOut   = $('mediaZoomOut');
    const zIn    = $('mediaZoomIn');
    const zReset = $('mediaZoomReset');
    const zFit   = $('mediaZoomFit');
    const zRange = $('mediaZoomRange');
    const zLabel = $('mediaZoomLabel');

    let currentUrl = '';
    let currentType = ''; // 'pdf' | 'img'
    let zoom = 100;       // percent
    let fitMode = false;  // PDF: page-width ; IMG: 100

    // state for smooth PDF reload
    let pdfReloadTimer = null;
    let pdfLoading = false;

    function showBusy(){ if (busy) busy.style.opacity = '1'; }
    function hideBusy(){ if (busy) busy.style.opacity = '0'; }

    function extOf(url){
        const clean = (url || '').split('#')[0].split('?')[0].toLowerCase();
        const m = clean.match(/\.([a-z0-9]+)$/);
        return m ? m[1] : '';
    }
    function isPdf(url){ return extOf(url) === 'pdf'; }
    function isImage(url){
        const e = extOf(url);
        return ['jpg','jpeg','png','webp','gif','svg'].includes(e);
    }
    function toAbs(url){
        try { return new URL(url, window.location.href).toString(); }
        catch (_) { return url; }
    }

    function setZoomUI(val){
        zoom = Math.max(50, Math.min(200, val));
        if (zRange) zRange.value = String(zoom);
        if (zLabel) zLabel.textContent = zoom + '%';
    }

    function buildPdfUrlWithZoom(absUrl){
        const base = absUrl.split('#')[0];
        const hasQuery = base.includes('?');
        const bust = (hasQuery ? '&' : '?') + '_z=' + Date.now();
        const zoomPart = fitMode ? 'page-width' : String(zoom);
        const hash = '#toolbar=0&navpanes=0&page=1&zoom=' + encodeURIComponent(zoomPart);
        return base + bust + hash;
    }

    function smoothReloadPdf(){
        if (!currentUrl || currentType !== 'pdf') return;

        if (pdfReloadTimer) clearTimeout(pdfReloadTimer);
        showBusy();

        pdfReloadTimer = setTimeout(() => {
            const next = buildPdfUrlWithZoom(currentUrl);
            pdfLoading = true;

            iframe.src = 'about:blank';
            setTimeout(() => { iframe.src = next; }, 40);
        }, 80);
    }

    iframe?.addEventListener('load', () => {
        if (currentType !== 'pdf') return;
        if (!pdfLoading) return;
        pdfLoading = false;
        setTimeout(() => hideBusy(), 60);
    });

    function applyZoom(){
        if (!currentUrl) return;

        if (currentType === 'img'){
            imgStage.style.transform = 'scale(' + (zoom/100) + ')';
            return;
        }

        if (currentType === 'pdf'){
            smoothReloadPdf();
            return;
        }
    }

    function openAsPdf(url){
        currentType = 'pdf';
        iframe.style.display = 'block';
        imgWrap.style.display = 'none';
        imgEl.src = '';
        imgStage.style.transform = 'scale(1)';
        fitMode = true;
        setZoomUI(100);
        showBusy();
        applyZoom();
    }

    function openAsImage(url){
        currentType = 'img';
        hideBusy();
        iframe.style.display = 'none';
        iframe.src = 'about:blank';
        imgWrap.style.display = 'block';
        imgEl.src = url;
        fitMode = false;
        setZoomUI(100);
        applyZoom();
    }

    function openMedia(url, title, forceAsImage){
        const abs = toAbs(url);
        currentUrl = abs;

        titleEl.textContent = title || 'Vista previa';

        // ✅ Descargar debe usar la URL base sin cache-buster/zoom
        aDown.href = abs.split('#')[0];

        iframe.style.display = 'none';
        imgWrap.style.display = 'none';
        iframe.src = 'about:blank';
        imgEl.src = '';
        imgStage.style.transform = 'scale(1)';

        pdfLoading = false;
        if (pdfReloadTimer) clearTimeout(pdfReloadTimer);
        pdfReloadTimer = null;

        if (isPdf(abs)){
            openAsPdf(abs);
        } else if (isImage(abs)){
            openAsImage(abs);
        } else if (forceAsImage){
            openAsImage(abs);
        } else {
            window.open(abs, '_blank');
            return;
        }

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeMedia(){
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');

        if (pdfReloadTimer) clearTimeout(pdfReloadTimer);
        pdfReloadTimer = null;
        pdfLoading = false;
        hideBusy();

        iframe.src = 'about:blank';
        imgEl.src = '';
        imgStage.style.transform = 'scale(1)';
        document.body.style.overflow = '';

        currentUrl = '';
        currentType = '';
        fitMode = false;
        setZoomUI(100);
    }

    function printPdf(){
        try{
            if (iframe && iframe.contentWindow){
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }catch(e){}
    }

    function printImage(url){
        const w = window.open('', '_blank', 'noopener,noreferrer');
        if (!w) return;

        const safeTitle = (titleEl.textContent || 'Imagen').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        w.document.open();
        w.document.write(`
          <!doctype html>
          <html>
            <head>
              <meta charset="utf-8" />
              <title>${safeTitle}</title>
              <style>
                html,body{height:100%;margin:0}
                body{display:flex;align-items:center;justify-content:center;background:#fff}
                img{max-width:100%;max-height:100%}
              </style>
            </head>
            <body>
              <img src="${url}" onload="window.focus(); window.print(); setTimeout(()=>window.close(), 50);" />
            </body>
          </html>
        `);
        w.document.close();
    }

    function printCurrent(){
        if (!currentUrl) return;
        if (currentType === 'pdf') return printPdf();
        if (currentType === 'img') return printImage(currentUrl);
    }

    // Zoom actions
    function zoomIn(){ fitMode = false; setZoomUI(zoom + 10); applyZoom(); }
    function zoomOut(){ fitMode = false; setZoomUI(zoom - 10); applyZoom(); }
    function zoomReset(){ fitMode = false; setZoomUI(100); applyZoom(); }
    function zoomFit(){ fitMode = true; setZoomUI(100); applyZoom(); }

    zIn?.addEventListener('click', zoomIn);
    zOut?.addEventListener('click', zoomOut);
    zReset?.addEventListener('click', zoomReset);
    zFit?.addEventListener('click', zoomFit);

    zRange?.addEventListener('input', (e)=>{
        fitMode = false;
        setZoomUI(parseInt(e.target.value || '100', 10));
        applyZoom();
    });

    overlay?.addEventListener('click', closeMedia);
    btnClose?.addEventListener('click', closeMedia);
    btnPrint?.addEventListener('click', printCurrent);

    document.addEventListener('keydown', (e)=>{
        if(e.key === 'Escape') closeMedia();
        if(!modal || modal.classList.contains('hidden')) return;

        if(e.key === '+' || (e.ctrlKey && e.key === '=')) { e.preventDefault(); zoomIn(); }
        if(e.key === '-' || (e.ctrlKey && e.key === '-')) { e.preventDefault(); zoomOut(); }
        if(e.key === '0' && e.ctrlKey) { e.preventDefault(); zoomReset(); }
    });

    // ✅ EXPO GLOBAL
    window.openMediaPreview = function(url, title, forceAsImage){
        if (!url) return;
        openMedia(url, title || 'Vista previa', !!forceAsImage);
    };

    // ✅ Delegado: captura [data-preview="media"] y también <a> normales (PDF/IMG)
    document.addEventListener('click', function(e){
        // ✅ NO interceptar clicks dentro del modal (ej: Descargar)
        if (e.target.closest('#mediaPreviewModal')) return;

        // ✅ si trae ignore explícito, tampoco
        if (e.target.closest('[data-modal-ignore="1"]')) return;

        const el = e.target.closest('[data-preview="media"], a');
        if(!el) return;

        const forced = el.getAttribute('data-preview') === 'media';

        let href = (el.getAttribute('href') || '').trim();
        const dataSrc = (el.getAttribute('data-src') || '').trim();

        let imgSrc = '';
        const img = el.querySelector ? el.querySelector('img') : null;
        if (img) imgSrc = (img.currentSrc || img.src || '').trim();

        const candidate = (href && href !== '#' && !href.startsWith('javascript:')) ? href : (dataSrc || imgSrc);
        if (!candidate) return;

        if (!forced && !isPdf(candidate) && !isImage(candidate)) return;

        e.preventDefault();

        const title =
            el.getAttribute('data-title') ||
            el.getAttribute('title') ||
            (el.textContent ? el.textContent.trim() : '') ||
            'Vista previa';

        openMedia(candidate, title, forced && !isPdf(candidate) && !isImage(candidate));
    }, true);
})();
</script>
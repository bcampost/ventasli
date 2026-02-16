{{-- resources/views/components/pdf-preview-modal.blade.php --}}
<div
    id="pdfPreviewModal"
    class="fixed inset-0 hidden"
    style="z-index:2147483647;"
    aria-hidden="true"
>
    {{-- Overlay --}}
    <div
        id="pdfPreviewOverlay"
        class="absolute inset-0 bg-black/85 backdrop-blur-sm"
    ></div>

    {{-- Contenedor centrado --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        {{-- Panel (como tu rectángulo rojo) --}}
        <div
            id="pdfPreviewPanel"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden"
            style="
                width: min(1100px, 92vw);
                height: min(92vh, 980px);
                display: grid;
                grid-template-rows: auto 1fr;
            "
        >
            {{-- Header --}}
            <div class="px-4 py-3 border-b bg-white flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Vista previa</p>
                    <h3 id="pdfPreviewTitle" class="text-lg font-semibold truncate">Documento</h3>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        id="pdfPreviewDownload"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        href="#"
                        download
                    >
                        Descargar
                    </a>

                    <button
                        id="pdfPreviewPrint"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        type="button"
                    >
                        Imprimir
                    </button>

                    <button
                        id="pdfPreviewClose"
                        class="px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50"
                        type="button"
                    >
                        Cerrar
                    </button>
                </div>
            </div>

            {{-- Body (toma TODO el espacio restante) --}}
            <div
                class="bg-gray-200"
                style="
                    min-height: 0;
                    overflow: hidden;
                "
            >
                <iframe
                    id="pdfPreviewIframe"
                    src="about:blank"
                    title="PDF Preview"
                    style="
                        display:block;
                        width:100%;
                        height:100%;
                        border:0;
                        background:#fff;
                    "
                ></iframe>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    function $(id){ return document.getElementById(id); }

    function isPdfHref(href){
        if (!href) return false;
        if (href.startsWith('#') || href.startsWith('javascript:')) return false;
        const clean = href.split('#')[0].split('?')[0].toLowerCase();
        return clean.endsWith('.pdf');
    }

    function openPdf(url, title){
        const modal = $('pdfPreviewModal');
        const iframe = $('pdfPreviewIframe');
        const titleEl = $('pdfPreviewTitle');
        const download = $('pdfPreviewDownload');

        titleEl.textContent = title || 'Documento';
        download.href = url;

        // Chrome suele respetar mejor page-width
        iframe.src = url + '#toolbar=0&navpanes=0&zoom=page-width';

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closePdf(){
        const modal = $('pdfPreviewModal');
        const iframe = $('pdfPreviewIframe');

        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        iframe.src = 'about:blank';
        document.body.style.overflow = '';
    }

    function printPdf(){
        try{
            const iframe = $('pdfPreviewIframe');
            if (iframe && iframe.contentWindow){
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }catch(e){}
    }

    $('pdfPreviewOverlay')?.addEventListener('click', closePdf);
    $('pdfPreviewClose')?.addEventListener('click', closePdf);
    $('pdfPreviewPrint')?.addEventListener('click', printPdf);

    document.addEventListener('keydown', (e)=>{
        if(e.key === 'Escape') closePdf();
    });

    // Intercepta clicks a PDFs
    document.addEventListener('click', function(e){
        const a = e.target.closest('a');
        if(!a) return;

        const href = a.getAttribute('href') || '';
        const force = a.dataset.preview === 'pdf';

        if(!force && !isPdfHref(href)) return;

        e.preventDefault();

        let absUrl = href;
        try { absUrl = new URL(href, window.location.href).toString(); } catch (_) {}

        const title =
            a.dataset.title ||
            a.getAttribute('title') ||
            (a.textContent ? a.textContent.trim() : '') ||
            'Documento';

        openPdf(absUrl, title);
    });
})();
</script>
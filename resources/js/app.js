import "./bootstrap";

import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/effect-coverflow";

import Swiper from "swiper";
import { Autoplay, Pagination, EffectCoverflow } from "swiper/modules";

window.initHomeSlider = () => {
    const el = document.querySelector(".swiper.hero3d-swiper");
    if (!el) return;

    new Swiper(".swiper.hero3d-swiper", {
        modules: [Autoplay, Pagination, EffectCoverflow],

        loop: true,
        centeredSlides: true,
        grabCursor: true,
        slidesPerView: "auto",

        // un poco de separación para “respirar”
        spaceBetween: 26,

        effect: "coverflow",
        coverflowEffect: {
            rotate: 20, // ✅ más giro lateral
            stretch: 0,
            depth: 520, // ✅ más “hacia atrás”
            modifier: 1.8, // ✅ volumen/teatro
            slideShadows: false,
        },

        autoplay: { delay: 5200, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },

        breakpoints: {
            0: {
                spaceBetween: 12,
                coverflowEffect: { rotate: 14, depth: 360, modifier: 1.55 },
            },
            768: {
                spaceBetween: 18,
                coverflowEffect: { rotate: 18, depth: 460, modifier: 1.7 },
            },
            1100: {
                spaceBetween: 26,
                coverflowEffect: { rotate: 20, depth: 520, modifier: 1.8 },
            },
        },
    });
};

// =======================
// PDF PREVIEW MODAL (GLOBAL)
// =======================
(function initPdfPreviewModal() {
    function isPdfUrl(url) {
        if (!url) return false;
        const clean = String(url).split("#")[0].split("?")[0].toLowerCase();
        return clean.endsWith(".pdf");
    }

    function getEl(id) {
        return document.getElementById(id);
    }

    function openPdfModal(url, title) {
        const modal = getEl("pdfPreviewModal");
        const iframe = getEl("pdfPreviewIframe");
        const titleEl = getEl("pdfPreviewTitle");
        const download = getEl("pdfPreviewDownload");

        if (!modal || !iframe || !titleEl || !download) return;

        titleEl.textContent = title || "Documento";
        download.href = url || "#";

        // Tip: oculta toolbar en muchos visores nativos
        const iframeSrc = url ? `${url}#toolbar=0&navpanes=0` : "about:blank";
        iframe.src = iframeSrc;

        modal.classList.remove("hidden");
        modal.setAttribute("aria-hidden", "false");
        document.body.classList.add("overflow-hidden");
    }

    function closePdfModal() {
        const modal = getEl("pdfPreviewModal");
        const iframe = getEl("pdfPreviewIframe");
        if (!modal || !iframe) return;

        modal.classList.add("hidden");
        modal.setAttribute("aria-hidden", "true");
        iframe.src = "about:blank";
        document.body.classList.remove("overflow-hidden");
    }

    function printPdfFromModal() {
        const iframe = getEl("pdfPreviewIframe");
        if (!iframe) return;

        // Intenta imprimir el contenido del iframe (mismo origen)
        try {
            if (iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                return;
            }
        } catch (e) {
            // puede fallar si el PDF viene de otro dominio (CORS/sandbox)
        }

        // Fallback: abre el PDF en una pestaña y manda imprimir
        const download = getEl("pdfPreviewDownload");
        const url = download?.href;
        if (url && url !== "#") {
            const w = window.open(url, "_blank", "noopener,noreferrer");
            if (w) {
                w.focus();
                // algunos navegadores requieren interacción del usuario para print
                try {
                    w.print();
                } catch (_) {}
            }
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Wire buttons
        const overlay = getEl("pdfPreviewOverlay");
        const closeBtn = getEl("pdfPreviewClose");
        const printBtn = getEl("pdfPreviewPrint");

        overlay?.addEventListener("click", closePdfModal);
        closeBtn?.addEventListener("click", closePdfModal);
        printBtn?.addEventListener("click", printPdfFromModal);

        // ESC closes
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closePdfModal();
        });

        // Intercept clicks to PDFs (or forced)
        document.addEventListener("click", (e) => {
            const a = e.target.closest("a");
            if (!a) return;

            const href = a.getAttribute("href") || "";
            const force = a.dataset.preview === "pdf";
            const shouldOpen = force || isPdfUrl(href);

            if (!shouldOpen) return;

            // Si el link abre en otra pestaña, igual lo interceptamos para el modal
            e.preventDefault();

            const title =
                a.dataset.title ||
                a.getAttribute("title") ||
                a.textContent?.trim() ||
                "Documento";

            openPdfModal(href, title);
        });

        // Expose global helper if needed elsewhere
        window.openPdfModal = openPdfModal;
        window.closePdfModal = closePdfModal;
    });
})();
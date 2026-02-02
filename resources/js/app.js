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

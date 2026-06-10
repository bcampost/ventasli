{{-- resources/views/partials/slide-image-hero.blade.php --}}
@php
  // ✅ Este es el src real que YA estás usando para renderizar el comunicado
  $src = asset('storage/' . ltrim($slide->image_path ?? '', '/'));
@endphp

<div class="hero-media hero-media--pro" data-media-src="{{ $src }}">
  {{-- Fondo full (cover) con blur: rellena todo sin franjas --}}
  <img src="{{ $src }}" class="hero-bg" alt="" aria-hidden="true" loading="lazy"/>

  {{-- Glow/vignette elegante --}}
  <div class="hero-glow" aria-hidden="true"></div>

  {{-- Flyer vertical “real” (sin recorte) --}}
  <img src="{{ $src }}" class="hero-flyer" alt="{{ $slide->title ?? 'slide' }}" loading="lazy"/>
</div>

<style>
  .hero-media--pro{
    position: relative;
    height: 520px;                /* ✅ vertical-friendly */
    border-radius: 18px;
    overflow: hidden;
    background: #0b0f14;          /* fallback */
  }

  /* Fondo que SIEMPRE rellena el escenario (no deja barras) */
  .hero-media--pro .hero-bg{
    position:absolute;
    inset: -18px;                 /* margen para blur */
    width: calc(100% + 36px);
    height: calc(100% + 36px);
    object-fit: cover;
    object-position: center;
    transform: scale(1.12);
    filter: blur(26px) saturate(1.05);
    opacity: .95;

    /* ✅ IMPORTANTE: no bloquear clicks del <a> padre */
    pointer-events: none;
  }

  /* Capa pro: baja contraste, agrega profundidad */
  .hero-media--pro .hero-glow{
    position:absolute;
    inset:0;
    background:
      radial-gradient(circle at 50% 38%,
        rgba(255,255,255,.55) 0%,
        rgba(255,255,255,.20) 28%,
        rgba(0,0,0,.08) 62%,
        rgba(0,0,0,.22) 100%
      ),
      linear-gradient(to bottom,
        rgba(0,0,0,.18) 0%,
        rgba(0,0,0,.06) 38%,
        rgba(0,0,0,.18) 100%
      );
    opacity: .75;

    /* ✅ IMPORTANTE: no bloquear clicks del <a> padre */
    pointer-events: none;
  }

  /* Flyer centrado, grande y legible */
  .hero-media--pro .hero-flyer{
    position: relative;
    z-index: 2;

    height: 100%;
    width: auto;
    max-width: min(420px, 92%);  /* ✅ evita que se haga “gigante” */
    object-fit: contain;
    display:block;
    margin: 0 auto;

    /* “tarjeta” pro */
    border-radius: 14px;
    box-shadow:
      0 28px 90px rgba(0,0,0,.30),
      0 10px 30px rgba(0,0,0,.18),
      inset 0 0 0 1px rgba(255,255,255,.16);
    background: rgba(255,255,255,.02);

    /* ✅ IMPORTANTE: no bloquear clicks del <a> padre */
    pointer-events: none;
  }

  /* Responsive */
  @media (max-width: 1100px){
    .hero-media--pro{ height: 460px; }
    .hero-media--pro .hero-flyer{ max-width: min(380px, 94%); }
  }
  @media (max-width: 640px){
    .hero-media--pro{ height: 340px; }
    .hero-media--pro .hero-flyer{ max-width: min(300px, 96%); border-radius: 12px; }
  }
</style>
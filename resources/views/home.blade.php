@extends('layouts.app')

@section('content')

<div class="hero-wrap">
  <div class="hero-top">
    <div class="hero-brand">
      <div class="hero-mark">LI</div>

      <div class="hero-title">
        <div class="hero-kicker">Portal</div>

        <div class="hero-title-row">
          <div class="hero-h1">Comunicados</div>

          {{-- ✅ Botón solo para admin --}}
          @role('admin')
            <a href="{{ route('admin.slides.index') }}" class="hero-admin-btn">
              <svg xmlns="http://www.w3.org/2000/svg" class="hero-admin-ico" viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 3a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h5v1H6a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2h-3v-1h5a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H4zm12 2v8H4V5h12z"/>
              </svg>
              Editar sliders
            </a>
          @endrole
        </div>
      </div>
    </div>

    <div class="hero-tools">
      <div class="hero-pill">
        <span class="hero-dot"></span>
        <span><b>Entrega proyectos:</b> 4 días hábiles</span>
      </div>

      <div class="hero-search">
        <input type="text" placeholder="Buscar comunicado…" />
      </div>
    </div>
  </div>

  <div class="hero-stage">
    <div class="hero-card">
      <div class="swiper hero3d-swiper">
        <div class="swiper-wrapper">
          @forelse($slides as $slide)
            <div class="swiper-slide hero3d-slide">
              <div class="hero3d-card">
                @if($slide->link)
                  <a href="{{ $slide->link }}" target="_blank" rel="noopener">
                    @include('partials.slide-image-hero', ['slide' => $slide])
                  </a>
                @else
                  @include('partials.slide-image-hero', ['slide' => $slide])
                @endif
              </div>
            </div>
          @empty
            <div style="padding:40px;color:#666;">No hay comunicados cargados. (Admin → Slides)</div>
          @endforelse
        </div>

        <div class="hero-dots">
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ✅ NUEVO: Dashboard simulado debajo del slider --}}
@include('partials.rankings-dashboard')

<style>
  :root{
    --text: #111827;
    --muted: rgba(17,24,39,.55);
    --line: rgba(17,24,39,.10);
    --card: #ffffff;
  }

  .hero-wrap{
    background: transparent;
    border: none;
    border-radius: 0;
    padding: 18px 0;
  }

  .hero-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 16px;
    padding: 6px 6px 14px;
    max-width: 1180px;
    margin: 0 auto;
  }

  .hero-brand{ display:flex; align-items:center; gap: 12px; }

  .hero-mark{
    width: 44px; height: 44px;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 12px;
    display:flex; align-items:center; justify-content:center;
    color: rgba(17,24,39,.55);
    font-weight: 800;
    font-size: 12px;
    letter-spacing: .08em;
  }

  .hero-title .hero-kicker{ font-size: 12px; color: var(--muted); margin-bottom: 2px; }

  .hero-title-row{
    display:flex;
    align-items:center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .hero-title .hero-h1{ font-size: 30px; line-height: 1.1; color: var(--text); font-weight: 650; }

  /* ✅ Botón admin (solo en Home) */
  .hero-admin-btn{
    display:inline-flex;
    align-items:center;
    gap: 8px;
    height: 34px;
    padding: 0 12px;
    border-radius: 999px;
    border: 1px solid var(--line);
    background: #fff;
    color: rgba(17,24,39,.85);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
    box-shadow: 0 10px 18px rgba(0,0,0,.08);
  }
  .hero-admin-btn:hover{
    background: rgba(17,24,39,.04);
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(0,0,0,.10);
  }
  .hero-admin-ico{ width: 16px; height: 16px; }

  .hero-tools{ display:flex; align-items:center; gap: 12px; }

  .hero-pill{
    display:flex; align-items:center; gap: 8px;
    background: rgba(17,24,39,.92);
    color:#fff;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    box-shadow: 0 10px 18px rgba(0,0,0,.18);
    white-space: nowrap;
  }
  .hero-dot{ width: 8px; height: 8px; border-radius: 999px; background:#fff; opacity:.95; display:inline-block; }

  .hero-search input{
    width: 260px; height: 36px;
    border-radius: 999px;
    border: 1px solid var(--line);
    background: #fff;
    padding: 0 14px;
    outline: none;
    font-size: 13px;
  }

  @media (max-width: 1100px){
    .hero-search input{ width: 180px; }
    .hero-title .hero-h1{ font-size: 26px; }
  }

  .hero-stage{ display:flex; justify-content:center; padding: 8px 0 2px; }

  /* 70vw ~15% por lado */
  .hero-card{
    width: 70vw;
    max-width: 70vw;
    margin: 0 auto;
    background: transparent;
    border-radius: 20px;
    position: relative;
    overflow: visible; /* ✅ no recorte */
  }
  @media (max-width: 1100px){
    .hero-card{ width: 92vw; max-width: 92vw; }
  }
  @media (max-width: 640px){
    .hero-card{ width: 96vw; max-width: 96vw; }
  }

  /* ✅ “teatro” */
  .hero3d-swiper{
    overflow: visible;
    padding: 24px 0 48px;
    perspective: 1800px; /* más profundo */
  }

  .hero3d-slide{ width: 900px; }
  @media (max-width: 1100px){ .hero3d-slide{ width: 92vw; } }

  /* Card base */
  .hero3d-card{
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(17,24,39,.10);
    background: #fff;
    transform: translateZ(0);
    transition: transform .35s ease, filter .35s ease, opacity .35s ease, box-shadow .35s ease;
  }

  /* ✅ Centro: flotando (sombra pro + glow suave) */
  .swiper-slide-active .hero3d-card{
    opacity: 1;
    filter: none;
    box-shadow:
      0 26px 80px rgba(0,0,0,.22),
      0 10px 26px rgba(0,0,0,.14);
  }

  /* ✅ Laterales: más atrás (blur/opacidad/escala) */
  .swiper-slide-prev .hero3d-card,
  .swiper-slide-next .hero3d-card{
    opacity: .55;
    filter: blur(1.6px) saturate(.92);
    box-shadow: 0 18px 44px rgba(0,0,0,.10);
  }

  /* Empuja hacia adentro para que NO toquen bordes */
  .swiper-slide-prev .hero3d-card{ transform: translateX(44px) scale(.94); }
  .swiper-slide-next .hero3d-card{ transform: translateX(-44px) scale(.94); }

  /* Dots */
  .hero-dots{
    position:absolute;
    left:0; right:0; bottom: 10px;
    display:flex;
    justify-content:center;
    pointer-events:none;
  }
  .swiper-pagination{ pointer-events:auto; }
  .swiper-pagination-bullet{
    width: 7px; height: 7px;
    opacity: .28;
    background: #111827;
    margin: 0 5px !important;
  }
  .swiper-pagination-bullet-active{
    opacity: 1;
    transform: scale(1.15);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    window.initHomeSlider?.();
  });
</script>

@endsection
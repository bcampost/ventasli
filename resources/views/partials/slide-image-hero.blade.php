@php
  $src = asset('storage/'.$slide->image_path);
@endphp

<div class="hero-media">
  {{-- Fondo relleno (cover) --}}
  <img src="{{ $src }}" class="hero-fill" alt="" aria-hidden="true" loading="lazy"/>

  {{-- Overlay limpio --}}
  <div class="hero-vignette" aria-hidden="true"></div>

  {{-- Imagen principal (contain) --}}
  <img src="{{ $src }}" class="hero-img" alt="{{ $slide->title ?? 'slide' }}" loading="lazy"/>
</div>

<style>
  .hero-media{
    position: relative;
    height: 440px;
    overflow: hidden;
    background: #0b0f14;
  }

  /* ✅ Por defecto: NADA de blur fuerte para que prev/next se vean nítidos */
  .hero-fill{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit: cover;
    object-position: center;
    transform: scale(1.03);
    filter: blur(0px);
    opacity: 0; /* apagado por defecto */
  }

  .hero-vignette{
    position:absolute;
    inset:0;
    opacity: 0; /* apagado por defecto */
    background: radial-gradient(
      circle at 50% 45%,
      rgba(255,255,255,.10) 0%,
      rgba(255,255,255,.06) 35%,
      rgba(0,0,0,.10) 75%,
      rgba(0,0,0,.18) 100%
    );
  }

  /* ✅ SOLO el slide activo: prende el fondo blur y la viñeta */
  .swiper-slide-active .hero-fill{
    opacity: .46;
    filter: blur(18px);
    transform: scale(1.06);
  }
  .swiper-slide-active .hero-vignette{
    opacity: 1;
  }

  /* Imagen principal */
  .hero-img{
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display:block;
    padding: 0;
    margin: 0;
  }

  @media (max-width: 1100px){
    .hero-media{ height: 380px; }
  }
  @media (max-width: 640px){
    .hero-media{ height: 280px; }
  }
</style>
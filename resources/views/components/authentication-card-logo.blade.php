<a href="{{ url('/') }}" class="authLogo" aria-label="Línea Italia">
  <span class="authLogo__box">
    <img
      src="{{ asset('assets/brand/linea-italia-logo.webp') }}"
      alt="Línea Italia"
      class="authLogo__img"
      loading="lazy"
    >
  </span>
</a>

<style>
  .authLogo{
    display:flex;
    justify-content:center;
    align-items:center;
    width:100%;
    text-decoration:none;
    margin-bottom: 10px;
  }

  /* Este es el “cuadrito” de arriba */
  .authLogo__box{
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(17,24,39,.10);
    box-shadow: 0 14px 26px rgba(0,0,0,.10);
    display:flex;
    align-items:center;
    justify-content:center;
  }

  /* Logo dentro del cuadrito */
  .authLogo__img{
    width: 40px;
    height: 40px;
    object-fit: contain;
    display:block;
    filter: drop-shadow(0 8px 14px rgba(0,0,0,.10));
  }

  @media (max-width: 640px){
    .authLogo__box{ width: 52px; height: 52px; border-radius: 13px; }
    .authLogo__img{ width: 38px; height: 38px; }
  }
</style>
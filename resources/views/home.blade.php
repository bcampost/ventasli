@extends('layouts.app')

@section('content')

  @php
    use Illuminate\Support\Str;

    $resolveSlideImageUrl = function ($slide) {
      if (!empty($slide->image_url))
        return $slide->image_url;

      if (!empty($slide->image)) {
        $img = ltrim($slide->image, '/');
        if (Str::startsWith($img, ['http://', 'https://']))
          return $img;
        if (Str::startsWith($img, 'slides/'))
          return asset('storage/' . $img);
        return asset('storage/slides/' . $img);
      }

      if (!empty($slide->path))
        return asset(ltrim($slide->path, '/'));

      // ✅ FIX: image_path vive en storage (igual que en slide-image-hero.blade.php)
      if (!empty($slide->image_path)) {
        return asset('storage/' . ltrim($slide->image_path, '/'));
      }

      return null;
    };
  @endphp

  <div class="hero-wrap">
    <div class="hero-top">


      <div class="hero-brand">
        <div class="hero-title">
          <div class="hero-title-row">
            <a href="{{ route('comunicados.index') }}" class="home-comunicados-link">
              Comunicados
            </a>
            @role('admin')
            <a href="{{ route('admin.slides.index') }}" class="hero-admin-btn">
              <svg xmlns="http://www.w3.org/2000/svg" class="hero-admin-ico" viewBox="0 0 20 20" fill="currentColor">
                <path
                  d="M4 3a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h5v1H6a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2h-3v-1h5a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H4zm12 2v8H4V5h12z" />
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


      </div>
    </div>


    <div class="hero-stage">
      <div class="hero-card">
        <div class="swiper hero3d-swiper">
          <div class="swiper-wrapper">
            @forelse($slides as $slide)
              @php
                $imgUrl = $resolveSlideImageUrl($slide);
                $title = $slide->title ?? $slide->label ?? 'Comunicado';
              @endphp

              <div class="swiper-slide hero3d-slide">
                <div class="hero3d-card">

                  <a href="{{ $imgUrl ?: '#' }}" data-preview="media" data-src="{{ $imgUrl ?: '' }}"
                    data-title="{{ $title }}" class="hero-slide-preview swiper-no-swiping" title="Ver más grande">
                    @include('partials.slide-image-hero', ['slide' => $slide])
                  </a>

                  @if(!empty($slide->link))
                    <a class="hero-slide-linkout swiper-no-swiping" href="{{ $slide->link }}" target="_blank" rel="noopener"
                      title="Abrir enlace">
                      Abrir enlace ↗
                    </a>
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

  {{-- ✅ Ranking sube automáticamente al ocultar comunicados --}}
  <div class="home-ranking-space">
    @include('partials.rankings-dashboard')
  </div>

  <style>
    :root {
      --text: #111827;
      --muted: rgba(17, 24, 39, .55);
      --line: rgba(17, 24, 39, .10);
      --card: #ffffff;
    }

    .hero-wrap {
      background: transparent;
      border: none;
      border-radius: 0;
      padding: 18px 0 28px;
      min-height: calc(100vh - 82px);
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }

    .hero-top {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 16px;
      padding: 6px 6px 6px;
      max-width: 1180px;
      margin: 0 auto;
    }

    @media(max-width: 600px) {
      .hero-top {
        flex-direction: column;
      }
    }

    .hero-title-row {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .hero-title .hero-h1 {
      font-size: 30px;
      line-height: 1.1;
      color: var(--text);
      font-weight: 650;
    }

    .hero-admin-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      height: 34px;
      padding: 0 12px;
      border-radius: 999px;
      border: 1px solid var(--line);
      background: #fff;
      color: rgba(17, 24, 39, .85);
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
      box-shadow: 0 10px 18px rgba(0, 0, 0, .08);
    }

    .hero-admin-btn:hover {
      background: rgba(17, 24, 39, .04);
      transform: translateY(-1px);
      box-shadow: 0 14px 24px rgba(0, 0, 0, .10);
    }

    .hero-admin-ico {
      width: 16px;
      height: 16px;
    }

    .hero-tools {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .hero-pill {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(17, 24, 39, .92);
      color: #fff;
      border-radius: 999px;
      padding: 8px 12px;
      font-size: 12px;
      box-shadow: 0 10px 18px rgba(0, 0, 0, .18);
      white-space: nowrap;
    }

    .hero-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: #fff;
      opacity: .95;
      display: inline-block;
    }

    .hero-search-form {
      margin: 0;
    }

    .hero-search-input {
      width: 260px;
      height: 36px;
      border-radius: 999px;
      border: 1px solid var(--line);
      background: #fff;
      padding: 0 14px;
      outline: none;
      font-size: 13px;
    }

    @media (max-width: 1100px) {
      .hero-search input {
        width: 180px;
      }

      .hero-title .hero-h1 {
        font-size: 26px;
      }
    }

    .hero-stage {
      display: flex;
      justify-content: center;
      align-items: center;
      flex: 1;
      padding: 8px 0 8px;
    }

    .hero-card {
      width: 88vw;
      max-width: 88vw;
      margin: 0 auto;
      background: transparent;
      border-radius: 20px;
      position: relative;
      overflow: visible;
    }

    @media (max-width: 1100px) {
      .hero-card {
        width: 96vw;
        max-width: 96vw;
      }
    }

    @media (max-width: 640px) {
      .hero-card {
        width: 98vw;
        max-width: 98vw;
      }
    }

    .hero3d-slide {
      width: 1180px;
      display: flex;
      justify-content: center;
    }

    @media (max-width: 1100px) {
      .hero3d-slide {
        width: 96vw;
      }
    }

    .hero3d-swiper {
      overflow: visible;
      padding: 22px 0 64px;
      perspective: 1800px;
    }


    .hero3d-card {
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid rgba(17, 24, 39, .10);
      background: #fff;
      transform: translateZ(0);
      transition: transform .35s ease, filter .35s ease, opacity .35s ease, box-shadow .35s ease;
      position: relative;
    }

    .swiper-slide-active .hero3d-card {
      opacity: 1;
      filter: none;
      box-shadow: 0 26px 80px rgba(0, 0, 0, .22), 0 10px 26px rgba(0, 0, 0, .14);
    }

    .swiper-slide-prev .hero3d-card,
    .swiper-slide-next .hero3d-card {
      opacity: .55;
      filter: blur(1.6px) saturate(.92);
      box-shadow: 0 18px 44px rgba(0, 0, 0, .10);
    }

    .hero-stage {
      display: flex;
      justify-content: center;
      align-items: center;
      flex: 1;
      padding: 8px 0 18px;
    }

    .hero-card {
      width: 78vw;
      max-width: 78vw;
      margin: 0 auto;
      background: transparent;
      border-radius: 20px;
      position: relative;
      overflow: visible;
    }

    .swiper-slide-prev .hero3d-card {
      transform: translateX(44px) scale(.94);
    }

    .swiper-slide-next .hero3d-card {
      transform: translateX(-44px) scale(.94);
    }

    .hero-dots {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 10px;
      display: flex;
      justify-content: center;
      pointer-events: none;
    }

    .swiper-pagination {
      pointer-events: auto;
    }

    .hero3d-swiper {
      overflow: visible;
      padding: 22px 0 64px;
      perspective: 1800px;
    }

    .hero3d-slide {
      width: 980px;
      display: flex;
      justify-content: center;
    }

    .hero-slide-preview {
      display: block;
      text-decoration: none;
      color: inherit;
      cursor: default;
    }

    .swiper-slide-active .hero-slide-preview {
      cursor: zoom-in;
    }

    .hero-slide-linkout {
      position: absolute;
      right: 12px;
      bottom: 12px;
      background: rgba(255, 255, 255, .92);
      border: 1px solid rgba(17, 24, 39, .14);
      border-radius: 999px;
      padding: 8px 10px;
      font-size: 12px;
      font-weight: 700;
      color: rgba(17, 24, 39, .85);
      text-decoration: none;
      box-shadow: 0 10px 18px rgba(0, 0, 0, .10);
      opacity: .35;
      pointer-events: none;
    }

    .swiper-slide-active .hero-slide-linkout {
      opacity: 1;
      pointer-events: auto;
    }

    .home-comunicados-link {
      color: #111827;
      text-decoration: none;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .home-comunicados-link:hover {
      text-decoration: underline;
    }

    .home-ranking-space {
      margin: 0;
      padding: 0 0 32px;
      line-height: 0;
    }

    .home-ranking-space>* {
      line-height: normal;
    }

    .home-ranking-space iframe {
      display: block;
      margin: 0;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      window.initHomeSlider?.();

      document.addEventListener('click', function (e) {
        const a = e.target.closest('a.hero-slide-preview');
        if (!a) return;

        const slide = a.closest('.swiper-slide');
        const isActive = slide && slide.classList.contains('swiper-slide-active');

        if (!isActive) {
          e.preventDefault();
          e.stopImmediatePropagation();
          return;
        }

        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        let src = (a.getAttribute('data-src') || '').trim();
        const title = a.getAttribute('data-title') || 'Comunicado';

        if (!src) {
          const href = (a.getAttribute('href') || '').trim();
          if (href && href !== '#') {
            src = href;
          } else {
            const img = a.querySelector('img');
            src = img?.currentSrc || img?.src || '';
          }
        }

        if (src && typeof window.openMediaPreview === 'function') {
          window.openMediaPreview(src, title);
        }

        return false;
      }, true);
    });
  </script>

@endsection
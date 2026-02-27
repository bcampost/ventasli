{{-- resources/views/menu/detail.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  // Imágenes del hero (array)
  $images = is_array($hero->images ?? null)
    ? ($hero->images ?? [])
    : (json_decode((string)($hero->images ?? '[]'), true) ?: []);

  // token base64url para la key (sin "/")
  $encode = function($raw){
    $b64 = base64_encode($raw);
    return rtrim(strtr($b64, '+/', '-_'), '=');
  };
  $token = $encode($fullPath);

  $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin');

  // Primera imagen como "cover" base
  $firstImg = !empty($images[0]) ? asset('storage/'.ltrim($images[0], '/')) : null;

  // Texto opcional (si no hay, no se pinta)
  $heroTitle = trim((string)($hero->title ?? ''));
  $heroDesc  = trim((string)($hero->description ?? ''));
@endphp

<div class="pd">

  {{-- ✅ HERO FULL WIDTH (como en tu screenshot 1) --}}
  <section class="pd-hero" aria-label="Hero del producto">
    <div class="pd-hero-shell">

      {{-- Slider --}}
      <div class="pd-slider" data-pd-slider>
        <div class="pd-track">
          @forelse($images as $img)
            @php $url = asset('storage/'.ltrim($img,'/')); @endphp
            <div class="pd-slide">
              <img src="{{ $url }}" alt="">
            </div>
          @empty
            <div class="pd-slide pd-empty">
              <div class="pd-empty-inner">
                <div class="pd-empty-title">Sin imágenes</div>
                <div class="pd-empty-sub">Cárgalas desde el botón de edición.</div>
              </div>
            </div>
          @endforelse
        </div>

        {{-- Flechas (solo si hay más de 1) --}}
        @if(count($images) > 1)
          <button type="button" class="pd-nav pd-prev" data-pd-prev aria-label="Anterior">‹</button>
          <button type="button" class="pd-nav pd-next" data-pd-next aria-label="Siguiente">›</button>
        @endif

        {{-- Dots --}}
        @if(count($images) > 1)
          <div class="pd-dots" data-pd-dots aria-hidden="true"></div>
        @endif
      </div>

      {{-- Overlay contenido (centrado, como screenshot 1) --}}
      <div class="pd-overlay">
        <div class="pd-overlay-inner">
          @if($heroTitle !== '' || $heroDesc !== '')
            <div class="pd-brand">
              @if($heroTitle !== '')
                <div class="pd-title">{{ $heroTitle }}</div>
              @endif
              @if($heroDesc !== '')
                <div class="pd-subtitle">{!! nl2br(e($heroDesc)) !!}</div>
              @endif
            </div>
          @endif
        </div>
      </div>

      {{-- Botón editar (respetando icono) --}}
      @if($isAdmin && \Illuminate\Support\Facades\Route::has('admin.menu-hero.edit'))
        <a class="pd-edit" href="{{ route('admin.menu-hero.edit', $token) }}" title="Editar HERO / Slider" aria-label="Editar">
          ✎
        </a>
      @endif

    </div>
  </section>

  {{-- ✅ CONTENIDO NORMAL (productos debajo, como en “Escritorios”) --}}
  <section class="pd-body">
    <div class="pd-wrap">

      <div class="pd-actions">
        @if($isAdmin)
          <button type="button" class="pd-btn" onclick="handleCreateProduct()">
            + Agregar producto
          </button>
        @endif
      </div>

          @if(isset($products) && $products->count())
            <div class="pd-grid">
              @foreach($products as $prod)
                <a class="pd-tile"
                  href="{{ route('menu.product.show', $prod) }}"
                  style="text-decoration:none; color:inherit;">
                  <div class="pd-img">
                    @if($prod->image_path)
                      <img src="{{ asset('storage/'.ltrim($prod->image_path,'/')) }}" alt="{{ $prod->title }}">
                    @else
                      <img
                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='700'%3E%3Crect width='100%25' height='100%25' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-size='34' font-family='Arial'%3ESin%20imagen%3C/text%3E%3C/svg%3E"
                        alt="Sin imagen">
                    @endif
                  </div>
                  <div class="pd-tt">{{ $prod->title }}</div>
                </a>
              @endforeach
            </div>
          @else
        <div class="pd-empty-products">No hay productos en este nivel.</div>
      @endif

    </div>
  </section>

</div>

<style>
  :root{
    --pd-ink:#0b1220;
    --pd-line:rgba(15,23,42,.12);
    --pd-shadow: 0 18px 48px rgba(2,6,23,.12);
    --pd-r: 22px;
    --pd-r2: 18px;
    --pd-blue:#2563eb;
  }

  .pd{ width:100%; }

  /* =========================
     HERO FULL WIDTH
     ========================= */
  .pd-hero{
    width:100%;
    padding: 0;                 /* full bleed */
    margin: 0;
  }

  .pd-hero-shell{
    position: relative;
    width: min(1480px, calc(100% - 44px));  /* como sitio (con margen) */
    margin: 22px auto 0;
    border-radius: 26px;
    overflow: hidden;
    box-shadow: var(--pd-shadow);
    background: #0b1220;
  }

  /* Slider (banner tipo web) */
  .pd-slider{
    position: relative;
    width: 100%;
    height: clamp(320px, 42vw, 520px); /* ✅ look tipo screenshot 1 */
    overflow: hidden;
  }

  .pd-track{
    display:flex;
    height: 100%;
    transition: transform .35s ease;
    will-change: transform;
  }

  .pd-slide{
    min-width: 100%;
    height: 100%;
    background: #0b1220;
    position: relative;
  }

  .pd-slide img{
    width: 100%;
    height: 100%;
    object-fit: cover;         /* ✅ como web: llena el banner */
    object-position: center;
    display:block;
  }

  /* Empty */
  .pd-empty{
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(180deg, rgba(15,23,42,.96), rgba(15,23,42,.86));
  }
  .pd-empty-inner{
    text-align:center;
    color:#fff;
    opacity:.9;
    padding: 22px;
  }
  .pd-empty-title{ font-weight:950; font-size: 22px; }
  .pd-empty-sub{ margin-top:6px; opacity:.8; font-weight:650; }

  /* Overlay text (centrado) */
  .pd-overlay{
    pointer-events: none;
    position:absolute;
    inset:0;
    background:
      radial-gradient(900px 420px at 50% 45%, rgba(0,0,0,.32), rgba(0,0,0,0) 65%),
      linear-gradient(180deg, rgba(0,0,0,.18), rgba(0,0,0,.10));
  }
  .pd-overlay-inner{
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 22px;
  }
  .pd-brand{
    text-align:center;
    max-width: 820px;
    color:#fff;
    text-shadow: 0 20px 60px rgba(0,0,0,.40);
  }
  .pd-title{
    font-weight: 950;
    letter-spacing: .06em;
    text-transform: uppercase;
    font-size: clamp(34px, 6vw, 86px);
    line-height: 1.02;
  }
  .pd-subtitle{
    margin-top: 10px;
    font-size: clamp(14px, 2.1vw, 22px);
    font-weight: 650;
    opacity: .92;
    line-height: 1.2;
  }

  /* Flechas */
  .pd-nav{
    position:absolute;
    top:50%;
    transform: translateY(-50%);
    width: 52px;
    height: 52px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.22);
    background: rgba(255,255,255,.92);
    color: rgba(2,6,23,.92);
    font-size: 30px;
    font-weight: 900;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    z-index: 30;
    box-shadow: 0 14px 34px rgba(0,0,0,.18);
    transition: transform .12s ease, background .12s ease;
  }
  .pd-nav:hover{ background:#fff; transform: translateY(-50%) scale(1.02); }
  .pd-prev{ left: 14px; }
  .pd-next{ right: 14px; }

  /* Dots */
  .pd-dots{
    position:absolute;
    left:0; right:0;
    bottom: 14px;
    display:flex;
    justify-content:center;
    gap: 8px;
    z-index: 35;
  }
  .pd-dot{
    width: 9px; height: 9px;
    border-radius: 999px;
    background: rgba(255,255,255,.55);
    border: 1px solid rgba(0,0,0,.14);
    box-shadow: 0 10px 24px rgba(0,0,0,.20);
  }
  .pd-dot.is-active{
    background: rgba(255,255,255,.95);
    transform: scale(1.12);
  }

  /* Edit icon top-right (como tu estilo) */
  .pd-edit{
    position:absolute;
    top: 14px;
    right: 14px;
    z-index: 40;
    width: 42px;
    height: 42px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: 950;
    text-decoration:none;
    color: rgba(2,6,23,.92);
    box-shadow: 0 14px 34px rgba(0,0,0,.18);
  }
  .pd-edit:hover{ background:#fff; }

  /* =========================
     BODY / PRODUCTS
     ========================= */
  .pd-body{ padding: 18px 0 34px; }
  .pd-wrap{ width: min(1480px, calc(100% - 44px)); margin: 0 auto; }

  .pd-actions{
    display:flex;
    justify-content:flex-end;
    margin: 10px 0 14px;
  }
  .pd-btn{
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    padding: 10px 14px;
    border-radius: 999px;
    font-weight: 950;
    cursor:pointer;
    box-shadow: 0 12px 26px rgba(2,6,23,.08);
  }
  .pd-btn:hover{ background:#fff; }

  .pd-grid{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
  }
  @media(max-width: 980px){
    .pd-grid{ grid-template-columns: repeat(2, 1fr); }
  }
  @media(max-width: 640px){
    .pd-grid{ grid-template-columns: 1fr; }
    .pd-hero-shell{ width: calc(100% - 26px); }
    .pd-wrap{ width: calc(100% - 26px); }
  }

  .pd-tile{
    background:#fff;
    border-radius: 14px;
    border: 1px solid rgba(15,23,42,.10);
    box-shadow: 0 12px 26px rgba(2,6,23,.06);
    overflow:hidden;
  }
  .pd-img{
    aspect-ratio: 16/9;
    background:#f3f4f6;
  }
  .pd-img img{
    width:100%;
    height:100%;
    object-fit: cover;
    display:block;
  }
  .pd-tt{
    padding: 12px 4px 0;
    font-weight: 700;
    opacity: .55;
  }

  .pd-empty-products{
    padding: 18px 2px;
    color: rgba(15,23,42,.65);
    font-weight: 700;
  }
</style>

<script>
(function(){
  const root = document.querySelector('[data-pd-slider]');
  if(!root) return;

  const track = root.querySelector('.pd-track');
  const slides = Array.from(root.querySelectorAll('.pd-slide'));
  const prev = root.querySelector('[data-pd-prev]');
  const next = root.querySelector('[data-pd-next]');
  const dotsWrap = root.querySelector('[data-pd-dots]');

  if(slides.length <= 1) return;

  let i = 0;

  // dots
  const dots = slides.map((_, idx) => {
    const d = document.createElement('span');
    d.className = 'pd-dot' + (idx === 0 ? ' is-active' : '');
    dotsWrap?.appendChild(d);
    d.addEventListener('click', () => { i = idx; render(); });
    return d;
  });

  function render(){
    track.style.transform = `translateX(${i * -100}%)`;
    dots.forEach((d, idx) => d.classList.toggle('is-active', idx === i));
  }

  prev?.addEventListener('click', () => { i = Math.max(0, i - 1); render(); });
  next?.addEventListener('click', () => { i = Math.min(slides.length - 1, i + 1); render(); });

  // swipe (móvil)
  let startX = null;
  root.addEventListener('touchstart', (e) => {
    startX = e.touches?.[0]?.clientX ?? null;
  }, {passive:true});
  root.addEventListener('touchend', (e) => {
    if(startX === null) return;
    const endX = e.changedTouches?.[0]?.clientX ?? startX;
    const dx = endX - startX;
    if(Math.abs(dx) > 45){
      if(dx < 0) i = Math.min(slides.length - 1, i + 1);
      else i = Math.max(0, i - 1);
      render();
    }
    startX = null;
  }, {passive:true});

})();
</script>

{{-- ✅ Si ya tienes tu modal de “Agregar producto” en show.blade.php,
    puedes reusar la misma función. Si aquí no existe, no truena: --}}
<script>
  function openCreateProduct(){
    // Si en tu layout ya existe el modal global, ábrelo
    const bd = document.getElementById('createProductBackdrop');
    const md = document.getElementById('createProductModal');
    if(bd && md){
      bd.classList.remove('hidden');
      md.classList.remove('hidden');
      md.classList.add('modal-open');
      document.body.classList.add('no-scroll');
      document.body.classList.add('modal-open');
      return;
    }
    // fallback: manda al admin listado (si quieres)
    // window.location.href = "{{ route('admin.menu-products.index') }}";
  }
</script>

<script>
function handleCreateProduct(){

  // 1) Si existe el modal global (como en show.blade.php), lo abre
  const backdrop = document.getElementById('createProductBackdrop');
  const modal    = document.getElementById('createProductModal');

  if(backdrop && modal){
    backdrop.classList.remove('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('modal-open');

    document.body.classList.add('no-scroll');
    document.body.classList.add('modal-open');
    return;
  }

  // 2) Si NO existe modal → redirige a la ruta que SÍ tienes definida
  const indexUrl = @json(route('admin.menu-products.index'));
  const menuKey  = @json($fullPath ?? '');

  const url = menuKey
    ? (indexUrl + (indexUrl.includes('?') ? '&' : '?') + 'menu_key=' + encodeURIComponent(menuKey))
    : indexUrl;

  window.location.href = url;
}
</script>

@endsection
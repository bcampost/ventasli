@extends('layouts.app')

@section('content')

@php
  use Illuminate\Support\Str;

  $imagesRaw = $hero->images ?? [];

  if (is_string($imagesRaw)) {
      $decoded = json_decode($imagesRaw, true);
      $images = is_array($decoded) ? $decoded : [];
  } elseif (is_array($imagesRaw)) {
      $images = $imagesRaw;
  } else {
      $images = [];
  }

  $images = array_values(array_filter($images));

  $encode = fn($raw) => rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
  $token = $encode($fullPath);

  $imgUrl = fn($p) => asset('storage/' . ltrim($p,'/'));
@endphp

<div class="product-detail-wrap">

  {{-- HERO SOLO IMAGEN --}}
  <div class="product-hero">

      {{-- BOTONES SUPERIORES --}}
      <div class="hero-actions">

          @role('admin')
          <a
            href="{{ route('admin.menu-hero.edit', ['token' => $token, 'redirect_to' => url()->current()]) }}"
            class="hero-edit-btn"
            title="Editar hero">
            ✎
          </a>
          @endrole

          @role('admin')
          <a
            href="{{ route('admin.menu-products.index') }}?menu_key={{ urlencode($fullPath) }}"
            class="hero-add-btn"
            title="Agregar producto">
            + Agregar producto
          </a>
          @endrole

      </div>

      {{-- SLIDER --}}
      <div class="hero-slider" data-slider>
          <div class="hero-track">
              @forelse($images as $img)
                  <div class="hero-slide">
                      <img src="{{ $imgUrl($img) }}" alt="">
                  </div>
              @empty
                  <div class="hero-empty">
                      Sin imágenes
                  </div>
              @endforelse
          </div>

          @if(count($images) > 1)
              <button class="nav prev" type="button" data-prev>‹</button>
              <button class="nav next" type="button" data-next>›</button>
          @endif
      </div>

  </div>

  {{-- PRODUCTOS --}}
  <div class="product-grid">
      @forelse($products as $prod)
          <div class="tile">
              <div class="tile-img">
                  @if($prod->image_path)
                      <img src="{{ asset('storage/'.ltrim($prod->image_path,'/')) }}">
                  @endif
              </div>
              <div class="tile-title">
                  {{ $prod->title }}
              </div>
          </div>
      @empty
          <div class="product-empty">
              No hay productos en este nivel.
          </div>
      @endforelse
  </div>

</div>

<style>
.product-detail-wrap{
  max-width:1180px;
  margin:20px auto 30px;
  padding:0 16px;
}

.product-hero{
  position:relative;
  border-radius:20px;
  overflow:hidden;
  background:#0b1220;
  box-shadow:0 16px 30px rgba(0,0,0,.15);
}

.hero-actions{
  position:absolute;
  top:14px;
  right:14px;
  display:flex;
  gap:10px;
  z-index:5;
}

.hero-edit-btn,
.hero-add-btn{
  height:38px;
  padding:0 14px;
  border-radius:999px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:800;
  text-decoration:none;
  background:#fff;
  border:1px solid rgba(0,0,0,.15);
  box-shadow:0 10px 18px rgba(0,0,0,.2);
  transition:.15s ease;
}

.hero-edit-btn{
  width:38px;
  padding:0;
}

.hero-edit-btn:hover,
.hero-add-btn:hover{
  transform:translateY(-1px);
}

.hero-slider{
  overflow:hidden;
}

.hero-track{
  display:flex;
  transition:transform .3s ease;
}

.hero-slide,
.hero-empty{
  min-width:100%;
  height:clamp(320px,55vh,520px);
  display:flex;
  align-items:center;
  justify-content:center;
}

.hero-slide img{
  width:100%;
  height:100%;
  object-fit:contain;
}

.hero-empty{
  color:#fff;
  font-weight:700;
}

.nav{
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  width:44px;
  height:44px;
  border-radius:999px;
  background:#fff;
  border:1px solid rgba(0,0,0,.2);
  font-size:26px;
  cursor:pointer;
}

.prev{ left:14px; }
.next{ right:14px; }

.product-grid{
  margin-top:22px;
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:14px;
}

@media(max-width:1000px){
  .product-grid{ grid-template-columns:repeat(3,1fr); }
}
@media(max-width:700px){
  .product-grid{ grid-template-columns:repeat(2,1fr); }
}
@media(max-width:500px){
  .product-grid{ grid-template-columns:1fr; }
}

.tile{
  border-radius:16px;
  overflow:hidden;
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:0 8px 16px rgba(0,0,0,.05);
}

.tile-img{
  height:170px;
  background:#f3f4f6;
  overflow:hidden;
}

.tile-img img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.tile-title{
  padding:12px;
  font-weight:900;
  font-size:13px;
}
</style>

<script>
(function(){
  const root = document.querySelector('[data-slider]');
  if(!root) return;

  const track = root.querySelector('.hero-track');
  const slides = root.querySelectorAll('.hero-slide');
  const prev = root.querySelector('[data-prev]');
  const next = root.querySelector('[data-next]');

  if(slides.length <= 1) return;

  let i=0;

  function render(){
    track.style.transform = `translateX(${i * -100}%)`;
  }

  prev?.addEventListener('click',()=>{ i=Math.max(0,i-1); render(); });
  next?.addEventListener('click',()=>{ i=Math.min(slides.length-1,i+1); render(); });

})();
</script>

@endsection
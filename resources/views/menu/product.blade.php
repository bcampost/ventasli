@extends('layouts.app')

@section('content')
@php
  $isAdmin = auth()->check() && method_exists(auth()->user(),'hasRole') && auth()->user()->hasRole('admin');

  $imgs = is_array($gallery) ? $gallery : [];
  $imgs = array_values(array_filter($imgs));

  $imgUrl = function($path){
    if(!$path) return null;
    return asset('storage/'.ltrim($path,'/'));
  };

  $m = $specs['measures'] ?? ['largo'=>'','ancho'=>'','alto'=>''];
  $colorsAcero = $specs['colors_acero'] ?? [];
  $colorsMel  = $specs['colors_melamina'] ?? [];
@endphp

<style>
  :root{
    --ink:#0b1220;
    --line:rgba(15,23,42,.12);
    --shadow: 0 22px 60px rgba(2,6,23,.12);
    --rXL: 26px;
  }

  .wrap{ max-width: 1180px; margin: 0 auto; padding: 22px 18px 36px; }

  .card{
    background:#fff;
    border:1px solid var(--line);
    border-radius: var(--rXL);
    box-shadow: var(--shadow);
    overflow:hidden;
  }

  .topbar{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; flex-wrap:wrap;
    margin-bottom: 14px;
  }

  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:.55rem; font-weight:900; border-radius: 16px;
    padding:.72rem .92rem; font-size:.88rem;
    border:1px solid rgba(15,23,42,.12);
    background:#fff; color:var(--ink);
    text-decoration:none;
    box-shadow: 0 12px 24px rgba(2,6,23,.06);
  }
  .btn:hover{ transform: translateY(-1px); }
  .btn:active{ transform: translateY(0); }

  /* ===== LAYOUT ===== */
  .layout{
    display:grid;
    grid-template-columns: 1.35fr .65fr;
    gap: 18px;
  }
  @media (max-width: 980px){
    .layout{ grid-template-columns: 1fr; }
  }

  /* ===== GALERÍA ===== */
  .gallery{
    position:relative;
  }

  .hero{
    position:relative;
    border-radius: 22px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.10);
    background:#f3f4f6;
  }

  .hero-stage{
    width:100%;
    aspect-ratio: 21/9;
    background:#0b1220;
    display:flex; align-items:center; justify-content:center;
    overflow:hidden;
  }

  .hero-stage img{
    width:100%;
    height:100%;
    object-fit: cover; /* ✅ look “web oficial” */
    display:block;
  }

  .nav{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:44px;height:44px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.92);
    font-size:22px;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;
    z-index:20;
  }
  .nav.prev{ left:12px; }
  .nav.next{ right:12px; }

  .thumbs{
    margin-top: 12px;
    display:flex;
    gap: 10px;
    align-items:center;
    overflow:auto;
    padding-bottom: 2px;
  }

  .thumb{
    width:84px; height:56px;
    border-radius: 14px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.12);
    background:#f3f4f6;
    cursor:pointer;
    flex: 0 0 auto;
    opacity:.85;
  }
  .thumb.active{ outline: 3px solid rgba(37,99,235,.35); opacity:1; }
  .thumb img{ width:100%; height:100%; object-fit: cover; display:block; }

  .edit-fab{
    position:absolute;
    top:14px;
    right:14px;
    width:44px;height:44px;
    border-radius:999px;
    border:1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    box-shadow: 0 18px 44px rgba(2,6,23,.18);
    display:flex;align-items:center;justify-content:center;
    font-weight:900;
    cursor:pointer;
    z-index:30;
  }

  /* ===== PANEL INFO (derecha) ===== */
  .info{
    padding: 16px;
  }
  .title{
    font-size: 26px;
    font-weight: 950;
    letter-spacing: -.02em;
    color: var(--ink);
  }
  .desc{
    margin-top: 10px;
    color: rgba(15,23,42,.72);
    font-weight: 650;
    line-height: 1.35;
  }

  .section{
    margin-top: 14px;
    border-top:1px solid rgba(15,23,42,.08);
    padding-top: 14px;
  }
  .h{
    font-weight: 950;
    color: rgba(15,23,42,.80);
    margin-bottom: 10px;
  }

  .measures{
    display:grid;
    grid-template-columns: repeat(3,1fr);
    gap:10px;
  }
  .kv{
    border:1px solid rgba(15,23,42,.12);
    border-radius:16px;
    padding:10px 12px;
    background: rgba(248,250,252,.85);
  }
  .kv .k{ font-size:12px; font-weight:900; opacity:.7; }
  .kv .v{ margin-top:4px; font-size:14px; font-weight:900; }

  .chips{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
  }
  .chip{
    padding: 7px 10px;
    border-radius: 999px;
    border:1px solid rgba(15,23,42,.14);
    background:#fff;
    font-weight: 850;
    font-size: 12.5px;
    color: rgba(15,23,42,.85);
  }
  .chip.empty{ opacity:.55; }

</style>

<div class="wrap">

  <div class="topbar">
    <a class="btn" href="{{ $backTo }}">← Volver</a>
  </div>

  <div class="layout">

    {{-- IZQUIERDA: GALERÍA --}}
    <div class="gallery">
      <div class="hero card" style="padding:14px;">
        <div class="hero-stage" id="heroStage">
          @if(count($imgs))
            <img id="heroImg" src="{{ $imgUrl($imgs[0]) }}" alt="">
          @else
            <div style="color:#94a3b8;font-weight:900;">Sin imágenes</div>
          @endif
        </div>

        @if($isAdmin)
          {{-- ✅ Respeta icono de edición --}}
          <a class="edit-fab" href="{{ route('admin.menu-products.index') }}?focus={{ $p->id }}" title="Editar producto">✎</a>
        @endif

        @if(count($imgs) > 1)
          <button class="nav prev" type="button" onclick="prevImg()">‹</button>
          <button class="nav next" type="button" onclick="nextImg()">›</button>
        @endif

        {{-- thumbnails --}}
        @if(count($imgs) > 1)
          <div class="thumbs" id="thumbs">
            @foreach($imgs as $i => $path)
              <div class="thumb {{ $i===0 ? 'active' : '' }}" data-i="{{ $i }}"
                   onclick="setImg({{ $i }})">
                <img src="{{ $imgUrl($path) }}" alt="">
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    {{-- DERECHA: INFO --}}
    <div class="card info">
      <div class="title">{{ $p->title }}</div>
      <div class="desc">{{ $p->description ?: 'Sin descripción.' }}</div>

      <div class="section">
        <div class="h">Medidas</div>
        <div class="measures">
          <div class="kv">
            <div class="k">Largo</div>
            <div class="v">{{ $m['largo'] ?: '—' }}</div>
          </div>
          <div class="kv">
            <div class="k">Ancho</div>
            <div class="v">{{ $m['ancho'] ?: '—' }}</div>
          </div>
          <div class="kv">
            <div class="k">Alto</div>
            <div class="v">{{ $m['alto'] ?: '—' }}</div>
          </div>
        </div>
      </div>

      <div class="section">
        <div class="h">Colores de Acero</div>
        <div class="chips">
          @forelse($colorsAcero as $c)
            <span class="chip">{{ $c }}</span>
          @empty
            <span class="chip empty">—</span>
          @endforelse
        </div>
      </div>

      <div class="section">
        <div class="h">Colores de Melamina</div>
        <div class="chips">
          @forelse($colorsMel as $c)
            <span class="chip">{{ $c }}</span>
          @empty
            <span class="chip empty">—</span>
          @endforelse
        </div>
      </div>

    </div>

  </div>
</div>

<script>
  const imgs = @json(array_map(fn($p)=> asset('storage/'.ltrim($p,'/')), $imgs));
  let idx = 0;

  function syncThumbs(){
    const thumbs = document.querySelectorAll('#thumbs .thumb');
    thumbs.forEach(t => t.classList.remove('active'));
    const current = document.querySelector(`#thumbs .thumb[data-i="${idx}"]`);
    if(current) current.classList.add('active');
  }

  function setImg(i){
    idx = i;
    const hero = document.getElementById('heroImg');
    if(hero && imgs[idx]) hero.src = imgs[idx];
    syncThumbs();
  }

  function prevImg(){
    if(!imgs.length) return;
    idx = Math.max(0, idx - 1);
    setImg(idx);
  }
  function nextImg(){
    if(!imgs.length) return;
    idx = Math.min(imgs.length - 1, idx + 1);
    setImg(idx);
  }
</script>
@endsection
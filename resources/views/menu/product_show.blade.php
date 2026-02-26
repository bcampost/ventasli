@extends('layouts.app')

@section('content')
@php
  $isAdmin = auth()->check() && method_exists(auth()->user(),'hasRole') && auth()->user()->hasRole('admin');

  $imgs = is_array($detail->images) ? $detail->images : (json_decode((string)$detail->images, true) ?: []);
  $imgs = array_values(array_filter($imgs, fn($p)=>trim((string)$p)!==''));

  $imgUrls = array_map(fn($p)=>asset('storage/'.ltrim($p,'/')), $imgs);

  $title = $detail->title ?: ($product->title ?? 'Producto');
  $desc  = trim((string)($detail->description ?? ''));
  $desc  = $desc !== '' ? $desc : 'Sin descripción.';

  $acero = $detail->acero_colors ?: [];
  $mela  = $detail->melamina_colors ?: [];

  $heroDefault = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1600' height='900'%3E%3Crect width='100%25' height='100%25' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-size='44' font-family='Arial'%3ESin%20imagen%3C/text%3E%3C/svg%3E";
@endphp

<style>
  :root{
    --ink:#0b1220;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);
    --rXL: 26px;
  }

  .pd-wrap{ max-width: 1220px; margin: 22px auto; padding: 0 18px 40px; }
  .pd-topbar{ display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px; }
  .pd-back{
    display:inline-flex; align-items:center; gap:10px;
    padding: .62rem .92rem; border-radius: 999px;
    background:#fff; border:1px solid var(--line);
    text-decoration:none; color:var(--ink); font-weight:900;
    box-shadow: var(--shadowM);
  }

  .pd-shell{
    display:grid;
    grid-template-columns: 1.35fr .85fr;
    gap: 16px;
    align-items:start;
  }

  @media (max-width: 980px){
    .pd-shell{ grid-template-columns: 1fr; }
  }

  /* IZQUIERDA: thumbnails arriba + imagen grande */
  .pd-left{
    border-radius: var(--rXL);
    background:#fff;
    border:1px solid var(--line);
    box-shadow: var(--shadowXL);
    overflow:hidden;
  }

  .pd-thumbs{
    padding: 12px;
    display:flex;
    gap:10px;
    overflow:auto;
    border-bottom: 1px solid rgba(15,23,42,.08);
    background: rgba(248,250,252,.75);
  }

  .pd-thumb{
    width:84px;
    height:58px;
    border-radius: 14px;
    border:1px solid rgba(15,23,42,.14);
    overflow:hidden;
    background:#fff;
    cursor:pointer;
    flex: 0 0 auto;
    opacity:.85;
    transition: transform .15s ease, opacity .15s ease, border-color .15s ease;
  }
  .pd-thumb:hover{ transform: translateY(-1px); opacity:1; border-color: rgba(15,23,42,.22); }
  .pd-thumb.active{ opacity:1; border-color: rgba(37,99,235,.55); box-shadow: 0 0 0 5px rgba(37,99,235,.14); }
  .pd-thumb img{ width:100%; height:100%; object-fit:cover; display:block; }

  .pd-stage{
    position:relative;
    background: #fff;
  }

  .pd-stage-inner{
    aspect-ratio: 21/9;
    background:#f3f4f6;
    overflow:hidden;
    position:relative;
  }

  .pd-stage-inner img{
    width:100%;
    height:100%;
    object-fit: cover; /* ✅ estilo tipo web (banner) */
    object-position: center;
    display:block;
  }

  .pd-nav{
    position:absolute;
    top:50%;
    transform: translateY(-50%);
    width:54px; height:54px;
    border-radius: 999px;
    background: rgba(255,255,255,.95);
    border: 1px solid rgba(15,23,42,.18);
    box-shadow: 0 14px 28px rgba(2,6,23,.16);
    cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size: 26px; font-weight: 900;
    user-select:none;
  }
  .pd-nav.prev{ left: 14px; }
  .pd-nav.next{ right: 14px; }

  .pd-edit-fab{
    position:absolute;
    top: 14px;
    right: 14px;
    width:46px; height:46px;
    border-radius:999px;
    background: rgba(255,255,255,.95);
    border:1px solid rgba(15,23,42,.18);
    box-shadow: 0 14px 28px rgba(2,6,23,.16);
    display:flex; align-items:center; justify-content:center;
    text-decoration:none;
    color: var(--ink);
    font-weight: 950;
    z-index: 5;
  }

  /* DERECHA: panel info */
  .pd-right{
    border-radius: var(--rXL);
    background:#fff;
    border:1px solid var(--line);
    box-shadow: var(--shadowXL);
    overflow:hidden;
  }

  .pd-rhead{
    padding: 16px 16px 12px;
    border-bottom: 1px solid rgba(15,23,42,.08);
  }
  .pd-title{ font-size: 22px; font-weight: 950; letter-spacing:-.02em; }
  .pd-desc{ margin-top: 8px; color: rgba(15,23,42,.68); font-weight: 650; line-height:1.35; }

  .pd-sec{ padding: 14px 16px; border-top: 1px solid rgba(15,23,42,.08); }
  .pd-sec h4{ margin:0 0 10px; font-size: 14px; font-weight: 950; color: rgba(15,23,42,.78); }

  .pd-measures{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }
  .pd-m{
    border-radius: 14px;
    border:1px solid rgba(15,23,42,.14);
    background: rgba(248,250,252,.75);
    padding: 10px 10px;
  }
  .pd-m small{ display:block; opacity:.7; font-weight:900; }
  .pd-m b{ display:block; font-size: 18px; margin-top: 3px; }

  .pd-pills{ display:flex; gap:8px; flex-wrap:wrap; }
  .pill{
    display:inline-flex; align-items:center;
    padding: 6px 10px;
    border-radius: 999px;
    border:1px solid rgba(15,23,42,.14);
    background: rgba(248,250,252,.85);
    font-weight: 900;
    color: rgba(15,23,42,.78);
    font-size: 12.5px;
  }
</style>

<div class="pd-wrap">
  <div class="pd-topbar">
    <a class="pd-back" href="{{ $redirectTo }}">← Volver</a>
    {{-- aquí podrías poner un botón extra si quieres --}}
  </div>

  <div class="pd-shell">

    {{-- IZQ --}}
    <div class="pd-left">
      <div class="pd-thumbs" id="pdThumbs">
        @if(count($imgUrls))
          @foreach($imgUrls as $i => $u)
            <div class="pd-thumb {{ $i===0?'active':'' }}" data-idx="{{ $i }}">
              <img src="{{ $u }}" alt="">
            </div>
          @endforeach
        @else
          <div class="pd-thumb active" data-idx="0">
            <img src="{{ $heroDefault }}" alt="">
          </div>
        @endif
      </div>

      <div class="pd-stage">
        @if($isAdmin)
          <a class="pd-edit-fab"
             href="{{ route('admin.product-details.edit', ['menu_product'=>$product->id, 'redirect_to'=>url()->current()]) }}"
             title="Editar producto">✎</a>
        @endif

        <div class="pd-stage-inner">
          <img id="pdMainImg"
               src="{{ count($imgUrls) ? $imgUrls[0] : $heroDefault }}"
               alt="">
          @if(count($imgUrls) > 1)
            <div class="pd-nav prev" id="pdPrev">‹</div>
            <div class="pd-nav next" id="pdNext">›</div>
          @endif
        </div>
      </div>
    </div>

    {{-- DER --}}
    <div class="pd-right">
      <div class="pd-rhead">
        <div class="pd-title">{{ $title }}</div>
        <div class="pd-desc">{{ $desc }}</div>
      </div>

      <div class="pd-sec">
        <h4>Medidas</h4>
        <div class="pd-measures">
          <div class="pd-m"><small>Largo</small><b>{{ $detail->largo ?: '—' }}</b></div>
          <div class="pd-m"><small>Ancho</small><b>{{ $detail->ancho ?: '—' }}</b></div>
          <div class="pd-m"><small>Alto</small><b>{{ $detail->alto  ?: '—' }}</b></div>
        </div>
      </div>

      <div class="pd-sec">
        <h4>Colores de Acero</h4>
        <div class="pd-pills">
          @if(count($acero))
            @foreach($acero as $c)
              <span class="pill">{{ $c }}</span>
            @endforeach
          @else
            <span class="pill">—</span>
          @endif
        </div>
      </div>

      <div class="pd-sec">
        <h4>Colores de Melamina</h4>
        <div class="pd-pills">
          @if(count($mela))
            @foreach($mela as $c)
              <span class="pill">{{ $c }}</span>
            @endforeach
          @else
            <span class="pill">—</span>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>

<script>
(function(){
  const urls = @json($imgUrls);
  if(!urls || urls.length <= 1) return;

  let idx = 0;

  const main = document.getElementById('pdMainImg');
  const thumbs = Array.from(document.querySelectorAll('#pdThumbs .pd-thumb'));

  function setActive(i){
    idx = Math.max(0, Math.min(urls.length - 1, i));
    main.src = urls[idx];

    thumbs.forEach(t => t.classList.remove('active'));
    const t = thumbs.find(x => Number(x.dataset.idx) === idx);
    if(t) t.classList.add('active');
  }

  thumbs.forEach(t => {
    t.addEventListener('click', () => setActive(Number(t.dataset.idx)));
  });

  document.getElementById('pdPrev')?.addEventListener('click', () => setActive(idx - 1));
  document.getElementById('pdNext')?.addEventListener('click', () => setActive(idx + 1));
})();
</script>

@endsection
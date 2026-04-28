@extends('layouts.app')

@section('content')
@php
  // $product: producto actual
  // $siblings: productos hermanos
  // $imgUrl: imagen del producto
@endphp

<div class="pl-wrap">
  <div class="pl-main">

    {{-- CARD GIGANTE (full width) --}}
    <section class="pl-heroCard">
      <div class="pl-heroHead">
        <div class="pl-title">{{ $product->title }}</div>
        @if(!empty($product->subtitle))
          <div class="pl-sub">{{ $product->subtitle }}</div>
        @endif
      </div>

      <div class="pl-heroMedia">
        @if(!empty($imgUrl))
          <img src="{{ $imgUrl }}" alt="{{ $product->title }}">
        @else
          <div class="pl-heroPlaceholder">Sin imagen</div>
        @endif
      </div>

      <div class="pl-heroBody">
        @if(!empty($product->description))
          <div class="pl-desc">{{ $product->description }}</div>
        @else
          <div class="pl-desc muted">Sin descripción.</div>
        @endif
      </div>

      <div class="pl-heroActions">
        <a class="pl-btn" href="{{ url()->previous() }}">← Regresar</a>

        @role('admin')
          <a class="pl-btn primary" href="{{ route('admin.menu-products.index') }}">
            Editar productos
          </a>
        @endrole
      </div>
    </section>

    {{-- “Productos hermanos” (abajo, horizontal) --}}
    <section class="pl-siblings">
      <div class="pl-siblingsHead">
        <div class="pl-siblingsTitle">Productos</div>
      </div>

      <div class="pl-row">
        @foreach($siblings as $sib)
          <a class="pl-mini {{ $sib->id === $product->id ? 'is-active' : '' }}"
             href="{{ $sib->url }}">
            <div class="pl-miniImg">
              @if(!empty($sib->img))
                <img src="{{ $sib->img }}" alt="{{ $sib->title }}">
              @else
                <div class="pl-miniPh">Sin imagen</div>
              @endif
            </div>
            <div class="pl-miniName">{{ $sib->title }}</div>
          </a>
        @endforeach
      </div>
    </section>

  </div>
</div>


<style>
  .pl-wrap{ max-width: 1180px; margin: 0 auto; padding: 18px 16px 30px; }
  .pl-main{ display:flex; flex-direction:column; gap: 18px; }

  /* HERO CARD full width */
  .pl-heroCard{
    border-radius: 22px;
    border: 1px solid rgba(17,24,39,.10);
    background: rgba(255,255,255,.88);
    backdrop-filter: blur(10px);
    overflow:hidden;
    box-shadow: 0 22px 70px rgba(0,0,0,.10);
  }

  .pl-heroHead{
    padding: 16px 18px;
    border-bottom: 1px solid rgba(17,24,39,.08);
    background: rgba(255,255,255,.94);
  }
  .pl-title{ font-size: 24px; font-weight: 950; letter-spacing: -.01em; color:#111827; }
  .pl-sub{ margin-top: 4px; color: rgba(17,24,39,.65); font-weight: 750; font-size: 13px; }

  .pl-heroMedia{
    height: 420px; /* ajusta */
    background: #f3f4f6;
    display:flex; align-items:center; justify-content:center;
  }
  .pl-heroMedia img{
    width:100%; height:100%;
    object-fit: contain; /* ✅ muestra completa */
    object-position: center;
    display:block;
    background:#f3f4f6;
  }
  .pl-heroPlaceholder{ font-weight: 300; color: rgba(17,24,39,.45); }

  .pl-heroBody{ padding: 16px 18px; }
  .pl-desc{ color: rgba(17,24,39,.86); font-weight: 650; line-height: 1.45; }
  .pl-desc.muted{ color: rgba(17,24,39,.45); }

  .pl-heroActions{
    padding: 14px 18px;
    border-top: 1px solid rgba(17,24,39,.08);
    display:flex; justify-content:space-between; gap: 10px; flex-wrap: wrap;
    background: rgba(17,24,39,.02);
  }

  .pl-btn{
    display:inline-flex; align-items:center; justify-content:center;
    height: 38px; padding: 0 14px;
    border-radius: 999px;
    border: 1px solid rgba(17,24,39,.12);
    background: #fff;
    font-weight: 300; font-size: 13px;
    text-decoration:none;
    color: rgba(17,24,39,.88);
    box-shadow: 0 10px 18px rgba(0,0,0,.06);
  }
  .pl-btn.primary{
    background: rgba(37,99,235,1);
    border-color: rgba(37,99,235,1);
    color: #fff;
  }

  /* SIBLINGS (horizontal row) */
  .pl-siblings{
    border-radius: 18px;
    border: 1px solid rgba(17,24,39,.10);
    background: rgba(255,255,255,.70);
    backdrop-filter: blur(8px);
    padding: 14px;
  }
  .pl-siblingsHead{ display:flex; align-items:center; justify-content:space-between; margin-bottom: 10px; }
  .pl-siblingsTitle{ font-weight: 950; color:#111827; }

  .pl-row{
    display:flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 6px;
    scroll-snap-type: x mandatory;
  }
  .pl-row::-webkit-scrollbar{ height: 8px; }
  .pl-row::-webkit-scrollbar-thumb{ background: rgba(17,24,39,.20); border-radius: 999px; }

  .pl-mini{
    flex: 0 0 220px;  /* ancho de cada card */
    scroll-snap-align: start;
    border-radius: 16px;
    border: 1px solid rgba(17,24,39,.10);
    background: #fff;
    overflow:hidden;
    text-decoration:none;
    color:#111827;
    box-shadow: 0 10px 18px rgba(0,0,0,.06);
    transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
  }
  .pl-mini:hover{ transform: translateY(-1px); box-shadow: 0 14px 24px rgba(0,0,0,.08); }
  .pl-mini.is-active{ border-color: rgba(37,99,235,.55); }

  .pl-miniImg{ height: 120px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; }
  .pl-miniImg img{ width:100%; height:100%; object-fit: cover; object-position:center; display:block; }
  .pl-miniPh{ font-weight: 300; color: rgba(17,24,39,.45); font-size: 12px; }

  .pl-miniName{ padding: 10px 12px; font-weight: 950; font-size: 13px; }

  @media (max-width: 640px){
    .pl-heroMedia{ height: 320px; }
    .pl-mini{ flex-basis: 200px; }
  }
</style>
@endsection
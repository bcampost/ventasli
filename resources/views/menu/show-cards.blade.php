@extends('layouts.app')

@section('content')
<style>
  :root{
    --ink:#0b1220;
    --uc-border:#e7eaf0;
    --uc-shadow: 0 2px 10px rgba(15,23,42,.03);
    --uc-shadow-hover: 0 22px 44px rgba(15,23,42,.10);
  }

  .wrap{ max-width: 1450px; margin:0 auto; padding: 26px 18px; }

  .head{ margin-bottom: 18px; }
  .title{
    font-size: 1.65rem;
    font-weight: 800;
    letter-spacing: -.02em;
    color: var(--ink);
    line-height: 1.1;
  }

  .grid{
    margin-top: 16px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
    gap: 22px;
    align-items: start;
  }

  /* Card unificado (igual que Material Visual) */
  .card{
    position: relative;
    background: #fff;
    border: 1px solid var(--uc-border);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: var(--uc-shadow);
    transition:
      transform .18s ease,
      box-shadow .18s ease,
      border-color .18s ease;
  }

  .card:hover{
    transform: translateY(-4px);
    box-shadow: var(--uc-shadow-hover);
    border-color: #dbe3ee;
  }

  .media{
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    background: #f3f4f6;
    overflow: hidden;
  }

  .media img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform .35s ease;
  }

  .card:hover .media img{
    transform: scale(1.03);
  }

  .cTitle{
    padding: 16px;
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
  }

  .cDesc{
    padding: 0 16px 16px;
    margin-top: -4px;
    font-size: .82rem;
    color: #64748b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  a.cardLink{ display:block; position:absolute; inset:0; z-index: 5; }
</style>

<div class="wrap">
  <div class="head">
    <div class="title">{{ $current->label ?? 'Menú' }}</div>
  </div>

  @if(count($cards))
    <div class="grid">
      @foreach($cards as $card)
        @php
          $title = $card['title'] ?? ($card['label'] ?? '—');
          $desc  = $card['description'] ?? '';
          $img   = $card['image'] ?? null;
          $href  = $card['href'] ?? '#';
        @endphp

        <div class="card">
          <a class="cardLink" href="{{ $href }}" aria-label="Abrir {{ $title }}"></a>

          <div class="media">
            @if($img)
              <img src="{{ $img }}" alt="{{ $title }}">
            @else
              <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='600'%3E%3Crect width='1200' height='600' fill='%23f1f5f9'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2394a3b8' font-size='32' font-family='Arial'%3ESin imagen%3C/text%3E%3C/svg%3E" alt="Sin imagen">
            @endif
          </div>

          <div class="cTitle">{{ $title }}</div>

          @if(trim((string)$desc) !== '')
            <div class="cDesc">{{ $desc }}</div>
          @endif
        </div>
      @endforeach
    </div>
  @else
    <div class="p-8 text-center text-slate-600">No hay opciones en este nivel.</div>
  @endif
</div>
@endsection
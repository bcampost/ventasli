@extends('layouts.app')

@section('content')
<style>
  :root{
    --ink:#0b1220;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --shadowL: 0 18px 50px rgba(15,23,42,.14);
    --rXL: 26px;
  }

  .wrap{ max-width: 1220px; margin:0 auto; padding: 26px 18px; }

  .head{ margin-bottom: 14px; }
  .title{
    font-size: 1.65rem;
    font-weight: 950;
    letter-spacing: -.02em;
    color: var(--ink);
    line-height: 1.1;
  }

  .grid{ margin-top: 16px; display:grid; gap: 14px; }

  .card{
    position:relative;
    height: 150px;
    border-radius: var(--rXL);
    overflow:hidden;
    border: 1px solid var(--line);
    background: #fff;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .card:hover{
    transform: translateY(-2px);
    border-color: var(--line2);
    box-shadow: var(--shadowL);
  }

  .media{ position:absolute; inset:0; background: rgba(15,23,42,.04); }
  .media img{
    width:100%;
    height:100%;
    object-fit: cover; /* ✅ sin distorsión fuerte */
    display:block;
  }

  .vlabel{
    position:absolute;
    left: 14px;
    top: 12px;
    bottom: 12px;
    width: 44px;
    border-radius: 18px;
    background: rgba(255,255,255,.70);
    border: 1px solid rgba(15,23,42,.10);
    backdrop-filter: blur(8px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 10px 0;
    transition: opacity .18s ease, transform .18s ease;
  }
  .vlabel span{
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    font-weight: 950;
    letter-spacing: .08em;
    color: rgba(15,23,42,.90);
    font-size: .82rem;
    text-transform: uppercase;
    max-height: 100%;
    overflow:hidden;
    white-space: nowrap;
  }

  .overlay{
    position:absolute;
    inset:0;
    display:flex;
    align-items:flex-end;
    padding: 18px 18px 18px 76px;
    background: linear-gradient(90deg,
      rgba(2,6,23,.78) 0%,
      rgba(2,6,23,.55) 45%,
      rgba(2,6,23,.10) 100%
    );
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity .20s ease, transform .20s ease;
  }
  .card:hover .overlay{ opacity: 1; transform: translateX(0); }
  .card:hover .vlabel{ opacity: 0; transform: translateX(-6px); }

  .hTitle{
    font-size: 1.25rem;
    font-weight: 950;
    letter-spacing: -.01em;
    color: #fff;
    line-height: 1.1;
  }
  .hDesc{
    margin-top: 8px;
    color: rgba(255,255,255,.82);
    font-size: .95rem;
    line-height: 1.35;
    max-width: 92ch;
    display:-webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow:hidden;
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

          <div class="vlabel"><span title="{{ $title }}">{{ $title }}</span></div>

          <div class="overlay">
            <div>
              <div class="hTitle">{{ $title }}</div>
              @if(trim((string)$desc) !== '')
                <div class="hDesc">{{ $desc }}</div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="p-8 text-center text-slate-600">No hay opciones en este nivel.</div>
  @endif
</div>
@endsection
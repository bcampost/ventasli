{{-- resources/views/components/right-quick-menu.blade.php --}}

@php
  use App\Models\QuickLink;

  $quickLinks = QuickLink::where('is_active', true)
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get();

  $isAdmin = auth()->check() && auth()->user()->hasRole('admin');

  $icon = function(string $name) {
    return match($name) {
      'home' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 10.5 12 4l8 6.5V20a1.5 1.5 0 0 1-1.5 1.5H15v-6h-6v6H5.5A1.5 1.5 0 0 1 4 20v-9.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
      'folder' => '<svg viewBox="0 0 24 24" fill="none"><path d="M3.5 7.5h6l2 2H20.5A1.5 1.5 0 0 1 22 11v8.5A1.5 1.5 0 0 1 20.5 21h-15A1.5 1.5 0 0 1 4 19.5V7.5a2 2 0 0 1-.5 0Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
      'briefcase' => '<svg viewBox="0 0 24 24" fill="none"><path d="M9 7V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.8"/><path d="M4 8.5h16A2 2 0 0 1 22 10.5v8A2.5 2.5 0 0 1 19.5 21h-15A2.5 2.5 0 0 1 2 18.5v-8A2 2 0 0 1 4 8.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
      'users' => '<svg viewBox="0 0 24 24" fill="none"><path d="M16 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/><path d="M8 12a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/><path d="M13 20a5 5 0 0 1 10 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M1 20a6 6 0 0 1 11 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
      'calc' => '<svg viewBox="0 0 24 24" fill="none"><path d="M7 3.5h10A2.5 2.5 0 0 1 19.5 6v12A2.5 2.5 0 0 1 17 20.5H7A2.5 2.5 0 0 1 4.5 18V6A2.5 2.5 0 0 1 7 3.5Z" stroke="currentColor" stroke-width="1.8"/><path d="M7.5 7.5h9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8 12h0M12 12h0M16 12h0M8 15.5h0M12 15.5h0M16 15.5h0" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>',
      'file' => '<svg viewBox="0 0 24 24" fill="none"><path d="M7 3.5h7l3 3v14A2 2 0 0 1 15 22H7A2 2 0 0 1 5 20.5v-15A2 2 0 0 1 7 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3.5V7h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
      'shield' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 3.5 20 7v6c0 5-3.2 8.4-8 9.5C7.2 21.4 4 18 4 13V7l8-3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
      'settings' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 15.5a3.5 3.5 0 1 0-3.5-3.5 3.5 3.5 0 0 0 3.5 3.5Z" stroke="currentColor" stroke-width="1.8"/><path d="M19.4 13a7.9 7.9 0 0 0 0-2l2-1.2-2-3.4-2.3.7a8.2 8.2 0 0 0-1.7-1L15 3.5h-6L8.6 6.1a8.2 8.2 0 0 0-1.7 1L4.6 6.4l-2 3.4L4.6 11a7.9 7.9 0 0 0 0 2l-2 1.2 2 3.4 2.3-.7a8.2 8.2 0 0 0 1.7 1L9 20.5h6l.4-2.6a8.2 8.2 0 0 0 1.7-1l2.3.7 2-3.4L19.4 13Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>',
      default => '<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/></svg>',
    };
  };

  $hrefFor = function(string $url) {
    $u = trim($url);
    if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) return $u;
    if ($u === '') return '#';
    return str_starts_with($u, '/') ? url($u) : url('/'.$u);
  };

  $isExternal = function(string $url) {
    $u = trim($url);
    return str_starts_with($u, 'http://') || str_starts_with($u, 'https://');
  };
@endphp

<style>
  html, body { overflow-x: hidden; }

  /* =========================================================
     ✅ CONTROLES RÁPIDOS (CAMBIA AQUÍ)
     ========================================================= */
  :root{
    --rq-right: 18px;      /* distancia a la derecha */
    --rq-gap: 10px;        /* separación entre items */

    --rq-item-w: 64px;     /* ancho de cada item (icono + label) */
    --rq-icon: 40px;       /* tamaño del círculo del icono */
    --rq-svg: 20px;        /* tamaño del svg */
    --rq-img: 20px;        /* tamaño de imagen subida */

    --rq-label-font: 11px; /* tamaño del texto */
    --rq-label-pad-y: 5px; /* padding label */
    --rq-label-pad-x: 9px;

    /* ✅ color de fondo del icono (aquí lo cambias) */
    --rq-bg: rgba(255,255,255,.35);

    /* borde/sombras del icono */
    --rq-border: rgba(255,255,255,.38);
    --rq-shadow: 0 10px 22px rgba(0,0,0,.10);
  }

  .rq-wrap{
    position: fixed;
    right: var(--rq-right);
    top: 50%;
    transform: translateY(-50%);
    z-index: 9999;
    pointer-events: none;
  }

  .rq{
    pointer-events: auto;
    display:flex;
    flex-direction:column;
    gap: var(--rq-gap);
    align-items:center;
  }

  .rq-item{
    width: var(--rq-item-w);
    display:flex;
    flex-direction:column;
    align-items:center;
    gap: 7px;
    text-decoration:none;
    outline:none;
    transition: transform .18s ease, filter .18s ease;
  }

  .rq-item:hover{
    transform: translateX(-2px) scale(1.04);
    filter: drop-shadow(0 16px 24px rgba(0,0,0,.10));
  }

  .rq-icon{
    width: var(--rq-icon);
    height: var(--rq-icon);
    border-radius: 999px;
    display:grid;
    place-items:center;

    background: var(--rq-bg);
    border: 1px solid var(--rq-border);
    box-shadow:
      var(--rq-shadow),
      inset 0 1px 0 rgba(255,255,255,.55),
      inset 0 -10px 20px rgba(255,255,255,.08);

    backdrop-filter: blur(10px) saturate(140%);
    -webkit-backdrop-filter: blur(10px) saturate(140%);

    position: relative;
    overflow: hidden;

    transition: transform .18s ease, background .18s ease, border-color .18s ease;
  }

  .rq-icon::before{
    content:"";
    position:absolute;
    inset: -20% -40%;
    background: radial-gradient(circle at 30% 25%,
      rgba(255,255,255,.85) 0%,
      rgba(255,255,255,.25) 35%,
      rgba(255,255,255,0) 62%);
    transform: rotate(12deg);
    pointer-events:none;
    opacity: .55;
  }

  .rq-icon::after{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(115deg,
      rgba(255,255,255,0) 0%,
      rgba(255,255,255,.30) 45%,
      rgba(255,255,255,0) 70%);
    transform: translateX(-120%);
    opacity: 0;
    pointer-events:none;
    transition: transform .35s ease, opacity .35s ease;
  }

  .rq-item:hover .rq-icon{
    background: color-mix(in srgb, var(--rq-bg) 90%, rgba(255,255,255,.18));
    border-color: color-mix(in srgb, var(--rq-border) 80%, rgba(255,255,255,.25));
    transform: translateZ(0);
  }
  .rq-item:hover .rq-icon::after{
    transform: translateX(120%);
    opacity: .35;
  }

  .rq-icon svg{
    width: var(--rq-svg);
    height: var(--rq-svg);
    color: rgba(15,23,42,.92);
    filter: drop-shadow(0 1px 0 rgba(255,255,255,.55));
  }

  .rq-img{
    width: var(--rq-img);
    height: var(--rq-img);
    object-fit: contain;
    display:block;
    filter: drop-shadow(0 1px 0 rgba(255,255,255,.45));
  }

  .rq-label{
    pointer-events: none;
    white-space: nowrap;
    font-size: var(--rq-label-font);
    font-weight: 900;
    letter-spacing: .2px;
    color: #0f172a;

    background: rgba(255,255,255,.92);
    border: 1px solid rgba(2,6,23,.08);
    border-radius: 999px;
    padding: var(--rq-label-pad-y) var(--rq-label-pad-x);
    box-shadow: 0 10px 22px rgba(0,0,0,.08);
  }

  .rq-sep{
    width: 30px;
    height: 1px;
    background: rgba(2,6,23,.12);
    border-radius: 999px;
    margin: 2px 0;
  }
</style>

<div class="rq-wrap">
  <nav class="rq" aria-label="Accesos rápidos">
    @foreach($quickLinks as $l)
      @php
        $href = $hrefFor($l->url);
        $ext  = $isExternal($l->url);
      @endphp

      <a class="rq-item"
         href="{{ $href }}"
         @if($ext) target="_blank" rel="noopener" @endif
      >
        <span class="rq-icon">
          @if(!empty($l->icon_path))
            <img class="rq-img" src="{{ asset('storage/'.$l->icon_path) }}" alt="{{ $l->name }}">
          @else
            {!! $icon($l->icon ?? 'dot') !!}
          @endif
        </span>

        <span class="rq-label">{{ $l->name }}</span>
      </a>
    @endforeach

    @if($isAdmin)
      <div class="rq-sep"></div>
      <a class="rq-item" href="{{ route('admin.quick-links.index') }}">
        <span class="rq-icon">
          {!! $icon('settings') !!}
        </span>
        <span class="rq-label">Editar panel</span>
      </a>
    @endif
  </nav>
</div>
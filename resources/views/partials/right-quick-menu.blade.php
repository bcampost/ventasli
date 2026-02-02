@php
  use App\Models\QuickLink;

  $quickLinks = QuickLink::where('is_active', true)
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get();

  $isAdmin = auth()->check() && auth()->user()->hasRole('Admin');

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
@endphp

<style>
  /* evita scroll horizontal por transforms */
  html, body { overflow-x: hidden; }

  .rq-wrap{
    position: fixed;
    right: 22px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 9999;
    pointer-events: none;
  }

  /* SOLO ICONOS: lista sin cápsula */
  .rq{
    pointer-events: auto;
    display:flex;
    flex-direction:column;
    gap: 14px;
    align-items:center;
  }

  .rq-item{
    position: relative;
    width: 46px;
    height: 46px;
    display:grid;
    place-items:center;
    text-decoration:none;
    outline:none;
    border-radius: 999px;
    background: transparent;        /* sin botón */
    border: none;                    /* sin borde */
    transition: transform .18s ease, filter .18s ease;
    transform-style: preserve-3d;
  }

  .rq-item:hover{
    transform: translateX(-2px) translateZ(18px) scale(1.06);
    filter: drop-shadow(0 18px 30px rgba(0,0,0,.12));
  }

  /* "Liquid glass" SOLO en el icono */
  .rq-icon{
    width: 48px;
    height: 48px;
    border-radius: 999px;
    display:grid;
    place-items:center;

    /* glass */
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.38);
    box-shadow:
      0 10px 26px rgba(0,0,0,.12),
      inset 0 1px 0 rgba(255,255,255,.55),
      inset 0 -10px 24px rgba(255,255,255,.10);
    backdrop-filter: blur(10px) saturate(140%);
    -webkit-backdrop-filter: blur(10px) saturate(140%);

    /* profundidad */
    transform: translateZ(0);
    position: relative;
    overflow: hidden;

    transition: transform .18s ease, background .18s ease, border-color .18s ease;
  }

  /* brillo especular */
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

  /* sheen al hover */
  .rq-icon::after{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(115deg,
      rgba(255,255,255,0) 0%,
      rgba(255,255,255,.35) 45%,
      rgba(255,255,255,0) 70%);
    transform: translateX(-120%);
    opacity: .0;
    pointer-events:none;
    transition: transform .35s ease, opacity .35s ease;
  }

  .rq-item:hover .rq-icon{
    background: rgba(255,255,255,.28);
    border-color: rgba(255,255,255,.55);
    transform: translateZ(10px);
  }
  .rq-item:hover .rq-icon::after{
    transform: translateX(120%);
    opacity: .35;
  }

  .rq-icon svg{
    width: 18px;
    height: 18px;
    color: rgba(15,23,42,.92);
    filter: drop-shadow(0 1px 0 rgba(255,255,255,.55));
  }

  /* tooltip a la izquierda (nombre) */
  .rq-label{
    position:absolute;
    right: 54px;
    top: 50%;
    transform: translateY(-50%) translateX(10px);
    opacity: 0;
    pointer-events: none;
    white-space: nowrap;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .2px;
    color: #0f172a;
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(2,6,23,.08);
    border-radius: 12px;
    padding: 8px 10px;
    box-shadow: 0 18px 45px rgba(0,0,0,.14);
    transition: opacity .18s ease, transform .18s ease;
  }
  .rq-item:hover .rq-label{
    opacity: 1;
    transform: translateY(-50%) translateX(0);
  }

  /* separador discreto para admin */
  .rq-sep{
    width: 26px;
    height: 1px;
    background: rgba(2,6,23,.12);
    border-radius: 999px;
    margin: 2px 0;
  }
</style>

<div class="rq-wrap">
  <nav class="rq" aria-label="Accesos rápidos">
    @foreach($quickLinks as $l)
      <a class="rq-item" href="{{ $hrefFor($l->url) }}" title="{{ $l->name }}">
        <span class="rq-icon">{!! $icon($l->icon) !!}</span>
        <span class="rq-label">{{ $l->name }}</span>
      </a>
    @endforeach

    @if($isAdmin)
      <div class="rq-sep"></div>
      <a class="rq-item" href="{{ route('admin.quick-links.index') }}" title="Editar panel">
        <span class="rq-icon">{!! $icon('settings') !!}</span>
        <span class="rq-label">Editar panel</span>
      </a>
    @endif
  </nav>
</div>
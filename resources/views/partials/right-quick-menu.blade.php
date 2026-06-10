@php
  use App\Models\QuickLink;

$isAdmin = auth()->check() && auth()->user()->hasRole('admin');

$quickLinks = QuickLink::where('is_active', true)
  ->when(!$isAdmin, function ($q) {
    $q->whereRaw('TRIM(LOWER(name)) != ?', ['permisos']);
  })
  ->orderBy('sort_order')
  ->orderBy('id')
  ->get();

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

.rq-wrap{
  position: fixed;
  right: 20px;
  top: 110px;
  z-index: 70;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.rq{
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  width: 74px;
  padding: 14px 10px;
  border: 1px solid #e5e7eb;
  border-radius: 24px;
  background: #ffffff;
  box-shadow: 0 18px 40px rgba(15,23,42,.12);
  transition: width .25s ease, padding .25s ease;
}

/* Botón que abre/cierra */
.rq-toggle{
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-width: 58px;
  height: 58px;
  padding: 0 18px;
  border: 1px solid #e5e7eb;
  border-radius: 18px;
  background: #ffffff;
  box-shadow: 0 12px 30px rgba(15,23,42,.10);
  color: #111827;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  white-space: nowrap;
  transition: all .22s ease;
}

.rq-toggle:hover{
  transform: translateY(-1px);
  box-shadow: 0 16px 34px rgba(15,23,42,.14);
}

.rq-toggle-icon{
  width: 22px;
  height: 22px;
  min-width: 22px;
  display: inline-grid;
  place-items: center;
  font-size: 18px;
  line-height: 1;
}

.rq-toggle-text{
  display: none;
}


/* Items */
.rq-item{
  width: 54px;
  min-height: 54px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding-left: 0.7em;
  margin: 0 auto;
  text-decoration: none;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid transparent;
  color: #111827;
  overflow: hidden;
  transition: all .22s ease;
}

.rq-item:hover{
  background: #f9fafb;
  border-color: #e5e7eb;
}

.rq-icon{
  width: 42px;
  height: 42px;
  min-width: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  padding: 0;
  overflow: hidden;
}

.rq-img{
  width: 20px;
  height: 20px;
  display: block;
  object-fit: contain;
  object-position: center;
  margin: 0 auto;
}

.rq-icon svg{
  width: 20px;
  height: 20px;
  color: #374151;
  display: block;
  margin: 0 auto;
}


.rq-label{
  max-width: 0;
  opacity: 0;
  overflow: hidden;
  white-space: nowrap;
  font-size: 14px;
  font-weight: 500;
  color: #111827;
  transition: max-width .22s ease, opacity .18s ease;
}

/* Separador */
.rq-sep{
  width: 36px;
  height: 1px;
  background: #e5e7eb;
  border-radius: 999px;
  margin: 2px 0;
  transition: width .22s ease;
}

/* Estado abierto */
.rq-wrap.is-open .rq-toggle{
  min-width: 170px;
  justify-content: flex-start;
}

.rq-wrap.is-open .rq-toggle-text{
  display: inline;
}

.rq-wrap.is-open .rq{
  width: 240px;
  align-items: stretch;
  padding: 14px;
}

.rq-wrap.is-open .rq-item{
  width: 100%;
  min-height: 54px;
  justify-content: flex-start;
  padding: 6px 12px;
  border-color: #f3f4f6;
}

.rq-wrap.is-open .rq-label{
  max-width: 140px;
  opacity: 1;
}

.rq-wrap.is-open .rq-sep{
  width: 100%;
}


@media (max-width: 1300px){
  .rq-wrap{
    right: 12px;
    bottom: 30px;
    top: auto;
    align-items: center;
  }
}

/* Tablet */
@media (max-width: 992px){

  .rq{
    width: 68px;
    padding: 12px 8px;
  }

  .rq-item{
    width: 50px;
    min-height: 50px;
  }

  .rq-icon{
    width:36px;
    height: 36px;
    min-width: 36px;
  }

  .rq-wrap.is-open .rq{
    width: 210px;
  }

  .rq-wrap.is-open .rq-toggle{
    min-width: 150px;
  }
}

/* Móvil */
@media (max-width: 768px){
  .rq-wrap{
    display: none;
  }

}
</style>

<div class="rq-wrap" id="rqWrap">
  <button
    type="button"
    class="rq-toggle"
    id="rqToggle"
    aria-label="Expandir accesos rápidos"
    aria-expanded="false"
    onclick="toggleQuickLinksPanel()"
  >
    <span class="rq-toggle-icon">☰</span>
    <span class="rq-toggle-text">Aplicativos</span>
  </button>

  <nav class="rq" id="rqPanel" aria-label="Accesos rápidos">
    @foreach($quickLinks as $l)
      @php
        $href = $hrefFor($l->url);
        $ext  = $isExternal($l->url);
      @endphp

      <a class="rq-item"
        href="{{ $href }}"
        title="{{ $l->name }}"
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

      <a class="rq-item" href="{{ route('admin.quick-links.index') }}" title="Editar panel">
        <span class="rq-icon">
          {!! $icon('settings') !!}
        </span>

        <span class="rq-label">Editar panel</span>
      </a>
    @endif
  </nav>
</div>

<script>
  function toggleQuickLinksPanel() {
    const wrap = document.getElementById('rqWrap');
    const btn = document.getElementById('rqToggle');

    if (!wrap || !btn) return;

    wrap.classList.toggle('is-open');

    const isOpen = wrap.classList.contains('is-open');
    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }
</script>
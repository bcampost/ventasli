{{-- resources/views/layouts/navigation.blade.php --}}
@php
  use App\Models\MenuNode;

  $navRoots = MenuNode::query()
    ->active()
    ->where(function($q){
      $q->whereNull('parent_id')->orWhere('parent_id', 0);
    })
    ->orderBy('sort')->orderBy('label')
    ->with(['children' => function($q){
      $q->active()->orderBy('sort')->orderBy('label');
    }])
    ->get();

  $user = auth()->user();
@endphp

<style>
  :root{
    --nav-bg: rgba(255,255,255,.88);
    --nav-line: rgba(15,23,42,.10);
    --nav-ink: #0b1220;
    --nav-muted: rgba(15,23,42,.62);
    --nav-shadow: 0 14px 40px rgba(2,6,23,.08);
    --nav-r: 18px;
    --nav-primary: #2563eb;
  }

  .v-nav{
    position: sticky;
    top: 0;
    z-index: 60;
    background: var(--nav-bg);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--nav-line);
  }

  .v-nav-wrap{
    max-width: 1280px;
    margin: 0 auto;
    padding: 10px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 16px;
  }

  .v-brand{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight: 950;
    letter-spacing: -.02em;
    color: var(--nav-ink);
    text-decoration:none;
    user-select:none;
  }

  .v-menu{
    display:flex;
    align-items:center;
    gap: 6px;
    flex-wrap: wrap;
  }

  .v-item{ position: relative; }

  .v-link{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 10px 12px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 14px;
    color: var(--nav-ink);
    text-decoration:none;
    border: 1px solid transparent;
    transition: background .15s ease, border-color .15s ease, transform .15s ease;
  }
  .v-link:hover{
    background: rgba(248,250,252,.85);
    border-color: rgba(15,23,42,.12);
    transform: translateY(-1px);
  }

  .v-caret{
    font-size: 12px;
    color: rgba(15,23,42,.55);
    margin-left: 2px;
  }

  /* ✅ puente invisible para que no se "rompa" el hover */
  .v-item::after{
    content:"";
    position:absolute;
    left:0;
    right:0;
    top:100%;
    height: 14px;
  }

  .v-dd{
    position:absolute;
    left:0;
    top: 100%;
    margin-top: 10px;
    min-width: 240px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 16px;
    box-shadow: var(--nav-shadow);
    padding: 8px;
    display:none;
    z-index: 80;
  }

  .v-item:hover .v-dd,
  .v-item:focus-within .v-dd{
    display:block;
  }

  .v-dd a{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding: 10px 10px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 14px;
    text-decoration:none;
    color: var(--nav-ink);
  }
  .v-dd a:hover{ background: rgba(248,250,252,.9); }

  .v-dd small{
    color: var(--nav-muted);
    font-weight: 700;
  }

  /* ✅ NUEVO: contenedor para engrane (abajo derecha) */
  .v-dd-tools{
    display:flex;
    justify-content:flex-end;
    padding-top: 6px;
    margin-top: 6px;
    border-top: 1px solid rgba(15,23,42,.08);
  }

  .v-dd-gear{
    width: 34px;
    height: 34px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius: 10px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(248,250,252,.85);
    color: rgba(15,23,42,.85);
    text-decoration:none;
    font-size: 16px;
    line-height: 1;
    transition: transform .15s ease, background .15s ease, border-color .15s ease;
  }
  .v-dd-gear:hover{
    transform: translateY(-1px);
    background: rgba(241,245,249,.95);
    border-color: rgba(15,23,42,.18);
  }

  .v-right{
    display:flex;
    align-items:center;
    gap: 10px;
  }

  /* User dropdown */
  .v-user{ position:relative; }

  .v-user-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 10px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 14px;
    color: var(--nav-ink);
    border: 1px solid rgba(15,23,42,.12);
    background:#fff;
    cursor:pointer;
  }

  .v-user-dd{
    position:absolute;
    right:0;
    top: calc(100% + 10px);
    min-width: 220px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 16px;
    box-shadow: var(--nav-shadow);
    padding: 8px;
    display:none;
  }

  .v-user.open .v-user-dd{ display:block; }

  .v-user-dd a, .v-user-dd button{
    width:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding: 10px 10px;
    border-radius: 12px;
    font-weight: 850;
    font-size: 14px;
    text-decoration:none;
    color: var(--nav-ink);
    background: transparent;
    border:0;
    cursor:pointer;
    text-align:left;
  }
  .v-user-dd a:hover, .v-user-dd button:hover{
    background: rgba(248,250,252,.9);
  }

  .topbrand{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color: inherit;
  }
  .topbrand-logo{
    height: 26px;
    width: auto;
    display:block;
    object-fit: contain;
  }
  .topbrand-text{
    font-weight: 700;
    letter-spacing: -0.01em;
  }
</style>

<nav class="v-nav">
  <div class="v-nav-wrap">
    <a class="v-brand" href="{{ route('home') }}">
    <a href="{{ route('home') }}" class="topbrand">
      <img src="{{ asset('images/linea-italia.png') }}" alt="Línea Italia" class="topbrand-logo">
    </a>
      <span>Ventas Linea Italia
        <a href="{{ route('home') }}" class="topbrand">
      </span>
    </a>

    <div class="v-menu">
      @foreach($navRoots as $root)
        @php
          $rootSlug = \Illuminate\Support\Str::slug($root->label, '-');
          $hasKids = $root->children && $root->children->count() > 0;

          // ✅ Root NO clickeable si tiene hijos (solo dropdown)
          $rootHref = $hasKids
            ? 'javascript:void(0)'
            : ($root->url ?: route('menu.section', $rootSlug));

          // ✅ NUEVO: detectar "Lista de precios" por label (case-insensitive)
          $isPriceList = mb_strtolower(trim($root->label)) === mb_strtolower('Lista de precios');

          // ✅ Solo admins ven engrane
          $canEditPriceList = $isPriceList && $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
        @endphp

        <div class="v-item">
          <a class="v-link" href="{{ $rootHref }}" @if($hasKids) onclick="event.preventDefault();" @endif>
            {{ $root->label }}
            @if($hasKids)<span class="v-caret">▾</span>@endif
          </a>

          @if($hasKids)
            <div class="v-dd">
              @foreach($root->children as $child)
                @php
                  // Si el child tiene url (ej: pdfs/xxx.pdf) usamos eso; si no, route normal
                  $childHref = $child->url
                    ? asset(ltrim($child->url, '/'))
                    : route('menu.section', [$rootSlug, \Illuminate\Support\Str::slug($child->label, '-')]);
                @endphp
                <a href="{{ $childHref }}">
                  <span>{{ $child->label }}</span>
                  <small>Ver</small>
                </a>
              @endforeach

              {{-- ✅ NUEVO: engrane abajo derecha SOLO para Lista de precios y SOLO admin --}}
              @if($canEditPriceList)
                <div class="v-dd-tools">
                  <a
                    class="v-dd-gear"
                    href="{{ route('admin.price-list-pdfs.index') }}"
                    title="Editar PDFs de Lista de precios"
                    aria-label="Editar PDFs de Lista de precios"
                  >⚙️</a>
                </div>
              @endif
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <div class="v-right">
      <div class="v-user" id="vUser">
        <button class="v-user-btn" type="button" onclick="toggleUserDd()">
          {{ $user?->name ?? 'Usuario' }}
          <span class="v-caret">▾</span>
        </button>

        <div class="v-user-dd" id="vUserDd">
          <a href="{{ route('home') }}">Dashboard <small>Inicio</small></a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar sesión <small>Salir</small></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  function toggleUserDd(){
    const wrap = document.getElementById('vUser');
    wrap.classList.toggle('open');
  }
  document.addEventListener('click', (e) => {
    const wrap = document.getElementById('vUser');
    if (!wrap) return;
    if (!wrap.contains(e.target)) wrap.classList.remove('open');
  });
</script>
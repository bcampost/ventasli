{{-- resources/views/layouts/navigation.blade.php --}}
@php
  use App\Models\MenuNode;
  use Illuminate\Support\Str;

  $navRoots = MenuNode::query()
    ->active()
    ->where(function($q){
      $q->whereNull('parent_id')->orWhere('parent_id', 0);
    })
    ->orderBy('sort')->orderBy('label')
    ->with([
      'children' => function($q){
        $q->active()->orderBy('sort')->orderBy('label');
      },
      // ✅ para "Productos" (nivel 3)
      'children.children' => function($q){
        $q->active()->orderBy('sort')->orderBy('label');
      }
    ])
    ->get();

  $user = auth()->user();
  $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');

  // Helper: resuelve href (url si existe, si no route menu.section)
  $hrefFor = function(string $rootSlug, $node, array $pathParts = []) {
    $u = trim((string)($node->url ?? ''));

    // 1) Si hay URL explícita (pdf/external/archivo)
    if ($u !== '') {
      if (Str::startsWith($u, ['http://','https://'])) return $u;
      return asset(ltrim($u, '/'));
    }

    // 2) Si no hay url, navega al menú (ruta dinámica)
    $path = implode('/', array_filter($pathParts));
    return $path !== ''
      ? route('menu.section', [$rootSlug, $path])
      : route('menu.section', $rootSlug);
  };
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
    max-width: 1580px;
    margin: 0 auto;
    padding: 10px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 16px;
  }

  /* ✅ Brand (sin <a> anidados) */
  .topbrand{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color: inherit;
    user-select:none;
  }
  .topbrand-logo{
    height: 26px;
    width: auto;
    display:block;
    object-fit: contain;
  }
  .topbrand-text{
    font-weight: 800;
    letter-spacing: -0.01em;
    color: var(--nav-ink);
    white-space: nowrap;
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

  /* ✅ puente invisible para hover */
  .v-item::after{
    content:"";
    position:absolute;
    left:0;
    right:0;
    top:100%;
    height: 14px;
  }

  /* Dropdown base (nivel 2) */
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

  .v-item:hover > .v-dd,
  .v-item:focus-within > .v-dd{
    display:block;
  }

  /* Links dentro */
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
    white-space: nowrap;
  }
  .v-dd a:hover{ background: rgba(248,250,252,.9); }

  .v-dd small{
    color: var(--nav-muted);
    font-weight: 700;
  }

  /* ✅ Para submenú (nivel 3) SOLO en Productos */
  .v-dd-item{ position: relative; }

  /* ✅ Puente invisible horizontal para ir al submenú (nivel 3) sin que se cierre */
  .v-dd-item.has-kids::after{
    content:"";
    position:absolute;
    top: 0;
    bottom: 0;
    right: -12px;
    width: 12px;
  }

  .v-dd-item.has-kids > .v-dd-sub{ display:none; }

  .v-dd-sub{
    position:absolute;
    top: -8px;
    left: calc(100% + 10px);
    min-width: 240px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 16px;
    box-shadow: var(--nav-shadow);
    padding: 8px;
    z-index: 90;
  }

  .v-dd-item.has-kids:hover > .v-dd-sub,
  .v-dd-item.has-kids:focus-within > .v-dd-sub{
    display:block;
  }

  .v-dd-arrow{
    font-size: 12px;
    color: rgba(15,23,42,.55);
    font-weight: 900;
    margin-left: 8px;
  }

  /* ✅ NUEVO: contenedor engranes abajo del dropdown */
  .v-dd-tools{
    display:flex;
    justify-content:flex-end;
    gap: 8px;
    padding-top: 8px;
    margin-top: 8px;
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

  /* ✅ Search */
  .v-search{
    width: 330px;
    max-width: 42vw;
  }
  .v-search input{
    width:100%;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 700;
    color: var(--nav-ink);
    outline: none;
    transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
  }
  .v-search input:focus{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 0 0 6px rgba(37,99,235,.14);
    background: #fff;
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
    z-index: 120;
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
</style>

<nav class="v-nav">
  <div class="v-nav-wrap">

    <a href="{{ route('home') }}" class="topbrand">
      <img src="{{ asset('images/linea-italia.png') }}" alt="Línea Italia" class="topbrand-logo">
      <span class="topbrand-text">Ventas</span>
    </a>

    <div class="v-menu">
      @foreach($navRoots as $root)
        @php
          $rootSlug = Str::slug($root->label, '-');
          $hasKids = $root->children && $root->children->count() > 0;

          // Root NO clickeable si tiene hijos
          $rootHref = $hasKids
            ? 'javascript:void(0)'
            : $hrefFor($rootSlug, $root, []);

          // Identificadores especiales
          $isPriceList = mb_strtolower(trim($root->label)) === mb_strtolower('Lista de precios');
          $isProducts  = mb_strtolower(trim($root->label)) === mb_strtolower('Productos');

          // ✅ engranes visibles si admin y hay dropdown
          $canShowTools = $isAdmin && $hasKids;

          // Rutas (proteger con Route::has para no romper si cambia el nombre)
          $hasManageRoute = \Illuminate\Support\Facades\Route::has('admin.menu.manage');
          $hasPricePdfsRoute = \Illuminate\Support\Facades\Route::has('admin.price-list-pdfs.index');
        @endphp

        <div class="v-item">
          <a class="v-link" href="{{ $rootHref }}" @if($hasKids) onclick="event.preventDefault();" @endif>
            {{ $root->label }}
            @if($hasKids)<span class="v-caret">▾</span>@endif
          </a>

          @if($hasKids)
            <div class="v-dd">
              {{-- ✅ Root normal: solo nivel 2 --}}
              @if(!$isProducts)
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childHref = $hrefFor($rootSlug, $child, [$childSlug]);
                  @endphp

                  <a href="{{ $childHref }}">
                    <span>{{ $child->label }}</span>
                    <small>Ver</small>
                  </a>
                @endforeach
              @else
                {{-- ✅ SOLO en Productos: nivel 2 + nivel 3 (flyout) --}}
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childHasKids = $child->children && $child->children->count() > 0;

                    // ✅ SIEMPRE clickeable (aunque tenga hijos) para ir a su página
                    $childHref = $hrefFor($rootSlug, $child, [$childSlug]);
                  @endphp

                  <div class="v-dd-item {{ $childHasKids ? 'has-kids' : '' }}">
                    <a href="{{ $childHref }}">
                      <span>{{ $child->label }}</span>
                      @if($childHasKids)
                        <span class="v-dd-arrow">▸</span>
                      @else
                        <small>Ver</small>
                      @endif
                    </a>

                    @if($childHasKids)
                      <div class="v-dd v-dd-sub">
                        @foreach($child->children as $g)
                          @php
                            $gSlug = Str::slug($g->label, '-');
                            $gHref = $hrefFor($rootSlug, $g, [$childSlug, $gSlug]);
                          @endphp
                          <a href="{{ $gHref }}">
                            <span>{{ $g->label }}</span>
                            <small>Ver</small>
                          </a>
                        @endforeach
                      </div>
                    @endif
                  </div>
                @endforeach
              @endif

              {{-- ✅ Engranes abajo del dropdown (ADMIN) --}}
              @if($canShowTools)
                <div class="v-dd-tools">
                  {{-- Editar este menú (manage del root) --}}
                  @if($hasManageRoute)
                    <a
                      class="v-dd-gear"
                      href="{{ route('admin.menu.manage', $root) }}"
                      title="Editar este menú"
                      aria-label="Editar este menú"
                    >⚙️</a>
                  @endif

                  {{-- Extra: PDFs lista de precios --}}
                  @if($isPriceList && $hasPricePdfsRoute)
                    <a
                      class="v-dd-gear"
                      href="{{ route('admin.price-list-pdfs.index') }}"
                      title="Editar PDFs de Lista de precios"
                      aria-label="Editar PDFs de Lista de precios"
                    >📄</a>
                  @endif
                </div>
              @endif

            </div>
          @endif
        </div>
      @endforeach
    </div>

    <div class="v-right">
      {{-- ✅ buscador siempre visible --}}
      <div class="v-search">
        <form method="GET" action="{{ route('search.global') }}">
          <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Buscar en todo..."
            autocomplete="off"
          >
        </form>
      </div>

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
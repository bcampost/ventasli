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
      'children.children' => function($q){
        $q->active()->orderBy('sort')->orderBy('label');
      }
    ])
    ->get();

  $user = auth()->user();
  $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');

  // ✅ Si hay URL (pdf/external/archivo), resuelve href para archivo
  $fileHref = function($node) {
    $u = trim((string)($node->url ?? ''));
    if ($u === '') return null;
    if (Str::startsWith($u, ['http://','https://'])) return $u;
    return asset(ltrim($u, '/'));
  };

  // ✅ URL normal del menú (/menu/...)
  $menuHref = function(string $rootSlug, array $pathParts = []) {
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

  .topbrand{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color: inherit;
    user-select:none;
  }
  .topbrand-logo{ height: 26px; width: auto; display:block; object-fit: contain; }
  .topbrand-text{ font-weight: 800; letter-spacing: -0.01em; color: var(--nav-ink); white-space: nowrap; }

  .v-menu{ display:flex; align-items:center; gap: 6px; flex-wrap: wrap; }
  .v-item{ position: relative; }

  .v-link{
    display:inline-flex; align-items:center; gap:8px;
    padding: 10px 12px; border-radius: 999px;
    font-weight: 800; font-size: 14px;
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
  .v-caret{ font-size: 12px; color: rgba(15,23,42,.55); margin-left: 2px; }

  /* ✅ puente vertical bajo el root */
  .v-item::after{
    content:"";
    position:absolute;
    left:0;
    right:0;
    top:100%;
    height: 14px;
    pointer-events: auto;
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
  .v-item:hover > .v-dd,
  .v-item:focus-within > .v-dd{
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
    white-space: nowrap;
    position: relative;
    z-index: 5;
  }
  .v-dd a:hover{ background: rgba(248,250,252,.9); }
  .v-dd small{ color: var(--nav-muted); font-weight: 700; }

  /* Submenú Productos (nivel 3) */
  .v-dd-item{ position: relative; }

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
    display:none;
  }

  .v-dd-item.has-kids:hover > .v-dd-sub,
  .v-dd-item.has-kids:focus-within > .v-dd-sub{
    display:block;
  }

  /* ✅ puente en el GAP (10px) para cruzar al submenú sin cerrarlo */
  .v-dd-sub::before{
    content:"";
    position:absolute;
    top: 0;
    bottom: 0;
    left: -10px;
    width: 10px;
    pointer-events: auto;
    background: transparent;
    z-index: 1;
  }

  .v-dd-arrow{
    font-size: 12px;
    color: rgba(15,23,42,.55);
    font-weight: 900;
    margin-left: 8px;
  }

  /* Engranes */
  .v-dd-tools{
    display:flex;
    justify-content:flex-end;
    gap: 10px;
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

  .v-right{ display:flex; align-items:center; gap: 10px; }
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
          $hasKids  = $root->children && $root->children->count() > 0;

          $rootFile = $fileHref($root);

          // ✅ REGLA:
          // - si hay PDF/url: abre PDF
          // - si NO hay PDF: navega a su página del menú (aunque tenga hijos)
          $rootHref = $rootFile ?: $menuHref($rootSlug, []);
          $rootBlank = (bool)$rootFile;

          $isPriceList = mb_strtolower(trim($root->label)) === mb_strtolower('Lista de precios');
          $isProducts  = mb_strtolower(trim($root->label)) === mb_strtolower('Productos');

          $canEditPriceList = $isAdmin && $isPriceList;
          $canEditMenuThis  = $isAdmin;
        @endphp

        <div class="v-item">
          <a
            class="v-link"
            href="{{ $rootHref }}"
            @if($rootBlank) target="_blank" rel="noopener noreferrer" @endif
          >
            {{ $root->label }}
            @if($hasKids)<span class="v-caret">▾</span>@endif
          </a>

          @if($hasKids)
            <div class="v-dd">

              {{-- Root normal: solo nivel 2 --}}
              @if(!$isProducts)
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childFile = $fileHref($child);

                    // ✅ si hay PDF: abre PDF; si no: navega a su nivel
                    $childHref = $childFile ?: $menuHref($rootSlug, [$childSlug]);
                    $childBlank = (bool)$childFile;
                  @endphp

                  <a
                    href="{{ $childHref }}"
                    @if($childBlank) target="_blank" rel="noopener noreferrer" @endif
                  >
                    <span>{{ $child->label }}</span>
                    <small>{{ $childFile ? 'PDF' : 'Ver' }}</small>
                  </a>
                @endforeach
              @else
                {{-- SOLO en Productos: nivel 2 + nivel 3 --}}
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childHasKids = $child->children && $child->children->count() > 0;

                    $childFile = $fileHref($child);

                    // ✅ CLAVE: aunque tenga hijos, si NO hay PDF debe navegar
                    $childHref = $childFile ?: $menuHref($rootSlug, [$childSlug]);
                    $childBlank = (bool)$childFile;
                  @endphp

                  <div class="v-dd-item {{ $childHasKids ? 'has-kids' : '' }}">
                    <a
                      href="{{ $childHref }}"
                      @if($childBlank) target="_blank" rel="noopener noreferrer" @endif
                    >
                      <span>{{ $child->label }}</span>
                      @if($childHasKids)
                        <span class="v-dd-arrow">▸</span>
                      @else
                        <small>{{ $childFile ? 'PDF' : 'Ver' }}</small>
                      @endif
                    </a>

                    @if($childHasKids)
                      <div class="v-dd v-dd-sub">
                        @foreach($child->children as $g)
                          @php
                            $gSlug = Str::slug($g->label, '-');
                            $gHasKids = $g->children && $g->children->count() > 0;

                            $gFile = $fileHref($g);

                            // ✅ si hay PDF: abre PDF; si no: navega al nivel
                            $gHref = $gFile ?: $menuHref($rootSlug, [$childSlug, $gSlug]);
                            $gBlank = (bool)$gFile;
                          @endphp

                          <a
                            href="{{ $gHref }}"
                            @if($gBlank) target="_blank" rel="noopener noreferrer" @endif
                          >
                            <span>{{ $g->label }}</span>
                            <small>{{ $gFile ? 'PDF' : 'Ver' }}</small>
                          </a>
                        @endforeach
                      </div>
                    @endif
                  </div>
                @endforeach
              @endif

              {{-- ENGRANES (admin) --}}
              @if($canEditMenuThis || $canEditPriceList)
                <div class="v-dd-tools">

                  @if($canEditMenuThis)
                    <a
                      class="v-dd-gear"
                      href="{{ route('admin.menu.manage', $root) }}"
                      title="Editar este menú"
                      aria-label="Editar este menú"
                    >⚙️</a>
                  @endif

                  @if($canEditPriceList)
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
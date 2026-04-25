{{-- resources/views/layouts/navigation.blade.php --}}
@php
  use App\Models\MenuNode;
  use Illuminate\Support\Str;
  use App\Models\QuickLink;

  $user = auth()->user();
  $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');

  $quickLinks = QuickLink::where('is_active', true)
    ->when(!$isAdmin, function ($q) {
      $q->whereRaw('TRIM(LOWER(name)) != ?', ['permisos']);
    })
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get();

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

  $hrefFor = function(string $rootSlug, $node, array $pathParts = []) {
    $u = trim((string)($node->url ?? ''));

    if ($u !== '') {
      if (Str::startsWith($u, ['http://','https://'])) return $u;
      return asset(ltrim($u, '/'));
    }

    $path = implode('/', array_filter($pathParts));
    return $path !== ''
      ? route('menu.section', [$rootSlug, $path])
      : route('menu.section', $rootSlug);
  };

  $isPdfUrl = function(?string $url) {
    $u = trim((string) $url);
    return $u !== '' && \Illuminate\Support\Str::endsWith(mb_strtolower($u), '.pdf');
  };
@endphp

<style>
:root{
  --nav-bg: rgba(255,255,255,.96);
  --nav-line: rgba(15,23,42,.08);
  --nav-ink: #111827;
  --nav-muted: rgba(17,24,39,.55);
  --nav-shadow: 0 18px 46px rgba(2,6,23,.08);
  --nav-r: 18px;
  --nav-primary: #2563eb;
  --nav-height: 58px;
}
body{
  padding-top: var(--nav-height);
}

.v-nav{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  background: var(--nav-bg);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);

  border-bottom: 1px solid var(--nav-line);
  box-shadow: 0 10px 24px rgba(15,23,42,.06);

  transition: all .25s ease;
}

/* Estado al hacer scroll */
.v-nav.scrolled{
  background: rgba(255,255,255,.92);
  box-shadow: 0 18px 40px rgba(15,23,42,.12);
}

/* 🔽 hace el navbar más compacto */
.v-nav.scrolled .v-nav-wrap{
  min-height: 58px;
  padding: 6px 22px;
}

/* 🔽 logo más chico */
.v-nav.scrolled .topbrand-logo{
  height: 28px;
}

/* 🔽 texto ligeramente más compacto */
.v-nav.scrolled .v-link{
  font-size: 13px;
}

.v-nav-wrap{
  max-width: 1580px;
  margin: 0 auto;
  padding: 10px 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 22px;
  min-height: 72px;
  position: relative;
}



/* ✅ Brand */
.topbrand{
  display:flex;
  align-items:center;
  gap:12px;
  text-decoration:none;
  color: inherit;
  user-select:none;
  flex: 0 0 auto;
}
.topbrand-logo{
  height: 34px;
  width: auto;
  display:block;
  object-fit: contain;
}
.topbrand-text{
  font-weight: 500;
  font-size: 15px;
  letter-spacing: -.01em;
  color: var(--nav-ink);
  white-space: nowrap;
}

/* ✅ Menú superior más premium */
.v-menu{
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 18px;
  flex: 1 1 auto;
  min-width: 0;
}

.v-item{
  position: relative;
  flex: 0 0 auto;
}

.v-link{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding: 8px 2px;
  font-weight: 400;
  font-size: 14px;
  letter-spacing: -.01em;
  color: var(--nav-ink);
  text-decoration:none;
  border: 0;
  border-radius: 0;
  background: transparent;
  position: relative;
  transition: color .18s ease, opacity .18s ease;
}

.v-link:hover{
  background: transparent;
  border-color: transparent;
  transform: none;
  color: #000;
}

.v-link::after{
  content:"";
  position:absolute;
  left:0;
  right:0;
  bottom:-10px;
  height:2px;
  border-radius:999px;
  background: #111827;
  transform: scaleX(0);
  transform-origin:center;
  transition: transform .2s ease;
  opacity:.9;
}

.v-item:hover > .v-link::after,
.v-item:focus-within > .v-link::after{
  transform: scaleX(1);
}

.v-caret{
  font-size: 10px;
  color: rgba(15,23,42,.48);
  margin-left: 2px;
  transform: translateY(1px);
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
  margin-top: 14px;
  min-width: 260px;
  background: #fff;
  border: 1px solid rgba(15,23,42,.08);
  border-radius: 18px;
  box-shadow: var(--nav-shadow);
  padding: 10px;
  display:none;
  z-index: 80;
}

.v-item.open > .v-dd{
  display:block;
}

.v-dd a{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  padding: 11px 12px;
  border-radius: 12px;
  font-weight: 400;
  font-size: 14px;
  text-decoration:none;
  color: var(--nav-ink);
  white-space: nowrap;
}
.v-dd a:hover{
  background: rgba(248,250,252,.92);
}

.v-dd small{
  color: var(--nav-muted);
  font-weight: 400;
}

/* ✅ Para submenú (nivel 3) SOLO en Productos */
.v-dd-item{ position: relative; }

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
  top: -10px;
  left: calc(100% + 10px);
  min-width: 250px;
  background: #fff;
  border: 1px solid rgba(15,23,42,.08);
  border-radius: 18px;
  box-shadow: var(--nav-shadow);
  padding: 10px;
  z-index: 90;
}
.v-dd-toggle{
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  box-sizing: border-box;
  padding: 11px 12px;
  border-radius: 12px;
  text-decoration: none;
  color: var(--nav-ink);
  background: transparent;
  border: 0;
  font: inherit;
  text-align: left;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
}

.v-dd-toggle:hover{
  background: rgba(248,250,252,.92);
}

.v-dd-item.open > .v-dd-sub{
  display:block;
}

.v-dd-item.open > .v-dd-toggle .v-dd-arrow{
  transform: rotate(90deg);
}

.v-dd-arrow{
  font-size: 11px;
  color: rgba(15,23,42,.55);
  font-weight: 900;
  margin-left: 8px;
}

/* ✅ contenedor engranes abajo del dropdown */
.v-dd-tools{
  display:flex;
  justify-content:flex-end;
  gap: 8px;
  padding-top: 8px;
  margin-top: 8px;
  border-top: 1px solid rgba(15,23,42,.06);
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
  gap: 12px;
  flex: 0 0 auto;
}

/* ✅ Search */
.v-search{
  width: 300px;
  max-width: 28vw;
}
.v-search input{
  width:100%;
  border-radius: 999px;
  border: 1px solid rgba(15,23,42,.10);
  background: #fff;
  padding: 11px 16px;
  font-size: 14px;
  font-weight: 500;
  color: var(--nav-ink);
  outline: none;
  transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
}
.v-search input::placeholder{
  color: rgba(15,23,42,.45);
  font-weight: 500;
}
.v-search input:focus{
  border-color: rgba(17,24,39,.18);
  box-shadow: 0 0 0 5px rgba(15,23,42,.06);
  background: #fff;
}

/* User dropdown */
.v-user{ position:relative; }

.v-user-btn{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding: 10px 16px;
  border-radius: 999px;
  font-weight: 400;
  font-size: 14px;
  color: var(--nav-ink);
  border: 1px solid rgba(15,23,42,.10);
  background:#fff;
  cursor:pointer;
  letter-spacing: -.01em;
  box-shadow: 0 2px 10px rgba(15,23,42,.04);
}

.v-user-dd{
  position:absolute;
  right:0;
  top: calc(100% + 12px);
  min-width: 220px;
  background: #fff;
  border: 1px solid rgba(15,23,42,.08);
  border-radius: 18px;
  box-shadow: var(--nav-shadow);
  padding: 10px;
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
  font-weight: 400;
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

.v-burger{
  display: none;
  width: 44px;
  height: 44px;
  border: 1px solid rgba(15,23,42,.10);
  border-radius: 12px;
  background: #fff;
  color: var(--nav-ink);
  font-size: 20px;
  line-height: 1;
  cursor: pointer;
  align-items: center;
  justify-content: center;
}

.v-mobile-panel{
  display: contents;
}

.v-mobile-links{
  display: none;
}
/*Media queries*/ 
@media(min-width: 1300px){
 :root{
    --nav-height: 58px !important;
  }
}
@media(max-width: 1400px) { 

  .v-search{
    width: 200px;
    max-width: 28vw;
  }

  .v-user-dd-btn, .v-user-dd a, .v-user-dd button{
    min-width: 180px;
    font-weight: 200;
    font-size: 12px;
  }
}
@media(max-width: 1300px) {
  :root{
    --nav-height: 169px !important;
  }
  .v-nav-wrap{
    flex-direction: column;
  }

  .v-menu{   
    flex-wrap: wrap;
  }  
}
/*Media queries*/ 
@media(max-width: 1000px) {
  :root{
    --nav-height: 169px;
  }
  .v-dd{
    min-width: 200px;
  }
  
}

@media (max-width: 900px) {
  .v-dd{
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
  }
}


.v-mobile-links{
  display: none;
}

@media (max-width: 768px){
  :root{
    --nav-height: 126px !important;
  }

  .v-item::after,
  .v-dd-item.has-kids::after{
    content: none !important;
    display: none !important;
  }
  .v-nav-wrap{
    display: grid;
    grid-template-columns: 44px 1fr auto;
    grid-template-areas:
      "brand brand user"
      "burger search search"
      "panel panel panel";
    align-items: center;
    gap: 10px 12px;
  }

  .v-burger{
    grid-area: burger;
    display: inline-flex;
    width: 44px;
    height: 44px;
    align-items: center;
    justify-content: center;
  }

  .topbrand{
    grid-area: brand;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    min-width: 0;
  }

  .v-user{
    grid-area: user;
    width: auto;
    justify-self: end;
  }

  .v-search{
    grid-area: search;
    width: 100%;
    max-width: 100%;
  }

  .v-search form,
  .v-search input{
    width: 100%;
  }

  .v-right{
    display: contents;
  }

  .v-mobile-panel{
    grid-area: panel;
    display: none;
    width: 100%;
    flex-direction: column;
    align-items: stretch;
    gap: 14px;
    padding-top: 4px;
  }

  .v-menu,
  .v-mobile-links{
    width: 100%;
  }

  .v-menu{
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }

  .v-item{
    width: 100%;
    display: block;
  }

  .v-link{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 4px;
    border-bottom: 1px solid rgba(15,23,42,.06);
    background: transparent;
  }

  .v-link::after,
  .v-item::after{
    display: none;
  }

  .v-item::after,
  .v-dd-item.has-kids::after{
    content: none !important;
    display: none !important;
    pointer-events: none !important;
  }

  .v-dd-item{
    position: relative;
  }

  .v-dd-toggle{
    position: relative;
    z-index: 5;
  }

  .v-dd,
  .v-dd-sub{
    position: static !important;
    left: auto !important;
    right: auto !important;
    top: auto !important;
    transform: none !important;
    display: none;
    width: 100%;
    min-width: 100%;
    margin-top: 8px;
    padding: 8px;
    border-radius: 14px;
    box-shadow: none;
  }
  .v-dd{
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
  }

  .v-dd-sub{
    background: #f8fafc;
    border: 1px solid rgba(15,23,42,.06);
  }

  /* desactiva completamente hover/focus de desktop en móvil */
  .v-item:hover > .v-dd,
  .v-item:focus-within > .v-dd,
  .v-dd-item.has-kids:hover > .v-dd-sub,
  .v-dd-item.has-kids:focus-within > .v-dd-sub{
    display: none;
  }

  .v-item.open > .v-dd{
    display: block !important;
  }

  .v-dd-item.open > .v-dd-sub{
    display: block !important;
  }

  .v-dd-item{
    width: 100%;
    display: block;
  }

  .v-dd-item > a,
  .v-dd-toggle{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    box-sizing: border-box;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    color: var(--nav-ink);
    background: transparent;
    border: 0;
    font: inherit;
    text-align: left;
  }

  .v-dd-toggle{
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
  }

  .v-dd-toggle:hover,
  .v-dd-item > a:hover{
    background: rgba(15,23,42,.04);
  }

  .v-dd-arrow{
    pointer-events: none;
    transition: transform .2s ease;
  }

  .v-dd-item.open > .v-dd-toggle .v-dd-arrow{
    transform: rotate(90deg);
  }
  .v-link .v-caret,
  .v-dd-toggle .v-dd-arrow,
  .v-mobile-links-toggle .v-caret{
    transition: transform .2s ease;
    pointer-events: none;
  }

  .v-item.open > .v-link .v-caret{
    transform: rotate(180deg);
  }

  .v-dd-item.open > .v-dd-toggle .v-dd-arrow{
    transform: rotate(90deg);
  }

  /* Accesos */
  .v-mobile-links{
    display: block;
    width: 100%;
    padding-top: 10px;
  }

  .v-mobile-links-group{
    width: 100%;
  }

  .v-mobile-links-toggle{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 4px;
    background: transparent;
    border: 0;
    border-bottom: 1px solid rgba(15,23,42,.06);
    color: var(--nav-ink);
    font: inherit;
    cursor: pointer;
    text-align: left;
  }

  .v-mobile-links-list{
    display: none;
    margin-top: 8px;
    width: 100%;
    flex-direction: column;
    gap: 8px;
    align-items: stretch;
  }

  .v-mobile-links-group.open .v-mobile-links-list{
    display: flex;
  }

  .v-mobile-links-group.open .v-caret{
    transform: rotate(180deg);
  }

  .v-mobile-link{
    width: 100%;
    max-width: 100%;
    flex: none;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    text-decoration: none;
    color: var(--nav-ink);
    box-shadow: 0 2px 10px rgba(15,23,42,.04);
  }

  .v-mobile-link-icon{
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border: 1px solid rgba(15,23,42,.08);
    overflow: hidden;
  }

  .v-mobile-link-icon img{
    width: 18px;
    height: 18px;
    object-fit: contain;
    display: block;
  }

  .v-mobile-link-text{
    font-size: 13px;
    font-weight: 500;
    line-height: 1.2;
  }

  .v-mobile-link-fallback{
    font-size: 18px;
    line-height: 1;
  }
  .v-nav{
    max-height: 100dvh;
    overflow: hidden;
  }

  .v-mobile-panel.open{
    display: flex;
    max-height: calc(100dvh - 126px);
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    padding-bottom: 24px;
  }
}

</style>

<nav class="v-nav">
  <div class="v-nav-wrap">

    <button
      type="button"
      class="v-burger"
      id="vBurger"
      aria-label="Abrir menú"
      aria-expanded="false"
      onclick="toggleMobileNav()"
    >
      ☰
    </button>

    <a href="{{ route('home') }}" class="topbrand">
      <img src="{{ asset('images/linea-italia.png') }}" alt="Línea Italia" class="topbrand-logo">
      <span class="topbrand-text">Ventas</span>
    </a>

    <div class="v-mobile-panel" id="vMobilePanel">
      <div class="v-menu">
      @foreach($navRoots as $root)
        @php
          $rootSlug = Str::slug($root->label, '-');
          $hasKids = $root->children && $root->children->count() > 0;

          $rootHref = $hasKids
            ? 'javascript:void(0)'
            : $hrefFor($rootSlug, $root, []);

          $isPriceList = mb_strtolower(trim($root->label)) === mb_strtolower('Lista de precios');
          $isProducts  = mb_strtolower(trim($root->label)) === mb_strtolower('Productos');

          $canShowTools = $isAdmin && $hasKids;

          $hasManageRoute = \Illuminate\Support\Facades\Route::has('admin.menu.manage');
          $hasPricePdfsRoute = \Illuminate\Support\Facades\Route::has('admin.price-list-pdfs.index');
        @endphp

        <div class="v-item">
          <a
            class="v-link"
            href="{{ $rootHref }}"
            @if($hasKids)
              onclick="return handleMenuToggle(event, this)"
            @endif
          >
            {{ $root->label }}
            @if($hasKids)<span class="v-caret">▾</span>@endif
          </a>

          @if($hasKids)
            <div class="v-dd">
              @if(!$isProducts)
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childHref = $hrefFor($rootSlug, $child, [$childSlug]);
                  @endphp

                  <a
                    href="{{ $childHref }}"
                    @if($isPdfUrl($childHref))
                      onclick="event.preventDefault(); window.openPdfPreview(@js($childHref), @js($child->label));"
                    @endif
                  >
                    <span>{{ $child->label }}</span>
                    <small>{{ $isPdfUrl($childHref) ? 'Preview' : 'Ver' }}</small>
                  </a>
                @endforeach
              @else
                @foreach($root->children as $child)
                  @php
                    $childSlug = Str::slug($child->label, '-');
                    $childHasKids = $child->children && $child->children->count() > 0;
                    $childHref = $hrefFor($rootSlug, $child, [$childSlug]);
                  @endphp

                  <div class="v-dd-item {{ $childHasKids ? 'has-kids' : '' }}">
                    @if($childHasKids)
                      <button
                        type="button"
                        class="v-dd-toggle"
                        onclick="return handleSubmenuToggle(event, this)"
                      >
                        <span>{{ $child->label }}</span>
                        <span class="v-dd-arrow">▸</span>
                      </button>
                    @else
                      <a
                        href="{{ $childHref }}"
                        @if($isPdfUrl($childHref))
                          onclick="event.preventDefault(); window.openPdfPreview(@js($childHref), @js($child->label));"
                        @endif
                      >
                        <span>{{ $child->label }}</span>
                        <small>{{ $isPdfUrl($childHref) ? 'Preview' : 'Ver' }}</small>
                      </a>
                    @endif

                    @if($childHasKids)
                      <div class="v-dd v-dd-sub">
                        @foreach($child->children as $g)
                          @php
                            $gSlug = Str::slug($g->label, '-');
                            $gHref = $hrefFor($rootSlug, $g, [$childSlug, $gSlug]);
                          @endphp
                          <a
                            href="{{ $gHref }}"
                            @if($isPdfUrl($gHref))
                              onclick="event.preventDefault(); window.openPdfPreview(@js($gHref), @js($g->label));"
                            @endif
                          >
                            <span>{{ $g->label }}</span>
                            <small>{{ $isPdfUrl($gHref) ? 'Preview' : 'Ver' }}</small>
                          </a>
                        @endforeach
                      </div>
                    @endif
                  </div>
                @endforeach
              @endif

              @if($canShowTools)
                <div class="v-dd-tools">
                  @if($hasManageRoute)
                    <a class="v-dd-gear" href="{{ route('admin.menu.manage', $root) }}">⚙️</a>
                  @endif

                  @if($isPriceList && $hasPricePdfsRoute)
                    <a class="v-dd-gear" href="{{ route('admin.price-list-pdfs.index') }}">📄</a>
                  @endif
                </div>
              @endif
            </div>
          @endif
        </div>
      @endforeach
    </div>
      <div class="v-mobile-links">
        <div class="v-mobile-links-group" id="vMobileLinksGroup">
          <button
            type="button"
            class="v-mobile-links-toggle"
            onclick="toggleMobileLinksGroup()"
            aria-expanded="false"
            id="vMobileLinksToggle"
          >
            <span>Accesos</span>
            <span class="v-caret">▾</span>
          </button>

          <div class="v-mobile-links-list" id="vMobileLinksList">
            @foreach($quickLinks as $l)
              @php
                $href = trim($l->url);
                $finalHref = str_starts_with($href, 'http://') || str_starts_with($href, 'https://')
                  ? $href
                  : (str_starts_with($href, '/') ? url($href) : url('/'.$href));

                $ext = str_starts_with($href, 'http://') || str_starts_with($href, 'https://');
              @endphp

              <a class="v-mobile-link"
                href="{{ $finalHref }}"
                @if($ext) target="_blank" rel="noopener" @endif>
                <span class="v-mobile-link-icon">
                  @if(!empty($l->icon_path))
                    <img src="{{ asset('storage/'.$l->icon_path) }}" alt="{{ $l->name }}">
                  @else
                    <span class="v-mobile-link-fallback">•</span>
                  @endif
                </span>
                <span class="v-mobile-link-text">{{ $l->name }}</span>
              </a>
            @endforeach

            @if($isAdmin)
              <a class="v-mobile-link" href="{{ route('admin.quick-links.index') }}">
                <span class="v-mobile-link-icon">⚙️</span>
                <span class="v-mobile-link-text">Editar panel</span>
              </a>
            @endif
          </div>
        </div>
      </div>
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
    </div>

  </div>
</nav>

<script>
  function toggleUserDd(){
    const wrap = document.getElementById('vUser');
    if (!wrap) return;
    wrap.classList.toggle('open');
  }

  function toggleMobileNav(){
    const panel = document.getElementById('vMobilePanel');
    const burger = document.getElementById('vBurger');
    const linksGroup = document.getElementById('vMobileLinksGroup');
    const linksToggle = document.getElementById('vMobileLinksToggle');

    if (!panel || !burger) return;

    const isOpen = panel.classList.toggle('open');
    burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

    document.documentElement.style.setProperty(
      '--nav-height',
      isOpen ? '450px' : '126px'
    );

    if (!isOpen) {
      document.querySelectorAll('.v-item.open, .v-dd-item.open').forEach(el => {
        el.classList.remove('open');
      });

      if (linksGroup && linksToggle) {
        linksGroup.classList.remove('open');
        linksToggle.setAttribute('aria-expanded', 'false');
      }
    }
  }

  function handleMenuToggle(event, el){
    event.preventDefault();
    event.stopPropagation();

    const item = el.closest('.v-item');
    if (!item) return false;

    const wasOpen = item.classList.contains('open');

    document.querySelectorAll('.v-item.open').forEach(node => {
      if (node !== item) node.classList.remove('open');
    });

    document.querySelectorAll('.v-dd-item.open').forEach(node => {
      node.classList.remove('open');
    });

    if (wasOpen) {
      item.classList.remove('open');
      return false;
    }

    item.classList.add('open');
    return false;
  }

  function handleSubmenuToggle(event, el){
    event.preventDefault();
    event.stopPropagation();

    const item = el.closest('.v-dd-item');
    if (!item) return false;

    const wasOpen = item.classList.contains('open');
    const parent = item.parentElement;

    if (parent) {
      Array.from(parent.children).forEach(node => {
        if (node !== item && node.classList.contains('v-dd-item')) {
          node.classList.remove('open');
        }
      });
    }

    if (wasOpen) {
      item.classList.remove('open');
      return false;
    }

    item.classList.add('open');
    return false;
  }

  document.addEventListener('click', (e) => {
    const userWrap = document.getElementById('vUser');
    const burger = document.getElementById('vBurger');
    const panel = document.getElementById('vMobilePanel');
    const linksGroup = document.getElementById('vMobileLinksGroup');
    const linksToggle = document.getElementById('vMobileLinksToggle');
    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    if (userWrap && !userWrap.contains(e.target)) {
      userWrap.classList.remove('open');
    }

    document.querySelectorAll('.v-item').forEach(item => {
      if (!item.contains(e.target)) {
        item.classList.remove('open');
      }
    });

    document.querySelectorAll('.v-dd-item').forEach(item => {
      if (!item.contains(e.target)) {
        item.classList.remove('open');
      }
    });

    if (
      isMobile &&
      panel &&
      burger &&
      !panel.contains(e.target) &&
      !burger.contains(e.target)
    ) {
      panel.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      document.documentElement.style.setProperty('--nav-height', '126px');

      document.querySelectorAll('.v-item.open, .v-dd-item.open').forEach(el => {
        el.classList.remove('open');
      });

      if (linksGroup && linksToggle) {
        linksGroup.classList.remove('open');
        linksToggle.setAttribute('aria-expanded', 'false');
      }
    }
  });

  window.addEventListener('resize', () => {
    const panel = document.getElementById('vMobilePanel');
    const burger = document.getElementById('vBurger');
    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    if (!panel || !burger) return;

    if (!isMobile) {
      panel.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      document.documentElement.style.setProperty('--nav-height', '126px');

      document.querySelectorAll('.v-item.open, .v-dd-item.open').forEach(el => {
        el.classList.remove('open');
      });
    }
  });

  function toggleMobileLinksGroup(){
    const group = document.getElementById('vMobileLinksGroup');
    const btn = document.getElementById('vMobileLinksToggle');

    if (!group || !btn) return;

    group.classList.toggle('open');
    btn.setAttribute(
      'aria-expanded',
      group.classList.contains('open') ? 'true' : 'false'
    );
  }
</script>
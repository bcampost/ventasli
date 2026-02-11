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

  .v-item{
    position: relative;
  }

  /* Root: NO navegar, solo hover */
  .v-root-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 10px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 14px;
    color: var(--nav-ink);
    background: transparent;
    border: 1px solid transparent;
    cursor: default;
    user-select:none;
    transition: background .15s ease, border-color .15s ease, transform .15s ease;
  }
  .v-root-btn:hover{
    background: rgba(248,250,252,.85);
    border-color: rgba(15,23,42,.12);
    transform: translateY(-1px);
  }

  .v-caret{
    font-size: 12px;
    color: rgba(15,23,42,.55);
    margin-left: 2px;
  }

  .v-dd{
    position:absolute;
    left:0;
    top: calc(100% + 10px);
    min-width: 240px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 16px;
    box-shadow: var(--nav-shadow);
    padding: 8px;
    display:none;
  }

  /* PUENTE invisible para que no se cierre por el gap */
  .v-dd::before{
    content:"";
    position:absolute;
    left:0;
    top:-10px;
    width:100%;
    height:10px;
  }

  /* ✅ Mantener abierto mientras hover esté en item O dropdown */
  .v-item:hover .v-dd,
  .v-dd:hover{
    display:block;
  }

  .v-dd a{
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
  }
  .v-dd a:hover{
    background: rgba(248,250,252,.9);
  }
  .v-dd small{
    color: var(--nav-muted);
    font-weight: 700;
  }

  .v-right{
    display:flex;
    align-items:center;
    gap: 10px;
  }

  /* User dropdown */
  .v-user{
    position:relative;
  }
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
  .v-user-dd::before{
    content:"";
    position:absolute;
    right:0;
    top:-10px;
    width:100%;
    height:10px;
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
    <a class="v-brand" href="{{ route('home') }}">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
        <path d="M12 2l8 4v6c0 5-3 9-8 10C7 21 4 17 4 12V6l8-4z" stroke="currentColor" stroke-width="1.6"/>
        <path d="M8 12l2.2 2.2L16 8.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span>ventasli</span>
    </a>

    {{-- Menú superior dinámico (solo hover) --}}
    <div class="v-menu">
      @foreach($navRoots as $root)
        @php
          $rootSlug = \Illuminate\Support\Str::slug($root->label, '-');
          $hasKids = $root->children && $root->children->count() > 0;
        @endphp

        <div class="v-item">
          <div class="v-root-btn">
            {{ $root->label }}
            @if($hasKids)<span class="v-caret">▾</span>@endif
          </div>

          @if($hasKids)
            <div class="v-dd">
              @foreach($root->children as $child)
                @php
                  $childHref = route('menu.section', [$rootSlug, \Illuminate\Support\Str::slug($child->label, '-')]);
                @endphp
                <a href="{{ $childHref }}">
                  <span>{{ $child->label }}</span>
                  <small>Abrir</small>
                </a>
              @endforeach
            </div>
          @endif
        </div>
      @endforeach
    </div>

    {{-- User dropdown --}}
    <div class="v-right">
      <div class="v-user" id="vUser">
        <button class="v-user-btn" type="button" onclick="toggleUserDd()">
          {{ $user?->name ?? 'Usuario' }}
          <span class="v-caret">▾</span>
        </button>

        <div class="v-user-dd" id="vUserDd">
          @role('admin')
            <a href="{{ route('admin.menu.index') }}">Administrar menú <small>Admin</small></a>
            <a href="{{ route('admin.menu-cards.index') }}">Editar cards <small>Admin</small></a>
          @endrole

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
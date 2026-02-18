{{-- resources/views/layouts/side-menu.blade.php --}}
@php
  // sideMenu llega desde MenuController (si no llega, fallback vacío)
  $sideMenu = $sideMenu ?? [];
@endphp

<style>
  .v-side{
    position: fixed;
    right: 22px;
    top: 140px;
    z-index: 70;
    display:flex;
    flex-direction:column;
    gap: 12px;
  }
  .v-side-btn{
    width: 44px; height: 44px;
    border-radius: 999px;
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(15,23,42,.12);
    box-shadow: 0 10px 26px rgba(2,6,23,.10);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer;
    transition: transform .15s ease, box-shadow .15s ease;
    position: relative;
  }
  .v-side-btn:hover{ transform: translateY(-1px); box-shadow: 0 14px 34px rgba(2,6,23,.14); }

  /* Flyout */
  .v-fly{
    position:absolute;
    right: calc(100% + 14px);
    top: 50%;
    transform: translateY(-50%);
    min-width: 260px;
    background:#fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 18px;
    box-shadow: 0 18px 50px rgba(2,6,23,.18);
    padding: 10px;
    display:none;
  }

  .v-side-item{ position: relative; }
  .v-side-item:hover > .v-fly{ display:block; }

  .v-fly a{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding: 10px 10px;
    border-radius: 14px;
    text-decoration:none;
    color: #0b1220;
    font-weight: 800;
    font-size: 14px;
  }
  .v-fly a:hover{ background: rgba(248,250,252,.9); }

  .v-fly small{ color: rgba(15,23,42,.60); font-weight:700; }

  /* Segundo nivel */
  .v-subfly{
    position:absolute;
    left: calc(100% + 10px);
    top: 0;
    min-width: 260px;
    background:#fff;
    border: 1px solid rgba(15,23,42,.12);
    border-radius: 18px;
    box-shadow: 0 18px 50px rgba(2,6,23,.18);
    padding: 10px;
    display:none;
  }
  .v-fly-item{ position: relative; }
  .v-fly-item:hover > .v-subfly{ display:block; }

  /* “puente” para que no se rompa el hover entre paneles */
  .v-fly-item::after{
    content:"";
    position:absolute;
    top:0;
    right:-14px;
    width: 14px;
    height: 100%;
  }
</style>

<div class="v-side">
  {{-- Botón Productos (hover despliega niveles) --}}
  <div class="v-side-item">
    <div class="v-side-btn" title="Productos">
      📦
    </div>

    <div class="v-fly">
      <div style="padding:6px 8px 10px; font-weight:950; color:#0b1220;">
        Productos
      </div>

      @forelse($sideMenu as $lvl1)
        <div class="v-fly-item">
          <a href="{{ $lvl1['href'] }}">
            <span>{{ $lvl1['label'] }}</span>
            <small>Ver</small>
          </a>

          @if(!empty($lvl1['children']))
            <div class="v-subfly">
              <div style="padding:6px 8px 10px; font-weight:950; color:#0b1220;">
                {{ $lvl1['label'] }}
              </div>

              @foreach($lvl1['children'] as $lvl2)
                <a href="{{ $lvl2['href'] }}">
                  <span>{{ $lvl2['label'] }}</span>
                  <small>Ver</small>
                </a>
              @endforeach
            </div>
          @endif
        </div>
      @empty
        <div style="padding:10px 10px; color: rgba(15,23,42,.60); font-weight:700;">
          No hay opciones.
        </div>
      @endforelse
    </div>
  </div>

  {{-- Puedes dejar tus otros botones laterales como ya los tenías (home, etc) --}}
</div>
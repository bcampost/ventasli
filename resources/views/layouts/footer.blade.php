{{-- resources/views/layouts/footer.blade.php --}}
@php
  // ✅ Showrooms oficiales (Línea Italia)
  $showrooms = [
    [
      'name' => 'Showroom Aguascalientes',
      'address' => 'Av. José María Chávez 643, Barrio del Encino, 20000 Aguascalientes, Ags.',
      'maps' => 'https://maps.app.goo.gl/mmEWfPqdcihBkPc99',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'tag' => 'Aguascalientes',
    ],
    [
      'name' => 'Showroom CDMX',
      'address' => 'Calz. Gral. Mariano Escobedo 218, Anáhuac I Secc, Miguel Hidalgo, 11310 Ciudad de México, CDMX',
      'maps' => 'https://maps.app.goo.gl/v1BJyoRZ5rL85r668',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'tag' => 'CDMX',
    ],
    [
      'name' => 'Showroom Querétaro',
      'address' => 'San Luis Potosí - Santiago de Querétaro 135-edif. D02-N1, Local 19, Jurica, 76100 Santiago de Querétaro, Qro.',
      'maps' => 'https://maps.app.goo.gl/75Ge3DanaTM8ciPWA',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'tag' => 'Querétaro',
    ],
    [
      'name' => 'Showroom Monterrey',
      'address' => 'Belisario Domínguez 2020, Obispado, 64060 Monterrey, N.L.',
      'maps' => 'https://maps.app.goo.gl/si5yWzUoBGKWTubGA',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'tag' => 'Monterrey',
    ],
  ];

  // ✅ Redes sociales
  $socials = [
    [
      'name' => 'Facebook',
      'url'  => 'https://www.facebook.com/lineaitaliamx/?locale=es_LA',
      'icon' => 'facebook',
    ],
    [
      'name' => 'Instagram',
      'url'  => 'https://www.instagram.com/lineaitalia/',
      'icon' => 'instagram',
    ],
    [
      'name' => 'X',
      'url'  => 'https://x.com/lineaitalia',
      'icon' => 'x',
    ],
    [
      'name' => 'YouTube',
      'url'  => 'https://www.youtube.com/@litaliaofficefurnit',
      'icon' => 'youtube',
    ],
  ];
@endphp

<style>
  :root{
    --f-ink:#0b1220;
    --f-muted:rgba(15,23,42,.62);
    --f-line:rgba(15,23,42,.10);
    --f-surface:rgba(255,255,255,.78);
    --f-surface2:rgba(255,255,255,.92);
    --f-shadow:0 18px 60px rgba(2,6,23,.12);
    --f-shadow2:0 12px 30px rgba(2,6,23,.10);
    --f-primary:#2563eb;
    --f-primary2:#1d4ed8;
    --f-r:22px;
  }

  .li-footer{
    border-top: 1px solid var(--f-line);
    background:
      radial-gradient(1100px 260px at 15% 0%, rgba(37,99,235,.10), transparent 55%),
      radial-gradient(900px 260px at 85% 20%, rgba(29,78,216,.10), transparent 55%),
      rgba(248,250,252,.65);
    backdrop-filter: blur(10px);
  }

  .li-footer__wrap{
    max-width: 1280px;
    margin: 0 auto;
    padding: 22px 18px 18px;
  }

  .li-footer__top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 14px;
    margin-bottom: 14px;
    flex-wrap: wrap;
  }

  .li-footer__brand{
    display:flex;
    align-items:center;
    gap:10px;
    user-select:none;
  }

  .li-footer__logo{
    width: 38px;
    height: 38px;
    border-radius: 14px;
    background: linear-gradient(180deg, rgba(37,99,235,.16), rgba(29,78,216,.10));
    border: 1px solid rgba(37,99,235,.18);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow: var(--f-shadow2);
  }

  .li-footer__title{
    font-weight: 950;
    letter-spacing: -.02em;
    color: var(--f-ink);
    line-height: 1.1;
  }

  .li-footer__subtitle{
    margin-top: 4px;
    color: var(--f-muted);
    font-weight: 700;
    font-size: .92rem;
  }

  .li-footer__grid{
    display:grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 12px;
    margin-top: 12px;
  }

  .li-footer__card{
    grid-column: span 12;
    border-radius: var(--f-r);
    border: 1px solid rgba(15,23,42,.10);
    background: var(--f-surface2);
    box-shadow: var(--f-shadow2);
    overflow:hidden;
    position:relative;
  }

  @media (min-width: 860px){
    .li-footer__card{ grid-column: span 6; }
  }

  @media (min-width: 1160px){
    .li-footer__card{ grid-column: span 3; }
  }

  .li-footer__cardInner{
    padding: 14px 14px 14px;
    display:flex;
    gap: 12px;
    align-items:flex-start;
  }

  .li-footer__pin{
    width: 44px;
    height: 44px;
    border-radius: 999px;
    border: 1px solid rgba(37,99,235,.18);
    background: rgba(255,255,255,.90);
    box-shadow: 0 10px 24px rgba(2,6,23,.10);
    display:flex;
    align-items:center;
    justify-content:center;
    flex: 0 0 auto;
    color: rgba(15,23,42,.74);
  }

  .li-footer__name{
    font-weight: 950;
    letter-spacing: -.01em;
    color: var(--f-ink);
    line-height: 1.15;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
  }

  .li-footer__mapIcon{
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(37,99,235,.20);
    background: rgba(37,99,235,.08);
    display:flex;
    align-items:center;
    justify-content:center;
    color: rgba(29,78,216,.95);
    text-decoration:none;
    box-shadow: 0 10px 22px rgba(2,6,23,.08);
    transition: transform .15s ease, background .15s ease, border-color .15s ease;
    flex: 0 0 auto;
  }
  .li-footer__mapIcon:hover{
    transform: translateY(-1px);
    background: rgba(37,99,235,.12);
    border-color: rgba(37,99,235,.32);
  }

  .li-footer__tag{
    display:inline-flex;
    align-items:center;
    gap:6px;
    margin-top: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    border: 1px solid rgba(37,99,235,.18);
    background: rgba(37,99,235,.08);
    color: rgba(29,78,216,.92);
    font-weight: 850;
    font-size: .78rem;
  }

  .li-footer__meta{
    margin-top: 10px;
    color: rgba(15,23,42,.68);
    font-weight: 700;
    font-size: .9rem;
    line-height: 1.35;
  }

  .li-footer__meta small{
    display:block;
    color: rgba(15,23,42,.55);
    font-weight: 750;
    margin-top: 6px;
  }

  .li-footer__actions{
    margin-top: 12px;
    display:flex;
    gap: 10px;
    align-items:center;
    flex-wrap: wrap;
  }

  .li-footer__btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 10px 12px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    color: rgba(15,23,42,.78);
    font-weight: 900;
    font-size: .85rem;
    text-decoration:none;
    box-shadow: 0 10px 22px rgba(2,6,23,.08);
    transition: transform .15s ease, background .15s ease, border-color .15s ease;
  }

  .li-footer__btn:hover{
    transform: translateY(-1px);
    background:#fff;
    border-color: rgba(15,23,42,.18);
  }

  .li-footer__btn.primary{
    border-color: rgba(37,99,235,.22);
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color: #fff;
    box-shadow: 0 16px 34px rgba(37,99,235,.20);
  }

  /* ✅ Socials */
  .li-footer__social{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap: wrap;
    justify-content:flex-end;
  }

  .li-footer__socialTitle{
    font-weight: 900;
    color: rgba(15,23,42,.72);
    font-size: .9rem;
    margin-right: 2px;
  }

  .li-footer__socialBtn{
    width: 42px;
    height: 42px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    box-shadow: 0 10px 22px rgba(2,6,23,.08);
    display:flex;
    align-items:center;
    justify-content:center;
    color: rgba(15,23,42,.78);
    text-decoration:none;
    transition: transform .15s ease, background .15s ease, border-color .15s ease;
  }
  .li-footer__socialBtn:hover{
    transform: translateY(-1px);
    background:#fff;
    border-color: rgba(15,23,42,.18);
  }

  .li-footer__bottom{
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid rgba(15,23,42,.08);
    display:flex;
    justify-content:space-between;
    gap: 10px;
    flex-wrap: wrap;
    color: rgba(15,23,42,.55);
    font-weight: 700;
    font-size: .88rem;
  }

  .li-footer__link{
    color: rgba(15,23,42,.70);
    text-decoration:none;
    font-weight: 850;
  }
  .li-footer__link:hover{ text-decoration: underline; }
</style>

<footer class="li-footer" role="contentinfo" aria-label="Showrooms Línea Italia">
  <div class="li-footer__wrap">

    <div class="li-footer__top">
      <div class="li-footer__brand">
        <div class="li-footer__logo" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M12 2l8 4v6c0 5-3 9-8 10C7 21 4 17 4 12V6l8-4z" stroke="currentColor" stroke-width="1.7"/>
            <path d="M8 12l2.2 2.2L16 8.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <div class="li-footer__title">Showrooms Línea Italia</div>
          <div class="li-footer__subtitle">Ubicaciones y acceso directo a cada sucursal</div>
        </div>
      </div>

      <div class="li-footer__social" aria-label="Redes sociales Línea Italia">
        

        {{-- Facebook --}}
        <a class="li-footer__socialBtn" href="https://www.facebook.com/lineaitaliamx/?locale=es_LA"
           target="_blank" rel="noopener" title="Facebook">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M14 9h3V6h-3c-2 0-4 1.7-4 4v3H7v3h3v6h3v-6h3l1-3h-4v-3c0-.6.4-1 1-1z"
                  fill="currentColor" style="opacity:.92"/>
          </svg>
        </a>

        {{-- Instagram --}}
        <a class="li-footer__socialBtn" href="https://www.instagram.com/lineaitalia/"
           target="_blank" rel="noopener" title="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4z"
                  stroke="currentColor" stroke-width="1.7"/>
            <path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"
                  stroke="currentColor" stroke-width="1.7"/>
            <path d="M17.5 6.5h.01" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"/>
          </svg>
        </a>

        {{-- X --}}
        <a class="li-footer__socialBtn" href="https://x.com/lineaitalia"
           target="_blank" rel="noopener" title="X">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
          </svg>
        </a>

        {{-- YouTube --}}
        <a class="li-footer__socialBtn" href="https://www.youtube.com/@litaliaofficefurnit"
           target="_blank" rel="noopener" title="YouTube">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 12s0-3.5-.5-5.1a2.6 2.6 0 0 0-1.8-1.8C17.1 4.6 12 4.6 12 4.6s-5.1 0-6.7.5A2.6 2.6 0 0 0 3.5 6.9C3 8.5 3 12 3 12s0 3.5.5 5.1a2.6 2.6 0 0 0 1.8 1.8c1.6.5 6.7.5 6.7.5s5.1 0 6.7-.5a2.6 2.6 0 0 0 1.8-1.8C21 15.5 21 12 21 12z"
                  stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M10.5 9.5l5 2.5-5 2.5v-5z" fill="currentColor" style="opacity:.92"/>
          </svg>
        </a>


      </div>
    </div>

    <div class="li-footer__grid">
      @foreach($showrooms as $s)
        <div class="li-footer__card">
          <div class="li-footer__cardInner">
            <div class="li-footer__pin" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M12 22s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                <path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" stroke="currentColor" stroke-width="1.7"/>
              </svg>
            </div>

            <div style="min-width:0; width:100%;">
              <div class="li-footer__name">
                <span style="min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                  {{ $s['name'] }}
                </span>

                {{-- ✅ Icono que abre Maps --}}
                <a class="li-footer__mapIcon" target="_blank" rel="noopener"
                   href="{{ $s['maps'] }}" title="Abrir en Google Maps">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 22s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" stroke="currentColor" stroke-width="1.7"/>
                  </svg>
                </a>
              </div>

              <div class="li-footer__tag">
                <span style="width:7px;height:7px;border-radius:999px;background:rgba(37,99,235,.8);display:inline-block;"></span>
                {{ $s['tag'] ?? 'Sucursal' }}
              </div>

              <div class="li-footer__meta">
                {{ $s['address'] }}
                <small>{{ $s['hours'] }}</small>
                @if(!empty($s['phone']))
                  <small>Tel: {{ $s['phone'] }}</small>
                @endif
              </div>

              <div class="li-footer__actions">
                <a class="li-footer__btn" target="_blank" rel="noopener"
                   href="{{ $s['maps'] }}" title="Cómo llegar">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M10 14l4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="M14 10h-4v4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 3h7v7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="M21 3l-9 9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                  </svg>
                  Cómo llegar
                </a>

                @if(!empty($s['phone']))
                  @php $tel = preg_replace('/[^0-9\+]/', '', $s['phone']); @endphp
                  <a class="li-footer__btn" href="tel:{{ $tel }}" title="Llamar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M22 16.5v3a2 2 0 0 1-2.2 2c-9.2-.9-16.4-8.1-17.3-17.3A2 2 0 0 1 4.5 2h3a2 2 0 0 1 2 1.7c.2 1.2.6 2.4 1.1 3.5a2 2 0 0 1-.5 2.2L9 10.6c1.4 2.6 3.5 4.7 6.1 6.1l1.2-1.1a2 2 0 0 1 2.2-.5c1.1.5 2.3.9 3.5 1.1A2 2 0 0 1 22 16.5z"
                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Llamar
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="li-footer__bottom">
      <div>© {{ date('Y') }} Línea Italia · Portal interno</div>
      <div>
        <a class="li-footer__link" href="{{ route('home') }}">Inicio</a>
        <span style="opacity:.5; padding:0 8px;">·</span>
        <a class="li-footer__link" target="_blank" rel="noopener" href="https://www.google.com/maps?q=Linea+Italia">Ubicaciones</a>
      </div>
    </div>

  </div>
</footer>
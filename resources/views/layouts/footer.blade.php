{{-- resources/views/layouts/footer.blade.php --}}
@php
  $isAdmin = auth()->check() && auth()->user()->hasRole('admin');

  // ==========
  // DATA BASE (por ahora hardcode; luego lo conectamos a DB)
  // ==========

  $locations = [
    [
      'id' => 'ags',
      'name' => '',
      'address' => 'Av. José María Chávez 643, Barrio del Encino, 20000 Aguascalientes, Ags.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/mmEWfPqdcihBkPc99',
    ],
    [
      'id' => 'cdmx',
      'name' => '',
      'address' => 'Calz. Gral. Mariano Escobedo 218, Anáhuac I Secc, Miguel Hidalgo, 11310 Ciudad de México, CDMX',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/v1BJyoRZ5rL85r668',
    ],
    [
      'id' => 'qro',
      'name' => '',
      'address' => 'San Luis Potosí - Santiago de Querétaro 135-edif. D02-N1, Local 19, Jurica, 76100 Santiago de Querétaro, Qro.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/75Ge3DanaTM8ciPWA',
    ],
    [
      'id' => 'mty',
      'name' => '',
      'address' => 'Belisario Domínguez 2020, Obispado, 64060 Monterrey, N.L.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/si5yWzUoBGKWTubGA',
    ],
  ];

  $capacitaciones = [
    ['id'=>'videos','label'=>'Videos','href'=>'' ],
    ['id'=>'presentaciones','label'=>'Presentaciones','href'=>'' ],
    ['id'=>'recetas','label'=>'Recetas','href'=>'' ],
    ['id'=>'guiones','label'=>'Guiones','href'=>'' ],
    ['id'=>'armados','label'=>'Armados','href'=>'' ],
    ['id'=>'tutoriales','label'=>'Tutoriales','href'=>'' ],
  ];

  $socials = [
    ['id'=>'fb', 'label'=>'Facebook', 'href'=>'https://www.facebook.com/lineaitaliamx/?locale=es_LA'],
    ['id'=>'ig', 'label'=>'Instagram', 'href'=>'https://www.instagram.com/lineaitalia/'],
    ['id'=>'x',  'label'=>'X', 'href'=>'https://x.com/lineaitalia'],
    ['id'=>'yt', 'label'=>'YouTube', 'href'=>'https://www.youtube.com/@litaliaofficefurnit'],
  ];
@endphp

<style>
  :root{
    --f-ink:#0b1220;
    --f-muted: rgba(15,23,42,.62);
    --f-line: rgba(15,23,42,.10);
    --f-surface: rgba(255,255,255,.86);
    --f-surface2: rgba(255,255,255,.72);
    --f-shadow: 0 18px 60px rgba(2,6,23,.12);
    --f-shadow2: 0 10px 30px rgba(2,6,23,.10);
    --f-r: 22px;
    --f-r2: 18px;
    --f-primary:#2563eb;
    --f-primary2:#1d4ed8;
  }

  .li-footer{
    position: relative;
    margin-top: 26px;
    border-top: 1px solid rgba(15,23,42,.08);
    background:
      radial-gradient(900px 260px at 20% 0%, rgba(37,99,235,.10), transparent 55%),
      radial-gradient(820px 300px at 85% 10%, rgba(29,78,216,.10), transparent 60%),
      #f6f7fb;
  }

  .li-footer-inner{
    max-width: 1120px;
    margin: 0 auto;
    padding: 18px 18px 16px;
  }

  .li-foot-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 14px;
    margin-bottom: 12px;
  }

  .li-foot-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight: 950;
    color: var(--f-ink);
    letter-spacing: -.02em;
  }

  .li-foot-sub{
    color: var(--f-muted);
    font-weight: 700;
    font-size: .88rem;
    margin-top: 2px;
  }

  .li-foot-tools{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap: wrap;
  }

  .li-icon{
    width: 42px;
    height: 42px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.88);
    box-shadow: var(--f-shadow2);
    display:flex;
    align-items:center;
    justify-content:center;
    color: rgba(15,23,42,.70);
    cursor:pointer;
    transition: transform .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
    user-select:none;
  }
  .li-icon:hover{
    transform: translateY(-1px);
    background:#fff;
    border-color: rgba(15,23,42,.18);
  }
  .li-icon.on{
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color:#fff;
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 16px 34px rgba(37,99,235,.22);
  }

  .li-foot-grid{
    display:grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 14px;
    align-items:start;
  }

  @media (max-width: 980px){
    .li-foot-grid{ grid-template-columns: 1fr; }
  }

  .li-block{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: var(--f-r);
    background: rgba(255,255,255,.76);
    box-shadow: var(--f-shadow);
    overflow:hidden;
  }

  .li-block-h{
    padding: 12px 14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    background: rgba(255,255,255,.65);
    border-bottom: 1px solid rgba(15,23,42,.08);
  }
  .li-block-h strong{
    font-weight: 950;
    letter-spacing:-.01em;
    color: var(--f-ink);
  }

  .li-pencil{
    display:none;
    align-items:center;
    justify-content:center;
    width: 36px; height: 36px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    cursor:pointer;
    box-shadow: 0 10px 22px rgba(2,6,23,.10);
  }
  .li-pencil:hover{ transform: translateY(-1px); }
  .li-edit-on .li-pencil{ display:flex; } /* aparece solo en modo edición */

  .li-list{
    padding: 10px 12px 12px;
    display:flex;
    flex-direction: column;
    gap: 10px;
  }

  /* Items compactos (solo nombre) */
  .li-item{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    background: rgba(255,255,255,.85);
    overflow:hidden;
    transition: border-color .15s ease, background .15s ease, transform .15s ease;
  }
  .li-item:hover{
    transform: translateY(-1px);
    border-color: rgba(15,23,42,.18);
    background: #fff;
  }

  .li-item-head{
    padding: 11px 12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    cursor:pointer;
    user-select:none;
  }
  .li-item-head .name{
    display:flex; align-items:center; gap:10px;
    font-weight: 950;
    color: var(--f-ink);
    letter-spacing:-.01em;
  }

  .li-chip{
    font-size: .78rem;
    font-weight: 900;
    padding: .25rem .55rem;
    border-radius: 999px;
    border: 1px solid rgba(37,99,235,.22);
    background: rgba(37,99,235,.08);
    color: rgba(37,99,235,.92);
    white-space:nowrap;
  }

  .li-caret{
    color: rgba(15,23,42,.55);
    font-weight: 900;
    transition: transform .15s ease;
  }

  .li-item.open .li-caret{ transform: rotate(180deg); }

  .li-item-body{
    display:none;
    padding: 0 12px 12px;
    color: rgba(15,23,42,.72);
    font-weight: 700;
    font-size: .90rem;
    line-height: 1.25;
  }
  .li-item.open .li-item-body{ display:block; }

  .li-meta{ margin-top: 8px; display:flex; flex-direction:column; gap:6px; }
  .li-meta small{ color: rgba(15,23,42,.62); font-weight: 800; }

  .li-actions{
    margin-top: 10px;
    display:flex; gap: 8px; flex-wrap: wrap;
  }
  .li-btn{
    display:inline-flex; align-items:center; gap:8px;
    border-radius: 999px;
    padding: .58rem .78rem;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.92);
    font-weight: 900;
    color: rgba(15,23,42,.78);
    text-decoration:none;
  }
  .li-btn:hover{ background:#fff; border-color: rgba(15,23,42,.18); }

  /* Capacitaciones simple list */
  .li-cap-list{
    padding: 10px 12px 12px;
    display:flex;
    flex-direction: column;
    gap: 8px;
  }
  .li-cap-item{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 12px;
    padding: 10px 12px;
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 16px;
    background: rgba(255,255,255,.85);
    text-decoration:none;
    color: var(--f-ink);
    font-weight: 950;
  }
  .li-cap-item:hover{ background:#fff; border-color: rgba(15,23,42,.18); }
  .li-cap-item small{ color: var(--f-muted); font-weight: 800; }

  .li-foot-bottom{
    margin-top: 12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 12px;
    color: rgba(15,23,42,.55);
    font-weight: 800;
    font-size: .86rem;
  }

  /* Modals */
  .li-modal-backdrop{
    position: fixed;
    inset:0;
    background: rgba(2,6,23,.72);
    backdrop-filter: blur(10px);
    z-index: 200;
    display:none;
  }
  .li-modal{
    position: fixed;
    inset:0;
    z-index: 210;
    display:none;
    align-items:center;
    justify-content:center;
    padding: 16px;
  }
  .li-modal-card{
    width: 100%;
    max-width: 860px;
    border-radius: 26px;
    overflow:hidden;
    background:
      radial-gradient(900px 260px at 20% 0%, rgba(37,99,235,.12), transparent 55%),
      #fff;
    border: 1px solid rgba(15,23,42,.14);
    box-shadow: 0 30px 90px rgba(2,6,23,.24);
  }
  .li-modal-head{
    padding: 16px 18px;
    border-bottom:1px solid rgba(15,23,42,.10);
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 10px;
    background: rgba(255,255,255,.80);
  }
  .li-modal-head .t{ font-weight: 950; color: var(--f-ink); letter-spacing:-.02em; }
  .li-modal-body{
    padding: 16px 18px 18px;
    max-height: calc(100vh - 190px);
    overflow:auto;
  }
  .li-modal-actions{
    padding: 14px 18px;
    border-top:1px solid rgba(15,23,42,.08);
    display:flex;
    justify-content:flex-end;
    gap: 10px;
    background: rgba(255,255,255,.80);
  }
  .li-mbtn{
    border-radius: 16px;
    padding: .70rem .95rem;
    font-weight: 950;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    cursor:pointer;
  }
  .li-mbtn.primary{
    border-color: rgba(37,99,235,.55);
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color:#fff;
  }

  .li-grid2{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  @media (max-width: 860px){
    .li-grid2{ grid-template-columns: 1fr; }
  }

  .li-field label{
    display:block;
    font-size:.83rem;
    font-weight: 950;
    color: rgba(15,23,42,.72);
    margin-bottom: 6px;
  }
  .li-input, .li-textarea, .li-select{
    width:100%;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.95);
    padding: .82rem .95rem;
    font-weight: 800;
    color: var(--f-ink);
    outline:none;
  }
  .li-textarea{ min-height: 90px; resize: vertical; }

  .li-row{
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    padding: 12px;
    background: rgba(248,250,252,.55);
  }
  .li-row + .li-row{ margin-top: 10px; }
  .li-row-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom: 10px;
  }
  .li-row-head strong{ font-weight: 950; color: var(--f-ink); }
  .li-del{
    width: 36px; height: 36px;
    border-radius: 999px;
    border: 1px solid rgba(225,29,72,.25);
    background: rgba(225,29,72,.08);
    cursor:pointer;
  }
  .li-del:hover{ background: rgba(225,29,72,.12); }

  .li-soc{
    display:flex;
    gap:10px;
    align-items:center;
  }
  .li-soc a{ text-decoration:none; }
</style>

<footer class="li-footer" id="liFooter">
  <div class="li-footer-inner">
    <div class="li-foot-top">
      <div>
        <div class="li-foot-title">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
            <path d="M12 2l8 4v6c0 5-3 9-8 10C7 21 4 17 4 12V6l8-4z" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8 12l2.2 2.2L16 8.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Showrooms Línea Italia</span>
        </div>
        <div class="li-foot-sub">Ubicaciones, capacitaciones y accesos rápidos</div>
      </div>

      <div class="li-foot-tools">
        {{-- Redes --}}
        <div class="li-soc" aria-label="Redes sociales">
          @foreach($socials as $s)
            <a class="li-icon" href="{{ $s['href'] }}" target="_blank" rel="noopener" title="{{ $s['label'] }}">
              @if($s['id']==='fb')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v3H7v3h3v4h3v-4h3l1-3h-4v-3c0-.6.4-1 1-1z" fill="currentColor"/></svg>
              @elseif($s['id']==='ig')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5z" stroke="currentColor" stroke-width="1.7"/><path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" stroke="currentColor" stroke-width="1.7"/><path d="M17.5 6.6h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
              @elseif($s['id']==='x')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 19L19 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M7 5h5l5 14h-5L7 5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
              @else
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M10 15l5.2-3L10 9v6z" fill="currentColor"/><path d="M21 7a3 3 0 0 0-2-2c-1.7-.5-7-.5-7-.5s-5.3 0-7 .5a3 3 0 0 0-2 2A31 31 0 0 0 3 12a31 31 0 0 0 .5 5 3 3 0 0 0 2 2c1.7.5 7 .5 7 .5s5.3 0 7-.5a3 3 0 0 0 2-2A31 31 0 0 0 21 12a31 31 0 0 0-.5-5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
              @endif
            </a>
          @endforeach
        </div>

        {{-- Engrane global (solo admin) --}}
        @if($isAdmin)
          <button type="button" class="li-icon" id="liFooterGear" title="Editar footer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" stroke="currentColor" stroke-width="1.7"/>
              <path d="M19.4 15a8 8 0 0 0 .1-1l2-1.2-2-3.6-2.3.8a7 7 0 0 0-1.7-1l-.4-2.4H9l-.4 2.4a7 7 0 0 0-1.7 1l-2.3-.8-2 3.6 2 1.2a8 8 0 0 0 .1 1 8 8 0 0 0-.1 1l-2 1.2 2 3.6 2.3-.8a7 7 0 0 0 1.7 1l.4 2.4h6.1l.4-2.4a7 7 0 0 0 1.7-1l2.3.8 2-3.6-2-1.2a8 8 0 0 0-.1-1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
            </svg>
          </button>
        @endif
      </div>
    </div>

    <div class="li-foot-grid" id="liFootGrid">

      {{-- UBICACIONES --}}
      <section class="li-block">
        <div class="li-block-h">
          <strong>Ubicaciones</strong>
          <button type="button" class="li-pencil" id="liEditLocationsBtn" title="Editar ubicaciones">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <div class="li-list" id="liLocationsList">
          @foreach($locations as $loc)
            <div class="li-item" data-loc="{{ $loc['id'] }}">
              <div class="li-item-head" onclick="LI_FOOT.toggleItem(this)">
                <div class="name">
                  <span>{{ $loc['name'] }}</span>
                  <span class="li-chip">{{ strtoupper($loc['id']) }}</span>
                </div>
                <span class="li-caret">▾</span>
              </div>

              <div class="li-item-body">
                <div class="li-meta">
                  <div>{{ $loc['address'] }}</div>
                  <small>Tel: {{ $loc['phone'] }}</small>
                  <small>{{ $loc['hours'] }}</small>
                </div>

                <div class="li-actions">
                  <a class="li-btn" href="{{ $loc['maps'] }}" target="_blank" rel="noopener" title="Abrir en Maps">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                      <path d="M12 21s7-5 7-11a7 7 0 1 0-14 0c0 6 7 11 7 11z" stroke="currentColor" stroke-width="1.6"/>
                      <path d="M12 10.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" fill="currentColor"/>
                    </svg>
                    Cómo llegar
                  </a>
                  <a class="li-btn" href="tel:{{ $loc['phone'] }}" title="Llamar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                      <path d="M7 4l3 1-1 3c1 2 3 4 5 5l3-1 1 3c-2 2-6 2-10-2S5 6 7 4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                    Llamar
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- CAPACITACIONES --}}
      <section class="li-block">
        <div class="li-block-h">
          <strong>Capacitaciones</strong>
          <button type="button" class="li-pencil" id="liEditCapsBtn" title="Editar capacitaciones">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <div class="li-cap-list" id="liCapsList">
          @foreach($capacitaciones as $c)
            @php
              // fallback interno si no hay href externo
              $fallback = '/menu/capacitaciones';
              $href = trim($c['href'] ?? '') !== '' ? $c['href'] : $fallback;
              $hint = trim($c['href'] ?? '') !== '' ? 'Externo/Custom' : 'Menú';
            @endphp
            <a class="li-cap-item" href="{{ $href }}" @if(str_starts_with($href,'http')) target="_blank" rel="noopener" @endif>
              <span>{{ $c['label'] }}</span>
              <small>{{ $hint }}</small>
            </a>
          @endforeach
        </div>
      </section>

    </div>

    <div class="li-foot-bottom">
      <div>© {{ date('Y') }} Línea Italia · Portal interno</div>
      <div style="display:flex; gap:10px; align-items:center;">
        <span style="opacity:.75;">Inicio</span>
        <span style="opacity:.55;">·</span>
        <span style="opacity:.75;">Ubicaciones</span>
      </div>
    </div>
  </div>

  {{-- ============= MODAL: EDIT UBICACIONES ============= --}}
  <div class="li-modal-backdrop" id="liModalBackdrop" onclick="LI_FOOT.closeAll()"></div>

  <div class="li-modal" id="liModalLocations" aria-hidden="true">
    <div class="li-modal-card">
      <div class="li-modal-head">
        <div>
          <div class="t">Editar Ubicaciones</div>
          <div style="color:rgba(15,23,42,.58); font-weight:800; font-size:.9rem;">Edita nombres, teléfono, horario y link de Maps.</div>
        </div>
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cerrar ✕</button>
      </div>

      <div class="li-modal-body" id="liLocationsEditor">
        {{-- Se llena por JS --}}
      </div>

      <div class="li-modal-actions">
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cancelar</button>
        <button class="li-mbtn primary" type="button" onclick="LI_FOOT.saveLocations()">Guardar cambios</button>
      </div>
    </div>
  </div>

  {{-- ============= MODAL: EDIT CAPACITACIONES ============= --}}
  <div class="li-modal" id="liModalCaps" aria-hidden="true">
    <div class="li-modal-card">
      <div class="li-modal-head">
        <div>
          <div class="t">Editar Capacitaciones</div>
          <div style="color:rgba(15,23,42,.58); font-weight:800; font-size:.9rem;">
            Define si el link es Externo (https://...) o Interno (/menu/...).
            Si lo dejas vacío, irá a <span style="font-family:ui-monospace;">/menu/capacitaciones</span>.
          </div>
        </div>
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cerrar ✕</button>
      </div>

      <div class="li-modal-body" id="liCapsEditor">
        {{-- Se llena por JS --}}
      </div>

      <div class="li-modal-actions">
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cancelar</button>
        <button class="li-mbtn primary" type="button" onclick="LI_FOOT.saveCaps()">Guardar cambios</button>
      </div>
    </div>
  </div>
</footer>

<script>
  // ==========
  // Estado en memoria (por ahora). Luego lo conectamos a DB con endpoints.
  // ==========
  const LI_FOOT = {
    isEdit: false,
    locations: @json($locations),
    caps: @json($capacitaciones),
    capsFallback: '/menu/capacitaciones',

    toggleItem(headEl){
      const item = headEl.closest('.li-item');
      if (!item) return;
      item.classList.toggle('open');
    },

    setEditMode(on){
      this.isEdit = !!on;
      const footer = document.getElementById('liFooter');
      const gear = document.getElementById('liFooterGear');
      if (!footer) return;

      if (this.isEdit) footer.classList.add('li-edit-on');
      else footer.classList.remove('li-edit-on');

      if (gear){
        if (this.isEdit) gear.classList.add('on');
        else gear.classList.remove('on');
      }
    },

    openModal(id){
      const bd = document.getElementById('liModalBackdrop');
      const m1 = document.getElementById('liModalLocations');
      const m2 = document.getElementById('liModalCaps');
      bd.style.display = 'block';
      if (id === 'loc') m1.style.display = 'flex';
      if (id === 'caps') m2.style.display = 'flex';
      document.body.classList.add('no-scroll');
    },

    closeAll(){
      document.getElementById('liModalBackdrop').style.display = 'none';
      document.getElementById('liModalLocations').style.display = 'none';
      document.getElementById('liModalCaps').style.display = 'none';
      document.body.classList.remove('no-scroll');
    },

    // ==========
    // Render editores
    // ==========
    renderLocationsEditor(){
      const wrap = document.getElementById('liLocationsEditor');
      if (!wrap) return;

      wrap.innerHTML = this.locations.map((l, idx) => {
        return `
          <div class="li-row" data-idx="${idx}">
            <div class="li-row-head">
              <strong>${this.escape(l.name || '')}</strong>
              <span style="color:rgba(15,23,42,.55); font-weight:900;">${this.escape((l.id||'').toUpperCase())}</span>
            </div>

            <div class="li-grid2">
              <div class="li-field">
                <label>Nombre</label>
                <input class="li-input" data-k="name" value="${this.escapeAttr(l.name||'')}" />
              </div>

              <div class="li-field">
                <label>Teléfono</label>
                <input class="li-input" data-k="phone" value="${this.escapeAttr(l.phone||'')}" />
              </div>

              <div class="li-field" style="grid-column:1/-1;">
                <label>Dirección</label>
                <textarea class="li-textarea" data-k="address">${this.escape(l.address||'')}</textarea>
              </div>

              <div class="li-field">
                <label>Horario</label>
                <input class="li-input" data-k="hours" value="${this.escapeAttr(l.hours||'')}" />
              </div>

              <div class="li-field">
                <label>Link Maps</label>
                <input class="li-input" data-k="maps" value="${this.escapeAttr(l.maps||'')}" />
              </div>
            </div>
          </div>
        `;
      }).join('');
    },

    renderCapsEditor(){
      const wrap = document.getElementById('liCapsEditor');
      if (!wrap) return;

      wrap.innerHTML = this.caps.map((c, idx) => {
        return `
          <div class="li-row" data-idx="${idx}">
            <div class="li-row-head">
              <strong>${this.escape(c.label||'')}</strong>
              <span style="color:rgba(15,23,42,.55); font-weight:900;">${this.escape((c.id||''))}</span>
            </div>

            <div class="li-grid2">
              <div class="li-field">
                <label>Nombre</label>
                <input class="li-input" data-k="label" value="${this.escapeAttr(c.label||'')}" />
              </div>

              <div class="li-field">
                <label>Link (externo o interno)</label>
                <input class="li-input" data-k="href" placeholder="https://... o /menu/..." value="${this.escapeAttr(c.href||'')}" />
              </div>
            </div>

            <div style="margin-top:8px; color:rgba(15,23,42,.60); font-weight:800; font-size:.85rem;">
              Tip: vacío = <span style="font-family:ui-monospace;">${this.escape(this.capsFallback)}</span>
            </div>
          </div>
        `;
      }).join('');
    },

    // ==========
    // Guardados (por ahora: solo UI). Luego lo mandamos a DB.
    // ==========
    saveLocations(){
      const wrap = document.getElementById('liLocationsEditor');
      if (!wrap) return;

      wrap.querySelectorAll('.li-row').forEach(row => {
        const idx = Number(row.getAttribute('data-idx'));
        if (Number.isNaN(idx)) return;
        row.querySelectorAll('[data-k]').forEach(inp => {
          const k = inp.getAttribute('data-k');
          const v = (inp.value ?? inp.textContent ?? '').trim();
          if (!k) return;
          this.locations[idx][k] = v;
        });
      });

      this.renderLocationsUI();
      this.closeAll();
    },

    saveCaps(){
      const wrap = document.getElementById('liCapsEditor');
      if (!wrap) return;

      wrap.querySelectorAll('.li-row').forEach(row => {
        const idx = Number(row.getAttribute('data-idx'));
        if (Number.isNaN(idx)) return;

        row.querySelectorAll('[data-k]').forEach(inp => {
          const k = inp.getAttribute('data-k');
          const v = (inp.value ?? '').trim();
          if (!k) return;
          this.caps[idx][k] = v;
        });
      });

      this.renderCapsUI();
      this.closeAll();
    },

    renderLocationsUI(){
      const list = document.getElementById('liLocationsList');
      if (!list) return;

      list.innerHTML = this.locations.map(l => {
        const id = l.id || '';
        const name = l.name || '';
        const address = l.address || '';
        const phone = l.phone || '';
        const hours = l.hours || '';
        const maps = l.maps || '#';

        return `
          <div class="li-item" data-loc="${this.escapeAttr(id)}">
            <div class="li-item-head" onclick="LI_FOOT.toggleItem(this)">
              <div class="name">
                <span>${this.escape(name)}</span>
                <span class="li-chip">${this.escape((id||'').toUpperCase())}</span>
              </div>
              <span class="li-caret">▾</span>
            </div>

            <div class="li-item-body">
              <div class="li-meta">
                <div>${this.escape(address)}</div>
                <small>Tel: ${this.escape(phone)}</small>
                <small>${this.escape(hours)}</small>
              </div>

              <div class="li-actions">
                <a class="li-btn" href="${this.escapeAttr(maps)}" target="_blank" rel="noopener" title="Abrir en Maps">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M12 21s7-5 7-11a7 7 0 1 0-14 0c0 6 7 11 7 11z" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M12 10.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" fill="currentColor"/>
                  </svg>
                  Cómo llegar
                </a>
                <a class="li-btn" href="tel:${this.escapeAttr(phone)}" title="Llamar">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M7 4l3 1-1 3c1 2 3 4 5 5l3-1 1 3c-2 2-6 2-10-2S5 6 7 4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                  </svg>
                  Llamar
                </a>
              </div>
            </div>
          </div>
        `;
      }).join('');
    },

    renderCapsUI(){
      const list = document.getElementById('liCapsList');
      if (!list) return;

      list.innerHTML = this.caps.map(c => {
        const label = (c.label || '').trim();
        const hrefRaw = (c.href || '').trim();
        const href = hrefRaw !== '' ? hrefRaw : this.capsFallback;
        const isExternal = /^https?:\/\//i.test(href);
        const hint = hrefRaw !== '' ? 'Externo/Custom' : 'Menú';

        return `
          <a class="li-cap-item" href="${this.escapeAttr(href)}" ${isExternal ? 'target="_blank" rel="noopener"' : ''}>
            <span>${this.escape(label)}</span>
            <small>${this.escape(hint)}</small>
          </a>
        `;
      }).join('');
    },

    escape(s){
      return String(s ?? '')
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'","&#039;");
    },
    escapeAttr(s){ return this.escape(s).replaceAll('\n',' '); },
  };

  // Bind UI
  (function(){
    const gear = document.getElementById('liFooterGear');
    const btnLoc = document.getElementById('liEditLocationsBtn');
    const btnCaps = document.getElementById('liEditCapsBtn');

    if (gear){
      gear.addEventListener('click', () => {
        LI_FOOT.setEditMode(!LI_FOOT.isEdit);
      });
    }

    if (btnLoc){
      btnLoc.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        LI_FOOT.renderLocationsEditor();
        LI_FOOT.openModal('loc');
      });
    }

    if (btnCaps){
      btnCaps.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        LI_FOOT.renderCapsEditor();
        LI_FOOT.openModal('caps');
      });
    }

    // ESC cierra modales
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') LI_FOOT.closeAll();
    });
  })();
</script>
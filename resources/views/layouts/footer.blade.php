{{-- resources/views/layouts/footer.blade.php --}}
@php
  $isAdmin = auth()->check() && auth()->user()->hasRole('admin');

  $locations = [
    [
      'id' => 'ags',
      'name' => 'Aguascalientes',
      'address' => 'Av. José María Chávez 643, Barrio del Encino, 20000 Aguascalientes, Ags.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/mmEWfPqdcihBkPc99',
    ],
    [
      'id' => 'cdmx',
      'name' => 'CDMX',
      'address' => 'Calz. Gral. Mariano Escobedo 218, Anáhuac I Secc, Miguel Hidalgo, 11310 Ciudad de México, CDMX',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/v1BJyoRZ5rL85r668',
    ],
    [
      'id' => 'qro',
      'name' => 'Querétaro',
      'address' => 'San Luis Potosí - Santiago de Querétaro 135-edif. D02-N1, Local 19, Jurica, 76100 Santiago de Querétaro, Qro.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/75Ge3DanaTM8ciPWA',
    ],
    [
      'id' => 'mty',
      'name' => 'Monterrey',
      'address' => 'Belisario Domínguez 2020, Obispado, 64060 Monterrey, N.L.',
      'phone' => '+525578586841',
      'hours' => 'Lunes a Viernes de 8:00 a.m - 6:00 p.m',
      'maps' => 'https://maps.app.goo.gl/si5yWzUoBGKWTubGA',
    ],
  ];

  $capacitaciones = [
    ['id'=>'videos',          'label'=>'Videos',          'href'=> '' ],
    ['id'=>'presentaciones',  'label'=>'Presentaciones',  'href'=> '' ],
    ['id'=>'receta',          'label'=>'Receta',          'href'=> '' ],
    ['id'=>'guias',           'label'=>'Guías',           'href'=> '' ],
    ['id'=>'armados',         'label'=>'Armados',         'href'=> '' ],
    ['id'=>'tutoriales',      'label'=>'Tutoriales',      'href'=> '' ],
  ];

  $socials = [
    ['id'=>'fb', 'label'=>'Facebook', 'href'=>'https://www.facebook.com/lineaitaliamx/?locale=es_LA'],
    ['id'=>'ig', 'label'=>'Instagram', 'href'=>'https://www.instagram.com/lineaitalia/'],
    ['id'=>'x',  'label'=>'X', 'href'=>'https://x.com/lineaitalia'],
    ['id'=>'yt', 'label'=>'YouTube', 'href'=>'https://www.youtube.com/@litaliaofficefurnit'],
  ];

  // ✅ Logo (mismo del login) -> ajusta si tu ruta real es otra
  $liLogo = asset('images/linea-italia.png');
@endphp

<style>
  :root{
    --f-bg:#0b1220;
    --f-bg2:#0a0f1a;
    --f-ink:rgba(255,255,255,.92);
    --f-muted:rgba(255,255,255,.62);
    --f-dim:rgba(255,255,255,.42);
    --f-line:rgba(255,255,255,.10);
    --f-line2:rgba(255,255,255,.14);
  }

  .li-footer{
    background:
      radial-gradient(1200px 420px at 50% -10%, rgba(255,255,255,.06), transparent 55%),
      linear-gradient(180deg, var(--f-bg), var(--f-bg2));
    border-top: 1px solid var(--f-line);
    margin-top: 28px;
    position: relative;
  }

  .li-footer-inner{
    max-width: 1180px;
    margin: 0 auto;
    padding: 36px 18px 18px;
  }

  .li-foot-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 18px;
    margin-bottom: 18px;
  }

  .li-brand-head{
    display:flex;
    align-items:center;
    gap: 12px;
  }
  .li-brand-head img{
    height: 30px;
    width: auto;
    display:block;
    object-fit: contain;
    filter: drop-shadow(0 12px 24px rgba(0,0,0,.35));
  }
  .li-brand-sub{
    color: var(--f-muted);
    font-weight: 600;
    font-size: 13px;
    line-height: 1.45;
    max-width: 520px;
  }

  .li-top-actions{
    display:flex;
    align-items:center;
    gap: 10px;
    flex-wrap: wrap;
    justify-content:flex-end;
  }

  .li-icon{
    width: 40px;
    height: 40px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    display:flex;
    align-items:center;
    justify-content:center;
    color: rgba(255,255,255,.92);
    text-decoration:none;
    cursor:pointer;
    transition: transform .15s ease, background .15s ease, border-color .15s ease;
    user-select:none;
  }
  .li-icon:hover{
    transform: translateY(-1px);
    background: rgba(255,255,255,.10);
    border-color: rgba(255,255,255,.18);
  }
  .li-icon.on{
    outline: 2px solid rgba(255,255,255,.14);
  }

  .li-foot-grid{
    display:grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 44px;
    align-items:start;
    padding-top: 10px;
  }

  @media (max-width: 980px){
    .li-foot-top{ flex-direction: column; align-items:flex-start; }
    .li-top-actions{ justify-content:flex-start; }
    .li-foot-grid{ grid-template-columns: 1fr; gap: 26px; }
  }

  .li-col-title{
    color: var(--f-ink);
    font-weight: 800;
    letter-spacing: .02em;
    font-size: 14px;
    margin-bottom: 10px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
  }

  .li-col-rule{
    width: 90px;
    height: 2px;
    background: var(--f-line2);
    border-radius: 999px;
    margin-bottom: 14px;
  }

  /* ===== Showrooms minimal ===== */
  .li-loc-list{
    display:flex;
    flex-direction: column;
    gap: 10px;
  }

  .li-loc{
    position: relative;
    padding: 8px 0;
    cursor: pointer;
    user-select: none;
  }

  .li-loc-name{
    color: var(--f-ink);
    font-weight: 800;
    font-size: 13.5px;
    letter-spacing: .01em;
    display:flex;
    align-items:center;
    gap: 10px;
  }

  .li-loc-pill{
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    color: rgba(255,255,255,.70);
  }

  .li-loc-tip{
    position: absolute;
    left: 0;
    top: 100%;
    margin-top: 8px;

    width: min(520px, 92vw);
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(12,18,32,.92);
    backdrop-filter: blur(8px);
    box-shadow: 0 18px 50px rgba(0,0,0,.28);

    color: rgba(255,255,255,.80);
    font-weight: 650;
    font-size: 12.5px;
    line-height: 1.35;

    opacity: 0;
    transform: translateY(-4px);
    pointer-events: none;
    transition: opacity .14s ease, transform .14s ease;
    z-index: 20;
  }

  .li-loc:hover .li-loc-tip{
    opacity: 1;
    transform: translateY(0);
  }

  .li-loc-tip .small{
    display:block;
    margin-top: 6px;
    color: rgba(255,255,255,.60);
    font-weight: 700;
    font-size: 11.5px;
  }

  .li-copy-toast{
    position: fixed;
    left: 50%;
    bottom: 26px;
    transform: translateX(-50%);
    z-index: 99999;
    background: rgba(0,0,0,.78);
    color: #fff;
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 999px;
    padding: 10px 14px;
    font-weight: 800;
    font-size: 12px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .18s ease, transform .18s ease;
  }
  .li-copy-toast.on{
    opacity: 1;
    transform: translateX(-50%) translateY(-2px);
  }

  /* ===== Capacitaciones ===== */
  .li-cap-item{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 12px;
    text-decoration:none;
    color: var(--f-muted);
    font-weight: 650;
    font-size: 13px;
    padding: 4px 0;
    border-bottom: 1px solid transparent;
    transition: color .15s ease, border-color .15s ease;
  }
  .li-cap-item:hover{
    color: var(--f-ink);
    border-color: var(--f-line);
  }
  .li-cap-item small{
    color: var(--f-dim);
    font-weight: 700;
    font-size: 12px;
    white-space: nowrap;
  }

  .li-pencil{
    display:none;
    align-items:center;
    justify-content:center;
    width: 34px; height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    cursor:pointer;
    color: rgba(255,255,255,.92);
    transition: transform .15s ease, background .15s ease;
  }
  .li-pencil:hover{ transform: translateY(-1px); background: rgba(255,255,255,.10); }
  .li-edit-on .li-pencil{ display:flex; }

  .li-foot-bottom{
    margin-top: 28px;
    padding-top: 14px;
    border-top: 1px solid var(--f-line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 12px;
    color: var(--f-dim);
    font-weight: 600;
    font-size: 12px;
  }

  /* ====== MODALES ====== */
  .li-modal-backdrop{
    position: fixed;
    inset:0;
    background: rgba(0,0,0,.62);
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
    border-radius: 18px;
    overflow:hidden;
    background: #0f172a;
    border: 1px solid rgba(255,255,255,.10);
    box-shadow: 0 30px 90px rgba(0,0,0,.35);
    color: rgba(255,255,255,.92);
  }
  .li-modal-head{
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,.10);
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 10px;
  }
  .li-modal-head .t{ font-weight: 850; }
  .li-modal-body{
    padding: 14px 16px 16px;
    max-height: calc(100vh - 190px);
    overflow:auto;
  }
  .li-modal-actions{
    padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,.10);
    display:flex;
    justify-content:flex-end;
    gap: 10px;
  }
  .li-mbtn{
    border-radius: 12px;
    padding: .65rem .85rem;
    font-weight: 800;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    cursor:pointer;
    color: rgba(255,255,255,.92);
  }
  .li-mbtn.primary{
    background: rgba(255,255,255,.14);
    border-color: rgba(255,255,255,.20);
  }

  .li-grid2{ display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  @media (max-width: 860px){ .li-grid2{ grid-template-columns: 1fr; } }

  .li-field label{
    display:block;
    font-size:.83rem;
    font-weight: 800;
    color: rgba(255,255,255,.70);
    margin-bottom: 6px;
  }
  .li-input, .li-textarea{
    width:100%;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    padding: .75rem .85rem;
    font-weight: 700;
    color: rgba(255,255,255,.92);
    outline:none;
  }
  .li-textarea{ min-height: 90px; resize: vertical; }

  .li-row{
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 14px;
    padding: 12px;
    background: rgba(255,255,255,.04);
  }
  .li-row + .li-row{ margin-top: 10px; }
  .li-row-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom: 10px;
  }
</style>

<footer class="li-footer" id="liFooter">
  <div class="li-footer-inner">

    {{-- TOP: Logo + redes + engrane --}}
    <div class="li-foot-top">
      <div>
        <div class="li-brand-head">
          <img src="{{ $liLogo }}" alt="Línea Italia">
        </div>
        <div class="li-brand-sub">
          Ubicaciones, capacitaciones y accesos rápidos.
        </div>
      </div>

      <div class="li-top-actions">
        {{-- Redes --}}
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

        {{-- Engrane admin (toggle modo edición) --}}
        @if($isAdmin)
          <button type="button" class="li-icon" id="liFooterGear" title="Modo edición">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" stroke="currentColor" stroke-width="1.7"/>
              <path d="M19.4 15a8 8 0 0 0 .1-1l2-1.2-2-3.6-2.3.8a7 7 0 0 0-1.7-1l-.4-2.4H9l-.4 2.4a7 7 0 0 0-1.7 1l-2.3-.8-2 3.6 2 1.2a8 8 0 0 0 .1 1 8 8 0 0 0-.1 1l-2 1.2 2 3.6 2.3-.8a7 7 0 0 0 1.7 1l.4 2.4h6.1l.4-2.4a7 7 0 0 0 1.7-1l2.3.8 2-3.6-2-1.2a8 8 0 0 0-.1-1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
            </svg>
          </button>
        @endif
      </div>
    </div>

    <div class="li-foot-grid">

      {{-- Capacitaciones (izquierda) --}}
      <section>
        <div class="li-col-title">
          <span>Capacitaciones</span>
          <button type="button" class="li-pencil" id="liEditCapsBtn" title="Editar capacitaciones">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        <div class="li-col-rule"></div>

        <div id="liCapsList">
          @foreach($capacitaciones as $c)
            @php
              $href = trim($c['href'] ?? '');
              $fallback = url('/menu/capacitaciones/' . ($c['id'] ?? ''));
              $finalHref = $href !== '' ? $href : $fallback;
              $hint = $href !== '' ? 'Vista previa' : 'Menú';
            @endphp
            <a
              class="li-cap-item"
              href="{{ $finalHref }}"
              @if($href !== '') data-preview="media" data-title="{{ $c['label'] }}" @endif
              title="{{ $hint }}"
            >
              <span>{{ $c['label'] }}</span>
              <small>{{ $hint }}</small>
            </a>
          @endforeach
        </div>
      </section>

      {{-- Showrooms minimal (centro) --}}
      <section>
        <div class="li-col-title">
          <span>Showrooms</span>
          <button type="button" class="li-pencil" id="liEditLocationsBtn" title="Editar showrooms">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        <div class="li-col-rule"></div>

        <div class="li-loc-list" id="liLocationsList">
          @foreach($locations as $loc)
            <div
              class="li-loc"
              role="button"
              tabindex="0"
              data-loc="{{ $loc['id'] }}"
              data-copy="{{ $loc['address'] }}"
              title="Click para copiar dirección"
            >
              <div class="li-loc-name">
                <span>{{ $loc['name'] }}</span>
                <span class="li-loc-pill">{{ strtoupper($loc['id']) }}</span>
              </div>

              <div class="li-loc-tip">
                {{ $loc['address'] }}
                <span class="small">Click para copiar</span>
              </div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- Columna derecha libre (puede ser texto mínimo) --}}
      <section>
        <div class="li-col-title"><span>Contacto</span></div>
        <div class="li-col-rule"></div>
        <div style="color:rgba(255,255,255,.62);font-weight:650;font-size:13px;line-height:1.55;">
          Ventas Línea Italia · Portal interno
        </div>
      </section>
    </div>

    <div class="li-foot-bottom">
      <div>© {{ date('Y') }} Línea Italia</div>
      <div style="opacity:.85;">Created with Línea Italia</div>
    </div>
  </div>

  <div class="li-copy-toast" id="liCopyToast">Dirección copiada</div>

  {{-- ============= MODAL: EDIT SHOWROOMS ============= --}}
  <div class="li-modal-backdrop" id="liModalBackdrop" onclick="LI_FOOT.closeAll()"></div>

  <div class="li-modal" id="liModalLocations" aria-hidden="true">
    <div class="li-modal-card">
      <div class="li-modal-head">
        <div>
          <div class="t">Editar Showrooms</div>
          <div style="color:rgba(255,255,255,.70); font-weight:700; font-size:.9rem;">Edita nombre, teléfono, horario y link de Maps.</div>
        </div>
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cerrar ✕</button>
      </div>

      <div class="li-modal-body" id="liLocationsEditor"></div>

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
          <div style="color:rgba(255,255,255,.70); font-weight:700; font-size:.9rem;">
            Si el link es archivo (pdf/mp4/webm/png/jpg), se abre en vista previa.
            Si lo dejas vacío, irá a /menu/capacitaciones/&lt;id&gt;
          </div>
        </div>
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cerrar ✕</button>
      </div>

      <div class="li-modal-body" id="liCapsEditor"></div>

      <div class="li-modal-actions">
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closeAll()">Cancelar</button>
        <button class="li-mbtn primary" type="button" onclick="LI_FOOT.saveCaps()">Guardar cambios</button>
      </div>
    </div>
  </div>
</footer>

<script>
  const LI_FOOT = {
    isEdit: false,
    locations: @json($locations),
    caps: @json($capacitaciones),
    capsFallbackBase: '{{ url("/menu/capacitaciones") }}',

    setEditMode(on){
      this.isEdit = !!on;
      const footer = document.getElementById('liFooterGear');
      if (!footer) return;
      if (this.isEdit) footer.classList.add('on');
      else footer.classList.remove('on');

      // habilita lápices
      const root = document.getElementById('liFooter');
      if (root){
        if (this.isEdit) root.classList.add('li-edit-on');
        else root.classList.remove('li-edit-on');
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

    renderLocationsEditor(){
      const wrap = document.getElementById('liLocationsEditor');
      if (!wrap) return;

      wrap.innerHTML = this.locations.map((l, idx) => `
        <div class="li-row" data-idx="${idx}">
          <div class="li-row-head">
            <strong>${this.escape(l.name || '')}</strong>
            <span style="opacity:.7;font-weight:800;">${this.escape((l.id||'').toUpperCase())}</span>
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
      `).join('');
    },

    renderCapsEditor(){
      const wrap = document.getElementById('liCapsEditor');
      if (!wrap) return;

      wrap.innerHTML = this.caps.map((c, idx) => `
        <div class="li-row" data-idx="${idx}">
          <div class="li-row-head">
            <strong>${this.escape(c.label||'')}</strong>
            <span style="opacity:.7;font-weight:800;">${this.escape((c.id||''))}</span>
          </div>

          <div class="li-grid2">
            <div class="li-field">
              <label>Nombre</label>
              <input class="li-input" data-k="label" value="${this.escapeAttr(c.label||'')}" />
            </div>

            <div class="li-field">
              <label>Link (archivo o /menu/...)</label>
              <input class="li-input" data-k="href" placeholder="https://... o /menu/..." value="${this.escapeAttr(c.href||'')}" />
            </div>
          </div>
        </div>
      `).join('');
    },

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

        return `
          <div class="li-loc" role="button" tabindex="0" data-loc="${this.escapeAttr(id)}" data-copy="${this.escapeAttr(address)}" title="Click para copiar dirección">
            <div class="li-loc-name">
              <span>${this.escape(name)}</span>
              <span class="li-loc-pill">${this.escape((id||'').toUpperCase())}</span>
            </div>
            <div class="li-loc-tip">
              ${this.escape(address)}
              <span class="small">Click para copiar</span>
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
        const fallback = this.capsFallbackBase + '/' + (c.id || '');
        const finalHref = hrefRaw !== '' ? hrefRaw : fallback;

        const hint = hrefRaw !== '' ? 'Vista previa' : 'Menú';
        const attrs = hrefRaw !== '' ? `data-preview="media" data-title="${this.escapeAttr(label)}"` : '';

        return `
          <a class="li-cap-item" href="${this.escapeAttr(finalHref)}" ${attrs} title="${this.escapeAttr(hint)}">
            <span>${this.escape(label)}</span>
            <small>${this.escape(hint)}</small>
          </a>
        `;
      }).join('');
    },

    toast(msg){
      const t = document.getElementById('liCopyToast');
      if(!t) return;
      t.textContent = msg || 'Copiado';
      t.classList.add('on');
      clearTimeout(this._toastTimer);
      this._toastTimer = setTimeout(()=> t.classList.remove('on'), 1200);
    },

    async copy(text){
      try{
        await navigator.clipboard.writeText(text);
        this.toast('Dirección copiada');
      }catch(e){
        // fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try{ document.execCommand('copy'); this.toast('Dirección copiada'); }catch(_){}
        document.body.removeChild(ta);
      }
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

    // ✅ Copiar dirección al click (delegación)
    document.addEventListener('click', (e) => {
      const loc = e.target.closest('.li-loc[data-copy]');
      if(!loc) return;
      const txt = (loc.getAttribute('data-copy') || '').trim();
      if(!txt) return;
      LI_FOOT.copy(txt);
    });

    // Enter/Space para accesibilidad
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') LI_FOOT.closeAll();

      if (e.key === 'Enter' || e.key === ' '){
        const active = document.activeElement;
        if (active && active.classList?.contains('li-loc') && active.getAttribute('data-copy')){
          e.preventDefault();
          const txt = (active.getAttribute('data-copy') || '').trim();
          if (txt) LI_FOOT.copy(txt);
        }
      }
    });
  })();
</script>
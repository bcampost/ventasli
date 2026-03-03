{{-- resources/views/layouts/footer.blade.php --}}
@php
  use App\Models\FooterLink;
  use Illuminate\Support\Facades\Storage;

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

  // =========================
  // Capacitaciones desde BD
  // (group = 'capacitaciones')
  // =========================
  $capLinksDb = FooterLink::where('group', 'capacitaciones')
      ->orderBy('sort')
      ->get();

  $capacitaciones = $capLinksDb->map(function($l){
      $fileUrl = $l->file_path ? Storage::url($l->file_path) : '';
      return [
        'id' => $l->id,                 // id BD (para guardar)
        'key' => $l->key,               // key lógico (videos, etc)
        'label' => $l->label,
        'link_mode' => $l->link_mode ?? 'menu', // menu|external|file
        'menu_path' => $l->menu_path ?? '',
        'external_url' => $l->external_url ?? '',
        'file_url' => $fileUrl,
        'file_name' => $l->file_name ?? '',
        'file_mime' => $l->file_mime ?? '',
        'sort' => (int)($l->sort ?? 0),
        'is_active' => (bool)($l->is_active ?? true),
      ];
  })->values()->all();

  $socials = [
    ['id'=>'fb', 'label'=>'Facebook', 'href'=>'https://www.facebook.com/lineaitaliamx/?locale=es_LA'],
    ['id'=>'ig', 'label'=>'Instagram', 'href'=>'https://www.instagram.com/lineaitalia/'],
    ['id'=>'x',  'label'=>'X', 'href'=>'https://x.com/lineaitalia'],
    ['id'=>'yt', 'label'=>'YouTube', 'href'=>'https://www.youtube.com/@litaliaofficefurnit'],
  ];

  // ✅ Logo (mismo del login) -> ajusta si tu ruta real es otra
  $liLogo = asset('images/linea-italia.png');

  // base fallback si modo=menu y no hay menu_path
  $capsFallbackBase = url('/menu/capacitaciones');
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

  /* ===== Preview modal ===== */
  .li-prev{
    position: fixed;
    inset: 0;
    z-index: 260;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  .li-prev.on{ display:flex; }
  .li-prev .bd{
    position:absolute; inset:0;
    background: rgba(0,0,0,.72);
    backdrop-filter: blur(10px);
  }
  .li-prev .card{
    position:relative;
    width: 100%;
    max-width: 980px;
    border-radius: 18px;
    overflow:hidden;
    background:#0f172a;
    border: 1px solid rgba(255,255,255,.10);
    box-shadow: 0 30px 90px rgba(0,0,0,.35);
    color: rgba(255,255,255,.92);
  }
  .li-prev .head{
    padding: 12px 14px;
    border-bottom: 1px solid rgba(255,255,255,.10);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
  }
  .li-prev .head .t{ font-weight: 850; }
  .li-prev .body{
    padding: 0;
    height: min(70vh, 720px);
    background: rgba(0,0,0,.15);
  }
  .li-prev iframe, .li-prev video, .li-prev img{
    width:100%;
    height:100%;
    display:block;
  }
  .li-prev img{ object-fit: contain; background:#0b1220; }
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
              if (!($c['is_active'] ?? true)) continue;

              $mode = $c['link_mode'] ?? 'menu';

              $fallback = $capsFallbackBase . '/' . ($c['key'] ?? '');
              $finalHref = $fallback;
              $hint = 'Menú';
              $previewable = false;

              if ($mode === 'external' && trim($c['external_url'] ?? '') !== '') {
                $finalHref = $c['external_url'];
                $hint = 'Vista previa';
                $previewable = true;
              } elseif ($mode === 'file' && trim($c['file_url'] ?? '') !== '') {
                $finalHref = $c['file_url'];
                $hint = 'Vista previa';
                $previewable = true;
              } elseif ($mode === 'menu') {
                $mp = trim((string)($c['menu_path'] ?? ''), '/');
                $finalHref = $mp !== '' ? url('/'.$mp) : $fallback;
                $hint = 'Menú';
                $previewable = false;
              } else {
                // fallback final si falta dato del modo
                $finalHref = $fallback;
                $hint = 'Menú';
                $previewable = false;
              }
            @endphp

            <a
              class="li-cap-item"
              href="{{ $finalHref }}"
              @if($previewable) data-preview="media" data-title="{{ $c['label'] }}" @endif
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

      {{-- Columna derecha --}}
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

  {{-- Preview modal --}}
  <div class="li-prev" id="liPrev">
    <div class="bd" onclick="LI_FOOT.closePreview()"></div>
    <div class="card">
      <div class="head">
        <div class="t" id="liPrevTitle">Vista previa</div>
        <button class="li-mbtn" type="button" onclick="LI_FOOT.closePreview()">Cerrar ✕</button>
      </div>
      <div class="body" id="liPrevBody"></div>
    </div>
  </div>

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
            Puedes elegir: Menú / Link externo / Archivo. Todo con vista previa cuando aplique.
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
    capsFallbackBase: @json($capsFallbackBase),
    bulkSaveUrl: @json(route('admin.footer_links.bulk')),
    csrf: @json(csrf_token()),

    setEditMode(on){
      this.isEdit = !!on;
      const gear = document.getElementById('liFooterGear');
      if (gear){
        if (this.isEdit) gear.classList.add('on');
        else gear.classList.remove('on');
      }

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

    /* ===================
     * Preview
     * =================== */
    openPreview(url, title){
      const wrap = document.getElementById('liPrev');
      const body = document.getElementById('liPrevBody');
      const t = document.getElementById('liPrevTitle');
      if(!wrap || !body || !t) return;

      const u = String(url||'').trim();
      if(!u) return;

      t.textContent = title || 'Vista previa';
      body.innerHTML = '';

      const lower = u.toLowerCase();

      // Heurística simple por extensión
      if (lower.match(/\.(png|jpg|jpeg|gif|webp)(\?.*)?$/)){
        const img = document.createElement('img');
        img.src = u;
        img.alt = title || 'Vista previa';
        body.appendChild(img);
      }
      else if (lower.match(/\.(mp4|webm|ogg)(\?.*)?$/)){
        const vid = document.createElement('video');
        vid.src = u;
        vid.controls = true;
        vid.playsInline = true;
        body.appendChild(vid);
      }
      else {
        // PDF o "lo que sea" -> iframe
        const ifr = document.createElement('iframe');
        ifr.src = u;
        ifr.setAttribute('loading','lazy');
        ifr.setAttribute('referrerpolicy','no-referrer');
        body.appendChild(ifr);
      }

      wrap.classList.add('on');
    },

    closePreview(){
      const wrap = document.getElementById('liPrev');
      const body = document.getElementById('liPrevBody');
      if(body) body.innerHTML = '';
      if(wrap) wrap.classList.remove('on');
    },

    /* ===================
     * Editors
     * =================== */
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

      wrap.innerHTML = this.caps.map((c, idx) => {
        const mode = c.link_mode || 'menu';
        const currentFile = c.file_url ? (String(c.file_url).split('/').pop()) : '';
        const currentFileLine = currentFile
          ? `<div style="margin-top:8px;opacity:.75;font-weight:700;font-size:12px;">Archivo actual: ${this.escape(currentFile)}</div>`
          : `<div style="margin-top:8px;opacity:.55;font-weight:700;font-size:12px;">Sin archivo cargado</div>`;

        return `
          <div class="li-row" data-idx="${idx}">
            <div class="li-row-head">
              <strong>${this.escape(c.label||'')}</strong>
              <span style="opacity:.7;font-weight:800;">${this.escape((c.key||''))}</span>
            </div>

            <div class="li-grid2">
              <div class="li-field">
                <label>Nombre</label>
                <input class="li-input" data-k="label" value="${this.escapeAttr(c.label||'')}" />
              </div>

              <div class="li-field">
                <label>Modo</label>
                <select class="li-input" data-k="link_mode">
                  <option value="menu" ${mode==='menu'?'selected':''}>Menú</option>
                  <option value="external" ${mode==='external'?'selected':''}>Link externo</option>
                  <option value="file" ${mode==='file'?'selected':''}>Archivo</option>
                </select>
              </div>

              <div class="li-field">
                <label>Ruta menú (ej: menu/capacitaciones/videos)</label>
                <input class="li-input" data-k="menu_path" placeholder="menu/..." value="${this.escapeAttr(c.menu_path||'')}" />
              </div>

              <div class="li-field">
                <label>Link externo</label>
                <input class="li-input" data-k="external_url" placeholder="https://..." value="${this.escapeAttr(c.external_url||'')}" />
              </div>

              <div class="li-field" style="grid-column:1/-1;">
                <label>Subir archivo (pdf / png / jpg / mp4 / webm)</label>
                <input type="file" class="li-input" data-file="file_${this.escapeAttr(c.id)}" />
                ${currentFileLine}
              </div>

              <div class="li-field">
                <label>Activo</label>
                <select class="li-input" data-k="is_active">
                  <option value="1" ${(c.is_active??true) ? 'selected' : ''}>Sí</option>
                  <option value="0" ${(!(c.is_active??true)) ? 'selected' : ''}>No</option>
                </select>
              </div>

              <div class="li-field">
                <label>Orden</label>
                <input class="li-input" data-k="sort" type="number" min="0" value="${this.escapeAttr(String(c.sort??0))}" />
              </div>

              <div class="li-field" style="grid-column:1/-1;">
                <button class="li-mbtn" type="button" onclick="LI_FOOT.previewFromRow(${idx})">Vista previa (según modo)</button>
              </div>
            </div>
          </div>
        `;
      }).join('');
    },

    previewFromRow(idx){
      const c = this.caps[idx];
      if(!c) return;

      const mode = c.link_mode || 'menu';
      let url = '';
      if (mode === 'external') url = (c.external_url||'').trim();
      if (mode === 'file') url = (c.file_url||'').trim();

      if (!url) {
        alert('No hay URL/archivo para vista previa en este registro.');
        return;
      }
      this.openPreview(url, c.label || 'Vista previa');
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

    async saveCaps(){
      const wrap = document.getElementById('liCapsEditor');
      if (!wrap) return;

      const items = [];
      const fd = new FormData();

      wrap.querySelectorAll('.li-row').forEach(row => {
        const idx = Number(row.getAttribute('data-idx'));
        if (Number.isNaN(idx)) return;

        const base = this.caps[idx] || {};
        const item = {
          id: base.id,
          key: base.key,
          label: base.label,
          link_mode: base.link_mode,
          menu_path: base.menu_path,
          external_url: base.external_url,
          sort: base.sort,
          is_active: base.is_active,
        };

        row.querySelectorAll('[data-k]').forEach(el => {
          const k = el.getAttribute('data-k');
          let v = (el.value ?? '').trim();

          if (k === 'sort') v = Number(v || 0);
          if (k === 'is_active') v = (v === '1');

          item[k] = v;
        });

        const fileInput = row.querySelector('[data-file]');
        if (fileInput && fileInput.files && fileInput.files[0]) {
          const fileKey = fileInput.getAttribute('data-file'); // file_ID
          fd.append(fileKey, fileInput.files[0]);
        }

        items.push(item);
      });

      fd.append('items', JSON.stringify(items));
      fd.append('_token', this.csrf);

      const res = await fetch(this.bulkSaveUrl, {
        method: 'POST',
        body: fd,
      });

      if (!res.ok) {
        alert('No se pudieron guardar los cambios.');
        return;
      }

      // recarga para re-leer BD (y que no se pierda)
      window.location.reload();
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

    // Copiar dirección (delegación)
    document.addEventListener('click', (e) => {
      const loc = e.target.closest('.li-loc[data-copy]');
      if(!loc) return;
      const txt = (loc.getAttribute('data-copy') || '').trim();
      if(!txt) return;
      LI_FOOT.copy(txt);
    });

    // Preview por data-preview
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[data-preview="media"]');
      if(!a) return;

      const href = a.getAttribute('href') || '';
      const title = a.getAttribute('data-title') || 'Vista previa';

      // abrir preview y evitar navegación
      e.preventDefault();
      e.stopPropagation();
      LI_FOOT.openPreview(href, title);
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape'){
        LI_FOOT.closeAll();
        LI_FOOT.closePreview();
      }

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
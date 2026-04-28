@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $mainImg = $product->image_path ? asset('storage/'.ltrim($product->image_path,'/')) : null;

  $steelColors = array_values(array_filter(array_map('trim', $specs['steel_colors'] ?? [])));
  $melColors   = array_values(array_filter(array_map('trim', $specs['melamine_colors'] ?? [])));

  $galleryArr = $gallery ?? [];

  $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin');

  $fichaUrl = !empty($product->ficha_tecnica_path) ? asset('storage/'.ltrim($product->ficha_tecnica_path,'/')) : null;
  $instUrl  = !empty($product->instructivo_path)   ? asset('storage/'.ltrim($product->instructivo_path,'/'))   : null;
@endphp

<style>
  :root{
    --ink:#0b1220;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);
    --rXL: 22px;
    --rL: 18px;
    --rM: 14px;
    --primary:#2563eb;
    --danger:#e11d48;
  }

  .pd-wrap{ max-width: 1200px; margin: 22px auto; padding: 0 18px; }
  .pd-top{
    display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
    margin-bottom: 14px;
  }
  .pd-title{ font-weight: 950; font-size: 22px; letter-spacing:-.02em; color: var(--ink); }
  .pd-sub{ margin-top:4px; font-weight: 700; color: rgba(15,23,42,.65); }

  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:.55rem; font-weight: 300; border-radius: 16px;
    padding: .72rem .92rem; font-size: .86rem;
    border: 1px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
    user-select:none; white-space:nowrap;
    text-decoration:none;
    cursor:pointer;
  }
  .btn:active{ transform: translateY(1px); }
  .btn-ghost{ background:#fff; border-color: var(--line); color: var(--ink); }
  .btn-ghost:hover{ background: rgba(248,250,252,.85); border-color: var(--line2); box-shadow: var(--shadowM); }
  .btn-primary{ background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1)); color:#fff; box-shadow: 0 14px 30px rgba(37,99,235,.22); }
  .btn-primary:hover{ opacity:.96; }
  .btn[disabled]{ opacity:.45; cursor:not-allowed; box-shadow:none; }

  .layout{
    display:grid;
    grid-template-columns: 1.75fr 1fr;
    gap: 16px;
    align-items:start;
  }
  @media(max-width: 980px){
    .layout{ grid-template-columns: 1fr; }
  }

  .card{
    border: 1px solid var(--line);
    border-radius: var(--rXL);
    background: #fff;
    box-shadow: 0 12px 26px rgba(2,6,23,.08);
    overflow:hidden;
  }
  .card-head{
    padding: 14px 16px;
    border-bottom: 1px solid rgba(15,23,42,.08);
    display:flex; align-items:center; justify-content:space-between;
    background: rgba(255,255,255,.78);
    gap: 10px;
  }
  .card-head .h{ font-weight: 950; color: var(--ink); }

  /* ====== Media / Slider ====== */
  .media{
    position:relative;
    background: #f3f4f6;
    overflow:hidden;
  }
  .media-frame{
    width:100%;
    aspect-ratio: 21/10;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
  }
  .media-frame img{
    width:100%;
    height:100%;
    object-fit: contain;
    display:block;
    background:#fff;
  }
  .media-empty{
    padding: 42px 18px;
    color:#94a3b8;
    font-weight: 300;
  }
  .nav{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:44px; height:44px;
    border-radius:999px;
    background: rgba(255,255,255,.92);
    border:1px solid rgba(15,23,42,.18);
    font-size:24px;
    display:flex; align-items:center; justify-content:center;
    cursor:pointer;
    box-shadow: 0 16px 34px rgba(2,6,23,.16);
  }
  .nav:hover{ background:#fff; }
  .nav.prev{ left: 12px; }
  .nav.next{ right: 12px; }

  .thumbs{
    display:flex;
    gap:10px;
    padding: 12px 14px;
    border-top: 1px solid rgba(15,23,42,.08);
    overflow:auto;
    background: rgba(15,23,42,.015);
  }
  .th{
    width: 92px;
    aspect-ratio: 16/10;
    border-radius: 12px;
    border: 1px solid rgba(15,23,42,.14);
    overflow:hidden;
    background:#fff;
    cursor:pointer;
    flex: 0 0 auto;
    position:relative;
  }
  .th img{ width:100%; height:100%; object-fit: contain; display:block; }
  .th.is-active{ outline: 4px solid rgba(37,99,235,.22); border-color: rgba(37,99,235,.45); }

  /* ====== Chips ====== */
  .chips{ padding: 14px 16px; display:flex; flex-wrap:wrap; gap:10px; }
  .chip{
    padding: 8px 12px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    font-weight: 300;
    font-size: 12.5px;
    cursor:pointer;
    user-select:none;
  }
  .chip:hover{ border-color: rgba(15,23,42,.22); }
  .chip.is-on{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 0 0 6px rgba(37,99,235,.14);
  }

  .hint{ padding: 0 16px 16px; font-weight: 750; color: rgba(15,23,42,.62); font-size: 12.5px; }

  /* ====== Right panels ====== */
  .info{ padding: 14px 16px; }
  .k{ font-weight: 950; color: var(--ink); }
  .v{ margin-top:6px; color: rgba(15,23,42,.72); font-weight: 650; line-height:1.35; }

  /* ====== PDF Modal ====== */
  .pdf-backdrop{
    position: fixed;
    inset:0;
    background: rgba(2,6,23,.72);
    backdrop-filter: blur(10px);
    display:none;
    z-index: 9998;
  }
  .pdf-modal{
    position: fixed;
    inset:0;
    display:none;
    z-index: 9999;
  }
  .pdf-backdrop.open, .pdf-modal.open{ display:block; }
  .pdf-shell{
    max-width: 980px;
    margin: 4vh auto;
    border-radius: 26px;
    overflow:hidden;
    background: #fff;
    border: 1px solid rgba(15,23,42,.14);
    box-shadow: var(--shadowXL);
  }
  .pdf-head{
    padding: 14px 16px;
    border-bottom: 1px solid rgba(15,23,42,.10);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
    background: rgba(255,255,255,.82);
  }
  .pdf-title{ font-weight: 950; color: var(--ink); }
  .pdf-frame{
    width: 100%;
    height: 76vh;
    border: 0;
    display:block;
  }

  .pdf-admin{
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed rgba(15,23,42,.18);
    display:grid;
    gap: 10px;
  }
  .file{
    width:100%;
    padding:.75rem 1rem;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
  }
  .mini{
    font-size: 12px;
    font-weight: 800;
    color: rgba(15,23,42,.65);
  }

  .no-scroll{ overflow:hidden !important; }
</style>

<div class="pd-wrap">

  <div class="pd-top">
    <div>
      <div class="pd-title">{{ $product->title }}</div>
      <div class="pd-sub">Detalle del producto</div>
    </div>
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <a class="btn btn-ghost" href="{{ $redirectTo }}">← Volver</a>

      @if($isAdmin)
        <button class="btn btn-primary" type="button" onclick="openEditProduct()">✎ Editar</button>
      @endif
    </div>
  </div>

  <div class="layout">

    {{-- LEFT: IMAGEN GRANDE + THUMBS --}}
    <div class="card">
      <div class="card-head">
        <div class="h">Galería</div>
        <div style="font-weight:900;color:rgba(15,23,42,.6);" id="pdFilterLabel">Sin filtros</div>
      </div>

      <div class="media">
        <div class="media-frame" id="pdMainFrame">
          @if($mainImg)
            <img id="pdMainImg" src="{{ $mainImg }}" alt="{{ $product->title }}">
          @else
            <div class="media-empty">Sin imagen principal</div>
          @endif
        </div>

        <button type="button" class="nav prev" id="pdPrev" style="display:none;">‹</button>
        <button type="button" class="nav next" id="pdNext" style="display:none;">›</button>
      </div>

      <div class="thumbs" id="pdThumbs"></div>
    </div>

    {{-- RIGHT: Variantes + Descripción arriba + botones PDF abajo --}}
    <div class="card">
      <div class="card-head">
        <div class="h">Variantes</div>
        <div style="font-weight:900;color:rgba(15,23,42,.6);">Estructura + Laminado</div>
      </div>

      {{-- ✅ DESCRIPCIÓN ARRIBA --}}
      <div class="info">
        <div class="k">Descripción</div>
        <div class="v">{{ $product->description ?: 'Sin descripción.' }}</div>
      </div>

      <div style="border-top:1px solid rgba(15,23,42,.08);"></div>

      {{-- ✅ ACERO + MELAMINA --}}
      <div class="info">
        <div class="k">Estructura</div>
        <div class="chips" id="chipsSteel"></div>

        <div class="k" style="margin-top:8px;">Laminado</div>
        <div class="chips" id="chipsMel"></div>

        <div class="hint" style="padding-left:0; padding-right:0;">
          Tip: si eliges <b>Estructura</b> + <b>Laminado</b>, se muestran solo las imágenes asignadas a esa combinación.
        </div>
      </div>

      <div style="border-top:1px solid rgba(15,23,42,.08);"></div>

      {{-- ✅ BOTONES PDF --}}
      <div class="info">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
          <button class="btn btn-primary"
                  type="button"
                  onclick="openPdf('Ficha técnica', @js($fichaUrl))"
                  @if(!$fichaUrl) disabled @endif
          >📄 Ficha técnica</button>

          <button class="btn btn-primary"
                  type="button"
                  onclick="openPdf('Instructivo', @js($instUrl))"
                  @if(!$instUrl) disabled @endif
          >📘 Instructivo</button>
        </div>

        @if($isAdmin)
          <div class="pdf-admin">
            <form method="POST"
                  action="{{ route('admin.menu-products.files.update', $product) }}"
                  enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

              <div>
                <div class="mini">Subir/Reemplazar Ficha técnica (PDF)</div>
                <input class="file" type="file" name="ficha_tecnica" accept="application/pdf">
                @if($fichaUrl)
                  <div class="mini">Actual: <a href="{{ $fichaUrl }}" target="_blank" style="text-decoration:underline;">Ver</a></div>
                @endif
              </div>

              <div>
                <div class="mini">Subir/Reemplazar Instructivo (PDF)</div>
                <input class="file" type="file" name="instructivo" accept="application/pdf">
                @if($instUrl)
                  <div class="mini">Actual: <a href="{{ $instUrl }}" target="_blank" style="text-decoration:underline;">Ver</a></div>
                @endif
              </div>

              <div style="display:flex; justify-content:flex-end; margin-top: 6px;">
                <button class="btn btn-ghost" type="submit">Guardar PDFs</button>
              </div>

              @if($errors->any())
                <div class="mini" style="color: var(--danger); font-weight:950;">
                  {{ $errors->first() }}
                </div>
              @endif
            </form>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

{{-- PDF MODAL --}}
<div id="pdfBackdrop" class="pdf-backdrop" onclick="closePdf()"></div>
<div id="pdfModal" class="pdf-modal">
  <div class="pdf-shell">
    <div class="pdf-head">
      <div class="pdf-title" id="pdfTitle">Documento</div>
      <button class="btn btn-ghost" type="button" onclick="closePdf()">Cerrar ✕</button>
    </div>
    <iframe id="pdfFrame" class="pdf-frame" src=""></iframe>
  </div>
</div>

<script>
  function lockBodyScroll(lock){
    const b = document.body;
    if(lock) b.classList.add('no-scroll');
    else b.classList.remove('no-scroll');
  }

  function openPdf(title, url){
    if(!url) return;
    document.getElementById('pdfTitle').textContent = title || 'Documento';
    document.getElementById('pdfFrame').src = url;

    document.getElementById('pdfBackdrop').classList.add('open');
    document.getElementById('pdfModal').classList.add('open');
    lockBodyScroll(true);
  }

  function closePdf(){
    document.getElementById('pdfBackdrop').classList.remove('open');
    document.getElementById('pdfModal').classList.remove('open');
    document.getElementById('pdfFrame').src = '';
    lockBodyScroll(false);
  }

  document.addEventListener('keydown', (e)=>{
    if(e.key === 'Escape') {
      closePdf();
      if (typeof closeEditProduct === 'function') closeEditProduct();
    }
  });
</script>

<script>
  window.PROD = @json([
    'id' => $product->id,
    'title' => $product->title,
    'main' => $mainImg,
    'steel' => $steelColors,
    'mel' => $melColors,
    'gallery' => array_map(function($it){
      return [
        'url' => asset('storage/'.ltrim($it['path'],'/')),
        'path' => ltrim($it['path'],'/'),
        'steel' => $it['steel'] ?? '',
        'melamine' => $it['melamine'] ?? '',
      ];
    }, $galleryArr),
  ]);

  (function(){
    const steel = window.PROD.steel || [];
    const mel = window.PROD.mel || [];
    const gallery = window.PROD.gallery || [];

    let selSteel = '';
    let selMel = '';
    let filtered = [];
    let idx = 0;

    const chipsSteel = document.getElementById('chipsSteel');
    const chipsMel = document.getElementById('chipsMel');
    const thumbs = document.getElementById('pdThumbs');
    const mainImg = document.getElementById('pdMainImg');
    const prev = document.getElementById('pdPrev');
    const next = document.getElementById('pdNext');
    const filterLabel = document.getElementById('pdFilterLabel');

    function chip(label, on){
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'chip' + (on ? ' is-on' : '');
      b.textContent = label;
      return b;
    }

    function renderChips(){
      chipsSteel.innerHTML = '';
      chipsMel.innerHTML = '';

      const allSteel = chip('Todos', selSteel==='');
      allSteel.addEventListener('click', ()=>{ selSteel=''; apply(); renderChips(); });
      chipsSteel.appendChild(allSteel);

      steel.forEach(c=>{
        const b = chip(c, selSteel===c);
        b.addEventListener('click', ()=>{
          selSteel = (selSteel===c) ? '' : c;
          apply(); renderChips();
        });
        chipsSteel.appendChild(b);
      });

      const allMel = chip('Todos', selMel==='');
      allMel.addEventListener('click', ()=>{ selMel=''; apply(); renderChips(); });
      chipsMel.appendChild(allMel);

      mel.forEach(c=>{
        const b = chip(c, selMel===c);
        b.addEventListener('click', ()=>{
          selMel = (selMel===c) ? '' : c;
          apply(); renderChips();
        });
        chipsMel.appendChild(b);
      });
    }

    function apply(){
      filtered = gallery.filter(it=>{
        const okS = selSteel ? (it.steel === selSteel) : true;
        const okM = selMel ? (it.melamine === selMel) : true;
        return okS && okM;
      });

      idx = 0;

      const a = selSteel ? `Acero: ${selSteel}` : '';
      const m = selMel ? `Melamina: ${selMel}` : '';
      filterLabel.textContent = (a||m) ? [a,m].filter(Boolean).join(' · ') : 'Sin filtros';

      renderGallery();
    }

    function renderGallery(){
      thumbs.innerHTML = '';
      const has = filtered.length > 0;
      const base = has ? filtered : (gallery.length ? gallery : []);

      if (!mainImg) return;

      const firstUrl = base[0]?.url || window.PROD.main || '';
      if (firstUrl) mainImg.src = firstUrl;

      base.forEach((it, i)=>{
        const d = document.createElement('div');
        d.className = 'th' + (i===0 ? ' is-active' : '');
        const im = document.createElement('img');
        im.src = it.url;
        d.appendChild(im);

        d.addEventListener('click', ()=>{
          idx = i;
          mainImg.src = base[idx].url;
          [...thumbs.querySelectorAll('.th')].forEach((x, k)=>x.classList.toggle('is-active', k===idx));
          updateNav(base.length);
        });

        thumbs.appendChild(d);
      });

      updateNav(base.length);
      prev.onclick = ()=>go(-1, base);
      next.onclick = ()=>go(+1, base);
    }

    function updateNav(len){
      if (len > 1) { prev.style.display='flex'; next.style.display='flex'; }
      else { prev.style.display='none'; next.style.display='none'; }
    }

    function go(step, base){
      if (!base.length) return;
      idx = Math.max(0, Math.min(base.length-1, idx + step));
      mainImg.src = base[idx].url;
      [...thumbs.querySelectorAll('.th')].forEach((x, k)=>x.classList.toggle('is-active', k===idx));
      updateNav(base.length);
    }

    renderChips();
    apply();
  })();
</script>

{{-- =========================
    ✅ MODAL EDICIÓN (solo admin) (tu mismo bloque intacto)
   ========================= --}}
@if($isAdmin)
  @php
    $specsSafe = is_array($product->specs) ? $product->specs : (json_decode((string)$product->specs, true) ?: []);
    $steelSafe = array_values(array_filter(array_map('trim', $specsSafe['steel_colors'] ?? [])));
    $melSafe   = array_values(array_filter(array_map('trim', $specsSafe['melamine_colors'] ?? [])));

    $gallerySafe = is_array($product->gallery_images) ? $product->gallery_images : (json_decode((string)$product->gallery_images, true) ?: []);
    $gallerySafe = array_values(array_filter(array_map(function($it){
      if(!is_array($it)) return null;
      $p = trim((string)($it['path'] ?? ''));
      if($p==='') return null;
      return [
        'path'=>ltrim($p,'/'),
        'steel'=>trim((string)($it['steel'] ?? '')),
        'melamine'=>trim((string)($it['melamine'] ?? '')),
      ];
    }, $gallerySafe)));
  @endphp

  <div id="epBackdrop" class="modal-backdrop fixed inset-0 hidden" onclick="closeEditProduct()"></div>
  <div id="epModal" class="fixed inset-0 hidden">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="modal-enter w-full max-w-4xl modal-shell">
        <div class="modal-header">
          <div>
            <div class="modal-title">Editar producto</div>
            <div class="modal-sub">Colores + galería con asignación (Estructura/Melamina)</div>
          </div>
          <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeEditProduct()">Cerrar ✕</button>
        </div>

        <form id="epForm" method="POST" action="{{ route('admin.menu-products.update', $product) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

          <input type="hidden" name="steel_colors_json" id="steel_colors_json" value='@json($steelSafe)'>
          <input type="hidden" name="melamine_colors_json" id="melamine_colors_json" value='@json($melSafe)'>

          <div class="modal-body">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <div class="md:col-span-6 space-y-4">
                <div>
                  <label class="field-label">Título</label>
                  <input class="input" name="title" value="{{ old('title', $product->title) }}" required>
                </div>

                <div>
                  <label class="field-label">Descripción</label>
                  <textarea class="textarea" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                  <label class="field-label">URL (opcional)</label>
                  <input class="input" name="url" value="{{ old('url', $product->url) }}">
                </div>

                <div class="flex items-center gap-2">
                  <input id="ep_active" type="checkbox" name="is_active" class="rounded" {{ $product->is_active ? 'checked' : '' }}>
                  <label for="ep_active" class="field-label" style="margin:0;">Activo</label>
                </div>

                <div>
                  <label class="field-label">Imagen principal (opcional)</label>
                  <input class="input" style="padding:.75rem 1rem;" type="file" name="image" accept="image/*" id="main_image_input">
                  <div class="hint" style="padding:8px 0 0 0;">Preview:</div>
                  <div style="border:1px solid rgba(15,23,42,.12); border-radius:16px; overflow:hidden; background:#f3f4f6;">
                    <div style="aspect-ratio:16/9; display:flex; align-items:center; justify-content:center;">
                      <img id="main_image_preview" src="{{ $mainImg ?: '' }}" style="width:100%;height:100%;object-fit:contain;display:{{ $mainImg ? 'block':'none' }};">
                      <div id="main_image_empty" style="font-weight:900;color:#94a3b8;display:{{ $mainImg ? 'none':'block' }};">Sin imagen</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="md:col-span-6 space-y-4">
                <div>
                  <div class="field-label">Colores de Estructura</div>
                  <div style="display:flex; gap:10px; margin-top:8px;">
                    <input id="steel_add" class="input" placeholder="Ej. Negro" style="flex:1;">
                    <button type="button" class="btn btn-primary" onclick="addColor('steel')">+ Agregar</button>
                  </div>
                  <div class="pillrow" id="steel_pills"></div>
                </div>

                <div>
                  <div class="field-label">Colores de Laminado</div>
                  <div style="display:flex; gap:10px; margin-top:8px;">
                    <input id="mel_add" class="input" placeholder="Ej. Encino" style="flex:1;">
                    <button type="button" class="btn btn-primary" onclick="addColor('mel')">+ Agregar</button>
                  </div>
                  <div class="pillrow" id="mel_pills"></div>
                </div>

                <div style="border-top:1px dashed rgba(15,23,42,.18); padding-top:12px;">
                  <div class="field-label">Subir imágenes para galería (con asignación)</div>
                  <input class="input" style="padding:.75rem 1rem;" type="file" name="gallery_images[]" id="gallery_input" accept="image/*" multiple>
                  <div class="hint" style="padding:8px 0 0 0;">
                    * Cada imagen debe asignarse a <b>Estructura</b> y <b>Laminado</b>.
                  </div>
                  <div class="upload-grid" id="upload_previews"></div>
                </div>
              </div>
            </div>

            <div style="margin-top:18px; border-top:1px solid rgba(15,23,42,.08); padding-top:14px;">
              <div class="field-label">Imágenes actuales (puedes reasignar o quitar)</div>

              @if(empty($gallerySafe))
                <div class="hint" style="padding:10px 0 0 0;">No hay imágenes todavía.</div>
              @else
                <div class="upload-grid">
                  @foreach($gallerySafe as $i => $it)
                    @php $u = asset('storage/'.ltrim($it['path'],'/')); @endphp
                    <div class="u-card">
                      <div class="u-prev"><img src="{{ $u }}" alt=""></div>
                      <div class="u-meta">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                          <label style="display:flex; align-items:center; gap:8px; font-weight:900;">
                            <input type="checkbox" name="remove_gallery[]" value="{{ $it['path'] }}">
                            Quitar
                          </label>
                          <a href="{{ $u }}" target="_blank" style="font-weight:900; text-decoration:none; opacity:.8;">Ver ↗</a>
                        </div>

                        <select class="select" name="existing_meta[{{ $i }}][steel]">
                          <option value="">Estructura (sin asignar)</option>
                          @foreach($steelSafe as $c)
                            <option value="{{ $c }}" {{ $it['steel']===$c ? 'selected':'' }}>{{ $c }}</option>
                          @endforeach
                        </select>

                        <select class="select" name="existing_meta[{{ $i }}][melamine]">
                          <option value="">Melamina (sin asignar)</option>
                          @foreach($melSafe as $c)
                            <option value="{{ $c }}" {{ $it['melamine']===$c ? 'selected':'' }}>{{ $c }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            <div class="mt-6 flex justify-end gap-2">
              <button type="button" class="btn btn-ghost" onclick="closeEditProduct()">Cancelar</button>
              <button class="btn btn-primary">Guardar cambios</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script>
    // Tu JS admin (igual que antes)...
    function lockBodyScroll(lock){
      const b = document.body;
      if(lock) b.classList.add('no-scroll');
      else b.classList.remove('no-scroll');
    }

    function openEditProduct(){
      document.getElementById('epBackdrop').classList.remove('hidden');
      const m = document.getElementById('epModal');
      m.classList.remove('hidden');
      m.classList.add('modal-open');
      lockBodyScroll(true);
      renderColorPills();
    }
    function closeEditProduct(){
      document.getElementById('epBackdrop').classList.add('hidden');
      const m = document.getElementById('epModal');
      m.classList.remove('modal-open');
      m.classList.add('hidden');
      lockBodyScroll(false);
    }

    function getJson(id){
      try { return JSON.parse(document.getElementById(id).value || '[]'); }
      catch(e){ return []; }
    }
    function setJson(id, arr){
      document.getElementById(id).value = JSON.stringify(arr || []);
    }

    function renderColorPills(){
      const steels = getJson('steel_colors_json');
      const mels = getJson('melamine_colors_json');

      const sp = document.getElementById('steel_pills');
      const mp = document.getElementById('mel_pills');
      sp.innerHTML = '';
      mp.innerHTML = '';

      steels.forEach((c, i)=>{
        const p = document.createElement('div');
        p.className = 'pill';
        p.innerHTML = `<span>${escapeHtml(c)}</span><button type="button" title="Quitar">×</button>`;
        p.querySelector('button').onclick = ()=>{ steels.splice(i,1); setJson('steel_colors_json', steels); renderColorPills(); refreshPreviewSelectors(); };
        sp.appendChild(p);
      });

      mels.forEach((c, i)=>{
        const p = document.createElement('div');
        p.className = 'pill';
        p.innerHTML = `<span>${escapeHtml(c)}</span><button type="button" title="Quitar">×</button>`;
        p.querySelector('button').onclick = ()=>{ mels.splice(i,1); setJson('melamine_colors_json', mels); renderColorPills(); refreshPreviewSelectors(); };
        mp.appendChild(p);
      });

      refreshPreviewSelectors();
    }

    function addColor(type){
      if(type === 'steel'){
        const el = document.getElementById('steel_add');
        const v = (el.value||'').trim();
        if(!v) return;
        const arr = getJson('steel_colors_json');
        if(!arr.includes(v)) arr.push(v);
        setJson('steel_colors_json', arr);
        el.value = '';
        renderColorPills();
      }else{
        const el = document.getElementById('mel_add');
        const v = (el.value||'').trim();
        if(!v) return;
        const arr = getJson('melamine_colors_json');
        if(!arr.includes(v)) arr.push(v);
        setJson('melamine_colors_json', arr);
        el.value = '';
        renderColorPills();
      }
    }

    const previewState = { items: [] };
    function refreshPreviewSelectors(){
      const steels = getJson('steel_colors_json');
      const mels = getJson('melamine_colors_json');

      previewState.items.forEach(it=>{
        if(it.steelEl){
          const val = it.steelEl.value;
          it.steelEl.innerHTML = `<option value="">Selecciona Estructura</option>` + steels.map(c=>`<option value="${escapeAttr(c)}">${escapeHtml(c)}</option>`).join('');
          it.steelEl.value = steels.includes(val) ? val : '';
        }
        if(it.melEl){
          const val = it.melEl.value;
          it.melEl.innerHTML = `<option value="">Selecciona Laminado</option>` + mels.map(c=>`<option value="${escapeAttr(c)}">${escapeHtml(c)}</option>`).join('');
          it.melEl.value = mels.includes(val) ? val : '';
        }
      });
    }

    function escapeHtml(s){
      return String(s).replace(/[&<>"']/g, (m)=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
    }
    function escapeAttr(s){ return escapeHtml(s); }

    window.openEditProduct = openEditProduct;
    window.closeEditProduct = closeEditProduct;
    window.addColor = addColor;
  </script>
@endif
@endsection
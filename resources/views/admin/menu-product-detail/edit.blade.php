  @extends('layouts.app')

  @section('content')
  @php
    $techUrl   = $product->tech_pdf_path   ? asset('storage/'.ltrim($product->tech_pdf_path,'/'))   : null;
    $manualUrl = $product->manual_pdf_path ? asset('storage/'.ltrim($product->manual_pdf_path,'/')) : null;

    $gallerySafe = $detail->images_safe ?? [];
    $gallerySafe = is_array($gallerySafe) ? $gallerySafe : [];

    $productAceroColors = collect($productAceroColors ?? [])
        ->map(fn($v) => trim((string)$v))
        ->filter()
        ->values()
        ->all();

    $productLaminadoColors = collect($productLaminadoColors ?? [])
        ->map(fn($v) => trim((string)$v))
        ->filter()
        ->values()
        ->all();
  @endphp

  <style>
    :root{
      --ep-ink:#0f172a;
      --ep-muted:#64748b;
      --ep-line:rgba(15,23,42,.10);
      --ep-line-2:rgba(15,23,42,.14);
      --ep-soft:#f8fafc;
      --ep-soft-2:#f1f5f9;
      --ep-primary:#2563eb;
      --ep-primary-2:#1d4ed8;
      --ep-success:#16a34a;
      --ep-danger:#e11d48;
      --ep-shadow:0 18px 46px rgba(15,23,42,.06);
      --ep-shadow-lg:0 24px 70px rgba(15,23,42,.10);
      --ep-radius:24px;
    }

    @media (min-width: 1024px){
      .ep-wrap{ padding-right: 118px; }
    }

    .ep-page{
      max-width: 1180px;
      margin: 0 auto;
      padding: 26px 18px 90px;
    }

    .ep-shell{
      background:#fff;
      border:1px solid var(--ep-line);
      border-radius: 28px;
      box-shadow: var(--ep-shadow);
      overflow: hidden;
    }

    .ep-head{
      padding: 28px 28px 22px;
      border-bottom:1px solid var(--ep-line);
      background:
        radial-gradient(1000px 220px at 10% 0%, rgba(37,99,235,.08), transparent 48%),
        linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,1));
    }

    .ep-head-top{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:16px;
      flex-wrap:wrap;
    }

    .ep-title{
      margin:0;
      font-size: 1.8rem;
      line-height:1.06;
      letter-spacing:-.03em;
      color:var(--ep-ink);
      font-weight:600;
    }

    .ep-sub{
      margin-top:8px;
      color:var(--ep-muted);
      font-size:.98rem;
      line-height:1.55;
    }

    .ep-meta{
      margin-top:16px;
      display:flex;
      flex-wrap:wrap;
      gap:10px;
    }

    .ep-chip{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:rgba(248,250,252,.95);
      border:1px solid var(--ep-line);
      color:#334155;
      font-size:.85rem;
      font-weight:500;
    }

    .ep-chip code{
      color:var(--ep-ink);
      font-weight:500;
      background:transparent;
    }

    .ep-flash{
      padding:11px 14px;
      border-radius:14px;
      font-size:.9rem;
      font-weight:500;
      border:1px solid transparent;
      white-space:nowrap;
    }
    .ep-flash.success{
      background:#ecfdf5;
      border-color:#bbf7d0;
      color:#166534;
    }
    .ep-flash.error{
      background:#fff1f2;
      border-color:#fecdd3;
      color:#be123c;
    }

    .ep-body{
      padding: 24px;
      display:grid;
      gap:18px;
    }

    .ep-card{
      border:1px solid var(--ep-line);
      border-radius:24px;
      background:#fff;
      box-shadow: 0 10px 28px rgba(15,23,42,.04);
      overflow:hidden;
    }

    .ep-card-head{
      padding:18px 20px 14px;
      border-bottom:1px solid rgba(15,23,42,.06);
      background:rgba(248,250,252,.55);
    }

    .ep-card-title{
      margin:0;
      font-size:1.03rem;
      color:var(--ep-ink);
      font-weight:600;
      letter-spacing:-.01em;
    }

    .ep-card-sub{
      margin-top:5px;
      color:var(--ep-muted);
      font-size:.9rem;
      line-height:1.45;
    }

    .ep-card-body{
      padding:20px;
    }

    .ep-grid-2{
      display:grid;
      grid-template-columns: 1.1fr .9fr;
      gap:18px;
    }

    .ep-grid-3{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:14px;
    }

    .ep-field{
      display:grid;
      gap:8px;
    }

    .ep-label{
      font-size:.88rem;
      font-weight:600;
      color:#334155;
    }

    .ep-help{
      font-size:.8rem;
      color:var(--ep-muted);
      line-height:1.45;
    }

    .ep-input,
    .ep-textarea,
    .ep-file{
      width:100%;
      border-radius:16px;
      border:1px solid var(--ep-line-2);
      background:#fff;
      color:var(--ep-ink);
      padding:12px 14px;
      font-size:.95rem;
      font-weight:400;
      outline:none;
      transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .ep-textarea{
      min-height: 122px;
      resize: vertical;
    }

    .ep-input:focus,
    .ep-textarea:focus,
    .ep-file:focus{
      border-color: rgba(37,99,235,.42);
      box-shadow: 0 0 0 5px rgba(37,99,235,.09);
      background:#fff;
    }

    .ep-pdf-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:16px;
    }

    .ep-pdf-box{
      border:1px solid var(--ep-line);
      border-radius:20px;
      background:linear-gradient(180deg, rgba(255,255,255,1), rgba(248,250,252,.86));
      padding:18px;
      display:grid;
      gap:14px;
    }

    .ep-pdf-top{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }

    .ep-pdf-name{
      font-size:1rem;
      font-weight:600;
      color:var(--ep-ink);
      line-height:1.2;
    }

    .ep-pdf-status{
      font-size:.83rem;
      color:var(--ep-muted);
      margin-top:4px;
    }

    .ep-actions{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      align-items:center;
    }

    .ep-check{
      display:inline-flex;
      align-items:center;
      gap:8px;
      font-size:.88rem;
      color:#334155;
      font-weight:500;
      white-space:nowrap;
    }

    .ep-check input{
      width:16px;
      height:16px;
      accent-color: var(--ep-primary);
    }

    .ep-btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      border-radius:16px;
      padding:11px 15px;
      font-size:.92rem;
      font-weight:500;
      text-decoration:none;
      border:1px solid transparent;
      cursor:pointer;
      transition: transform .14s ease, box-shadow .14s ease, background .14s ease, border-color .14s ease, opacity .14s ease;
      user-select:none;
      white-space:nowrap;
    }
    .ep-btn:hover{ transform: translateY(-1px); }

    .ep-btn-dark{
      background:#0f172a;
      color:#fff;
      box-shadow: 0 12px 24px rgba(15,23,42,.12);
    }

    .ep-btn-soft{
      background:#fff;
      color:var(--ep-ink);
      border-color: var(--ep-line-2);
    }

    .ep-btn-soft:hover{
      background:var(--ep-soft);
    }

    .ep-btn-primary{
      background:linear-gradient(180deg, var(--ep-primary), var(--ep-primary-2));
      color:#fff;
      box-shadow: 0 16px 34px rgba(37,99,235,.22);
    }

    .ep-btn-ghost{
      background:var(--ep-soft);
      color:#334155;
      border-color: var(--ep-line);
    }

    .ep-note{
      padding:12px 14px;
      border-radius:16px;
      background:#f8fafc;
      border:1px solid var(--ep-line);
      color:#475569;
      font-size:.86rem;
      line-height:1.5;
    }

    .ep-errors{
      padding:14px 16px;
      border-radius:18px;
      background:#fff1f2;
      border:1px solid #fecdd3;
      color:#be123c;
      font-size:.9rem;
      line-height:1.5;
    }

    .ep-errors ul{
      margin:0;
      padding-left:18px;
    }

    .ep-sticky-bar{
      position: sticky;
      bottom: 14px;
      z-index: 30;
      margin-top: 8px;
    }

    .ep-sticky-inner{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:14px;
      flex-wrap:wrap;
      padding:14px 16px;
      border-radius:20px;
      border:1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.96);
      backdrop-filter: blur(10px);
      box-shadow: var(--ep-shadow-lg);
    }

    .ep-sticky-text{
      color:#475569;
      font-size:.9rem;
      line-height:1.45;
    }

    .ep-sticky-actions{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
    }

    @media (max-width: 980px){
      .ep-grid-2,
      .ep-pdf-grid,
      .ep-grid-3{
        grid-template-columns: 1fr;
      }

      .ep-head,
      .ep-card-body{
        padding-left:18px;
        padding-right:18px;
      }
    }
    .ep-upload-grid{
      display:grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap:16px;
    }

    .ep-u-card{
      border:1px solid var(--ep-line);
      border-radius:20px;
      overflow:hidden;
      background:#fff;
      box-shadow: 0 8px 22px rgba(15,23,42,.04);
    }

    .ep-u-prev{
      aspect-ratio: 16 / 10;
      background:#f8fafc;
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
      border-bottom:1px solid rgba(15,23,42,.06);
    }

    .ep-u-prev img{
      width:100%;
      height:100%;
      object-fit:contain;
      display:block;
      background:#fff;
    }

    .ep-u-meta{
      padding:14px;
      display:grid;
      gap:10px;
    }

    .ep-pillrow{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-top:8px;
    }

    .ep-pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 10px;
      border-radius:999px;
      border:1px solid rgba(15,23,42,.14);
      background:rgba(15,23,42,.02);
      font-weight:800;
      font-size:12.5px;
    }

    .ep-pill button{
      width:22px;
      height:22px;
      border-radius:999px;
      border:1px solid rgba(225,29,72,.25);
      background:rgba(225,29,72,.08);
      cursor:pointer;
      font-weight:900;
      line-height:1;
    }

    @media (max-width: 980px){
      .ep-upload-grid{
        grid-template-columns: 1fr;
      }
    }

  </style>

  <div class="ep-page ep-wrap">
    <div class="ep-shell">

      <div class="ep-head">
        <div class="ep-head-top">
          <div class="min-w-0">
            <h1 class="ep-title">Editar detalle del producto</h1>
            <div class="ep-sub">
              Centraliza la información comercial y documental del producto. Desde aquí puedes actualizar descripción, medidas y archivos PDF visibles para el usuario.
            </div>

            <div class="ep-meta">
              <div class="ep-chip">Producto <code>{{ $product->title }}</code></div>
              <div class="ep-chip">ID <code>{{ $product->id }}</code></div>
            </div>
          </div>

          @if(session('success'))
            <div class="ep-flash success">
              {{ session('success') }}
            </div>
          @endif

          @if(session('status'))
            <div class="ep-flash success">
              {{ session('status') }}
            </div>
          @endif
        </div>
      </div>

      <form method="POST"
            action="{{ route('admin.product-details.update', $product) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

        <div class="ep-body">

          @if($errors->any())
            <div class="ep-errors">
              <ul>
                @foreach($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="ep-grid-2">
            {{-- Información base --}}
            <div class="ep-card">
              <div class="ep-card-head">
                <h2 class="ep-card-title">Información principal</h2>
                <div class="ep-card-sub">Edita el contenido que el usuario verá como presentación del producto.</div>
              </div>

              <div class="ep-card-body">
                <div class="ep-field">
                  <label class="ep-label">Título personalizado</label>
                  <input name="title"
                        value="{{ old('title', $detail->title) }}"
                        class="ep-input"
                        placeholder="Ej. Escritorio ejecutivo modular">
                  <div class="ep-help">Si lo dejas vacío, el sistema mostrará automáticamente el título principal del producto.</div>
                </div>

                <div class="ep-field" style="margin-top:16px;">
                  <label class="ep-label">Descripción</label>
                  <textarea name="description"
                            rows="5"
                            class="ep-textarea"
                            placeholder="Describe el producto, sus ventajas o su uso recomendado.">{{ old('description', $detail->description) }}</textarea>
                  <div class="ep-help">Usa una redacción clara y comercial para ayudar al usuario a entender mejor el producto.</div>
                </div>
              </div>
            </div>

            {{-- Medidas --}}
            <div class="ep-card">
              <div class="ep-card-head">
                <h2 class="ep-card-title">Dimensiones</h2>
                <div class="ep-card-sub">Registra las medidas principales del producto para referencia rápida.</div>
              </div>

              <div class="ep-card-body">
                <div class="ep-grid-3">
                  <div class="ep-field">
                    <label class="ep-label">Largo</label>
                    <input name="length"
                          value="{{ old('length', $detail->length) }}"
                          class="ep-input"
                          placeholder="Ej. 180 cm">
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Ancho</label>
                    <input name="width"
                          value="{{ old('width', $detail->width) }}"
                          class="ep-input"
                          placeholder="Ej. 80 cm">
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Alto</label>
                    <input name="height"
                          value="{{ old('height', $detail->height) }}"
                          class="ep-input"
                          placeholder="Ej. 75 cm">
                  </div>
                </div>

                <div class="ep-note" style="margin-top:16px;">
                  Mantén el mismo formato de captura en todas las medidas para que la lectura sea consistente en la plataforma.
                </div>
              </div>
            </div>
          </div>

  <div class="ep-card">
    <div class="ep-card-head">
      <h2 class="ep-card-title">Galería del producto</h2>
      <div class="ep-card-sub">
        Sube nuevas imágenes, asigna sus colores y administra las imágenes actuales del producto.
      </div>
    </div>

    <div class="ep-card-body">
      <div class="ep-field">
        <label class="ep-label">Subir nuevas imágenes</label>
        <input type="file"
              name="gallery_images[]"
              id="gallery_input"
              accept="image/*"
              multiple
              class="ep-file">
        <div class="ep-help">
          Puedes seleccionar varias imágenes al mismo tiempo y asignar a cada una su color de acero y laminado.
        </div>
      </div>

      <div id="upload_previews" class="ep-upload-grid" style="margin-top:16px;"></div>

      <div style="margin-top:20px; border-top:1px solid rgba(15,23,42,.08); padding-top:18px;">
        <div class="ep-label" style="margin-bottom:10px;">Imágenes actuales</div>

        @if(empty($gallerySafe))
          <div class="ep-note">
            No hay imágenes cargadas todavía.
          </div>
        @else
          <div class="ep-upload-grid">
            @foreach($gallerySafe as $i => $it)
              @php $u = asset('storage/'.ltrim($it['path'],'/')); @endphp

              <div class="ep-u-card">
                <div class="ep-u-prev">
                  <img src="{{ $u }}" alt="">
                </div>

                <div class="ep-u-meta">
                  <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
                    <label class="ep-check">
                      <input type="checkbox" name="remove_gallery[]" value="{{ $it['path'] }}">
                      Quitar imagen
                    </label>

                    <a href="{{ $u }}" target="_blank" class="ep-btn ep-btn-soft">Ver ↗</a>
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Acero</label>
                    <select class="ep-input" name="existing_meta[{{ $i }}][acero]">
                      <option value="">Acero (sin asignar)</option>
                      @foreach($productAceroColors as $c)
                        <option value="{{ $c }}" {{ (($it['acero'] ?? '') === $c) ? 'selected' : '' }}>
                          {{ $c }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Laminado</label>
                    <select class="ep-input" name="existing_meta[{{ $i }}][melamina]">
                      <option value="">Laminado (sin asignar)</option>
                      @foreach($productLaminadoColors as $c)
                        <option value="{{ $c }}" {{ (($it['melamina'] ?? '') === $c) ? 'selected' : '' }}>
                          {{ $c }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>


  <div class="ep-card">
    <div class="ep-card-head">
      <h2 class="ep-card-title">Colores globales y selección del producto</h2>
      <div class="ep-card-sub">
        Los colores se administran globalmente y aquí decides cuáles se muestran en este producto.
      </div>
    </div>

    <div class="ep-card-body">
      <div class="ep-grid-2">
        <div>
          <div class="ep-label" style="margin-bottom:10px;">Acero global</div>

          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <input type="text" id="new_global_acero" class="ep-input" placeholder="Ej. Negro">
            <button type="button" class="ep-btn ep-btn-dark" onclick="addGlobalColor('acero')">+ Agregar</button>
          </div>

          <div id="global_acero_list" style="display:grid;gap:10px;">
            @foreach($globalAcero as $color)
              <label class="ep-check" style="justify-content:space-between;border:1px solid rgba(15,23,42,.08);padding:10px 12px;border-radius:14px;">
                <span>
                  <input type="checkbox"
                        name="selected_color_ids[]"
                        value="{{ $color->id }}"
                        {{ in_array((int)$color->id, $selectedColorIds ?? [], true) ? 'checked' : '' }}>
                  {{ $color->name }}
                </span>
                <span style="font-size:.8rem;color:#64748b;">Global</span>
              </label>
            @endforeach
          </div>
        </div>

        <div>
          <div class="ep-label" style="margin-bottom:10px;">Laminado global</div>

          <div style="display:flex;gap:10px;margin-bottom:12px;">
            <input type="text" id="new_global_laminado" class="ep-input" placeholder="Ej. Encino">
            <button type="button" class="ep-btn ep-btn-dark" onclick="addGlobalColor('laminado')">+ Agregar</button>
          </div>

          <div id="global_laminado_list" style="display:grid;gap:10px;">
            @foreach($globalLaminado as $color)
              <label class="ep-check" style="justify-content:space-between;border:1px solid rgba(15,23,42,.08);padding:10px 12px;border-radius:14px;">
                <span>
                  <input type="checkbox"
                        name="selected_color_ids[]"
                        value="{{ $color->id }}"
                        {{ in_array((int)$color->id, $selectedColorIds ?? [], true) ? 'checked' : '' }}>
                  {{ $color->name }}
                </span>
                <span style="font-size:.8rem;color:#64748b;">Global</span>
              </label>
            @endforeach
          </div>
        </div>
      </div>

      <div class="ep-note" style="margin-top:16px;">
        Agregar un color aquí lo hace disponible para todos los productos. Marcarlo o desmarcarlo define si este producto lo muestra.
      </div>
    </div>
  </div>


          {{-- PDFs --}}
          <div class="ep-card">
            <div class="ep-card-head">
              <h2 class="ep-card-title">Documentación del producto</h2>
              <div class="ep-card-sub">
                Administra la ficha técnica y el instructivo. Aquí puedes reemplazar archivos, previsualizarlos o quitarlos del producto.
              </div>
            </div>

            <div class="ep-card-body">
              <div class="ep-pdf-grid">

                {{-- Ficha técnica --}}
                <div class="ep-pdf-box">
                  <div class="ep-pdf-top">
                    <div>
                      <div class="ep-pdf-name">Ficha técnica (PDF)</div>
                      <div class="ep-pdf-status">
                        {{ $techUrl ? 'Documento disponible para consulta.' : 'No hay ficha técnica cargada.' }}
                      </div>
                    </div>

                    <div class="ep-actions">
                      @if($techUrl)
                        <button type="button"
                                class="ep-btn ep-btn-soft"
                                onclick="window.openPdfPreview(@js($techUrl), 'Ficha técnica')">
                          Ver preview
                        </button>
                      @endif
                    </div>
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Seleccionar nuevo archivo</label>
                    <input type="file"
                          name="tech_pdf"
                          accept="application/pdf"
                          class="ep-file">
                  </div>

                  <div class="ep-actions">
                    @if($techUrl)
                      <label class="ep-check">
                        <input type="checkbox" name="remove_tech_pdf" value="1">
                        Quitar PDF actual
                      </label>
                    @endif
                  </div>

                  <div class="ep-help">
                    Si seleccionas un nuevo archivo y guardas cambios, la ficha técnica anterior será reemplazada.
                  </div>
                </div>

                {{-- Instructivo --}}
                <div class="ep-pdf-box">
                  <div class="ep-pdf-top">
                    <div>
                      <div class="ep-pdf-name">Instructivo (PDF)</div>
                      <div class="ep-pdf-status">
                        {{ $manualUrl ? 'Documento disponible para consulta.' : 'No hay instructivo cargado.' }}
                      </div>
                    </div>

                    <div class="ep-actions">
                      @if($manualUrl)
                        <button type="button"
                                class="ep-btn ep-btn-soft"
                                onclick="window.openPdfPreview(@js($manualUrl), 'Instructivo')">
                          Ver preview
                        </button>
                      @endif
                    </div>
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Seleccionar nuevo archivo</label>
                    <input type="file"
                          name="manual_pdf"
                          accept="application/pdf"
                          class="ep-file">
                  </div>

                  <div class="ep-actions">
                    @if($manualUrl)
                      <label class="ep-check">
                        <input type="checkbox" name="remove_manual_pdf" value="1">
                        Quitar PDF actual
                      </label>
                    @endif
                  </div>

                  <div class="ep-help">
                    Usa este documento para manuales de armado, instalación o uso del producto.
                  </div>
                </div>

              </div>
            </div>
          </div>

          {{-- Barra fija inferior --}}
          <div class="ep-sticky-bar">
            <div class="ep-sticky-inner">
              <div class="ep-sticky-text">
                Revisa los cambios antes de guardar. Esta acción actualizará la información visible del producto y sus archivos asociados.
              </div>

              <div class="ep-sticky-actions">
                <a href="{{ route('menu.product.show', ['menu_product' => $product->id, 'redirect_to' => $redirectTo]) }}" class="ep-btn ep-btn-ghost">
                  Volver
                </a>

                <button type="submit" class="ep-btn ep-btn-primary">
                  Guardar cambios
                </button>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>


      <script>
        const PRODUCT_ACERO_COLORS = @json($productAceroColors ?? []);
        const PRODUCT_LAMINADO_COLORS = @json($productLaminadoColors ?? []);

        function escapeHtml(text){
          return String(text || '').replace(/[&<>"']/g, function(m){
            return ({
              '&': '&amp;',
              '<': '&lt;',
              '>': '&gt;',
              '"': '&quot;',
              "'": '&#039;'
            })[m];
          });
        }

        function getCurrentAceroColors(){
          return Array.isArray(PRODUCT_ACERO_COLORS) ? PRODUCT_ACERO_COLORS : [];
        }

        function getCurrentLaminadoColors(){
          return Array.isArray(PRODUCT_LAMINADO_COLORS) ? PRODUCT_LAMINADO_COLORS : [];
        }

        function buildSelect(name, options, placeholder){
          const safeOptions = Array.isArray(options) ? options : [];
          const autoValue = safeOptions.length === 1 ? safeOptions[0] : '';

          return `
            <select class="ep-input" name="${name}">
              <option value="">${placeholder}</option>
              ${safeOptions.map(opt => `
                <option value="${escapeHtml(opt)}" ${autoValue === opt ? 'selected' : ''}>
                  ${escapeHtml(opt)}
                </option>
              `).join('')}
            </select>
          `;
        }

        function renderUploadPreviews(){
          const input = document.getElementById('gallery_input');
          const wrap = document.getElementById('upload_previews');
          if (!input || !wrap) return;

          wrap.innerHTML = '';

          const files = Array.from(input.files || []);
          const acero = getCurrentAceroColors();
          const laminado = getCurrentLaminadoColors();

          files.forEach((file, idx) => {
            const url = URL.createObjectURL(file);

            const card = document.createElement('div');
            card.className = 'ep-u-card';
            card.innerHTML = `
              <div class="ep-u-prev">
                <img src="${url}" alt="${escapeHtml(file.name)}">
              </div>

              <div class="ep-u-meta">
                <div style="font-weight:700;color:#334155;">${escapeHtml(file.name)}</div>

                <div class="ep-field">
                  <label class="ep-label">Acero</label>
                  ${buildSelect(`gallery_meta[${idx}][acero]`, acero, 'Acero (sin asignar)')}
                </div>

                <div class="ep-field">
                  <label class="ep-label">Laminado</label>
                  ${buildSelect(`gallery_meta[${idx}][melamina]`, laminado, 'Laminado (sin asignar)')}
                </div>
              </div>
            `;

            wrap.appendChild(card);
          });
        }

        document.addEventListener('DOMContentLoaded', function(){
          const galleryInput = document.getElementById('gallery_input');
          if (galleryInput) {
            galleryInput.addEventListener('change', renderUploadPreviews);
          }
        });
      </script>



  <script>
    async function addGlobalColor(type){
      const input = document.getElementById(type === 'acero' ? 'new_global_acero' : 'new_global_laminado');
      if (!input) return;

      const name = (input.value || '').trim();
      if (!name) return;

      const fd = new FormData();
      fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
      fd.append('type', type);
      fd.append('name', name);

      const res = await fetch(@json(route('admin.material-colors.store')), {
        method: 'POST',
        body: fd,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        }
      });

      if (!res.ok) {
        alert('No se pudo agregar el color global.');
        return;
      }

      window.location.reload();
    }
  </script>

  @endsection
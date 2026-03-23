@extends('layouts.app')

@section('content')
@php
  $techUrl   = $product->tech_pdf_path   ? asset('storage/'.ltrim($product->tech_pdf_path,'/'))   : null;
  $manualUrl = $product->manual_pdf_path ? asset('storage/'.ltrim($product->manual_pdf_path,'/')) : null;
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
@endsection
  @extends('layouts.app')

  @section('content')
  @php
$techUrl   = $product->tech_pdf_path   ? asset('storage/'.ltrim($product->tech_pdf_path,'/'))   : null;
$manualUrl = $product->manual_pdf_path ? asset('storage/'.ltrim($product->manual_pdf_path,'/')) : null;
$videoUrl  = $product->video_path      ? asset('storage/'.ltrim($product->video_path,'/'))     : null;

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
        max-width: 1160px;
        margin: 0 auto;
        padding: 18px 18px 90px;
      }

      .ep-shell{
        background:transparent;
        border:0;
        border-radius:0;
        box-shadow:none;
        overflow:visible;
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
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap:16px;
    }

    @media (max-width: 1180px){
      .ep-pdf-grid{
        grid-template-columns: 1fr 1fr;
      }
    }

    .ep-switch{
      position:relative;
      display:inline-flex;
      align-items:center;
      gap:10px;
      cursor:pointer;
      user-select:none;
      font-size:.88rem;
      color:#334155;
      font-weight:600;
    }

    .ep-switch input{
      position:absolute;
      opacity:0;
      pointer-events:none;
      width:0;
      height:0;
    }

    .ep-switch-track{
      width:42px;
      height:24px;
      border-radius:999px;
      background:#cbd5e1;
      position:relative;
      transition: background .18s ease;
      flex-shrink:0;
    }

    .ep-switch-track::after{
      content:'';
      position:absolute;
      top:3px;
      left:3px;
      width:18px;
      height:18px;
      border-radius:50%;
      background:#fff;
      box-shadow:0 1px 3px rgba(15,23,42,.25);
      transition: transform .18s ease;
    }

    .ep-switch input:checked + .ep-switch-track{
      background: var(--ep-primary);
    }

    .ep-switch input:checked + .ep-switch-track::after{
      transform: translateX(18px);
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

    /* =========================================
      DRAG & DROP UPLOADER
    ========================================= */

    .ep-dropzone{
        position:relative;
        border:2px dashed rgba(37,99,235,.22);
        background:
            linear-gradient(
                180deg,
                rgba(248,250,252,.96),
                rgba(255,255,255,1)
            );
        border-radius:24px;
        padding:34px 26px;
        text-align:center;
        transition:
            border-color .22s ease,
            background .22s ease,
            transform .22s ease,
            box-shadow .22s ease;
        overflow:hidden;
    }

    .ep-dropzone:hover{
        border-color:rgba(37,99,235,.45);
        box-shadow:0 18px 42px rgba(37,99,235,.10);
        transform:translateY(-1px);
    }

    .ep-dropzone.dragging{
        border-color:#2563eb;
        background:
            linear-gradient(
                180deg,
                rgba(219,234,254,.72),
                rgba(239,246,255,.92)
            );
        box-shadow:
            0 0 0 6px rgba(37,99,235,.10),
            0 22px 48px rgba(37,99,235,.14);
    }

    .ep-drop-icon{
        width:72px;
        height:72px;
        margin:0 auto 18px;
        border-radius:22px;
        background:
            linear-gradient(
                180deg,
                rgba(37,99,235,.12),
                rgba(37,99,235,.06)
            );
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:34px;
    }

    .ep-drop-title{
        font-size:1.08rem;
        font-weight:700;
        color:#0f172a;
        letter-spacing:-.01em;
    }

    .ep-drop-sub{
        margin-top:8px;
        color:#64748b;
        font-size:.92rem;
        line-height:1.55;
    }

    .ep-drop-browse{
        margin-top:18px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        height:44px;
        padding:0 18px;
        border-radius:14px;
        background:#2563eb;
        color:#fff;
        font-size:.9rem;
        font-weight:600;
        cursor:pointer;
        transition:.2s ease;
        box-shadow:0 14px 28px rgba(37,99,235,.18);
    }

    .ep-drop-browse:hover{
        transform:translateY(-1px);
        background:#1d4ed8;
    }

    .ep-drop-meta{
        margin-top:14px;
        font-size:.8rem;
        color:#94a3b8;
    }

    .ep-dropzone input[type="file"]{
        display:none;
    }

    .ep-upload-grid{
      display:grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap:12px;
    }

    .ep-u-card{
      border:1px solid var(--ep-line);
      border-radius:20px;
      overflow:hidden;
      background:#fff;
      box-shadow: 0 8px 22px rgba(15,23,42,.04);
    }

.ep-u-prev{
  aspect-ratio: 4 / 3;
  max-height: 160px;
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
  padding:10px;
  display:grid;
  gap:8px;
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

.ep-color-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:18px;
}

.ep-color-panel{
  border:1px solid rgba(15,23,42,.10);
  border-radius:22px;
  padding:16px;
  background:#fff;
}

.ep-color-head{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:12px;
  margin-bottom:12px;
}

.ep-color-title{
  font-size:.95rem;
  font-weight:600;
  color:#0f172a;
}

.ep-color-sub{
  margin-top:3px;
  font-size:.78rem;
  color:#64748b;
}

.ep-color-add{
  display:grid;
  grid-template-columns:1fr auto;
  gap:10px;
  margin-bottom:14px;
}

.ep-color-list{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
}

.ep-color-chip-row{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:5px 6px 5px 9px;
  border:1px solid rgba(15,23,42,.12);
  border-radius:999px;
  background:#f8fafc;
}

.ep-color-chip{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:.84rem;
  color:#334155;
  cursor:pointer;
  white-space:nowrap;
}

.ep-color-chip input{
  width:14px;
  height:14px;
  accent-color:#2563eb;
}

.ep-color-delete{
  width:24px;
  height:24px;
  border-radius:999px;
  border:0;
  background:#ffe4e6;
  color:#be123c;
  font-weight:800;
  cursor:pointer;
  line-height:1;
}

.ep-color-delete:hover{
  background:#fecdd3;
}

@media(max-width: 980px){
  .ep-color-grid{
    grid-template-columns:1fr;
  }

  .ep-color-add{
    grid-template-columns:1fr;
  }
}

.ep-u-meta .ep-field{
  gap:5px;
}

.ep-u-meta .ep-label{
  font-size:.75rem;
}

.ep-u-meta .ep-input{
  border-radius:12px;
  padding:8px 10px;
  font-size:.82rem;
}

.ep-u-meta .ep-check{
  font-size:.78rem;
}

.ep-u-meta .ep-btn{
  padding:7px 10px;
  border-radius:12px;
  font-size:.78rem;
}

@media (max-width: 1200px){
  .ep-upload-grid{
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (max-width: 780px){
  .ep-upload-grid{
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 520px){
  .ep-upload-grid{
    grid-template-columns: 1fr;
  }
}
.ep-gallery-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:14px;
    margin-top:20px;
}

.ep-gallery-item{
    aspect-ratio:1/1;
    border-radius:18px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.08);
    background:#f8fafc;
    cursor:pointer;
    transition:.2s ease;
}

.ep-gallery-item:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(15,23,42,.08);
}

.ep-gallery-item img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.ep-image-modal{
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.55);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:99999;
    backdrop-filter:blur(6px);
}

.ep-image-modal-card{
    width:min(1100px,95vw);
    background:#fff;
    border-radius:28px;
    overflow:hidden;
}

.ep-image-modal-head{
    padding:24px;
    border-bottom:1px solid rgba(15,23,42,.08);

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.ep-image-modal-title{
    font-size:1.3rem;
    font-weight:700;
    color:#0f172a;
}

.ep-image-modal-sub{
    margin-top:4px;
    color:#64748b;
}

.ep-modal-close{
    width:40px;
    height:40px;
    border-radius:999px;
    border:0;
    background:#f1f5f9;
    font-size:24px;
    cursor:pointer;
}

.ep-image-modal-body{
    padding:24px;

    display:grid;
    grid-template-columns:380px 1fr;
    gap:30px;
}

.ep-image-preview{
    border-radius:20px;
    overflow:hidden;
    background:#f8fafc;
    border:1px solid rgba(15,23,42,.08);
}

.ep-image-preview img{
    width:100%;
    display:block;
}

.ep-image-group-title{
    font-weight:700;
    margin-bottom:12px;
    color:#0f172a;
}

.ep-chip-wrap{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
}

.ep-select-chip{
    border-radius:999px;
    border:1px solid rgba(15,23,42,.12);
    background:#fff;
    padding:10px 14px;
    cursor:pointer;
    transition:.2s ease;
}

.ep-select-chip.active{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
}

.ep-image-modal-footer{
    padding:24px;
    border-top:1px solid rgba(15,23,42,.08);

    display:flex;
    justify-content:flex-end;
    gap:12px;
}
.ep-cover-check{
    margin-top:24px;
    display:flex;
    align-items:center;
    gap:12px;
    border:1px solid rgba(15,23,42,.12);
    border-radius:16px;
    padding:14px 16px;
    cursor:pointer;
    background:#fff;
    font-weight:600;
    color:#0f172a;
}

.ep-cover-check input{
    width:20px;
    height:20px;
    accent-color:#2563eb;
}

.ep-gallery-item.is-cover{
    outline:4px solid rgba(37,99,235,.35);
    border-color:#2563eb;
}

.ep-gallery-cover-badge{
    position:absolute;
    top:8px;
    left:8px;
    background:#2563eb;
    color:#fff;
    width:22px;
    height:22px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:900;
}

.ep-gallery-scroll{
    margin-top:20px;
    max-height:520px;
    overflow-y:auto;
    overflow-x:hidden;
    padding:4px 8px 4px 2px;
}

.ep-gallery-grid{
    display:grid;
    grid-template-columns:repeat(5, minmax(0, 1fr));
    gap:14px;
}

.ep-gallery-card{
    position:relative;
}

.ep-gallery-item{
    position:relative;
    width:100%;
    aspect-ratio:1/1;
    border-radius:18px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.08);
    background:#f8fafc;
    cursor:pointer;
    transition:.2s ease;
    padding:0;
    display:block;
}

.ep-gallery-item:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(15,23,42,.08);
}

.ep-gallery-item img{
    width:100%;
    height:100%;
    object-fit:contain;
    display:block;
    pointer-events:none;
}

.ep-status-dot{
    position:absolute;
    top:9px;
    right:9px;
    width:14px;
    height:14px;
    border-radius:999px;
    z-index:5;
    border:2px solid #fff;
    box-shadow:0 4px 12px rgba(15,23,42,.22);
}

.status-green{
    background:#16a34a;
}

.status-yellow{
    background:#f59e0b;
}

.status-red{
    background:#dc2626;
}

.ep-gallery-item.is-cover{
    outline:4px solid rgba(37,99,235,.35);
    border-color:#2563eb;
}

.ep-gallery-cover-badge{
    position:absolute;
    top:8px;
    left:8px;
    background:#2563eb;
    color:#fff;
    width:22px;
    height:22px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:900;
    z-index:6;
}

@media(max-width:1100px){
    .ep-gallery-grid{
        grid-template-columns:repeat(4, minmax(0, 1fr));
    }
}

@media(max-width:780px){
    .ep-gallery-grid{
        grid-template-columns:repeat(3, minmax(0, 1fr));
    }
}

@media(max-width:520px){
    .ep-gallery-grid{
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }
}
.ep-topbar{
    background:#fff;
    border:1px solid rgba(15,23,42,.10);
    border-radius:16px;
    padding:18px 26px;
    margin-bottom:18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:18px;
    box-shadow:0 8px 24px rgba(15,23,42,.04);
}

.ep-breadcrumb{
    display:flex;
    align-items:center;
    gap:9px;
    font-size:.78rem;
    color:#94a3b8;
    margin-bottom:8px;
}

.ep-breadcrumb a{
    color:#94a3b8;
    text-decoration:none;
}

.ep-edit-title{
    margin:0;
    color:#0f172a;
    font-size:1.35rem;
    font-weight:700;
    letter-spacing:-.02em;
}

.ep-edit-meta{
    margin-top:4px;
    color:#64748b;
    font-size:.82rem;
}

.ep-top-actions{
    display:flex;
    align-items:center;
    gap:12px;
}

.ep-cancel-btn,
.ep-save-btn{
    height:44px;
    padding:0 18px;
    border-radius:8px;
    font-size:.9rem;
    font-weight:500;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.ep-cancel-btn{
    background:#fff;
    color:#0f172a;
    border:1px solid rgba(15,23,42,.12);
}

.ep-save-btn{
    background:#8fb3f4;
    color:#fff;
    border:0;
}

@media(max-width:780px){
    .ep-topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    .ep-top-actions{
        width:100%;
    }

    .ep-cancel-btn,
    .ep-save-btn{
        flex:1;
    }
}

.ep-main-card{
    overflow:hidden;
}

.ep-main-layout{
    display:grid;
    grid-template-columns:220px 1fr;
    gap:22px;
    padding:22px;
}

.ep-main-side{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.ep-side-cover{
    width:100%;
    aspect-ratio:1/1;
    border-radius:14px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.08);
    background:#f8fafc;
}

.ep-side-cover img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.ep-side-empty{
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
    color:#94a3b8;
}

.ep-side-upload{
    width:100%;
    justify-content:center;
}

.ep-main-content{
    min-width:0;
}

.ep-main-content .ep-input{
    height:46px;
}

.ep-main-content .ep-textarea{
    min-height:260px;
    resize:vertical;
}

@media(max-width:980px){

    .ep-main-layout{
        grid-template-columns:1fr;
    }

    .ep-main-side{
        max-width:260px;
    }
}

.ep-basic-layout{
    display:grid;
    grid-template-columns:190px 1fr;
    gap:28px;
    padding:22px 26px;
}

.ep-basic-side{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.ep-basic-content{
    min-width:0;
}

.ep-cover-box{
    width:100%;
    aspect-ratio:1/1;
    border-radius:10px;
    border:1px solid rgba(15,23,42,.10);
    background:#eaf0f7;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
}

.ep-cover-box img{
    width:100%;
    height:100%;
    object-fit:contain;
    display:block;
}

.ep-cover-empty{
    color:#94a3b8;
    font-size:34px;
}

.ep-cover-edit{
    height:34px;
    border-radius:6px;
    border:1px solid rgba(15,23,42,.12);
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:.82rem;
    color:#0f172a;
}

.ep-description-area{
    min-height:325px;
}

@media(max-width:900px){
    .ep-basic-layout{
        grid-template-columns:1fr;
    }

    .ep-basic-side{
        max-width:220px;
    }
}

.ep-main-card{
    width:100%;
}

.ep-basic-layout{
    grid-template-columns:210px 1fr;
}

.ep-description-area{
    min-height:330px;
    height:330px;
}
  </style>

  <div class="ep-page ep-wrap">
    <div class="ep-shell">

<div class="ep-topbar">
    <div>
        <div class="ep-breadcrumb">
            <a href="{{ $redirectTo }}">← Productos</a>
            <span>›</span>
            <span>{{ $product->title }}</span>
            <span>›</span>
            <span>Editar</span>
        </div>

        <h1 class="ep-edit-title">{{ $product->title }}</h1>

        <div class="ep-edit-meta">
            SKU {{ $product->sku ?? $product->title }}
            · ID interno {{ $product->id }}
            · Editado hace 2 días
        </div>
    </div>

    <div class="ep-top-actions">
        <a href="{{ $redirectTo }}" class="ep-cancel-btn">
            Cancelar
        </a>

          <button type="submit" form="productDetailForm" class="ep-save-btn">
              Guardar cambios
          </button>
    </div>
</div>

        <form id="productDetailForm"
              method="POST"
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

{{-- Información básica --}}
<div class="ep-card ep-main-card">

    <div class="ep-card-head">
        <h2 class="ep-card-title">Información básica</h2>
        <div class="ep-card-sub">
            Datos que el usuario verá como presentación del producto.
        </div>
    </div>

    <div class="ep-basic-layout">

        {{-- COLUMNA IZQUIERDA --}}
        <div class="ep-basic-side">

            <div class="ep-field">
                <label class="ep-label">Portada</label>

                <div class="ep-cover-box">
                    @if($product->image_path)
                        <img
                            src="{{ asset('storage/'.ltrim($product->image_path,'/')) }}"
                            alt="{{ $product->title }}"
                        >
                    @else
                        <div class="ep-cover-empty">▧</div>
                    @endif
                </div>
            </div>

            <div class="ep-field">
                <label class="ep-label">SKU</label>
                <input
                    name="product_title"
                    value="{{ old('product_title', $product->title) }}"
                    class="ep-input"
                >
            </div>

            <div class="ep-field">
                <label class="ep-label">Código de ingeniería</label>
                <input
                    name="ingenieria_code"
                    value="{{ old('ingenieria_code', $product->ingenieria_code) }}"
                    class="ep-input"
                    placeholder="Ej. 1214"
                >
                <div class="ep-help">
                    El sistema buscará 1214F, 1214L, etc.
                </div>
            </div>

        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="ep-basic-content">

            <div class="ep-field">
                <label class="ep-label">Nombre del producto</label>
                <input
                    name="title"
                    value="{{ old('title', $detail->title ?: $product->title) }}"
                    class="ep-input"
                    placeholder="Ej. Escritorio 180 x 80"
                >
            </div>

            <div class="ep-field" style="margin-top:18px;">
                <label class="ep-label">Descripción</label>
                <textarea
                    name="description"
                    rows="11"
                    class="ep-textarea ep-description-area"
                >{{ old('description', $detail->description) }}</textarea>
            </div>

        </div>

    </div>

</div>

  {{-- GALERÍA NUEVA --}}
<div class="ep-card">

    <div class="ep-card-head">
        <h2 class="ep-card-title">Galería del producto</h2>

        <div class="ep-card-sub">
            Haz click sobre una imagen para etiquetar sus colores.
        </div>
    </div>

    <div class="ep-card-body">

        {{-- SUBIDA --}}
        <div class="ep-field">
            <label class="ep-label">Subir imágenes</label>

          <div
              class="ep-dropzone"
              id="gallery_dropzone"
          >

              <div class="ep-drop-icon">
                  🖼
              </div>

              <div class="ep-drop-title">
                  Arrastra imágenes aquí
              </div>

              <div class="ep-drop-sub">
                  O selecciónalas manualmente desde tu computadora.
                  <br>
                  Puedes subir múltiples imágenes al mismo tiempo.
              </div>

              <label
                  for="gallery_input"
                  class="ep-drop-browse"
              >
                  Seleccionar imágenes
              </label>

              <div class="ep-drop-meta">
                  JPG · PNG · WEBP
              </div>

              <div
                  id="gallery_count"
                  style="
                      margin-top:8px;
                      font-size:.82rem;
                      color:#2563eb;
                      font-weight:600;
                  "
              ></div>

              <input
                  type="file"
                  name="gallery_images[]"
                  id="gallery_input"
                  accept="image/*"
                  multiple
              >
          </div>

<div id="upload_previews" class="ep-upload-grid" style="margin-top:16px;"></div>


        </div>

<div class="ep-gallery-scroll">
    <div class="ep-gallery-grid">

        @foreach($gallerySafe as $i => $it)

            @php
                $imgUrl = asset('storage/'.ltrim($it['path'],'/'));
                $acero = trim($it['acero'] ?? '');
                $melamina = trim($it['melamina'] ?? '');

                $statusClass = 'status-red';

                if ($acero && $melamina) {
                    $statusClass = 'status-green';
                } elseif ($acero || $melamina) {
                    $statusClass = 'status-yellow';
                }
            @endphp

            <div class="ep-gallery-card">

                <button
                    type="button"
class="ep-gallery-item"
data-index="{{ $i }}"
onclick='openImageTagger(
                        {{ $i }},
                        @json($imgUrl),
                        @json($acero),
                        @json($melamina)
                    )'
                >
                    <span class="ep-status-dot {{ $statusClass }}"></span>

                    @if($product->image_path && ltrim($product->image_path, '/') === ltrim($it['path'], '/'))
                        <span class="ep-gallery-cover-badge">★</span>
                    @endif

                    <img src="{{ $imgUrl }}" alt="">
                </button>

                <input
                    type="hidden"
                    name="existing_meta[{{ $i }}][acero]"
                    id="existing_acero_{{ $i }}"
                    value="{{ $acero }}"
                >

                <input
                    type="hidden"
                    name="existing_meta[{{ $i }}][melamina]"
                    id="existing_mela_{{ $i }}"
                    value="{{ $melamina }}"
                >

                <input
                    type="hidden"
                    name="existing_meta[{{ $i }}][cover]"
                    id="existing_cover_{{ $i }}"
                    value="{{ $product->image_path && ltrim($product->image_path, '/') === ltrim($it['path'], '/') ? '1' : '0' }}"
                >

                <input
                    type="checkbox"
                    name="remove_gallery[]"
                    id="remove_gallery_{{ $i }}"
                    value="{{ $it['path'] }}"
                    style="display:none;"
                >

            </div>

        @endforeach

    </div>
</div>

    </div>

</div>

{{-- MODAL TAGGER --}}
<div class="ep-image-modal" id="imageTaggerModal">

    <div class="ep-image-modal-card">

        <div class="ep-image-modal-head">

            <div>
                <div class="ep-image-modal-title">
                    Etiquetar imagen
                </div>

                <div class="ep-image-modal-sub">
                    Selecciona colores de estructura y laminado.
                </div>
            </div>

            <button
                type="button"
                class="ep-modal-close"
                onclick="closeImageTagger()"
            >
                ×
            </button>

        </div>

        <div class="ep-image-modal-body">

            {{-- IMAGE --}}
            <div class="ep-image-preview">
                <img id="taggerPreview" src="">
            </div>

            {{-- COLORS --}}
            <div class="ep-image-options">

                {{-- ESTRUCTURA --}}
                <div class="ep-image-group">

                    <div class="ep-image-group-title">
                        Estructura
                    </div>

                    <div class="ep-chip-wrap">

                        @foreach($productAceroColors as $color)

                            <button
                                type="button"
                                class="ep-select-chip"
                                data-type="acero"
                                data-value="{{ $color }}"
                            >
                                {{ $color }}
                            </button>

                        @endforeach

                    </div>

                </div>

                {{-- LAMINADO --}}
                <div class="ep-image-group" style="margin-top:20px;">

                    <div class="ep-image-group-title">
                        Laminado
                    </div>

                    <div class="ep-chip-wrap">

                        @foreach($productLaminadoColors as $color)

                            <button
                                type="button"
                                class="ep-select-chip"
                                data-type="melamina"
                                data-value="{{ $color }}"
                            >
                                {{ $color }}
                            </button>

                        @endforeach

                    </div>

                </div>

                  <label class="ep-cover-check">
                      <input type="checkbox" id="taggerCover">
                      <span>⭐ Marcar como portada</span>
                  </label>

            </div>

        </div>

          <div class="ep-image-modal-footer" style="justify-content:space-between;">

              <button
                  type="button"
                  class="ep-btn"
                  style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3;"
                  onclick="deleteCurrentImage()"
              >
                  🗑 Eliminar imagen
              </button>

              <div style="display:flex;gap:12px;">
                  <button
                      type="button"
                      class="ep-btn ep-btn-soft"
                      onclick="closeImageTagger()"
                  >
                      Cancelar
                  </button>

                  <button
                      type="button"
                      class="ep-btn ep-btn-primary"
                      onclick="applyImageTags()"
                  >
                      Aplicar
                  </button>
              </div>

          </div>

    </div>

</div>


<div class="ep-card">
  <div class="ep-card-head">
    <h2 class="ep-card-title">Colores globales y selección del producto</h2>
    <div class="ep-card-sub">
      Administra colores globales y selecciona cuáles aplican a este producto.
    </div>
  </div>

  <div class="ep-card-body">
    <div class="ep-color-grid">

      {{-- ESTRUCTURA --}}
      <div class="ep-color-panel">
        <div class="ep-color-head">
          <div>
            <div class="ep-color-title">Estructura global</div>
            <div class="ep-color-sub">Antes Acero</div>
          </div>
        </div>

        <div class="ep-color-add">
          <input type="text" id="new_global_acero" class="ep-input" placeholder="Ej. Negro">
          <button type="button" class="ep-btn ep-btn-dark" onclick="addGlobalColor('acero')">
            + Agregar
          </button>
        </div>

        <div id="global_acero_list" class="ep-color-list">
          @foreach($globalAcero as $color)
            <div class="ep-color-chip-row">
              <label class="ep-color-chip">
                <input type="checkbox"
                       name="selected_color_ids[]"
                       value="{{ $color->id }}"
                       {{ in_array((int)$color->id, $selectedColorIds ?? [], true) ? 'checked' : '' }}>
                <span>{{ $color->name }}</span>
              </label>

<button type="button"
        class="ep-color-delete"
        onclick="deleteMaterialColor('{{ route('admin.material-colors.destroy', $color) }}')">
  ×
</button>
            </div>
          @endforeach
        </div>
      </div>

      {{-- LAMINADO --}}
      <div class="ep-color-panel">
        <div class="ep-color-head">
          <div>
            <div class="ep-color-title">Laminado global</div>
            <div class="ep-color-sub">Laminado  / acabados</div>
          </div>
        </div>

        <div class="ep-color-add">
          <input type="text" id="new_global_laminado" class="ep-input" placeholder="Ej. Encino">
          <button type="button" class="ep-btn ep-btn-dark" onclick="addGlobalColor('laminado')">
            + Agregar
          </button>
        </div>

        <div id="global_laminado_list" class="ep-color-list">
          @foreach($globalLaminado as $color)
            <div class="ep-color-chip-row">
              <label class="ep-color-chip">
                <input type="checkbox"
                       name="selected_color_ids[]"
                       value="{{ $color->id }}"
                       {{ in_array((int)$color->id, $selectedColorIds ?? [], true) ? 'checked' : '' }}>
                <span>{{ $color->name }}</span>
              </label>

<button type="button"
        class="ep-color-delete"
        onclick="deleteMaterialColor('{{ route('admin.material-colors.destroy', $color) }}')">
  ×
</button>
            </div>
          @endforeach
        </div>
      </div>

    </div>

    <div class="ep-note" style="margin-top:16px;">
      Marca los colores que este producto debe mostrar. El botón × elimina el color global.
    </div>
  </div>
</div>


          {{-- PDFs y Video --}}
          <div class="ep-card">
            <div class="ep-card-head">
              <h2 class="ep-card-title">Documentación del producto</h2>
              <div class="ep-card-sub">
                Administra la ficha técnica, el instructivo y el video. Aquí puedes reemplazar archivos, previsualizarlos o quitarlos del producto.
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
                          accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp,.svg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/*"
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
                          accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp,.svg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/*"
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

                {{-- Video --}}
                @php
                  $videoPath = $product->video_path ?? null;
                  $videoUrlAdmin = $videoPath ? asset('storage/' . ltrim($videoPath, '/')) : null;
                  $videoEnabled = (bool) ($product->video_enabled ?? true);
                @endphp
                <div class="ep-pdf-box">
                  <div class="ep-pdf-top">
                    <div>
                      <div class="ep-pdf-name">Video (MP4 / WEBM / Imagen)</div>
                      <div class="ep-pdf-status">
                        {{ $videoUrlAdmin ? 'Archivo disponible para consulta.' : 'No hay video cargado.' }}
                      </div>
                    </div>

                    <div class="ep-actions">
                      @if($videoUrlAdmin)
                        <button type="button"
                                class="ep-btn ep-btn-soft"
                                onclick="window.openPdfPreview(@js($videoUrlAdmin), 'Video')">
                          Ver preview
                        </button>
                      @endif
                    </div>
                  </div>

                  <div class="ep-field">
                    <label class="ep-label">Seleccionar nuevo archivo</label>
                    <input type="file"
                          name="video_file"
                          accept=".mp4,.webm,.ogg,.mov,.jpg,.jpeg,.png,.gif,.webp,video/*,image/*"
                          class="ep-file">
                  </div>

                  <div class="ep-actions" style="justify-content:space-between;">
                    <label class="ep-switch" title="Habilitar / deshabilitar botón de Video en la página pública">
                      <input type="hidden" name="video_enabled" value="0">
                      <input type="checkbox" name="video_enabled" value="1" {{ $videoEnabled ? 'checked' : '' }}>
                      <span class="ep-switch-track"></span>
                      <span>Mostrar botón de Video</span>
                    </label>

                    @if($videoUrlAdmin)
                      <label class="ep-check">
                        <input type="checkbox" name="remove_video" value="1">
                        Quitar archivo actual
                      </label>
                    @endif
                  </div>

                  <div class="ep-help">
                    Si el switch está desactivado, el botón de Video no se mostrará en la página pública aunque exista un archivo cargado.
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

<button
  type="submit"
  form="deleteProductForm"
  class="ep-btn"
  style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3;"
  onclick="return confirm('¿Seguro que quieres eliminar este producto? Esta acción no se puede deshacer.');"
>
  🗑 Eliminar producto
</button>

                <button type="submit" class="ep-btn ep-btn-primary">
                  Guardar cambios
                </button>
              </div>
            </div>
          </div>

        </div>
      </form>
      <form
  id="deleteProductForm"
  method="POST"
  action="{{ route('admin.menu-products.destroy', $product) }}"
  style="display:none;"
>
  @csrf
  @method('DELETE')
  <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
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
          const count = document.getElementById('gallery_count');
            if(count){
                count.textContent = files.length
                    ? `${files.length} imagen(es) seleccionada(s)`
                    : '';
            }

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
                  <label class="ep-label">Estructura</label>
                  ${buildSelect(`gallery_meta[${idx}][acero]`, acero, 'Estructura (sin asignar)')}
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

        function setupGalleryDropzone(){

            const dropzone = document.getElementById('gallery_dropzone');
            const input = document.getElementById('gallery_input');

            if(!dropzone || !input){
                return;
            }

              dropzone.addEventListener('click', (e) => {

                  const isBrowseBtn = e.target.closest('.ep-drop-browse');

                  if(isBrowseBtn){
                      return;
                  }

                  input.click();
              });

            [
                'dragenter',
                'dragover'
            ].forEach(eventName => {

                dropzone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();

                    dropzone.classList.add('dragging');
                });

            });

            [
                'dragleave',
                'dragend',
                'drop'
            ].forEach(eventName => {

                dropzone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();

                    dropzone.classList.remove('dragging');
                });

            });

            dropzone.addEventListener('drop', e => {

                const files = e.dataTransfer.files;

                if(!files || !files.length){
                    return;
                }

                const dt = new DataTransfer();
                  Array.from(files).forEach(file => {
                      dt.items.add(file);
                  });

                  input.files = dt.files;

                renderUploadPreviews();
            });
        }


        document.addEventListener('DOMContentLoaded', function(){
          const galleryInput = document.getElementById('gallery_input');
          if (galleryInput) {
            galleryInput.addEventListener('change', renderUploadPreviews);
            setupGalleryDropzone();
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

    async function deleteMaterialColor(url) {
  if (!confirm('¿Eliminar este color? Se quitará también de los productos donde esté asignado.')) return;

  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: '_method=DELETE'
  });

  if (!res.ok) {
    alert('No se pudo eliminar el color.');
    return;
  }

  window.location.reload();
}

  </script>

<script>

let currentImageIndex = null;
let currentAcero = '';
let currentMelamina = '';

function openImageTagger(index, image, acero, melamina){

    currentImageIndex = index;
    currentAcero = acero || '';
    currentMelamina = melamina || '';

    document.getElementById('taggerPreview').src = image;

const coverInput = document.getElementById(`existing_cover_${index}`);
document.getElementById('taggerCover').checked = coverInput && coverInput.value === '1';

    document.querySelectorAll('.ep-select-chip').forEach(chip => {
        chip.classList.remove('active');

        const type = chip.dataset.type;
        const value = chip.dataset.value;

        if(type === 'acero' && value === currentAcero){
            chip.classList.add('active');
        }

        if(type === 'melamina' && value === currentMelamina){
            chip.classList.add('active');
        }
    });

    document.getElementById('imageTaggerModal').style.display = 'flex';
}

function closeImageTagger(){
    document.getElementById('imageTaggerModal').style.display = 'none';
}

document.querySelectorAll('.ep-select-chip').forEach(chip => {

    chip.addEventListener('click', () => {

        const type = chip.dataset.type;

        document.querySelectorAll(`.ep-select-chip[data-type="${type}"]`)
            .forEach(c => c.classList.remove('active'));

        chip.classList.add('active');

        if(type === 'acero'){
            currentAcero = chip.dataset.value;
        }

        if(type === 'melamina'){
            currentMelamina = chip.dataset.value;
        }
    });

});

function applyImageTags(){

    if(currentImageIndex === null){
        return;
    }

    const aceroInput = document.getElementById(
        `existing_acero_${currentImageIndex}`
    );

    const melaInput = document.getElementById(
        `existing_mela_${currentImageIndex}`
    );

    if(aceroInput){
        aceroInput.value = currentAcero;
    }

    if(melaInput){
        melaInput.value = currentMelamina;
    }

const item = document.querySelector(`.ep-gallery-item[data-index="${currentImageIndex}"]`);
if (item) {
    const dot = item.querySelector('.ep-status-dot');

    if (dot) {
        dot.classList.remove('status-green', 'status-yellow', 'status-red');

        if (currentAcero && currentMelamina) {
            dot.classList.add('status-green');
        } else if (currentAcero || currentMelamina) {
            dot.classList.add('status-yellow');
        } else {
            dot.classList.add('status-red');
        }
    }
}

    const coverChecked = document.getElementById('taggerCover').checked;

if (coverChecked) {
    document.querySelectorAll('[id^="existing_cover_"]').forEach(input => {
        input.value = '0';
    });

    document.querySelectorAll('.ep-gallery-item').forEach(item => {
        item.classList.remove('is-cover');

        const badge = item.querySelector('.ep-gallery-cover-badge');
        if (badge) badge.remove();
    });

    const coverInput = document.getElementById(`existing_cover_${currentImageIndex}`);
    if (coverInput) {
        coverInput.value = '1';
    }

const item = document.querySelector(`.ep-gallery-item[data-index="${currentImageIndex}"]`);

    if (item) {
        item.classList.add('is-cover');

        const badge = document.createElement('span');
        badge.className = 'ep-gallery-cover-badge';
        badge.textContent = '★';

        item.appendChild(badge);
    }
}

    closeImageTagger();
}

function deleteCurrentImage() {

    if (currentImageIndex === null) {
        return;
    }

    if (!confirm('¿Eliminar esta imagen del producto?')) {
        return;
    }

    const removeInput = document.getElementById(
        `remove_gallery_${currentImageIndex}`
    );

    if (removeInput) {
        removeInput.checked = true;
    }

    const item = document.querySelector(
        `.ep-gallery-item[data-index="${currentImageIndex}"]`
    );

    if (item) {

        const card = item.closest('.ep-gallery-card');

    if (card) {
        card.style.opacity = '.35';
        card.style.pointerEvents = 'none';
    }
    }

    closeImageTagger();
}

</script>

  @endsection
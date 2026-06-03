@extends('layouts.app')

@section('content')

<div class="cm-page">

  <div class="cm-hero">
    <div>
      <h1>Comunicados</h1>
      <p>Historial de comunicados publicados en el portal.</p>
    </div>
  </div>

  @php
    $statusLabels = [
      'nuevo'         => 'Nuevo',
      'vigente'       => 'Vigente',
      'expira_pronto' => 'Expira pronto',
      'vencido'       => 'Vencido',
    ];
  @endphp

  <div class="cm-grid">
    @forelse($comunicados as $item)
      @php
        $img = $item->image_path ? asset('storage/'.ltrim($item->image_path, '/')) : null;
        $status = method_exists($item, 'status') ? $item->status() : null;
        $statusLabel = $status ? ($statusLabels[$status] ?? null) : null;
      @endphp

      <button type="button"
              class="cm-card"
              onclick="openComunicadoModal(@js($img), @js($item->title ?? 'Comunicado'))">

        <div class="cm-thumb">
          @if($img)
            <img src="{{ $img }}" alt="{{ $item->title }}">
          @else
            <div class="cm-empty-img">Sin imagen</div>
          @endif
        </div>

        <div class="cm-body">
          <div class="cm-title-row">
            <div class="cm-title">{{ $item->title ?: 'Comunicado' }}</div>
            @if($statusLabel)
              <span class="cm-status cm-status--{{ $status }}">{{ $statusLabel }}</span>
            @endif
          </div>

          <div class="cm-meta">
            {{ optional($item->created_at)->format('d/m/Y') }}
            @if($item->expires_at)
              · Vence {{ \Illuminate\Support\Carbon::parse($item->expires_at)->format('d/m/Y') }}
            @endif
          </div>
        </div>
      </button>
    @empty
      <div class="cm-empty">
        No hay comunicados todavía.
      </div>
    @endforelse
  </div>

</div>

<div id="comunicadoModal" class="cm-modal" onclick="closeComunicadoModal()">
  <div class="cm-modal-box" onclick="event.stopPropagation()">
    <div class="cm-modal-head">
      <div id="comunicadoModalTitle">Comunicado</div>

      <div class="cm-modal-actions">
        <a id="comunicadoDownload"
           href="#"
           download
           class="cm-modal-btn">
          Descargar
        </a>

        <button type="button" class="cm-modal-btn" onclick="toggleComunicadoZoom()">
          Zoom
        </button>

        <button type="button" class="cm-modal-btn" onclick="closeComunicadoModal()">
          Cerrar ✕
        </button>
      </div>
    </div>

    <div class="cm-modal-body" id="comunicadoModalBody">
      <img id="comunicadoModalImg" src="" alt="">
    </div>
  </div>
</div>

<style>
.cm-page{
  max-width:1360px;
  margin:0 auto;
  padding:28px 22px 70px;
}

.cm-hero{
  display:flex;
  align-items:flex-end;
  justify-content:space-between;
  gap:18px;
  margin-bottom:24px;
}

.cm-hero h1{
  margin:0;
  font-size:2rem;
  font-weight:800;
  letter-spacing:-.03em;
  color:#111827;
}

.cm-hero p{
  margin:7px 0 0;
  color:#64748b;
  font-size:.95rem;
}

.cm-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(270px, 1fr));
  gap:20px;
}

.cm-card{
  padding:0;
  text-align:left;
  border:1px solid rgba(15,23,42,.10);
  border-radius:22px;
  background:#fff;
  overflow:hidden;
  cursor:pointer;
  box-shadow:0 14px 34px rgba(15,23,42,.07);
  transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}

.cm-card:hover{
  transform:translateY(-4px);
  box-shadow:0 24px 54px rgba(15,23,42,.12);
  border-color:rgba(15,23,42,.18);
}

.cm-thumb{
  aspect-ratio:16/10;
  background:#f1f5f9;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

.cm-thumb img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}

.cm-empty-img{
  color:#94a3b8;
  font-weight:700;
}

.cm-body{
  padding:15px 16px 16px;
}

.cm-title-row{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:10px;
}

.cm-title{
  color:#111827;
  font-size:1rem;
  font-weight:800;
  line-height:1.25;
  flex:1;
  min-width:0;
}

.cm-meta{
  margin-top:7px;
  color:#64748b;
  font-size:.82rem;
}

/* Badges de estado */
.cm-status{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:4px 10px;
  border-radius:999px;
  font-size:.72rem;
  font-weight:800;
  letter-spacing:.04em;
  text-transform:uppercase;
  white-space:nowrap;
  border:1px solid transparent;
  flex-shrink:0;
}

.cm-status::before{
  content:'';
  width:6px;
  height:6px;
  border-radius:999px;
  background:currentColor;
}

.cm-status--nuevo{
  background:#dbeafe;
  color:#1d4ed8;
  border-color:rgba(29,78,216,.18);
}

.cm-status--vigente{
  background:#dcfce7;
  color:#15803d;
  border-color:rgba(21,128,61,.18);
}

.cm-status--expira_pronto{
  background:#fef3c7;
  color:#a16207;
  border-color:rgba(161,98,7,.20);
}

.cm-status--vencido{
  background:#fee2e2;
  color:#b91c1c;
  border-color:rgba(185,28,28,.20);
}

.cm-empty{
  grid-column:1 / -1;
  padding:40px;
  text-align:center;
  color:#64748b;
  background:#fff;
  border:1px solid rgba(15,23,42,.10);
  border-radius:22px;
}

.cm-modal{
  display:none;
  position:fixed;
  inset:0;
  z-index:999999;
  background:rgba(2,6,23,.72);
  backdrop-filter:blur(10px);
  align-items:center;
  justify-content:center;
  padding:22px;
}

.cm-modal-box{
  width:min(1100px, 96vw);
  max-height:92vh;
  background:#fff;
  border-radius:24px;
  overflow:hidden;
  box-shadow:0 30px 90px rgba(2,6,23,.35);
}

.cm-modal-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
  padding:14px 18px;
  border-bottom:1px solid rgba(15,23,42,.10);
  font-weight:800;
  color:#111827;
}

.cm-modal-head button{
  border:1px solid rgba(15,23,42,.14);
  background:#fff;
  border-radius:14px;
  padding:9px 12px;
  cursor:pointer;
  font-weight:700;
}

.cm-modal-body{
  background:#f8fafc;
  display:flex;
  align-items:center;
  justify-content:center;
  max-height:calc(92vh - 58px);
  overflow:auto;
}

.cm-modal-body img{
  max-width:100%;
  max-height:calc(92vh - 70px);
  object-fit:contain;
  display:block;
}

@media(max-width:700px){
  .cm-grid{
    grid-template-columns:1fr;
  }

  .cm-page{
    padding:22px 16px 60px;
  }
}


.cm-modal-actions{
  display:flex;
  align-items:center;
  gap:8px;
  flex-wrap:wrap;
}

.cm-modal-btn{
  border:1px solid rgba(15,23,42,.14);
  background:#fff;
  color:#111827;
  text-decoration:none;
  border-radius:14px;
  padding:9px 12px;
  cursor:pointer;
  font-weight:700;
  font-size:.84rem;
}

.cm-modal-btn:hover{
  background:#f8fafc;
}

.cm-modal-body.zoomed img{
  max-width:none;
  width:auto;
  height:auto;
  transform:scale(1.35);
  transform-origin:center center;
  cursor:zoom-out;
}
</style>

<script>
let comunicadoZoomed = false;

function openComunicadoModal(img, title){
  if (!img) return;

  comunicadoZoomed = false;

  const modal = document.getElementById('comunicadoModal');
  const modalImg = document.getElementById('comunicadoModalImg');
  const modalTitle = document.getElementById('comunicadoModalTitle');
  const download = document.getElementById('comunicadoDownload');
  const body = document.getElementById('comunicadoModalBody');

  modalImg.src = img;
  modalTitle.textContent = title || 'Comunicado';
  download.href = img;
  body.classList.remove('zoomed');

  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeComunicadoModal(){
  const modal = document.getElementById('comunicadoModal');
  const modalImg = document.getElementById('comunicadoModalImg');
  const body = document.getElementById('comunicadoModalBody');

  modal.style.display = 'none';
  modalImg.src = '';
  body.classList.remove('zoomed');
  comunicadoZoomed = false;
  document.body.style.overflow = '';
}

function toggleComunicadoZoom(){
  const body = document.getElementById('comunicadoModalBody');

  comunicadoZoomed = !comunicadoZoomed;
  body.classList.toggle('zoomed', comunicadoZoomed);
}

document.addEventListener('keydown', function(e){
  if(e.key === 'Escape'){
    closeComunicadoModal();
  }
});
</script>

@endsection
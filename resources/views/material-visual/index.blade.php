@extends('layouts.app')

@section('content')

<div class="mv-page">

  {{-- HEADER --}}
  <div class="mv-header">
    <h1>Material Visual</h1>

    <input id="mvSearch" type="text" placeholder="Buscar..." />
  </div>

<div class="mv-tabs">
  <button class="mv-tab {{ $initialTab === 'catalogos' ? 'active' : '' }}" data-tab="catalogos">Catálogos</button>
  <button class="mv-tab {{ $initialTab === 'renders' ? 'active' : '' }}" data-tab="renders">Renders</button>
  <button class="mv-tab {{ $initialTab === 'fotos' ? 'active' : '' }}" data-tab="fotos">Fotos</button>
  <button class="mv-tab {{ $initialTab === 'videos' ? 'active' : '' }}" data-tab="videos">Videos</button>
  <button class="mv-tab {{ $initialTab === 'historias' ? 'active' : '' }}" data-tab="historias">Historias</button>
</div>
  {{-- GRID --}}
  <div id="mvGrid" class="mv-grid"></div>

</div>


<style>
.mv-page{
  max-width:1400px;
  margin:auto;
  padding:24px;
}

.mv-header{
  display:flex;
  justify-content:space-between;
  margin-bottom:20px;
}

.mv-header input{
  padding:12px;
  border-radius:12px;
  border:1px solid #ccc;
  width:260px;
}

.mv-tabs{
  display:flex;
  gap:10px;
  margin-bottom:20px;
}

.mv-tab{
  padding:10px 16px;
  border-radius:999px;
  border:1px solid #ddd;
  background:#fff;
  cursor:pointer;
  font-weight:600;
}

.mv-tab.active{
  background:#111827;
  color:#fff;
}

.mv-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
  gap:20px;
}

.mv-card{
  border-radius:14px;
  overflow:hidden;
  background:#fff;
  border:1px solid #eee;
  cursor:pointer;
  transition:.2s;
}

.mv-card:hover{
  transform:translateY(-4px);
}

.mv-thumb{
  aspect-ratio:16/10;
  background:#f3f4f6;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:30px;
}

.mv-body{
  padding:12px;
}

.mv-title{
  font-weight:700;
}
</style>


<script>
const MV = {
  tab: @json($initialTab),
  search: ''
};

// 🔥 DATA MOCK (igual al video)
const DATA = {

catalogos: [
  { name:'Catálogo 2025', icon:'📘' },
  { name:'Catálogo 2026', icon:'📗' },
  { name:'Acabados', icon:'🎨' },
  { name:'Acusto', icon:'📄' },
],

  renders: [
    { name:'Oficina Vasari', icon:'🖼️' },
    { name:'Render Ejecutivo', icon:'🖼️' },
    { name:'Área Operativa', icon:'🖼️' },
  ],
  fotos: [
    { name:'Proyecto Continental', icon:'📷' },
    { name:'Proyecto Kellogg', icon:'📷' },
  ],
  videos: [
    { name:'Video Showroom', icon:'🎬' },
  ],
  historias: [
    { name:'Caso de éxito', icon:'🏆' },
  ]
};

// 🔥 RENDER
function render(){
  const grid = document.getElementById('mvGrid');
  grid.innerHTML = '';

  let items = DATA[MV.tab];

  if(MV.search){
    items = items.filter(i =>
      i.name.toLowerCase().includes(MV.search.toLowerCase())
    );
  }

  items.forEach(item=>{
    const el = document.createElement('div');
    el.className = 'mv-card';

    el.innerHTML = `
      <div class="mv-thumb">${item.icon}</div>
      <div class="mv-body">
        <div class="mv-title">${item.name}</div>
      </div>
    `;

    grid.appendChild(el);
  });
}

// 🔥 TABS
document.querySelectorAll('.mv-tab').forEach(tab=>{
  tab.addEventListener('click', ()=>{
    document.querySelectorAll('.mv-tab').forEach(t=>t.classList.remove('active'));
    tab.classList.add('active');

    MV.tab = tab.dataset.tab;
    render();
  });
});

// 🔥 SEARCH
document.getElementById('mvSearch').addEventListener('input', e=>{
  MV.search = e.target.value;
  render();
});

// INIT
render();
</script>

@endsection
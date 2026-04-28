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
const DB_ITEMS = @json($items ?? []);

const MV = {
  tab: @json($initialTab ?? 'renders'),
  search: ''
};

const TAB_LABELS = {
  catalogos: 'Catálogos',
  renders: 'Renders',
  fotos: 'Fotos',
  videos: 'Videos',
  proyectos: 'Proyectos'
};

function normalize(text){
  return String(text || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');
}

function iconFor(type, section){
  if(type === 'folder') return '📁';
  if(section === 'fotos') return '📷';
  if(section === 'videos') return '🎬';
  if(section === 'proyectos') return '🏆';
  if(section === 'catalogos') return '📄';
  return '🖼️';
}

function render(){
  const grid = document.getElementById('mvGrid');
  if (!grid) return;

  grid.innerHTML = '';

  let items = DB_ITEMS.filter(item => item.section === MV.tab && !item.parent_key);

  if(MV.search){
    items = items.filter(item =>
      normalize(item.title).includes(normalize(MV.search)) ||
      normalize(item.description).includes(normalize(MV.search))
    );
  }

  if(!items.length){
    grid.innerHTML = `
      <div style="grid-column:1/-1; padding:34px; text-align:center; color:#64748b; font-weight:700;">
        No hay elementos en esta sección.
      </div>
    `;
    return;
  }

  items.forEach(item=>{
    const el = document.createElement('div');
    el.className = 'mv-card';

    el.innerHTML = `
      <div class="mv-thumb">
        ${item.thumb_url
          ? `<img src="${item.thumb_url}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover;">`
          : `<span>${iconFor(item.type, item.section)}</span>`
        }
      </div>
      <div class="mv-body">
        <div class="mv-title">${item.title}</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">
          ${item.type === 'folder' ? 'Carpeta' : 'Archivo'}
        </div>
      </div>
    `;

    grid.appendChild(el);
  });
}

document.querySelectorAll('.mv-tab').forEach(tab=>{
  const tabName = tab.dataset.tab;

  if(tabName === MV.tab){
    tab.classList.add('active');
  } else {
    tab.classList.remove('active');
  }

  tab.addEventListener('click', ()=>{
    document.querySelectorAll('.mv-tab').forEach(t=>t.classList.remove('active'));
    tab.classList.add('active');

    MV.tab = tab.dataset.tab;

    const url = new URL(window.location.href);
    url.searchParams.set('tab', MV.tab);
    window.history.replaceState({}, '', url);

    render();
  });
});

document.getElementById('mvSearch')?.addEventListener('input', e=>{
  MV.search = e.target.value;
  render();
});

render();
</script>

@endsection
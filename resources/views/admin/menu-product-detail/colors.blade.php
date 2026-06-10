@extends('layouts.app')

@section('content')
@php
  $acero = is_array($detail->acero_colors ?? null) ? $detail->acero_colors : (json_decode((string)($detail->acero_colors ?? ''), true) ?: []);
  $mela  = is_array($detail->melamina_colors ?? null) ? $detail->melamina_colors : (json_decode((string)($detail->melamina_colors ?? ''), true) ?: []);
  $acero = array_values(array_filter(array_map('trim', $acero)));
  $mela  = array_values(array_filter(array_map('trim', $mela)));
@endphp

<div style="max-width:980px;margin:26px auto;padding:0 16px;">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:900;">Editar colores (Estructura / Laminado)</div>
      <div style="opacity:.7;font-weight:700;margin-top:4px;">Producto: <b>{{ $product->title }}</b></div>
    </div>
    <a href="{{ $redirectTo }}" style="text-decoration:none;font-weight:900;">← Volver</a>
  </div>

  @if(session('success'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(16,185,129,.3);background:rgba(16,185,129,.08);border-radius:12px;font-weight:800;">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);border-radius:12px;">
      <div style="font-weight:900;margin-bottom:6px;">Corrige esto:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="colorsForm" method="POST"
        action="{{ route('admin.product-variants.colors.update', ['menu_product' => $product->id]) }}"
        style="margin-top:18px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;">
    @csrf
    @method('PUT')

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    {{-- ✅ Enviamos JSON (robusto) --}}
    <input type="hidden" name="acero_colors_json" id="acero_colors_json" value='@json($acero)'>
    <input type="hidden" name="melamina_colors_json" id="melamina_colors_json" value='@json($mela)'>

    <div style="padding:16px;display:grid;gap:16px;">
      <div>
        <div style="font-weight:900;margin-bottom:8px;">Estructura</div>
        <div id="aceroWrap" style="display:flex;flex-wrap:wrap;gap:10px;"></div>

        <div style="margin-top:10px;display:flex;gap:10px;">
          <input id="aceroAdd" type="text" placeholder="Ej. Negro"
                 style="flex:1;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
          <button type="button" onclick="addColor('acero')"
                  style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#111827;color:#fff;font-weight:900;cursor:pointer;">
            + Agregar
          </button>
        </div>
      </div>

      <div style="border-top:1px dashed rgba(15,23,42,.14);padding-top:14px;">
        <div style="font-weight:900;margin-bottom:8px;">Laminado</div>
        <div id="melaWrap" style="display:flex;flex-wrap:wrap;gap:10px;"></div>

        <div style="margin-top:10px;display:flex;gap:10px;">
          <input id="melaAdd" type="text" placeholder="Ej. Encino"
                 style="flex:1;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
          <button type="button" onclick="addColor('mela')"
                  style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#111827;color:#fff;font-weight:900;cursor:pointer;">
            + Agregar
          </button>
        </div>
      </div>
    </div>

    <div style="padding:16px;border-top:1px solid rgba(15,23,42,.08);display:flex;justify-content:flex-end;">
      <button type="submit"
              style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));color:#fff;font-weight:900;cursor:pointer;">
        Guardar
      </button>
    </div>
  </form>
</div>

<script>
  const form = document.getElementById('colorsForm');

  const state = {
    acero: safeParse(document.getElementById('acero_colors_json')?.value, []),
    mela: safeParse(document.getElementById('melamina_colors_json')?.value, []),
  };

  function safeParse(v, fallback){
    try { return JSON.parse(v || '[]'); } catch(e){ return fallback; }
  }

  function esc(s){
    return String(s).replace(/[&<>"']/g, (m)=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
  }

  function pill(text, onRemove){
    const el = document.createElement('div');
    el.style.cssText = "display:inline-flex;align-items:center;gap:8px;padding:8px 10px;border-radius:999px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.02);font-weight:900;font-size:12.5px;";
    el.innerHTML = `<span>${esc(text)}</span>`;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = '×';
    btn.style.cssText = "width:22px;height:22px;border-radius:999px;border:1px solid rgba(225,29,72,.25);background:rgba(225,29,72,.08);cursor:pointer;font-weight:950;line-height:1;";
    btn.onclick = onRemove;

    el.appendChild(btn);
    return el;
  }

  function syncHidden(){
    document.getElementById('acero_colors_json').value = JSON.stringify(state.acero);
    document.getElementById('melamina_colors_json').value = JSON.stringify(state.mela);
  }

  function render(){
    const aw = document.getElementById('aceroWrap');
    const mw = document.getElementById('melaWrap');
    aw.innerHTML = '';
    mw.innerHTML = '';

    state.acero.forEach((c,i)=>{
      aw.appendChild(pill(c, ()=>{
        state.acero.splice(i,1);
        syncHidden();
        render();
      }));
    });

    state.mela.forEach((c,i)=>{
      mw.appendChild(pill(c, ()=>{
        state.mela.splice(i,1);
        syncHidden();
        render();
      }));
    });
  }

  function addColor(type){
    if(type === 'acero'){
      const el = document.getElementById('aceroAdd');
      const v = (el.value||'').trim();
      if(!v) return;
      if(!state.acero.includes(v)) state.acero.push(v);
      el.value = '';
      syncHidden();
      render();
    } else {
      const el = document.getElementById('melaAdd');
      const v = (el.value||'').trim();
      if(!v) return;
      if(!state.mela.includes(v)) state.mela.push(v);
      el.value = '';
      syncHidden();
      render();
    }
  }

  // ✅ blindaje: antes de enviar, sincroniza sí o sí
  form.addEventListener('submit', ()=> syncHidden());

  // init
  syncHidden();
  render();

  window.addColor = addColor;
</script>
@endsection
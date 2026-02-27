@extends('layouts.app')

@section('content')
@php
  $acero = is_array($detail->acero_colors ?? null) ? $detail->acero_colors : (json_decode((string)($detail->acero_colors ?? ''), true) ?: []);
  $mela  = is_array($detail->melamina_colors ?? null) ? $detail->melamina_colors : (json_decode((string)($detail->melamina_colors ?? ''), true) ?: []);
  $acero = array_values(array_filter(array_map('trim', $acero)));
  $mela  = array_values(array_filter(array_map('trim', $mela)));

  $variant = $detail->images_safe ?? [];
@endphp

<div style="max-width:1100px;margin:26px auto;padding:0 16px;">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:900;">Asignar imágenes (Acero/Melamina)</div>
      <div style="opacity:.7;font-weight:700;margin-top:4px;">Producto: <b>{{ $product->title }}</b></div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a href="{{ route('admin.product-variants.colors.edit', ['menu_product' => $product->id, 'redirect_to' => url()->current()]) }}"
         style="text-decoration:none;font-weight:900;">🎨 Editar colores</a>
      <a href="{{ $redirectTo }}" style="text-decoration:none;font-weight:900;">← Volver</a>
    </div>
  </div>

  <form method="POST"
        action="{{ route('admin.product-details.update', ['menu_product' => $product->id]) }}"
        enctype="multipart/form-data"
        style="margin-top:18px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;">
    @csrf
    @method('PUT')

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    <div style="padding:16px;border-bottom:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Datos del detalle
    </div>

    <div style="padding:16px;display:grid;gap:14px;">
      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">Título (opcional)</label>
        <input name="title" value="{{ old('title', $detail->title) }}"
               style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:700;outline:none;">
      </div>

      <div>
        <label style="display:block;font-weight:900;margin-bottom:6px;">Descripción</label>
        <textarea name="description" rows="4"
                  style="width:100%;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:700;outline:none;">{{ old('description', $detail->description) }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">Largo</label>
          <input name="length" value="{{ old('length', $detail->length) }}"
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;">
        </div>
        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">Ancho</label>
          <input name="width" value="{{ old('width', $detail->width) }}"
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;">
        </div>
        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">Alto</label>
          <input name="height" value="{{ old('height', $detail->height) }}"
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;">
        </div>
      </div>
    </div>

    <div style="padding:16px;border-top:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Subir imágenes nuevas (con asignación)
    </div>

    <div style="padding:16px;">
      @if(empty($acero) || empty($mela))
        <div style="padding:12px;border:1px solid rgba(245,158,11,.35);background:rgba(245,158,11,.08);border-radius:12px;font-weight:900;">
          Primero define colores en “Editar colores”, luego podrás asignar imágenes.
        </div>
      @endif

      <input type="file" name="gallery_images[]" id="gallery_input" accept="image/*" multiple
             {{ (empty($acero) || empty($mela)) ? 'disabled' : '' }}>

      <div id="upload_previews"
           style="margin-top:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;"></div>
    </div>

    <div style="padding:16px;border-top:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Imágenes actuales
    </div>

    <div style="padding:16px;">
      @if(empty($variant))
        <div style="opacity:.7;font-weight:800;">No hay imágenes todavía.</div>
      @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;">
          @foreach($variant as $i => $it)
            @php
              $p = ltrim((string)($it['path'] ?? ''), '/');
              $u = $p ? asset('storage/'.$p) : null;
              $a = trim((string)($it['acero'] ?? ''));
              $m = trim((string)($it['melamina'] ?? ''));
            @endphp

            <div style="border:1px solid rgba(15,23,42,.12);border-radius:16px;overflow:hidden;background:#fff;">
              <div style="aspect-ratio:16/10;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                @if($u)
                  <img src="{{ $u }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                @else
                  <div style="font-weight:900;color:#94a3b8;">Sin imagen</div>
                @endif
              </div>

              <div style="padding:10px;display:grid;gap:8px;">
                <label style="display:flex;align-items:center;gap:8px;font-weight:900;">
                  <input type="checkbox" name="remove_gallery[]" value="{{ $p }}"> Quitar
                </label>

                <select name="existing_meta[{{ $i }}][acero]"
                        style="height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;">
                  <option value="">Acero (sin asignar)</option>
                  @foreach($acero as $c)
                    <option value="{{ $c }}" {{ $a===$c ? 'selected':'' }}>{{ $c }}</option>
                  @endforeach
                </select>

                <select name="existing_meta[{{ $i }}][melamina]"
                        style="height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;">
                  <option value="">Melamina (sin asignar)</option>
                  @foreach($mela as $c)
                    <option value="{{ $c }}" {{ $m===$c ? 'selected':'' }}>{{ $c }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          @endforeach
        </div>
      @endif
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
  const ACEROS = @json($acero);
  const MELAS = @json($mela);

  const previewState = { items: [] };

  function esc(s){
    return String(s).replace(/[&<>"']/g, (m)=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
  }

  (function(){
    const input = document.getElementById('gallery_input');
    const wrap = document.getElementById('upload_previews');
    if(!input || !wrap) return;

    input.addEventListener('change', ()=>{
      wrap.innerHTML = '';
      previewState.items = [];

      const files = Array.from(input.files || []);
      files.forEach((f, idx)=>{
        const card = document.createElement('div');
        card.style.cssText = "border:1px solid rgba(15,23,42,.12);border-radius:16px;overflow:hidden;background:#fff;";

        const url = URL.createObjectURL(f);

        const prev = document.createElement('div');
        prev.style.cssText = "aspect-ratio:16/10;background:#f3f4f6;overflow:hidden;";
        prev.innerHTML = `<img src="${url}" style="width:100%;height:100%;object-fit:cover;display:block;">`;

        const meta = document.createElement('div');
        meta.style.cssText = "padding:10px;display:grid;gap:8px;";

        const a = document.createElement('select');
        a.name = `gallery_meta[${idx}][acero]`;
        a.required = true;
        a.style.cssText = "height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;";
        a.innerHTML = `<option value="">Selecciona Acero</option>` + ACEROS.map(c=>`<option value="${esc(c)}">${esc(c)}</option>`).join('');

        const m = document.createElement('select');
        m.name = `gallery_meta[${idx}][melamina]`;
        m.required = true;
        m.style.cssText = "height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:800;outline:none;";
        m.innerHTML = `<option value="">Selecciona Melamina</option>` + MELAS.map(c=>`<option value="${esc(c)}">${esc(c)}</option>`).join('');

        meta.appendChild(a);
        meta.appendChild(m);

        card.appendChild(prev);
        card.appendChild(meta);
        wrap.appendChild(card);

        previewState.items.push({ aEl:a, mEl:m });
      });
    });
  })();

  (function(){
    const form = document.querySelector('form');
    if(!form) return;
    form.addEventListener('submit', (e)=>{
      const anyMissing = previewState.items.some(it => (it.aEl && !it.aEl.value) || (it.mEl && !it.mEl.value));
      if(anyMissing){
        e.preventDefault();
        alert('Asigna Acero y Melamina a cada imagen antes de guardar.');
      }
    });
  })();
</script>
@endsection 
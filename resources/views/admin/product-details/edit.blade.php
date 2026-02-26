@extends('layouts.app')

@section('content')
@php
  $imgs = is_array($detail->images) ? $detail->images : (json_decode((string)$detail->images, true) ?: []);
  $imgs = array_values(array_filter($imgs, fn($p)=>trim((string)$p)!==''));

  $toCsv = function($arr){
    $arr = is_array($arr) ? $arr : [];
    return implode(', ', array_values($arr));
  };
@endphp

<div style="max-width:1100px;margin:24px auto;padding:0 16px;">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:950;">Editar producto</div>
      <div style="opacity:.7;font-weight:700;margin-top:4px;">
        Producto: <b>{{ $product->title }}</b> (ID: {{ $product->id }})
      </div>
    </div>

    <a href="{{ $redirectTo }}" class="btn btn-secondary" style="text-decoration:none;">
      ← Volver
    </a>
  </div>

  @if(session('success'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(16,185,129,.3);background:rgba(16,185,129,.08);border-radius:12px;font-weight:800;">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);border-radius:12px;">
      <div style="font-weight:950;margin-bottom:6px;">Corrige esto:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)
          <li style="font-weight:700;">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST"
        action="{{ route('admin.product-details.update', ['menu_product'=>$product->id]) }}"
        enctype="multipart/form-data"
        style="margin-top:16px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:18px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    @csrf
    @method('PUT')

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    <div style="padding:16px;border-bottom:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);">
      <div style="font-weight:950;">Información</div>
      <div style="opacity:.7;font-weight:700;font-size:13px;margin-top:3px;">Título / descripción / medidas / colores / slider</div>
    </div>

    <div style="padding:16px;display:grid;grid-template-columns:1.1fr .9fr;gap:16px;">
      {{-- LEFT: preview + uploads --}}
      <div>
        <div style="font-weight:950;margin-bottom:10px;">Imágenes</div>

        <div id="newPreviewWrap"
             style="border:1px solid rgba(15,23,42,.12);border-radius:16px;overflow:hidden;background:#fff;">
          <div style="aspect-ratio:21/9;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
            <img id="mainNewPreview" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:none;">
            <div id="noNewPreview" style="padding:18px;font-weight:900;color:#64748b;text-align:center;">
              Sin imagen seleccionada<br><span style="font-weight:700;opacity:.7;">Selecciona imágenes para previsualizar.</span>
            </div>
          </div>
        </div>

        <div style="margin-top:12px;">
          <label style="display:block;font-weight:950;margin-bottom:6px;">Subir imágenes (múltiple)</label>
          <input id="imagesInput" type="file" name="images[]" multiple accept="image/*">
          <div style="opacity:.7;font-weight:700;font-size:12.5px;margin-top:6px;">
            Tip: selecciona varias, se agregan al slider.
          </div>
        </div>

        <div id="newThumbs" style="margin-top:10px;display:flex;gap:10px;overflow:auto;"></div>

        <div style="margin-top:16px;font-weight:950;">Imágenes actuales</div>
        @if(empty($imgs))
          <div style="opacity:.7;font-weight:800;margin-top:8px;">No hay imágenes todavía.</div>
        @else
          <div style="margin-top:10px;display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:12px;">
            @foreach($imgs as $p)
              @php $url = asset('storage/' . ltrim($p,'/')); @endphp
              <div style="border:1px solid rgba(15,23,42,.12);border-radius:14px;overflow:hidden;background:#fff;">
                <div style="aspect-ratio:16/10;background:#f3f4f6;">
                  <img src="{{ $url }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                </div>
                <div style="padding:10px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                  <label style="display:flex;align-items:center;gap:8px;font-weight:900;">
                    <input type="checkbox" name="remove_images[]" value="{{ $p }}">
                    Quitar
                  </label>
                  <a href="{{ $url }}" target="_blank" style="font-weight:900;text-decoration:none;opacity:.8;">Ver ↗</a>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- RIGHT: fields --}}
      <div>
        <div style="display:grid;grid-template-columns:1fr;gap:12px;">
          <div>
            <label style="display:block;font-weight:950;margin-bottom:6px;">Título</label>
            <input name="title" value="{{ old('title', $detail->title) }}"
                   style="width:100%;height:44px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:750;outline:none;">
          </div>

          <div>
            <label style="display:block;font-weight:950;margin-bottom:6px;">Descripción</label>
            <textarea name="description" rows="5"
                      style="width:100%;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:750;outline:none;">{{ old('description', $detail->description) }}</textarea>
          </div>

          <div style="border:1px solid rgba(15,23,42,.10);border-radius:16px;padding:12px;background:rgba(248,250,252,.7);">
            <div style="font-weight:950;margin-bottom:10px;">Medidas</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
              <div>
                <label style="display:block;font-weight:900;margin-bottom:6px;opacity:.75;">Largo</label>
                <input name="largo" value="{{ old('largo', $detail->largo) }}"
                       style="width:100%;height:42px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:800;outline:none;">
              </div>
              <div>
                <label style="display:block;font-weight:900;margin-bottom:6px;opacity:.75;">Ancho</label>
                <input name="ancho" value="{{ old('ancho', $detail->ancho) }}"
                       style="width:100%;height:42px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:800;outline:none;">
              </div>
              <div>
                <label style="display:block;font-weight:900;margin-bottom:6px;opacity:.75;">Alto</label>
                <input name="alto" value="{{ old('alto', $detail->alto) }}"
                       style="width:100%;height:42px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 10px;font-weight:800;outline:none;">
              </div>
            </div>
          </div>

          <div>
            <label style="display:block;font-weight:950;margin-bottom:6px;">Colores de Acero (separados por coma)</label>
            <input name="acero_colors" value="{{ old('acero_colors', $toCsv($detail->acero_colors)) }}"
                   placeholder="Ej. Negro, Blanco, Gris"
                   style="width:100%;height:44px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:750;outline:none;">
          </div>

          <div>
            <label style="display:block;font-weight:950;margin-bottom:6px;">Colores de Melamina (separados por coma)</label>
            <input name="melamina_colors" value="{{ old('melamina_colors', $toCsv($detail->melamina_colors)) }}"
                   placeholder="Ej. Encino, Nogal, Blanco"
                   style="width:100%;height:44px;border-radius:14px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:750;outline:none;">
          </div>
        </div>

        <div style="margin-top:14px;display:flex;justify-content:flex-end;gap:10px;">
          <a href="{{ $redirectTo }}"
             style="height:44px;padding:0 16px;border-radius:14px;border:1px solid rgba(15,23,42,.14);background:#fff;color:#0b1220;font-weight:950;text-decoration:none;display:inline-flex;align-items:center;">
            Cancelar
          </a>

          <button type="submit"
                  style="height:44px;padding:0 16px;border-radius:14px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:950;cursor:pointer;">
            Guardar cambios
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
(function(){
  const input = document.getElementById('imagesInput');
  const main  = document.getElementById('mainNewPreview');
  const none  = document.getElementById('noNewPreview');
  const thumbs = document.getElementById('newThumbs');

  function clearThumbs(){ thumbs.innerHTML = ''; }

  function setMain(url){
    main.src = url;
    main.style.display = 'block';
    none.style.display = 'none';
  }

  input?.addEventListener('change', (e)=>{
    const files = Array.from(e.target.files || []);
    clearThumbs();

    if(!files.length){
      main.src = '';
      main.style.display = 'none';
      none.style.display = 'block';
      return;
    }

    // main = primera imagen
    setMain(URL.createObjectURL(files[0]));

    // thumbs
    files.forEach((f, i)=>{
      const url = URL.createObjectURL(f);
      const t = document.createElement('div');
      t.style.width = '88px';
      t.style.height = '60px';
      t.style.borderRadius = '14px';
      t.style.border = '1px solid rgba(15,23,42,.16)';
      t.style.overflow = 'hidden';
      t.style.cursor = 'pointer';
      t.style.flex = '0 0 auto';
      t.style.opacity = (i===0 ? '1' : '.85');

      const img = document.createElement('img');
      img.src = url;
      img.style.width='100%';
      img.style.height='100%';
      img.style.objectFit='cover';
      img.style.display='block';

      t.appendChild(img);
      t.addEventListener('click', ()=>{
        setMain(url);
        Array.from(thumbs.children).forEach(x=>x.style.opacity='.85');
        t.style.opacity='1';
      });

      thumbs.appendChild(t);
    });
  });
})();
</script>

@endsection
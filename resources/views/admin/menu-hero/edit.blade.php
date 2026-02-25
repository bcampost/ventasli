@extends('layouts.app')

@section('content')
<div style="max-width:980px;margin:26px auto;padding:0 16px;">

  <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:800;">Editar HERO / Slider</div>
      <div style="opacity:.7;font-weight:600;margin-top:4px;">Key: <code>{{ $key }}</code></div>
    </div>

    <a href="{{ $redirectTo }}" class="btn btn-secondary" style="text-decoration:none;">
      ← Volver
    </a>
  </div>

  @if(session('success'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(16,185,129,.3);background:rgba(16,185,129,.08);border-radius:12px;font-weight:700;">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);border-radius:12px;">
      <div style="font-weight:800;margin-bottom:6px;">Corrige esto:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.menu-hero.update', ['token' => $token]) }}" enctype="multipart/form-data"
        style="margin-top:18px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    @csrf
    @method('PUT')

    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

    <div style="padding:16px;border-bottom:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);">
      <div style="font-weight:900;">Contenido</div>
      <div style="opacity:.7;font-weight:600;font-size:13px;margin-top:3px;">Título / descripción + imágenes del slider</div>
    </div>

    <div style="padding:16px;display:grid;grid-template-columns:1fr;gap:14px;">
      <div>
        <label style="display:block;font-weight:800;margin-bottom:6px;">Título</label>
        <input name="title" value="{{ old('title', $hero->title) }}"
               style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:650;outline:none;">
      </div>

      <div>
        <label style="display:block;font-weight:800;margin-bottom:6px;">Descripción</label>
        <textarea name="description" rows="4"
                  style="width:100%;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:650;outline:none;">{{ old('description', $hero->description) }}</textarea>
      </div>

      <div>
        <label style="display:block;font-weight:800;margin-bottom:6px;">Subir imágenes (multiple)</label>

        {{-- ✅ id para preview --}}
        <input id="heroImages" type="file" name="images[]" multiple accept="image/*">

        <div style="opacity:.7;font-weight:600;font-size:12.5px;margin-top:6px;">
          Tip: puedes subir varias imágenes para el slider.
        </div>

        {{-- ✅ Preview antes de guardar --}}
        <div id="heroPreview" class="hero-preview"></div>
      </div>
    </div>

    <div style="padding:16px;border-top:1px solid rgba(15,23,42,.08);background:rgba(15,23,42,.02);font-weight:900;">
      Imágenes actuales
    </div>

    <div style="padding:16px;">
      @php
        $imgs = is_array($hero->images) ? $hero->images : (json_decode((string)$hero->images, true) ?: []);
      @endphp

      @if(empty($imgs))
        <div style="opacity:.7;font-weight:700;">No hay imágenes todavía.</div>
      @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;">
          @foreach($imgs as $p)
            @php $url = asset('storage/' . ltrim($p,'/')); @endphp
            <div style="border:1px solid rgba(15,23,42,.12);border-radius:14px;overflow:hidden;background:#fff;">
              <div style="aspect-ratio: 16/10; background:#f3f4f6;">
                <img src="{{ $url }}" alt=""
                     style="width:100%;height:100%;object-fit:cover;display:block;">
              </div>
              <div style="padding:10px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                <label style="display:flex;align-items:center;gap:8px;font-weight:800;">
                  <input type="checkbox" name="remove[]" value="{{ $p }}">
                  Quitar
                </label>
                <a href="{{ $url }}" target="_blank" style="font-weight:800;text-decoration:none;opacity:.8;">Ver ↗</a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div style="padding:16px;border-top:1px solid rgba(15,23,42,.08);display:flex;justify-content:flex-end;gap:10px;">
      <button type="submit"
              style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:900;cursor:pointer;">
        Guardar
      </button>
    </div>

  </form>
</div>

<style>
  .hero-preview{
    margin-top: 12px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
  }

  .hero-preview .p-card{
    border: 1px solid rgba(0,0,0,.10);
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 10px 18px rgba(0,0,0,.06);
  }

  .hero-preview .p-img{
    height: 110px;
    background: #f3f4f6;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
  }

  .hero-preview img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display:block;
  }

  .hero-preview .p-meta{
    padding: 8px 10px;
    font-size: 12px;
    font-weight: 800;
    color: rgba(0,0,0,.75);
    display:flex;
    justify-content:space-between;
    gap: 8px;
  }

  .hero-preview .p-meta span{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('heroImages');
  const preview = document.getElementById('heroPreview');

  if(!input || !preview) return;

  const clearPreview = () => {
    // liberar URLs para evitar memory leaks
    preview.querySelectorAll('img[data-blob="1"]').forEach(img => {
      try { URL.revokeObjectURL(img.src); } catch(e){}
    });
    preview.innerHTML = '';
  };

  input.addEventListener('change', () => {
    clearPreview();

    const files = Array.from(input.files || []);
    if(!files.length) return;

    files.forEach((file, idx) => {
      if(!file.type.startsWith('image/')) return;

      const url = URL.createObjectURL(file);

      const card = document.createElement('div');
      card.className = 'p-card';

      const imgWrap = document.createElement('div');
      imgWrap.className = 'p-img';

      const img = document.createElement('img');
      img.src = url;
      img.alt = file.name;
      img.setAttribute('data-blob','1');

      const meta = document.createElement('div');
      meta.className = 'p-meta';

      const name = document.createElement('span');
      name.textContent = file.name;

      const num = document.createElement('span');
      num.textContent = `#${idx + 1}`;

      meta.appendChild(name);
      meta.appendChild(num);

      imgWrap.appendChild(img);
      card.appendChild(imgWrap);
      card.appendChild(meta);

      preview.appendChild(card);
    });
  });
});
</script>

@endsection
{{-- resources/views/admin/slides/create.blade.php --}}
@extends('layouts.app')

@section('content')
  <style>
    :root {
      --ink: #0b1220;
      --muted: rgba(15, 23, 42, .62);
      --line: rgba(15, 23, 42, .12);
      --line2: rgba(15, 23, 42, .18);
      --shadowM: 0 12px 30px rgba(15, 23, 42, .10);
      --shadowL: 0 18px 50px rgba(15, 23, 42, .12);
      --primary: #2563eb;
      --primary2: #1d4ed8;
      --rXL: 24px;
      --rL: 18px;
    }

    .wrap {
      max-width: 980px;
      margin: 0 auto;
      padding: 22px 18px 28px;
    }

    .title {
      font-size: 1.55rem;
      font-weight: 950;
      letter-spacing: -.02em;
      color: var(--ink);
    }

    .subtitle {
      margin-top: 8px;
      color: var(--muted);
      font-weight: 700;
      font-size: .95rem;
    }

    .panel {
      margin-top: 14px;
      border: 1px solid var(--line);
      border-radius: var(--rXL);
      background:
        radial-gradient(900px 260px at 15% 0%, rgba(37, 99, 235, .06), transparent 55%),
        #fff;
      box-shadow: var(--shadowM);
      overflow: hidden;
    }

    .panel-head {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      background: rgba(255, 255, 255, .78);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .panel-head .h {
      font-weight: 950;
      color: var(--ink);
    }

    .body {
      padding: 16px;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 12px;
    }

    @media(min-width:900px) {
      .grid {
        grid-template-columns: 1.1fr .9fr;
      }
    }

    .field-label {
      font-size: .85rem;
      font-weight: 950;
      color: rgba(15, 23, 42, .78);
    }

    .input {
      width: 100%;
      border-radius: 16px;
      border: 1px solid rgba(15, 23, 42, .14);
      background: rgba(255, 255, 255, .92);
      padding: .95rem 1rem;
      font-size: .95rem;
      color: var(--ink);
      outline: none;
      transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
    }

    .input:focus {
      border-color: rgba(37, 99, 235, .55);
      box-shadow: 0 0 0 6px rgba(37, 99, 235, .14);
      background: #fff;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      font-weight: 300;
      border-radius: 16px;
      padding: .72rem .92rem;
      font-size: .86rem;
      border: 1px solid transparent;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
      user-select: none;
      white-space: nowrap;
    }

    .btn:active {
      transform: translateY(1px);
    }

    .btn-ghost {
      background: #fff;
      border-color: var(--line);
      color: var(--ink);
      text-decoration: none;
    }

    .btn-ghost:hover {
      background: rgba(248, 250, 252, .85);
      border-color: var(--line2);
      box-shadow: var(--shadowM);
    }

    .btn-primary {
      background: linear-gradient(180deg, rgba(37, 99, 235, 1), rgba(29, 78, 216, 1));
      color: #fff;
      box-shadow: 0 14px 30px rgba(37, 99, 235, .22);
    }

    .btn-primary:hover {
      opacity: .96;
    }

    .preview {
      border: 1px solid rgba(15, 23, 42, .10);
      border-radius: 18px;
      overflow: hidden;
      background: #fff;
      box-shadow: var(--shadowM);
    }

    .preview .ph {
      height: 220px;
      background: rgba(15, 23, 42, .04);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .preview img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
    }

    .preview .meta {
      padding: 12px;
    }

    .muted {
      color: rgba(15, 23, 42, .60);
      font-weight: 800;
      font-size: .92rem;
    }
  </style>

  <div class="wrap">
    <div>
      <div class="title">Nuevo slide</div>
      <div class="subtitle">Crea un aviso para el slider del HOME.</div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <div class="h">Formulario</div>
        <a class="btn btn-ghost" href="{{ route('admin.slides.index') }}">← Volver</a>
      </div>

      <form class="body" method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid">
          <div class="space-y-4" style="display:flex; flex-direction:column; gap:12px;">
            <div>
              <label class="field-label">Título (opcional)</label>
              <input class="input" type="text" name="title" value="{{ old('title') }}"
                placeholder="Ej. Escritorios - Línea Italia">
            </div>

            <div>
              <label class="field-label">Link (opcional)</label>
              <input class="input" type="text" name="link" value="{{ old('link') }}" placeholder="https://...">
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
              <div>
                <label class="field-label">Orden</label>
                <input class="input" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
              </div>

              <div style="display:flex; align-items:flex-end; gap:10px; padding-bottom:6px;">
                <input id="is_active" name="is_active" type="checkbox" class="rounded" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <label for="is_active" class="field-label" style="margin:0;">Activo</label>
              </div>
            </div>

            {{-- 🔥 NUEVO --}}
            <div>
              <label class="field-label">Fecha de vencimiento (opcional)</label>
              <input class="input" type="datetime-local" name="expires_at" value="{{ old('expires_at') }}">
              <div class="muted" style="margin-top:6px;">
                Si se define, el comunicado se ocultará automáticamente al llegar esa fecha.
              </div>
            </div>

            <div>
              <label class="field-label">Imagen</label>
              <input id="image" class="input" style="padding:.75rem 1rem;" type="file" name="image" accept="image/*"
                required>
              <div class="muted" style="margin-top:8px;">Tip: usa imágenes horizontales para que el slider se vea pro.
              </div>
            </div>
          </div>

          <div>
            <div class="preview">
              <div class="ph" id="ph">
                <div class="muted">Preview</div>
              </div>
              <img id="imgPreview" src="" alt="" style="display:none;">
              <div class="meta">
                <div style="font-weight:950; color:rgba(15,23,42,.88);" id="pvTitle">—</div>
                <div class="muted" id="pvLink"
                  style="margin-top:6px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">—</div>
              </div>
            </div>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 16px;">
          <a class="btn btn-ghost" href="{{ route('admin.slides.index') }}">Cancelar</a>
          <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function () {
      const img = document.getElementById('imgPreview');
      const ph = document.getElementById('ph');
      const file = document.getElementById('image');
      const title = document.querySelector('input[name="title"]');
      const link = document.querySelector('input[name="link"]');
      const pvTitle = document.getElementById('pvTitle');
      const pvLink = document.getElementById('pvLink');

      function sync() {
        pvTitle.textContent = (title.value || '—');
        pvLink.textContent = (link.value || '—');
      }
      title.addEventListener('input', sync);
      link.addEventListener('input', sync);
      sync();

      file.addEventListener('change', (e) => {
        const f = e.target.files && e.target.files[0];
        if (!f) return;
        const url = URL.createObjectURL(f);
        img.src = url;
        img.style.display = 'block';
        ph.style.display = 'none';
      });
    })();
  </script>
@endsection
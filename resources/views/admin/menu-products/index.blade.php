@extends('layouts.app')

@section('content')
@php
  $menuKey = $menuKey ?? request('menu_key', '');
  $redirectTo = $redirectTo ?? request('redirect_to', url()->previous());
@endphp

<div style="max-width:1100px;margin:26px auto;padding:0 16px;">

  <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div>
      <div style="font-size:22px;font-weight:900;">Productos del menú</div>
      <div style="opacity:.7;font-weight:650;margin-top:4px;">
        Aquí agregas / editas productos por nivel.
        @if(trim($menuKey)!=='')
          <span style="margin-left:8px;">Nivel: <code>{{ $menuKey }}</code></span>
        @endif
      </div>
    </div>

    <a href="{{ $redirectTo }}" class="btn btn-secondary" style="text-decoration:none;">
      ← Volver
    </a>
  </div>

  @if(session('ok'))
    <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(16,185,129,.3);background:rgba(16,185,129,.08);border-radius:12px;font-weight:800;">
      {{ session('ok') }}
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

  {{-- ============ CARD: AGREGAR PRODUCTO ============ --}}
  <div style="margin-top:16px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    <div style="padding:14px 16px;border-bottom:1px solid rgba(15,23,42,.08);font-weight:950;background:rgba(15,23,42,.02);">
      Agregar producto
    </div>

    <form method="POST" action="{{ route('admin.menu-products.store') }}" enctype="multipart/form-data" style="padding:16px;">
      @csrf

      {{-- ✅ no mostrar al usuario --}}
      <input type="hidden" name="menu_key" value="{{ $menuKey }}">
      <input type="hidden" name="sort" value="0">
      <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">Título</label>
          <input name="title" required
                 value="{{ old('title') }}"
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:650;outline:none;">
        </div>

        <div>
          <label style="display:block;font-weight:900;margin-bottom:6px;">URL (opcional)</label>
          <input name="url" value="{{ old('url') }}" placeholder="https://..."
                 style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:650;outline:none;">
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:900;margin-bottom:6px;">Descripción (opcional)</label>
          <textarea name="description" rows="4"
                    style="width:100%;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:650;outline:none;">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
          <input id="create_active" type="checkbox" name="is_active" checked>
          <label for="create_active" style="font-weight:900;margin:0;">Activo</label>
        </div>

        <div style="grid-column:1/-1;display:grid;grid-template-columns:360px 1fr;gap:14px;align-items:start;">
          {{-- PREVIEW --}}
          <div style="border:1px solid rgba(15,23,42,.12);border-radius:14px;overflow:hidden;background:#fff;box-shadow:0 10px 18px rgba(2,6,23,.06);">
            <div style="padding:10px 12px;border-bottom:1px solid rgba(15,23,42,.08);font-weight:950;background:rgba(15,23,42,.02);">
              Imagen (preview)
            </div>
            <div id="createPreviewBox" style="aspect-ratio:16/10;background:#f3f4f6;display:flex;align-items:center;justify-content:center;position:relative;">
              <img id="createPreviewImg" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
              <div id="createPreviewEmpty" style="text-align:center;color:#64748b;font-weight:900;">
                Sin imagen seleccionada
                <div style="margin-top:6px;font-weight:700;opacity:.85;font-size:13px;">
                  Selecciona una imagen para ver el preview.
                </div>
              </div>
            </div>
          </div>

          {{-- INPUT FILE PRO --}}
          <div>
            <label style="display:block;font-weight:900;margin-bottom:6px;">Seleccionar archivo (opcional)</label>

            <div style="border:1px dashed rgba(15,23,42,.22);border-radius:14px;padding:14px;background:rgba(248,250,252,.8);">
              <input id="create_image" type="file" name="image" accept="image/*"
                     style="display:block;width:100%;">
              <div style="margin-top:8px;opacity:.75;font-weight:650;font-size:13px;">
                Tip: usa imágenes en buena resolución (se verá mejor en las cards).
              </div>
            </div>
          </div>
        </div>

        <div style="grid-column:1/-1;display:flex;justify-content:flex-end;margin-top:4px;">
          <button type="submit"
                  style="height:42px;padding:0 16px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:950;cursor:pointer;">
            Guardar producto
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- ============ TABLA LISTADO ============ --}}
  <div style="margin-top:16px;background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;overflow:hidden;box-shadow:0 10px 22px rgba(0,0,0,.06);">
    <div style="padding:14px 16px;border-bottom:1px solid rgba(15,23,42,.08);font-weight:950;background:rgba(15,23,42,.02);">
      Productos ({{ $items->total() }})
    </div>

    <div style="padding:0;overflow:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:rgba(248,250,252,.9);text-align:left;">
            <th style="padding:12px 12px;border-bottom:1px solid rgba(15,23,42,.08);width:84px;">Img</th>
            <th style="padding:12px 12px;border-bottom:1px solid rgba(15,23,42,.08);">Título</th>
            <th style="padding:12px 12px;border-bottom:1px solid rgba(15,23,42,.08);width:90px;">Activo</th>
            <th style="padding:12px 12px;border-bottom:1px solid rgba(15,23,42,.08);width:220px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $p)
            @php
              $imgUrl = $p->image_path ? asset('storage/'.ltrim($p->image_path,'/')) : null;
            @endphp

            <tr>
              <td style="padding:12px;border-bottom:1px solid rgba(15,23,42,.06);vertical-align:top;">
                <div style="width:64px;height:44px;border-radius:10px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(15,23,42,.08);display:flex;align-items:center;justify-content:center;">
                  @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                  @else
                    <span style="font-size:11px;color:#94a3b8;font-weight:900;">—</span>
                  @endif
                </div>
              </td>

              <td style="padding:12px;border-bottom:1px solid rgba(15,23,42,.06);vertical-align:top;">
                <div style="font-weight:950;">{{ $p->title }}</div>
                @if($p->description)
                  <div style="margin-top:4px;opacity:.75;font-weight:650;font-size:13px;max-width:720px;">
                    {{ $p->description }}
                  </div>
                @endif
                @if($p->url)
                  <div style="margin-top:6px;">
                    <a href="{{ $p->url }}" target="_blank" style="font-weight:900;text-decoration:none;opacity:.8;">
                      Ver URL ↗
                    </a>
                  </div>
                @endif
              </td>

              <td style="padding:12px;border-bottom:1px solid rgba(15,23,42,.06);vertical-align:top;">
                @if($p->is_active)
                  <span style="display:inline-flex;align-items:center;gap:6px;font-weight:950;color:#065f46;">
                    ✅ Sí
                  </span>
                @else
                  <span style="display:inline-flex;align-items:center;gap:6px;font-weight:950;color:#991b1b;">
                    ⛔ No
                  </span>
                @endif
              </td>

              <td style="padding:12px;border-bottom:1px solid rgba(15,23,42,.06);vertical-align:top;">
                <button type="button"
                        onclick="openEditProduct(
                          @js($p->id),
                          @js($p->title ?? ''),
                          @js($p->description ?? ''),
                          @js($p->url ?? ''),
                          @js((int)$p->is_active),
                          @js($imgUrl ?? '')
                        )"
                        style="height:36px;padding:0 12px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#fff;font-weight:950;cursor:pointer;">
                  ✎ Editar
                </button>

                <form method="POST" action="{{ route('admin.menu-products.destroy', $p) }}" style="display:inline-block;margin-left:8px;"
                      onsubmit="return confirm('¿Eliminar este producto?');">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                  <button type="submit"
                          style="height:36px;padding:0 12px;border-radius:12px;border:1px solid rgba(225,29,72,.25);background:rgba(225,29,72,.08);font-weight:950;cursor:pointer;">
                    🗑 Eliminar
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="padding:16px;opacity:.7;font-weight:800;">
                No hay productos todavía.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($items->hasPages())
      <div style="padding:12px 16px;border-top:1px solid rgba(15,23,42,.08);">
        {{ $items->links() }}
      </div>
    @endif
  </div>

</div>

{{-- ============ MODAL EDITAR ============ --}}
<div id="editBackdrop" style="position:fixed;inset:0;background:rgba(2,6,23,.65);backdrop-filter:blur(8px);display:none;z-index:9998;" onclick="closeEditProduct()"></div>

<div id="editModal" style="position:fixed;inset:0;display:none;z-index:9999;">
  <div style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:16px;">
    <div style="width:100%;max-width:920px;background:#fff;border-radius:18px;overflow:hidden;border:1px solid rgba(15,23,42,.14);box-shadow:0 30px 90px rgba(2,6,23,.25);">
      <div style="padding:14px 16px;border-bottom:1px solid rgba(15,23,42,.08);display:flex;justify-content:space-between;gap:12px;align-items:center;background:rgba(15,23,42,.02);">
        <div style="font-weight:950;">Editar producto</div>
        <button type="button" onclick="closeEditProduct()"
                style="height:34px;padding:0 12px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#fff;font-weight:950;cursor:pointer;">
          Cerrar ✕
        </button>
      </div>

      <form id="editForm" method="POST" action="#" enctype="multipart/form-data" style="padding:16px;">
        @csrf
        @method('PUT')
        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
        <input type="hidden" name="sort" value="0">

        <div style="display:grid;grid-template-columns:360px 1fr;gap:14px;align-items:start;">
          <div style="border:1px solid rgba(15,23,42,.12);border-radius:14px;overflow:hidden;background:#fff;">
            <div style="padding:10px 12px;border-bottom:1px solid rgba(15,23,42,.08);font-weight:950;background:rgba(15,23,42,.02);">
              Imagen (preview)
            </div>
            <div style="aspect-ratio:16/10;background:#f3f4f6;display:flex;align-items:center;justify-content:center;position:relative;">
              <img id="editPreviewImg" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
              <div id="editPreviewEmpty" style="text-align:center;color:#64748b;font-weight:900;">
                Sin imagen
              </div>
            </div>
          </div>

          <div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
              <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:900;margin-bottom:6px;">Título</label>
                <input id="edit_title" name="title" required
                       style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:650;outline:none;">
              </div>

              <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:900;margin-bottom:6px;">Descripción (opcional)</label>
                <textarea id="edit_description" name="description" rows="4"
                          style="width:100%;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:10px 12px;font-weight:650;outline:none;"></textarea>
              </div>

              <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:900;margin-bottom:6px;">URL (opcional)</label>
                <input id="edit_url" name="url" placeholder="https://..."
                       style="width:100%;height:42px;border-radius:12px;border:1px solid rgba(15,23,42,.16);padding:0 12px;font-weight:650;outline:none;">
              </div>

              <div style="display:flex;align-items:center;gap:10px;">
                <input id="edit_active" type="checkbox" name="is_active">
                <label for="edit_active" style="font-weight:900;margin:0;">Activo</label>
              </div>

              <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:900;margin-bottom:6px;">Cambiar imagen (opcional)</label>
                <input id="edit_image" type="file" name="image" accept="image/*" style="display:block;width:100%;">
              </div>

              <div style="grid-column:1/-1;display:flex;justify-content:flex-end;gap:10px;margin-top:6px;">
                <button type="button" onclick="closeEditProduct()"
                        style="height:40px;padding:0 14px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#fff;font-weight:950;cursor:pointer;">
                  Cancelar
                </button>
                <button type="submit"
                        style="height:40px;padding:0 14px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:rgba(15,23,42,.92);color:#fff;font-weight:950;cursor:pointer;">
                  Guardar cambios
                </button>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
  // ✅ preview create
  (function(){
    const input = document.getElementById('create_image');
    const img = document.getElementById('createPreviewImg');
    const empty = document.getElementById('createPreviewEmpty');

    if(!input) return;

    input.addEventListener('change', (e)=>{
      const f = e.target.files && e.target.files[0];
      if(!f){
        img.src = '';
        img.style.display = 'none';
        empty.style.display = 'block';
        return;
      }
      const url = URL.createObjectURL(f);
      img.src = url;
      img.style.display = 'block';
      empty.style.display = 'none';
    });
  })();

  function openEditProduct(id, title, description, url, isActive, imgUrl){
    const backdrop = document.getElementById('editBackdrop');
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');

    // arma ruta update: /admin/menu-products/{menu_product}
    const tpl = @js(route('admin.menu-products.update', ['menu_product' => '__ID__']));
    form.action = tpl.replace('__ID__', String(id));

    document.getElementById('edit_title').value = title || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_url').value = url || '';
    document.getElementById('edit_active').checked = !!Number(isActive);

    const img = document.getElementById('editPreviewImg');
    const empty = document.getElementById('editPreviewEmpty');

    if(imgUrl){
      img.src = imgUrl;
      img.style.display = 'block';
      empty.style.display = 'none';
    } else {
      img.src = '';
      img.style.display = 'none';
      empty.style.display = 'block';
    }

    const file = document.getElementById('edit_image');
    file.value = '';
    file.onchange = (e) => {
      const f = e.target.files && e.target.files[0];
      if(!f) return;
      const url2 = URL.createObjectURL(f);
      img.src = url2;
      img.style.display = 'block';
      empty.style.display = 'none';
    };

    backdrop.style.display = 'block';
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeEditProduct(){
    document.getElementById('editBackdrop').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', (e)=>{
    if(e.key === 'Escape') closeEditProduct();
  });
</script>
@endsection
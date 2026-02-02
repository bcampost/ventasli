@extends('layouts.app')

@section('content')
@php
  $defaultIcons = [
    'home' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 10.5 12 4l8 6.5V20a1.5 1.5 0 0 1-1.5 1.5H15v-6h-6v6H5.5A1.5 1.5 0 0 1 4 20v-9.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    'folder' => '<svg viewBox="0 0 24 24" fill="none"><path d="M3.5 7.5h6l2 2H20.5A1.5 1.5 0 0 1 22 11v8.5A1.5 1.5 0 0 1 20.5 21h-15A1.5 1.5 0 0 1 4 19.5V7.5a2 2 0 0 1-.5 0Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    'briefcase' => '<svg viewBox="0 0 24 24" fill="none"><path d="M9 7V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.8"/><path d="M4 8.5h16A2 2 0 0 1 22 10.5v8A2.5 2.5 0 0 1 19.5 21h-15A2.5 2.5 0 0 1 2 18.5v-8A2 2 0 0 1 4 8.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    'users' => '<svg viewBox="0 0 24 24" fill="none"><path d="M16 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/><path d="M8 12a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/><path d="M13 20a5 5 0 0 1 10 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M1 20a6 6 0 0 1 11 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
    'calc' => '<svg viewBox="0 0 24 24" fill="none"><path d="M7 3.5h10A2.5 2.5 0 0 1 19.5 6v12A2.5 2.5 0 0 1 17 20.5H7A2.5 2.5 0 0 1 4.5 18V6A2.5 2.5 0 0 1 7 3.5Z" stroke="currentColor" stroke-width="1.8"/><path d="M7.5 7.5h9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8 12h0M12 12h0M16 12h0M8 15.5h0M12 15.5h0M16 15.5h0" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>',
    'file' => '<svg viewBox="0 0 24 24" fill="none"><path d="M7 3.5h7l3 3v14A2 2 0 0 1 15 22H7A2 2 0 0 1 5 20.5v-15A2 2 0 0 1 7 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3.5V7h3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    'shield' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 3.5 20 7v6c0 5-3.2 8.4-8 9.5C7.2 21.4 4 18 4 13V7l8-3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    'settings' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 15.5a3.5 3.5 0 1 0-3.5-3.5 3.5 3.5 0 0 0 3.5 3.5Z" stroke="currentColor" stroke-width="1.8"/><path d="M19.4 13a7.9 7.9 0 0 0 0-2l2-1.2-2-3.4-2.3.7a8.2 8.2 0 0 0-1.7-1L15 3.5h-6L8.6 6.1a8.2 8.2 0 0 0-1.7 1L4.6 6.4l-2 3.4L4.6 11a7.9 7.9 0 0 0 0 2l-2 1.2 2 3.4 2.3-.7a8.2 8.2 0 0 0 1.7 1L9 20.5h6l.4-2.6a8.2 8.2 0 0 0 1.7-1l2.3.7 2-3.4L19.4 13Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>',
    'dot' => '<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/></svg>',
  ];
@endphp

<div style="max-width:800px;margin:0 auto;padding:24px;">
  <div style="background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:16px;">
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:900;">Editar acceso rápido</h2>

    @if($errors->any())
      <div style="background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:10px 12px;border-radius:12px;margin-bottom:12px;">
        <b>Revisa:</b>
        <ul style="margin:6px 0 0;padding-left:18px;">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.quick-links.update',$quickLink) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Nombre</label>
      <input name="name" value="{{ old('name', $quickLink->name) }}" required
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">URL</label>
      <input name="url" value="{{ old('url', $quickLink->url) }}" required
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <input type="hidden" name="icon" id="icon_key" value="{{ old('icon', $quickLink->icon ?? 'dot') }}">
      <input type="hidden" name="icon_path" id="icon_path" value="{{ old('icon_path', $quickLink->icon_path ?? '') }}">

      <div style="display:flex;gap:10px;align-items:center;margin:8px 0 10px;">
        <div id="icon-preview"
             style="width:44px;height:44px;border-radius:14px;border:1px solid rgba(0,0,0,.08);display:grid;place-items:center;background:#f8fafc;overflow:hidden;color:#0f172a;">
          @if($quickLink->icon_path)
            <img src="{{ asset('storage/'.$quickLink->icon_path) }}" style="width:22px;height:22px;object-fit:contain;">
          @else
            {!! $defaultIcons[$quickLink->icon ?? 'dot'] ?? $defaultIcons['dot'] !!}
          @endif
        </div>
        <div style="font-size:12px;color:#64748b;">
          Icono actual
        </div>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin:8px 0 8px;">
        <div style="font-size:12px;color:#475569;font-weight:900;">Cambiar icono</div>
        <button type="button" id="icon-clear"
          style="border:none;background:transparent;color:#334155;font-weight:900;cursor:pointer;font-size:12px;">
          Quitar selección
        </button>
      </div>

      <div style="font-size:12px;color:#475569;font-weight:900;margin:10px 0 6px;">Default</div>
      <div id="grid-default" style="
        display:grid;grid-template-columns: repeat(8, 1fr);
        gap:10px;padding:10px;border-radius:14px;border:1px solid rgba(0,0,0,.08);
        background:#f8fafc;margin-bottom:12px;
      ">
        @foreach($defaultIcons as $key => $svg)
          <button type="button"
            class="tile tile-default"
            data-icon="{{ $key }}"
            title="{{ $key }}"
            style="aspect-ratio:1/1;border-radius:14px;border:1px solid rgba(0,0,0,.08);background:rgba(255,255,255,.95);
                   display:grid;place-items:center;cursor:pointer;padding:0;transition:.15s;color:#0f172a;">
            <span style="width:22px;height:22px;display:grid;place-items:center;">{!! $svg !!}</span>
          </button>
        @endforeach
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin:6px 0 6px;">
        <div style="font-size:12px;color:#475569;font-weight:900;">Subidos</div>
        <span style="font-size:12px;color:#94a3b8;">({{ count($icons) }})</span>
      </div>

      <div id="grid-uploaded" style="
        display:grid;grid-template-columns: repeat(8, 1fr);
        gap:10px;padding:10px;border-radius:14px;border:1px solid rgba(0,0,0,.08);
        background:#f8fafc;max-height:220px;overflow:auto;margin-bottom:12px;
      ">
        @forelse($icons as $path)
          <button type="button"
            class="tile tile-uploaded"
            data-path="{{ $path }}"
            title="{{ basename($path) }}"
            style="aspect-ratio:1/1;border-radius:14px;border:1px solid rgba(0,0,0,.08);background:rgba(255,255,255,.95);
                   display:grid;place-items:center;cursor:pointer;padding:0;transition:.15s;">
            <img src="{{ asset('storage/'.$path) }}" style="width:22px;height:22px;object-fit:contain;">
          </button>
        @empty
          <div style="grid-column:1/-1;color:#64748b;font-size:12px;padding:6px 2px;">Aún no hay iconos subidos.</div>
        @endforelse
      </div>

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Subir icono (svg/png/webp/jpg)</label>
      <input type="file" name="icon_upload" accept=".svg,.png,.webp,.jpg,.jpeg" id="icon_upload"
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px dashed rgba(0,0,0,.18);margin-bottom:10px;" />

      <label style="display:flex;gap:8px;align-items:center;font-size:13px;color:#0f172a;margin-bottom:12px;">
        <input type="checkbox" name="remove_icon" value="1" />
        Quitar icono subido (borra el archivo del servidor)
      </label>

      <label style="display:block;font-size:12px;color:#475569;margin-bottom:6px;">Orden</label>
      <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $quickLink->sort_order ?? 0) }}"
             style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);margin-bottom:10px;" />

      <label style="display:flex;gap:8px;align-items:center;font-size:13px;color:#0f172a;margin-bottom:12px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', (bool)$quickLink->is_active)) />
        Activo
      </label>

      <button type="submit"
        style="width:100%;padding:10px 12px;border-radius:12px;border:none;background:#0f172a;color:#fff;font-weight:900;cursor:pointer;">
        Guardar cambios
      </button>

      <a href="{{ route('admin.quick-links.index') }}"
         style="display:block;text-align:center;margin-top:10px;text-decoration:none;color:#334155;font-weight:800;">
        Cancelar
      </a>

      <style>
        .tile:hover{ transform: translateY(-1px); box-shadow:0 10px 20px rgba(2,6,23,.10); border-color:rgba(2,6,23,.18); }
        .tile.is-selected{ border-color:rgba(37,99,235,.55)!important; box-shadow:0 10px 22px rgba(37,99,235,.15); outline:2px solid rgba(37,99,235,.22); }
      </style>

      <script>
        (function(){
          const iconKey   = document.getElementById('icon_key');
          const iconPath  = document.getElementById('icon_path');
          const gridDef   = document.getElementById('grid-default');
          const gridUp    = document.getElementById('grid-uploaded');
          const preview   = document.getElementById('icon-preview');
          const clearBtn  = document.getElementById('icon-clear');
          const uploadInp = document.getElementById('icon_upload');

          function clearTiles(){ document.querySelectorAll('.tile').forEach(t => t.classList.remove('is-selected')); }
          function setPreviewHtml(html){ preview.innerHTML = html; }
          function setPreviewImg(src){ preview.innerHTML = `<img src="${src}" style="width:22px;height:22px;object-fit:contain;display:block;">`; }

          function resetToDot(){
            iconKey.value='dot'; iconPath.value=''; uploadInp.value='';
            clearTiles();
            setPreviewHtml('<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/></svg>');
          }

          gridDef.addEventListener('click',(e)=>{
            const btn=e.target.closest('.tile-default'); if(!btn) return;
            uploadInp.value=''; iconPath.value=''; iconKey.value=btn.dataset.icon||'dot';
            clearTiles(); btn.classList.add('is-selected');
            const svgWrap=btn.querySelector('span'); setPreviewHtml(svgWrap?svgWrap.innerHTML:'');
          });

          gridUp.addEventListener('click',(e)=>{
            const btn=e.target.closest('.tile-uploaded'); if(!btn) return;
            uploadInp.value=''; iconKey.value='dot'; iconPath.value=btn.dataset.path||'';
            clearTiles(); btn.classList.add('is-selected');
            const img=btn.querySelector('img'); if(img) setPreviewImg(img.src);
          });

          uploadInp.addEventListener('change',()=>{
            if(!uploadInp.files||!uploadInp.files[0]) return;
            iconPath.value=''; iconKey.value='dot'; clearTiles();
            setPreviewImg(URL.createObjectURL(uploadInp.files[0]));
          });

          clearBtn.addEventListener('click', resetToDot);

          // restore current selection
          const curPath = iconPath.value;
          const curKey  = iconKey.value;

          if(curPath){
            const btn = gridUp.querySelector(`.tile-uploaded[data-path="${CSS.escape(curPath)}"]`);
            if(btn){
              clearTiles(); btn.classList.add('is-selected');
              const img=btn.querySelector('img'); if(img) setPreviewImg(img.src);
            }
          } else if(curKey){
            const btn = gridDef.querySelector(`.tile-default[data-icon="${CSS.escape(curKey)}"]`);
            if(btn){
              clearTiles(); btn.classList.add('is-selected');
              const svgWrap=btn.querySelector('span'); if(svgWrap) setPreviewHtml(svgWrap.innerHTML);
            }
          }
        })();
      </script>

    </form>
  </div>
</div>
@endsection
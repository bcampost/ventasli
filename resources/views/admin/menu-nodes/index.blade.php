@extends('layouts.app')

@section('content')

<style>
  :root{
    --ink:#0b1220;
    --muted:#5b6b84;
    --line:rgba(15,23,42,.12);
    --line2:rgba(15,23,42,.18);
    --soft: rgba(248,250,252,.72);

    --shadowXL: 0 30px 90px rgba(2,6,23,.22);
    --shadowL: 0 18px 50px rgba(15,23,42,.14);
    --shadowM: 0 12px 30px rgba(15,23,42,.10);

    --primary:#2563eb;
    --primaryHover:#1d4ed8;
    --danger:#e11d48;

    --radiusXL: 24px;
    --radiusL: 18px;
    --radiusM: 14px;
  }

  /* Header */
  .page-wrap{ max-width: 1100px; margin:0 auto; padding: 28px 18px; }
  .page-title{
    font-size: 1.35rem;
    font-weight: 900;
    letter-spacing: -.02em;
    color: var(--ink);
  }
  .page-sub{ margin-top:6px; color: var(--muted); font-size:.92rem; }

  /* Cards (root + children rows) */
  .node-card{
    border:1px solid var(--line);
    border-radius: var(--radiusXL);
    background:
      radial-gradient(900px 260px at 15% 0%, rgba(37,99,235,.08), transparent 55%),
      #fff;
    overflow:hidden;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }
  .node-card:hover{
    transform: translateY(-2px);
    box-shadow: var(--shadowL);
    border-color: var(--line2);
  }

  .node-row{
    display:flex;
    gap:14px;
    align-items:center;
    padding: 18px 18px;
  }

  .chev-btn{
    width: 44px; height:44px;
    border-radius: 16px;
    border:1px solid var(--line);
    background:#fff;
    display:flex; align-items:center; justify-content:center;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
    user-select:none;
  }
  .chev-btn:hover{
    background: rgba(248,250,252,.85);
    border-color: var(--line2);
    box-shadow: var(--shadowM);
  }

  .chev{
    transition: transform .25s ease;
    color: rgba(15,23,42,.70);
    font-weight: 900;
  }

  .meta{
    display:flex;
    flex-wrap: wrap;
    gap:8px;
    margin-top:6px;
    font-size:.78rem;
    color: rgba(15,23,42,.62);
  }

  .pill{
    display:inline-flex;
    align-items:center;
    gap:.4rem;
    border-radius: 999px;
    padding:.38rem .65rem;
    font-size:.74rem;
    font-weight: 900;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(248,250,252,.9);
    color: rgba(15,23,42,.80);
  }
  .pill-cat{ background: rgba(37,99,235,.10); border-color: rgba(37,99,235,.18); color: rgba(29,78,216,.95); }
  .pill-off{ background: rgba(225,29,72,.08); border-color: rgba(225,29,72,.22); color: rgba(225,29,72,.95); }

  .node-title{
    font-size: 1.08rem;
    font-weight: 950;
    color: var(--ink);
    line-height: 1.1;
  }

  /* Buttons PRO */
  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:.5rem;
    font-weight: 900;
    border-radius: 16px;
    padding: .75rem .95rem;
    font-size: .86rem;
    border: 1px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, opacity .15s ease;
    user-select:none; white-space:nowrap;
  }
  .btn:active{ transform: translateY(1px); }

  .btn-primary{
    background: linear-gradient(180deg, rgba(37,99,235,1), rgba(29,78,216,1));
    color:#fff;
    box-shadow: 0 14px 30px rgba(37,99,235,.22);
  }
  .btn-primary:hover{ opacity:.96; }

  .btn-ghost{
    background:#fff;
    border-color: var(--line);
    color: var(--ink);
  }
  .btn-ghost:hover{
    background: rgba(248,250,252,.85);
    border-color: var(--line2);
    box-shadow: var(--shadowM);
  }

  .btn-danger{
    background: rgba(225,29,72,.08);
    border-color: rgba(225,29,72,.25);
    color: var(--danger);
  }
  .btn-danger:hover{
    background: rgba(225,29,72,.12);
    border-color: rgba(225,29,72,.35);
  }

  /* Modal WOW */
  .modal-backdrop{
    background: rgba(2,6,23,.72);
    backdrop-filter: blur(10px);
  }
  .modal-shell{
    border-radius: 26px;
    overflow:hidden;
    background:
      radial-gradient(900px 260px at 20% 0%, rgba(37,99,235,.12), transparent 55%),
      #fff;
    border: 1px solid rgba(15,23,42,.14);
    box-shadow: var(--shadowXL);
  }
  .modal-enter{
    transform: translateY(12px) scale(.985);
    opacity:0;
    transition: transform .22s ease, opacity .22s ease;
  }
  .modal-open .modal-enter{
    transform: translateY(0) scale(1);
    opacity:1;
  }
  .modal-header{
    padding: 18px 22px;
    border-bottom:1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.75);
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }
  .modal-title{ font-size:1.15rem; font-weight:950; letter-spacing:-.02em; color:var(--ink); }
  .modal-sub{ margin-top:4px; font-size:.9rem; color: var(--muted); }

  .field-label{
    font-size:.85rem;
    font-weight: 950;
    color: rgba(15,23,42,.78);
  }
  .field-help{
    margin-top:6px;
    font-size:.78rem;
    color: rgba(15,23,42,.55);
  }
  .input, .textarea{
    width:100%;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.92);
    padding: .95rem 1rem;
    font-size:.95rem;
    color: var(--ink);
    outline:none;
    transition: box-shadow .15s ease, border-color .15s ease, background .15s ease;
  }
  .textarea{ padding:1rem; }
  .input:focus, .textarea:focus{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 0 0 6px rgba(37,99,235,.14);
    background:#fff;
  }

  .top-actions{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    margin-top: 16px;
  }

  .notice-ok{
    border:1px solid rgba(16,185,129,.22);
    background: rgba(16,185,129,.08);
    border-radius: 18px;
    padding: 12px 14px;
    color: rgba(15,23,42,.86);
    font-size:.9rem;
    font-weight: 800;
  }
  .notice-err{
    border:1px solid rgba(225,29,72,.22);
    background: rgba(225,29,72,.08);
    border-radius: 18px;
    padding: 12px 14px;
    color: rgba(15,23,42,.86);
    font-size:.9rem;
  }
</style>

<div class="page-wrap">

  <div class="flex items-start justify-between gap-4">
    <div>
      <div class="page-title">Editar menú superior</div>
      <div class="page-sub">Despliega cada opción para editar, eliminar o agregar sub-opciones.</div>
    </div>

    <button type="button" class="btn btn-primary" onclick="openCreateModal('')">
      <span class="text-lg leading-none">+</span> Agregar opción raíz
    </button>
  </div>

  <div class="top-actions">
    <div class="text-sm text-slate-600">
      Tip: Usa “Orden” para controlar la posición en el menú superior.
    </div>
  </div>

  @if(session('ok'))
    <div class="mt-4 notice-ok">{{ session('ok') }}</div>
  @endif

  @if($errors->any())
    <div class="mt-4 notice-err">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="mt-5 grid gap-12">
    @forelse($roots as $node)
      @include('admin.menu-nodes.partials.node', ['node' => $node, 'level' => 0])
    @empty
      <div class="node-card">
        <div class="node-row">
          <div class="node-title">No hay opciones aún.</div>
        </div>
      </div>
    @endforelse
  </div>
</div>

{{-- =========================
   MODAL: CREATE/EDIT (UNO)
========================= --}}
<div id="nodeModalBackdrop" class="modal-backdrop fixed inset-0 hidden z-[80]" onclick="closeNodeModal()"></div>

<div id="nodeModal" class="fixed inset-0 hidden z-[90]">
  <div class="min-h-full flex items-center justify-center p-4">
    <div class="modal-enter w-full max-w-2xl modal-shell">
      <div class="modal-header">
        <div>
          <div id="nodeModalTitle" class="modal-title">—</div>
          <div id="nodeModalSub" class="modal-sub">—</div>
        </div>
        <button type="button" class="btn btn-ghost" style="padding:.65rem .9rem; border-radius:14px;" onclick="closeNodeModal()">
          Cerrar ✕
        </button>
      </div>

      <form id="nodeModalForm" method="POST" action="#" class="p-6">
        @csrf
        <input type="hidden" id="nodeFormMethod" name="_method" value="POST">
        <input type="hidden" id="node_parent_id" name="parent_id" value="">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="field-label">Nombre</label>
            <input id="node_label" name="label" class="input" placeholder="Ej. Catálogos" required>
          </div>

          <div class="md:col-span-2">
            <label class="field-label">URL (opcional)</label>
            <input id="node_url" name="url" class="input" placeholder="#">
            <div class="field-help">Si queda vacío, se tratará como categoría desplegable.</div>
          </div>

          <div>
            <label class="field-label">Orden</label>
            <input id="node_sort" name="sort" type="number" class="input" value="0" min="0">
          </div>

          <div class="flex items-end gap-2 pb-1">
            <input id="node_active" name="is_active" type="checkbox" class="rounded" checked>
            <label for="node_active" class="field-label" style="margin:0;">Activo</label>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <button type="button" class="btn btn-ghost" onclick="closeNodeModal()">Cancelar</button>
          <button class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openCreateModal(parentId) {
    const backdrop = document.getElementById('nodeModalBackdrop');
    const modal = document.getElementById('nodeModal');

    document.getElementById('nodeModalTitle').textContent = 'Agregar opción';
    document.getElementById('nodeModalSub').textContent = parentId ? 'Se agregará como sub-opción.' : 'Se agregará al nivel raíz.';

    const form = document.getElementById('nodeModalForm');
    form.action = @js(route('admin.menu.store'));

    document.getElementById('nodeFormMethod').value = 'POST';
    document.getElementById('node_parent_id').value = parentId || '';

    document.getElementById('node_label').value = '';
    document.getElementById('node_url').value = '';
    document.getElementById('node_sort').value = 0;
    document.getElementById('node_active').checked = true;

    backdrop.classList.remove('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('modal-open');
  }

  function openEditModal(id, label, url, sort, isActive) {
    const backdrop = document.getElementById('nodeModalBackdrop');
    const modal = document.getElementById('nodeModal');

    document.getElementById('nodeModalTitle').textContent = 'Editar opción';
    document.getElementById('nodeModalSub').textContent = 'Modifica nombre, URL, orden y estado.';

    // ✅ PARAM CORRECTO: menuNode
    const tpl = @js(route('admin.menu.update', ['menuNode' => '___ID___']));
    const action = tpl.replace('___ID___', encodeURIComponent(id));

    const form = document.getElementById('nodeModalForm');
    form.action = action;

    document.getElementById('nodeFormMethod').value = 'PUT';
    document.getElementById('node_parent_id').value = '';

    document.getElementById('node_label').value = label || '';
    document.getElementById('node_url').value = url || '';
    document.getElementById('node_sort').value = (sort ?? 0);
    document.getElementById('node_active').checked = !!isActive;

    backdrop.classList.remove('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('modal-open');
  }

  function closeNodeModal() {
    const backdrop = document.getElementById('nodeModalBackdrop');
    const modal = document.getElementById('nodeModal');
    modal.classList.remove('modal-open');
    backdrop.classList.add('hidden');
    modal.classList.add('hidden');
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeNodeModal();
  });
</script>

@endsection
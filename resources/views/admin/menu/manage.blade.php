{{-- resources/views/admin/menu/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  .mm-wrap{
    max-width: 1220px;
    margin: 0 auto;
    padding: 24px 18px 32px;
  }

  .mm-shell{
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 28px;
    box-shadow: 0 20px 60px rgba(15,23,42,.06);
    overflow: hidden;
  }

  .mm-head{
    padding: 28px 28px 22px;
    border-bottom: 1px solid rgba(15,23,42,.07);
    background:
      radial-gradient(1200px 180px at 10% 0%, rgba(37,99,235,.06), transparent 45%),
      linear-gradient(180deg, rgba(248,250,252,.88), rgba(255,255,255,1));
  }

  .mm-head-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
  }

  .mm-title{
    font-size: 1.65rem;
    line-height: 1.1;
    font-weight: 600;
    color:#0f172a;
    letter-spacing:-.02em;
    margin:0;
  }

  .mm-sub{
    margin-top: 8px;
    color: rgba(15,23,42,.66);
    font-size: .98rem;
    line-height: 1.55;
  }

  .mm-flash{
    padding: 11px 14px;
    border-radius: 14px;
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
    color: #166534;
    font-size: .9rem;
    font-weight: 500;
    white-space: nowrap;
  }

  .mm-meta{
    margin-top: 16px;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  .mm-chip{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 9px 12px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.08);
    background: rgba(248,250,252,.9);
    color:#334155;
    font-size: .86rem;
    font-weight: 500;
  }

  .mm-chip code{
    background: transparent;
    color:#0f172a;
    font-size: .84rem;
    font-weight: 500;
  }

  .mm-body{
    padding: 24px;
  }

  .mm-card{
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 10px 30px rgba(15,23,42,.04);
  }

  .mm-card + .mm-card{
    margin-top: 18px;
  }

  .mm-card-head{
    padding: 18px 20px 14px;
    border-bottom: 1px solid rgba(15,23,42,.06);
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
  }

  .mm-card-title{
    font-size: 1.08rem;
    font-weight: 600;
    color:#0f172a;
    margin:0;
    letter-spacing:-.01em;
  }

  .mm-card-sub{
    margin-top: 4px;
    color: rgba(15,23,42,.58);
    font-size: .9rem;
  }

  .mm-card-body{
    padding: 18px 20px 20px;
  }

  .mm-create{
    display:grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    align-items:center;
  }

  .mm-input,
  .mm-file{
    width:100%;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.12);
    background: #fff;
    color:#0f172a;
    padding: 12px 14px;
    font-size: .95rem;
    font-weight: 400;
    outline:none;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
  }

  .mm-input:focus,
  .mm-file:focus{
    border-color: rgba(37,99,235,.35);
    box-shadow: 0 0 0 5px rgba(37,99,235,.08);
  }

  .mm-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    border-radius: 16px;
    padding: 11px 16px;
    font-size: .92rem;
    font-weight: 500;
    border: 1px solid transparent;
    text-decoration:none;
    cursor:pointer;
    transition: transform .14s ease, box-shadow .14s ease, background .14s ease, border-color .14s ease;
    user-select:none;
  }

  .mm-btn:hover{
    transform: translateY(-1px);
  }

  .mm-btn-dark{
    background: #0f172a;
    color:#fff;
    box-shadow: 0 12px 24px rgba(15,23,42,.12);
  }

  .mm-btn-dark:hover{
    background:#111827;
  }

  .mm-btn-soft{
    background: #f8fafc;
    color:#0f172a;
    border-color: rgba(15,23,42,.10);
  }

  .mm-btn-soft:hover{
    background:#f1f5f9;
  }

  .mm-btn-danger{
    background: #fff5f5;
    color:#b91c1c;
    border-color: rgba(239,68,68,.18);
  }

  .mm-btn-danger:hover{
    background:#fef2f2;
  }

  .mm-empty{
    padding: 18px 20px;
    border-radius: 18px;
    border: 1px dashed rgba(15,23,42,.12);
    background:#f8fafc;
    color:#475569;
  }

  .mm-item{
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 24px;
    background: linear-gradient(180deg, rgba(255,255,255,1), rgba(250,250,252,.95));
    box-shadow: 0 12px 26px rgba(15,23,42,.045);
    padding: 22px;
  }

  .mm-item + .mm-item{
    margin-top: 16px;
  }

  .mm-item-grid{
    display:grid;
    grid-template-columns: minmax(260px, 1.05fr) minmax(380px, 1.25fr);
    gap: 24px;
    align-items:start;
  }

  .mm-item-title{
    font-size: 1.22rem;
    font-weight: 600;
    color:#0f172a;
    line-height: 1.15;
    margin:0;
    letter-spacing:-.02em;
  }

  .mm-badges{
    margin-top: 14px;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  .mm-badge{
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding: 8px 11px;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid rgba(15,23,42,.08);
    color:#475569;
    font-size: .82rem;
    font-weight: 500;
    max-width: 100%;
  }

  .mm-badge code{
    color:#0f172a;
    font-size: .81rem;
    font-weight: 500;
    overflow-wrap:anywhere;
    word-break:break-word;
  }

  .mm-link{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-top: 16px;
    color:#1d4ed8;
    text-decoration:none;
    font-size: .92rem;
    font-weight: 500;
  }

  .mm-link:hover{
    text-decoration: underline;
  }

  .mm-actions{
    display:grid;
    gap: 14px;
  }

  .mm-section{
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 18px;
    background: #fff;
    padding: 16px;
  }

  .mm-section-title{
    font-size: .88rem;
    font-weight: 600;
    color:#0f172a;
    margin-bottom: 12px;
    letter-spacing: -.01em;
  }

  .mm-upload-row{
    display:grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items:center;
  }

  .mm-edit-grid{
    display:grid;
    grid-template-columns: 1.2fr .55fr 1.2fr auto auto;
    gap: 10px;
    align-items:center;
  }

  .mm-check{
    display:inline-flex;
    align-items:center;
    gap:8px;
    white-space:nowrap;
    color:#475569;
    font-size: .9rem;
    font-weight: 500;
  }

  .mm-check input{
    width: 16px;
    height: 16px;
    accent-color: #2563eb;
  }

  .mm-danger-row{
    display:flex;
    justify-content:flex-start;
  }

  .mm-error{
    margin-top: 10px;
    color:#dc2626;
    font-size: .88rem;
    font-weight: 500;
  }

  .mm-tip{
    margin-top: 20px;
    padding: 15px 16px;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid rgba(15,23,42,.07);
    color:#64748b;
    font-size: .9rem;
  }

  .mm-tip code{
    color:#0f172a;
    font-weight: 500;
  }

  @media (max-width: 980px){
    .mm-item-grid{
      grid-template-columns: 1fr;
      gap: 18px;
    }

    .mm-edit-grid{
      grid-template-columns: 1fr;
    }

    .mm-upload-row,
    .mm-create{
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="mm-wrap">
  <div class="mm-shell">

    <div class="mm-head">
      <div class="mm-head-top">
        <div class="min-w-0">
          <h1 class="mm-title">Editar menú: {{ $node->label }}</h1>
          <p class="mm-sub">
            Aquí puedes crear sub-opciones y asignarles PDF. Los cambios se reflejan automáticamente en el menú superior.
          </p>
        </div>

        @if(session('status'))
          <div class="mm-flash">
            {{ session('status') }}
          </div>
        @endif
      </div>

      <div class="mm-meta">
        <div class="mm-chip">Nodo ID <code>{{ $node->id }}</code></div>
        <div class="mm-chip">Key <code>{{ $node->key ?: '(vacío)' }}</code></div>
        <div class="mm-chip">URL <code>{{ $node->url ?: '(vacío)' }}</code></div>
      </div>
    </div>

    <div class="mm-body">

      {{-- ✅ Crear hijo --}}
      <div class="mm-card">
        <div class="mm-card-head">
          <div>
            <h2 class="mm-card-title">Agregar nueva opción</h2>
            <div class="mm-card-sub">Crea una sub-opción que después podrás editar, activar o asociar a un PDF.</div>
          </div>
        </div>

        <div class="mm-card-body">
          <form method="POST" action="{{ route('admin.menu.children.store', $node) }}" class="mm-create">
            @csrf
            <input
              type="text"
              name="label"
              value="{{ old('label') }}"
              placeholder="Ej. Accesorios"
              class="mm-input"
              required
            >
            <button class="mm-btn mm-btn-dark">
              Crear
            </button>
          </form>

          @error('label')
            <div class="mm-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- ✅ Hijos existentes --}}
      <div class="mt-5">
        @forelse($children as $child)
          @php
            $currentUrl = $child->url ? asset(ltrim($child->url, '/')) : null;
          @endphp

          <div class="mm-item">
            <div class="mm-item-grid">

              {{-- Lado izquierdo: información --}}
              <div>
                <h3 class="mm-item-title">{{ $child->label }}</h3>

                <div class="mm-badges">
                  <div class="mm-badge">
                    slug:
                    <code>{{ $child->slug ?: '(vacío)' }}</code>
                  </div>

                  <div class="mm-badge">
                    key:
                    <code>{{ $child->key ?: '(vacío)' }}</code>
                  </div>

                  <div class="mm-badge">
                    url:
                    <code>{{ $child->url ?: '(vacío)' }}</code>
                  </div>
                </div>

                @if($currentUrl)
                  <a href="{{ $currentUrl }}" target="_blank" class="mm-link">
                    Ver archivo actual
                  </a>
                @endif
              </div>

              {{-- Lado derecho: acciones --}}
              <div class="mm-actions">

                {{-- ✅ Subir PDF --}}
                <div class="mm-section">
                  <div class="mm-section-title">Subir / reemplazar PDF</div>

                  <form
                    method="POST"
                    action="{{ route('admin.menu.upload', $child) }}"
                    enctype="multipart/form-data"
                    class="mm-upload-row"
                  >
                    @csrf
                    <input type="file" name="pdf" accept="application/pdf" class="mm-file" required>
                    <button class="mm-btn mm-btn-dark">
                      Subir PDF
                    </button>
                  </form>

                  @error('pdf')
                    <div class="mm-error">{{ $message }}</div>
                  @enderror
                </div>

                {{-- ✅ Edit rápido --}}
                <div class="mm-section">
                  <div class="mm-section-title">Configuración rápida</div>

                  <form method="POST" action="{{ route('admin.menu.update', $child) }}" class="mm-edit-grid">
                    @csrf
                    @method('PUT')

                    <input
                      name="label"
                      value="{{ $child->label }}"
                      class="mm-input"
                      placeholder="Label"
                      required
                    />

                    <input
                      name="sort"
                      value="{{ (int)$child->sort }}"
                      class="mm-input"
                      placeholder="Sort"
                    />

                    <input
                      name="url"
                      value="{{ $child->url }}"
                      class="mm-input"
                      placeholder="url (opcional)"
                    />

                    <label class="mm-check">
                      <input type="checkbox" name="is_active" value="1" {{ $child->is_active ? 'checked' : '' }}>
                      Activo
                    </label>

                    <button class="mm-btn mm-btn-soft">
                      Guardar
                    </button>
                  </form>
                </div>

                {{-- ✅ Eliminar --}}
                <div class="mm-danger-row">
                  <form method="POST" action="{{ route('admin.menu.destroy', $child) }}"
                        onsubmit="return confirm('¿Seguro que quieres eliminar esta opción y sus hijos?');">
                    @csrf
                    @method('DELETE')
                    <button class="mm-btn mm-btn-danger">
                      Eliminar
                    </button>
                  </form>
                </div>

              </div>
            </div>
          </div>

        @empty
          <div class="mm-empty">
            No hay opciones debajo de este nodo.
          </div>
        @endforelse
      </div>

      <div class="mm-tip">
        Tip: esta vista vive en <code>/admin/menu/{id}/manage</code> y está pensada para usarse desde el engrane del menú.
      </div>

    </div>
  </div>
</div>
@endsection
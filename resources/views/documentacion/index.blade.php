@extends('layouts.app')

@section('content')

<style>
    :root{
        --navy:#1f3155;
        --line:#e5e7eb;
        --bg:#f5f7fb;
    }

    body{
        background:var(--bg);
    }

    .docs-wrap{
        max-width:1380px;
        margin:26px auto;
        padding:0 18px;
    }

    .docs-card{
        background:#fff;
        border-radius:24px;
        overflow:hidden;
        border:1px solid rgba(15,23,42,.06);
        box-shadow:0 14px 40px rgba(2,6,23,.05);
    }

    .docs-top{
        padding:18px 22px;
        border-bottom:1px solid var(--line);

        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
    }

    .docs-left{
        display:flex;
        align-items:center;
        gap:14px;
        flex-wrap:wrap;
    }

    .search-box{
        position:relative;
    }

    .search-box input{
        width:320px;
        height:44px;
        border-radius:14px;
        border:1px solid var(--line);
        background:#fff;
        padding:0 16px 0 42px;
        outline:none;
    }

    .search-icon{
        position:absolute;
        left:14px;
        top:50%;
        transform:translateY(-50%);
        opacity:.5;
    }

    .tab-btn{
        border:1px solid var(--line);
        background:#fff;
        height:42px;
        padding:0 18px;
        border-radius:12px;
        cursor:pointer;
    }

    .tab-btn.active{
        background:var(--navy);
        color:#fff;
        border-color:var(--navy);
    }

    .add-btn{
        height:44px;
        padding:0 18px;
        border:none;
        border-radius:14px;
        background:var(--navy);
        color:#fff;
        font-weight:700;
        cursor:pointer;
    }

    .table-wrap{
        overflow:auto;
    }

    table{
        width:100%;
        border-collapse:collapse;
        min-width:1200px;
    }

    thead th{
        text-align:left;
        padding:18px 16px;
        font-size:12px;
        letter-spacing:.08em;
        color:#6b7280;
        border-bottom:1px solid var(--line);
    }

    tbody td{
        padding:18px 16px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }

    tbody tr:hover{
        background:#fafcff;
    }

    .badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }

    .badge-blue{
        background:#e0f2fe;
        color:#0369a1;
    }

    .badge-yellow{
        background:#fef9c3;
        color:#854d0e;
    }

    .badge-green{
        background:#dcfce7;
        color:#166534;
    }

    .badge-red{
        background:#fee2e2;
        color:#991b1b;
    }

    .dot{
        width:8px;
        height:8px;
        border-radius:999px;
        background:currentColor;
    }

    .action-btn{
        border:none;
        border-radius:10px;
        padding:8px 12px;
        cursor:pointer;
        font-size:12px;
        font-weight:700;
    }

    .btn-edit{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .btn-delete{
        background:#fee2e2;
        color:#b91c1c;
    }

    .btn-download{
        background:#1f3155;
        color:#fff;
    }

    .modal-bg{
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.45);
        backdrop-filter:blur(6px);
        display:none;
        align-items:center;
        justify-content:center;
        z-index:9999;
    }

    .modal-card{
        width:min(720px,95vw);
        background:#fff;
        border-radius:24px;
        padding:22px;
    }

    .form-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
    }

    .field{
        display:flex;
        flex-direction:column;
        gap:6px;
    }

    .field-full{
        grid-column:1/-1;
    }

    .field input,
    .field select{
        height:44px;
        border-radius:12px;
        border:1px solid #d1d5db;
        padding:0 12px;
        outline:none;
    }

    .save-btn{
        height:46px;
        border:none;
        border-radius:14px;
        background:#1f3155;
        color:#fff;
        font-weight:700;
        cursor:pointer;
    }
</style>

<div class="docs-wrap">

    <div class="docs-card">

        <div class="docs-top">

            <div class="docs-left">

                <div class="search-box">
                    <span class="search-icon">🔎</span>

                    <input
                        type="text"
                        id="docSearch"
                        placeholder="Buscar documento..."
                    >
                </div>

                <button class="tab-btn active" data-filter="all">Todas</button>
                <button class="tab-btn" data-filter="Área fiscal">&Aacute;rea fiscal</button>
                <button class="tab-btn" data-filter="Contabilidad">Contabilidad</button>
                <button class="tab-btn" data-filter="Legal">Legal</button>

            </div>

            <button class="add-btn" onclick="openCreateModal()">
                + Agregar documento
            </button>

        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CATEGOR&Iacute;A</th>
                        <th>NOMBRE</th>
                        <th>VIGENCIA</th>
                        <th>ESTATUS</th>
                        <th>PERIODO</th>
                        <th>ACTUALIZADO POR</th>
                        <th>RESPONSABLE</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>

                <tbody id="docsTable">

                    @foreach($documentos as $doc)

                        <tr data-category="{{ $doc->categoria }}">

                            <td>{{ $doc->id }}</td>

                            <td>
                                <span class="badge {{ $doc->categoria === 'Contabilidad' ? 'badge-yellow' : 'badge-blue' }}">
                                    {{ $doc->categoria }}
                                </span>
                            </td>

                            <td>{{ $doc->nombre }}</td>

                            <td>{{ $doc->vigencia }}</td>

                            <td>
                                <span class="badge {{ $doc->estatus === 'Vigente' ? 'badge-green' : 'badge-red' }}">
                                    <span class="dot"></span>
                                    {{ $doc->estatus }}
                                </span>
                            </td>

                            <td>{{ $doc->periodo }}</td>

                            <td>{{ $doc->actualizado_por }}</td>

                            <td>{{ $doc->responsable }}</td>

                            <td style="display:flex; gap:8px; flex-wrap:wrap;">

                                @if($doc->archivo_path)
                                    <a
                                        href="{{ asset('storage/' . $doc->archivo_path) }}"
                                        download
                                        class="action-btn btn-download"
                                        style="text-decoration:none;"
                                    >
                                        Descargar
                                    </a>
                                @endif

                                <button
                                    class="action-btn btn-edit"
                                    onclick='openEditModal(@json($doc))'
                                >
                                    Editar
                                </button>

                                <form
                                    action="{{ route('documentacion.destroy', $doc) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar documento?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="action-btn btn-delete">
                                        Eliminar
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL --}}
<div class="modal-bg" id="docModal">

    <div class="modal-card">

        <form
            id="docForm"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div id="methodPut"></div>

            <div class="form-grid">

                <div class="field">
                    <label>Categoría</label>

                    <select name="categoria" id="fCategoria">
                        <option>Área fiscal</option>
                        <option>Contabilidad</option>
                        <option>Legal</option>
                    </select>
                </div>

                <div class="field">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="fNombre">
                </div>

                <div class="field">
                    <label>Vigencia</label>
                    <input type="text" name="vigencia" id="fVigencia">
                </div>

                <div class="field">
                    <label>Estatus</label>

                    <select name="estatus" id="fEstatus">
                        <option>Vigente</option>
                        <option>Vencido</option>
                    </select>
                </div>

                <div class="field">
                    <label>Periodo</label>
                    <input type="text" name="periodo" id="fPeriodo">
                </div>

                <div class="field">
                    <label>Actualizado por</label>
                    <input type="text" name="actualizado_por" id="fActualizado">
                </div>

                <div class="field field-full">
                    <label>Responsable</label>
                    <input type="text" name="responsable" id="fResponsable">
                </div>

                <div class="field field-full">
                    <label>Archivo</label>
                    <input type="file" name="archivo">
                </div>

                <div class="field field-full">
                    <button class="save-btn">
                        Guardar documento
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

<script>

    const modal = document.getElementById('docModal');

    function openCreateModal(){

        document.getElementById('docForm').reset();

        document.getElementById('docForm').action =
            "{{ route('documentacion.store') }}";

        document.getElementById('methodPut').innerHTML = '';

        modal.style.display = 'flex';
    }

    function openEditModal(doc){

        document.getElementById('fCategoria').value = doc.categoria || '';
        document.getElementById('fNombre').value = doc.nombre || '';
        document.getElementById('fVigencia').value = doc.vigencia || '';
        document.getElementById('fEstatus').value = doc.estatus || '';
        document.getElementById('fPeriodo').value = doc.periodo || '';
        document.getElementById('fActualizado').value = doc.actualizado_por || '';
        document.getElementById('fResponsable').value = doc.responsable || '';

        document.getElementById('docForm').action =
            '/documentacion/' + doc.id;

        document.getElementById('methodPut').innerHTML =
            '@method("PUT")';

        modal.style.display = 'flex';
    }

    modal.addEventListener('click', (e)=>{

        if(e.target === modal){
            modal.style.display = 'none';
        }

    });

</script>

@endsection
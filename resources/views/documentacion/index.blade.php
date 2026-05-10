@extends('layouts.app')

@section('content')

    <style>
        :root {
            --navy: #1f3155;
            --line: #e5e7eb;
            --bg: #f5f7fb;
        }

        body {
            background: var(--bg);
        }

        .docs-wrap {
            max-width: 1380px;
            margin: 26px auto;
            padding: 0 18px;
        }

        .docs-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, .06);
            box-shadow: 0 14px 40px rgba(2, 6, 23, .05);
        }

        .docs-top {
            padding: 18px 22px;
            border-bottom: 1px solid var(--line);

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .docs-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 320px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 0 16px 0 42px;
            outline: none;
        }


        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .tab-btn {
            border: 1px solid var(--line);
            background: #fff;
            height: 42px;
            padding: 0 18px;
            border-radius: 12px;
            cursor: pointer;
        }

        .tab-btn.active {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }

        .add-btn {
            height: 44px;
            padding: 0 18px;
            border: none;
            border-radius: 14px;
            background: var(--navy);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .table-wrap {
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        thead th {
            text-align: left;
            padding: 18px 16px;
            font-size: 12px;
            letter-spacing: .08em;
            color: #6b7280;
            border-bottom: 1px solid var(--line);
        }

        tbody td {
            padding: 18px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #fafcff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-blue {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-yellow {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
        }

        .action-btn {
            border: none;
            border-radius: 10px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-download {
            background: #1f3155;
            color: #fff;
        }

        .btn-preview {

            background: #eef2ff;

            color: #3730a3;

        }

        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-card {
            width: min(720px, 95vw);
            background: #fff;
            border-radius: 24px;
            padding: 22px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-full {
            grid-column: 1/-1;
        }

        .field input,
        .field select {
            height: 44px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            padding: 0 12px;
            outline: none;
        }

        .save-btn {
            height: 46px;
            border: none;
            border-radius: 14px;
            background: #1f3155;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
    </style>

    <div class="docs-wrap">

        <div class="docs-card">

            <div class="docs-top">

                <div class="docs-left">

                    <div class="search-box">
                        <span class="search-icon" aria-hidden="true">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M20 20L16.5 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                </path>
                            </svg>
                        </span>

                        <input type="text" id="docSearch" placeholder="Buscar documento...">
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
                                    @php

                                        $categoriaOriginal = trim($doc->categoria);

                                        $categoriaLimpia = mb_strtolower(
                                            iconv('UTF-8', 'UTF-8//IGNORE', $categoriaOriginal)
                                        );

                                        if (
                                            str_contains($categoriaLimpia, 'fiscal') ||
                                            str_contains($categoriaLimpia, 'rea')
                                        ) {

                                            $categoria = 'Área fiscal';

                                        } else {

                                            $categoria = $categoriaOriginal;
                                        }

                                    @endphp

                                    <span class="badge {{ $categoria === 'Contabilidad' ? 'badge-yellow' : 'badge-blue' }}">
                                        {!! $categoria === 'Área fiscal' ? '&Aacute;rea fiscal' : e($categoria) !!} </span>
                                </td>

                                <td>{{ $doc->nombre }}</td>

                                <td>{{ $doc->vigencia }}</td>

                                <td>
                                    <span class="badge {{ $doc->estatus === 'Vigente' ? 'badge-green' : 'badge-red' }}">
                                        <span class="dot"></span>
                                        {{ $doc->estatus }}
                                    </span>
                                </td>

                                <td>{{ $doc->actualizado_por }}</td>

                                <td>{{ $doc->responsable }}</td>

                                <td style="display:flex; gap:8px; flex-wrap:wrap;">

                                    @if($doc->archivo_path)

                                        <button type="button" class="action-btn btn-preview"
                                            onclick="openPreview('{{ asset('storage/' . $doc->archivo_path) }}')">
                                            Preview
                                        </button>

                                        <a href="{{ asset('storage/' . $doc->archivo_path) }}" download
                                            class="action-btn btn-download" style="text-decoration:none;">
                                            Descargar
                                        </a>

                                    @endif

                                    <button class="action-btn btn-edit" onclick='openEditModal(@json($doc))'>
                                        Editar
                                    </button>

                                    <form action="{{ route('documentacion.destroy', $doc) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar documento?')">
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

            <form id="docForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="methodPut"></div>

                <div class="form-grid">

                    <div class="field">
                        <label>Categor&iacute;a</label>

                        <select name="categoria" id="fCategoria">
                            <option value="Área fiscal">&Aacute;rea fiscal</option>
                            <option value="Contabilidad">Contabilidad</option>
                            <option value="Legal">Legal</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="fNombre">
                    </div>

                    <div class="field">
                        <label>Vigencia</label>
                        <input type="date" name="vigencia" id="fVigencia">
                    </div>

                    <div class="field">
                        <label>Estatus</label>

                        <select name="estatus" id="fEstatus">
                            <option>Vigente</option>
                            <option>Vencido</option>
                        </select>
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

    {{-- MODAL PREVIEW --}}

    <div class="modal-bg" id="previewModal">

        <div class="modal-card" style="width:min(1200px,96vw); height:min(90vh,900px); padding:0; overflow:hidden;">

            <div
                style="height:60px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; padding:0 18px; gap:12px;">

                <strong>Preview documento</strong>

                <div style="display:flex; gap:8px; align-items:center;">

                    <button type="button" class="action-btn btn-preview" onclick="zoomPreviewOut()">-</button>

                    <button type="button" class="action-btn btn-preview" onclick="zoomPreviewReset()">100%</button>

                    <button type="button" class="action-btn btn-preview" onclick="zoomPreviewIn()">+</button>

                    <button type="button" class="action-btn btn-download" onclick="printPreview()">

                        Imprimir

                    </button>

                    <button type="button" onclick="closePreview()" class="action-btn btn-delete">

                        Cerrar

                    </button>

                </div>

            </div>

            <div id="previewScroll" style="height:calc(100% - 60px); overflow:auto; background:#f8fafc;">

                <div id="previewStage" style="
                                            min-height:100%;
                                            width:100%;
                                            display:flex;
                                            justify-content:center;
                                            align-items:center;
                                            padding:18px;
                                            box-sizing:border-box;
                                        ">

                    <iframe id="previewFrame" src="" style="
                                                display:none;
                                                width:100%;
                                                height:780px;
                                                border:0;
                                                background:#fff;
                                                transform-origin:center center;
                                            "></iframe>

                    <img id="previewImage" src="" alt="" style="
                                                display:none;
                                                max-width:100%;
                                                max-height:calc(90vh - 120px);
                                                width:auto;
                                                height:auto;
                                                object-fit:contain;
                                                transform-origin:center center;
                                                transition:transform .12s ease;
                                            ">
                </div>

            </div>

        </div>

    </div>

    <script>

        const modal = document.getElementById('docModal');

        const previewModal = document.getElementById('previewModal');
        const previewFrame = document.getElementById('previewFrame');
        const previewImage = document.getElementById('previewImage');

        let previewZoom = 1;

        const previewScroll = document.getElementById('previewScroll');
        const previewStage = document.getElementById('previewStage');

        function isImageUrl(url) {
            return /\.(jpg|jpeg|png|webp|gif|svg)(\?.*)?$/i.test(url);
        }

        function isImagePreviewOpen() {
            return previewImage && previewImage.style.display !== 'none';
        }

        function currentPreviewEl() {
            return isImagePreviewOpen() ? previewImage : previewFrame;
        }

        function updateZoomLabel() {
            const zoomBtn = document.querySelector('[onclick="zoomPreviewReset()"]');
            if (zoomBtn) {
                zoomBtn.textContent = Math.round(previewZoom * 100) + '%';
            }
        }

        function resetPreviewScroll() {
            if (!previewScroll) return;

            previewScroll.scrollTop = 0;
            previewScroll.scrollLeft = 0;
        }

        function centerPreviewScroll() {
            if (!previewScroll) return;

            requestAnimationFrame(() => {
                const maxLeft = previewScroll.scrollWidth - previewScroll.clientWidth;
                const maxTop = previewScroll.scrollHeight - previewScroll.clientHeight;

                previewScroll.scrollLeft = Math.max(0, maxLeft / 2);
                previewScroll.scrollTop = Math.max(0, maxTop / 2);
            });
        }

        function applyPreviewZoom() {

            const el = currentPreviewEl();

            if (!el) return;

            el.style.transform = `scale(${previewZoom})`;

            updateZoomLabel();

            if (previewZoom > 1) {

                centerPreviewScroll();

            } else {

                resetPreviewScroll();

            }

        }

        function openPreview(url) {
            previewZoom = 1;

            previewFrame.src = '';
            previewImage.src = '';

            previewFrame.style.transform = 'scale(1)';
            previewImage.style.transform = 'scale(1)';

            if (isImageUrl(url)) {
                previewFrame.style.display = 'none';

                previewImage.src = url;
                previewImage.style.display = 'block';

                previewStage.style.alignItems = 'center';
                previewStage.style.justifyContent = 'center';
            } else {
                previewImage.style.display = 'none';

                previewFrame.src = url;
                previewFrame.style.display = 'block';

                previewStage.style.alignItems = 'flex-start';
                previewStage.style.justifyContent = 'center';
            }

            previewModal.style.display = 'flex';

            setTimeout(() => {
                applyPreviewZoom();
                resetPreviewScroll();
            }, 80);
        }

        function closePreview() {
            previewModal.style.display = 'none';

            previewFrame.src = '';
            previewImage.src = '';

            previewFrame.style.display = 'none';
            previewImage.style.display = 'none';

            previewFrame.style.transform = 'scale(1)';
            previewImage.style.transform = 'scale(1)';

            previewZoom = 1;
            updateZoomLabel();
            resetPreviewScroll();
        }

        function zoomPreviewIn() {
            previewZoom = Math.min(3, previewZoom + 0.15);
            applyPreviewZoom();
        }

        function zoomPreviewOut() {
            previewZoom = Math.max(0.5, previewZoom - 0.15);
            applyPreviewZoom();
        }

        function zoomPreviewReset() {
            previewZoom = 1;
            applyPreviewZoom();

            setTimeout(() => {
                resetPreviewScroll();
            }, 50);
        }

        function printPreview() {
            try {
                if (previewImage && previewImage.style.display !== 'none') {
                    const w = window.open('', '_blank');

                    if (!w) return;

                    w.document.write(`
                                            <html>
                                                <head>
                                                    <title>Imprimir</title>
                                                    <style>
                                                        body {
                                                            margin: 0;
                                                            min-height: 100vh;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            background: #fff;
                                                        }

                                                        img {
                                                            max-width: 100%;
                                                            max-height: 100vh;
                                                            object-fit: contain;
                                                        }
                                                    </style>
                                                </head>
                                                <body>
                                                    <img src="${previewImage.src}" onload="window.print(); window.close();" />
                                                </body>
                                            </html>
                                        `);

                    w.document.close();
                    return;
                }

                previewFrame.contentWindow.focus();
                previewFrame.contentWindow.print();

            } catch (e) {
                window.open(previewFrame.src || previewImage.src, '_blank');
            }
        }

        const DOCUMENTACION_BASE_URL = @json(url('/documentacion'));

        function openCreateModal() {

            document.getElementById('docForm').reset();

            document.getElementById('docForm').action =
                "{{ route('documentacion.store') }}";

            document.getElementById('methodPut').innerHTML = '';

            modal.style.display = 'flex';
        }

        function openEditModal(doc) {

            document.getElementById('fCategoria').value =
                doc.categoria || '';

            document.getElementById('fNombre').value =
                doc.nombre || '';

            document.getElementById('fVigencia').value =
                doc.vigencia || '';

            document.getElementById('fEstatus').value =
                doc.estatus || '';

            document.getElementById('fActualizado').value =
                doc.actualizado_por || '';

            document.getElementById('fResponsable').value =
                doc.responsable || '';

            document.getElementById('docForm').action =
                DOCUMENTACION_BASE_URL + '/' + doc.id;

            document.getElementById('methodPut').innerHTML =
                '@method("PUT")';

            modal.style.display = 'flex';
        }

        modal.addEventListener('click', (e) => {

            if (e.target === modal) {

                modal.style.display = 'none';
            }

        });

        previewModal.addEventListener('click', (e) => {

            if (e.target === previewModal) {

                closePreview();
            }

        });


        const docSearch = document.getElementById('docSearch');
        const tabButtons = document.querySelectorAll('.tab-btn');
        const docRows = document.querySelectorAll('#docsTable tr');

        function filterDocs() {
            const term = (docSearch?.value || '').toLowerCase().trim();
            const activeFilter =
                document.querySelector('.tab-btn.active')?.dataset.filter || 'all';

            docRows.forEach(row => {
                const category = row.dataset.category || '';
                const text = row.innerText.toLowerCase();

                const matchesSearch = text.includes(term);
                const matchesCategory =
                    activeFilter === 'all' || category === activeFilter;

                row.style.display =
                    matchesSearch && matchesCategory ? '' : 'none';
            });
        }

        docSearch?.addEventListener('input', filterDocs);

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(x => x.classList.remove('active'));
                btn.classList.add('active');
                filterDocs();
            });
        });

    </script>

@endsection
@extends('layouts.app')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:22px 18px;">
  <h2 style="font-weight:900;font-size:20px;margin-bottom:8px;">Búsqueda</h2>

  <form method="GET" action="{{ route('search.global') }}" style="display:flex;gap:10px;align-items:center;margin-bottom:16px;">
    <input name="q" value="{{ $q }}" placeholder="Buscar en todo..." style="flex:1;border:1px solid rgba(15,23,42,.14);border-radius:14px;padding:.85rem 1rem;">
    <button style="border:0;border-radius:14px;padding:.85rem 1rem;background:#2563eb;color:#fff;font-weight:900;cursor:pointer;">Buscar</button>
  </form>

  @if($q === '')
    <div style="color:rgba(15,23,42,.6);">Escribe algo para buscar.</div>
  @else
    <div style="color:rgba(15,23,42,.6);margin-bottom:12px;">
      Resultados para: <b>{{ $q }}</b> ({{ count($results) }})
    </div>

    @if(!count($results))
      <div style="padding:16px;border:1px solid rgba(15,23,42,.12);border-radius:16px;background:#fff;">
        Sin resultados.
      </div>
    @else
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
        @foreach($results as $r)
          <div style="border:1px solid rgba(15,23,42,.12);border-radius:16px;background:#fff;padding:14px;">
            <div style="font-size:12px;color:rgba(15,23,42,.55);font-weight:900;margin-bottom:6px;">{{ $r['type'] }}</div>
            <div style="font-weight:950;margin-bottom:6px;">{{ $r['title'] }}</div>
            <div style="font-size:13px;color:rgba(15,23,42,.68);line-height:1.3;">{{ $r['snippet'] }}</div>

            @if(!empty($r['url']))
              <div style="margin-top:10px;">
                <a href="{{ $r['url'] }}" style="font-weight:900;color:#2563eb;text-decoration:none;">Abrir ↗</a>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  @endif
</div>
@endsection
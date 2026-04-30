@extends('layouts.app')

@section('content')

<div class="cm-page">

  <div class="cm-header">
    <h1>Comunicados</h1>
    <p>Historial de comunicados</p>
  </div>

  <div class="cm-grid">

    @forelse($comunicados as $item)

      <div class="cm-card">

        <div class="cm-thumb">
          @if($item->image_path)
            <img src="{{ asset('storage/' . $item->image_path) }}">
          @else
            <div class="cm-noimg">Sin imagen</div>
          @endif
        </div>

        <div class="cm-body">
          <div class="cm-title">{{ $item->title }}</div>

          <div class="cm-date">
            {{ $item->created_at->format('d M Y') }}
          </div>
        </div>

      </div>

    @empty

      <div class="cm-empty">
        No hay comunicados todavía
      </div>

    @endforelse

  </div>

</div>

<style>
.cm-page {
  max-width: 1300px;
  margin: auto;
  padding: 24px;
}

.cm-header h1 {
  font-size: 28px;
  font-weight: 800;
}

.cm-header p {
  color: #64748b;
}

.cm-grid {
  margin-top: 24px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 18px;
}

.cm-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #eee;
  transition: .2s;
}

.cm-card:hover {
  transform: translateY(-4px);
}

.cm-thumb {
  aspect-ratio: 4/5;
  background: #f1f5f9;
}

.cm-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cm-noimg {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #94a3b8;
}

.cm-body {
  padding: 12px;
}

.cm-title {
  font-weight: 700;
}

.cm-date {
  font-size: 12px;
  color: #64748b;
}

.cm-empty {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px;
  color: #64748b;
}
</style>

@endsection
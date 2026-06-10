@extends('layouts.app')

@section('content')

<div class="inv-frame-page">
  <iframe
    src="https://pedidos.lineaitalia.net/inventario/frame"
    class="inv-frame"
    title="Inventarios"
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
    allow="clipboard-read; clipboard-write; fullscreen"></iframe>
</div>

<style>
  .inv-frame-page {
    width: 100%;
    max-width: 1450px;
    margin: 0 auto;
    padding: 18px;
  }

  .inv-frame {
    width: 100%;
    height: calc(100vh - var(--nav-height, 58px) - 120px);
    min-height: 600px;
    border: 1px solid rgba(15, 23, 42, .08);
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 18px 46px rgba(2, 6, 23, .08);
    display: block;
  }

  @media (max-width: 1300px) {
    .inv-frame {
      height: calc(100vh - 169px - 100px);
    }
  }

  @media (max-width: 768px) {
    .inv-frame-page {
      padding: 10px;
    }

    .inv-frame {
      height: calc(100vh - 126px - 80px);
      border-radius: 14px;
    }
  }
</style>

@endsection

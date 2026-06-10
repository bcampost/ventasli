{{-- resources/views/partials/rankings-dashboard.blade.php --}}

@php
  $rankingUrl = 'https://crm.lineaitalia.mx/public/ranking/asesores';
@endphp

<style>
  .rk-embed-wrap {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 16px;
  }

  .rk-embed-viewport {
    width: 100%;
    height: 980px;
    overflow: hidden;
    background: transparent;
    border-radius: 0;
  }

  .rk-embed-frame {
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
    background: transparent;
  }

  @media(max-width: 1200px) {
    .rk-embed-viewport {
      height: 1040px;
    }
  }

  @media(max-width: 768px) {
    .rk-embed-wrap {
      padding: 0 10px;
    }

    .rk-embed-viewport {
      height: 1280px;
    }
  }
</style>

<div class="rk-embed-wrap">
  <div class="rk-embed-viewport">
    <iframe src="{{ $rankingUrl }}" class="rk-embed-frame" loading="lazy" title="Ranking de asesores">
    </iframe>
  </div>
</div>
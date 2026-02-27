@props([
  // items: [
  //   ['name'=>'CAROLINA REYES','branch'=>'QRO','avatar'=>null,'amount'=>1739620],
  //   ...
  // ]
  'title' => 'TOP 5 DEL MES',
  'items' => [],
])

@php
  $labels = [
    1 => ['P1', 'LEADER'],
    2 => ['P2', 'CHALLENGER'],
    3 => ['P3', 'ON FIRE'],
    4 => ['P4', 'CONSISTENT'],
    5 => ['P5', 'RISING'],
  ];

  // Colores por lugar (ajústalos a tu gusto)
  $colors = [
    1 => ['from' => '#D4AF37', 'to' => '#8C6B1F'], // oro
    2 => ['from' => '#BFC7D5', 'to' => '#6B778C'], // plata
    3 => ['from' => '#E04B3F', 'to' => '#7C1F17'], // rojo
    4 => ['from' => '#7E57C2', 'to' => '#3E2670'], // morado
    5 => ['from' => '#4CCB7F', 'to' => '#1F6B3E'], // verde
  ];

  $fmtMoney = function($n){
    if ($n === null || $n === '') return '$0';
    return '$' . number_format((float)$n, 0, '.', ',');
  };

  $fallbackAvatar = function($name){
    $name = trim((string)$name);
    $parts = preg_split('/\s+/', $name);
    $a = strtoupper(mb_substr($parts[0] ?? 'U', 0, 1));
    $b = strtoupper(mb_substr($parts[1] ?? 'S', 0, 1));
    return $a.$b;
  };
@endphp

<style>
  .t5-card{
    border-radius: 18px;
    padding: 14px 14px 12px;
    color: #fff;
    background:
      radial-gradient(420px 180px at 18% 0%, rgba(255,255,255,.12), transparent 60%),
      radial-gradient(420px 180px at 90% 40%, rgba(255,255,255,.06), transparent 60%),
      #0b0f18;
    box-shadow: 0 18px 40px rgba(2,6,23,.22);
    border: 1px solid rgba(255,255,255,.08);
  }
  .t5-head{ display:flex; align-items:center; gap:10px; margin-bottom: 10px; }
  .t5-flag{
    width: 30px; height: 30px; border-radius: 10px;
    display:grid; place-items:center;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.10);
    font-size: 16px;
  }
  .t5-title{ font-weight: 950; letter-spacing:.02em; font-size: 14px; opacity:.95; }

  .t5-row{
    display:grid;
    grid-template-columns: 86px 1fr auto;
    gap: 12px;
    align-items:center;
    padding: 10px 10px;
    border-radius: 14px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.06);
    margin-bottom: 10px;
    position: relative;
    overflow:hidden;
  }
  .t5-row:last-child{ margin-bottom: 0; }

  .t5-rank{
    border-radius: 12px;
    padding: 10px 10px;
    font-weight: 950;
    line-height: 1.05;
    text-transform: uppercase;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.10);
    background: linear-gradient(180deg, var(--c1), var(--c2));
  }
  .t5-rank .p{ font-size: 13px; opacity:.95; }
  .t5-rank .tag{ font-size: 10px; opacity:.92; margin-top: 4px; letter-spacing:.08em; }

  .t5-main{ display:flex; align-items:center; gap: 10px; min-width:0; }
  .t5-avatar{
    width: 34px; height: 34px; border-radius: 999px;
    display:grid; place-items:center;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.12);
    overflow:hidden;
    flex: 0 0 auto;
    font-weight: 950;
    font-size: 12px;
  }
  .t5-avatar img{ width:100%; height:100%; object-fit:cover; display:block; }
  .t5-meta{ min-width:0; }
  .t5-name{
    font-weight: 950;
    font-size: 13px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }
  .t5-sub{
    margin-top: 2px;
    font-size: 11px;
    opacity:.82;
    font-weight: 800;
  }

  .t5-amt{
    font-weight: 950;
    font-size: 13px;
    white-space: nowrap;
    opacity:.95;
  }
</style>

<div class="t5-card">
  <div class="t5-head">
    <div class="t5-flag">🏁</div>
    <div class="t5-title">{{ $title }}</div>
  </div>

  @foreach(array_slice($items, 0, 5) as $i => $row)
    @php
      $pos = $i + 1;
      $lab = $labels[$pos] ?? ['P'.$pos, 'TOP'];
      $col = $colors[$pos] ?? ['from'=>'#999', 'to'=>'#333'];
      $name = $row['name'] ?? '—';
      $branch = $row['branch'] ?? '';
      $avatar = $row['avatar'] ?? null;
      $amount = $row['amount'] ?? 0;
    @endphp

    <div class="t5-row" style="--c1: {{ $col['from'] }}; --c2: {{ $col['to'] }};">
      <div class="t5-rank">
        <div class="p">{{ $lab[0] }}</div>
        <div class="tag">{{ $lab[1] }}</div>
      </div>

      <div class="t5-main">
        <div class="t5-avatar">
          @if($avatar)
            <img src="{{ $avatar }}" alt="{{ $name }}">
          @else
            {{ $fallbackAvatar($name) }}
          @endif
        </div>
        <div class="t5-meta">
          <div class="t5-name">{{ $name }}</div>
          <div class="t5-sub">{{ $branch }}</div>
        </div>
      </div>

      <div class="t5-amt">{{ $fmtMoney($amount) }}</div>
    </div>
  @endforeach
</div>
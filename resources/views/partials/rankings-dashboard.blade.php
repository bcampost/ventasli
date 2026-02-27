{{-- resources/views/partials/rankings-dashboard.blade.php --}}

@php
  // ===========================
  // ✅ DATA SIMULADA
  // ===========================
  $kpis = [
    ['label' => 'Objetivo Venta Directa 2025', 'value' => '$250,000,000'],
    ['label' => 'Acumulado 2025', 'value' => '$12,834,879'],
    ['label' => 'Faltante para lograr objetivo', 'value' => '$237,165,121'],
  ];

  // ✅ 25 asesores simulados
  $ranking = [
    ['name' => 'Carolina Reyes', 'sucursal' => 'QRO',  'promedio' => '$1,783,274', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'Fabiola Partida', 'sucursal' => 'CDMX', 'promedio' => '$1,656,796', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'Liz Cervantes', 'sucursal' => 'CDMX', 'promedio' => '$1,536,040', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'Adriana Ruiz', 'sucursal' => 'CDMX', 'promedio' => '$1,058,495', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'Yazmin Cantú', 'sucursal' => 'MTY',  'promedio' => '$1,056,319', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'Mayra Trujillo', 'sucursal' => 'AGS',  'promedio' => '$951,874',  'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'Tania Gallegos', 'sucursal' => 'CDMX', 'promedio' => '$789,730',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Ignacio del Toro', 'sucursal' => 'QRO', 'promedio' => '$666,696',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Jessica Villafaña', 'sucursal' => 'MTY', 'promedio' => '$559,383',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Marisol Maldonado', 'sucursal' => 'MTY', 'promedio' => '$499,510',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Emiliano Veliz', 'sucursal' => 'MTY', 'promedio' => '$474,144',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Cristina Medina', 'sucursal' => 'AGS', 'promedio' => '$463,059',  'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'Kathia Ramírez', 'sucursal' => 'AGS', 'promedio' => '$327,701',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Rodrigo Bustillo', 'sucursal' => 'CDMX', 'promedio' => '$318,683',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Susana Chavez', 'sucursal' => 'AGS', 'promedio' => '$313,991',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Daniel Sanchez', 'sucursal' => 'CDMX', 'promedio' => '$288,599',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Elizabeth Duran', 'sucursal' => 'QRO', 'promedio' => '$281,183',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Aurora Mac', 'sucursal' => 'MTY', 'promedio' => '$264,462',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Eduardo Herrera', 'sucursal' => 'CDMX', 'promedio' => '$261,666',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Lorena Paredes', 'sucursal' => 'AGS', 'promedio' => '$258,042',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Sharon Garcia', 'sucursal' => 'QRO', 'promedio' => '$222,191',  'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'Ariadna Pilvoras', 'sucursal' => 'QRO', 'promedio' => '$144,425',  'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'Xcaret Fuentes', 'sucursal' => 'QRO', 'promedio' => '$99,905',  'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'Mireya de Leon', 'sucursal' => 'AGS', 'promedio' => '$75,917',  'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'Enrique Buck', 'sucursal' => 'QRO', 'promedio' => '$66,373',  'status' => 'Fuera de expectativas', 'icon' => '❌'],
  ];

  $mejoresFeb = [
    ['name' => 'Aurora Mac', 'sucursal' => 'MTY', 'monto' => '$281,181'],
    ['name' => 'Adriana Ruiz', 'sucursal' => 'CDMX', 'monto' => '$22,014'],
    ['name' => 'Lorena Paredes', 'sucursal' => 'AGS', 'monto' => '$0'],
    ['name' => 'Cristina Medina', 'sucursal' => 'AGS', 'monto' => '$0'],
    ['name' => 'Tania Gallegos', 'sucursal' => 'CDMX', 'monto' => '$0'],
  ];

  $mejoresEne = [
    ['name' => 'Tania Gallegos', 'sucursal' => 'CDMX', 'monto' => '$2,317,632'],
    ['name' => 'Carolina Reyes', 'sucursal' => 'QRO',  'monto' => '$2,090,028'],
    ['name' => 'Mayra Trujillo', 'sucursal' => 'AGS',  'monto' => '$1,644,594'],
    ['name' => 'Adriana Ruiz', 'sucursal' => 'CDMX', 'monto' => '$1,175,490'],
    ['name' => 'Ariadna Pilvoras', 'sucursal' => 'QRO', 'monto' => '$773,208'],
  ];

  $rangos = [
    ['icon' => '🏆', 'label' => 'Redefiniendo expectativas', 'range' => 'mayor a $1,500,000'],
    ['icon' => '✅', 'label' => 'Superando expectativas', 'range' => 'de $800,000 a $1,500,000'],
    ['icon' => '☑️', 'label' => 'Cumpliendo expectativas', 'range' => 'de $400,000 a $800,000'],
    ['icon' => '⚠️', 'label' => 'Abajo de expectativas', 'range' => 'de $200,000 a $400,000'],
    ['icon' => '❌', 'label' => 'Fuera de expectativas', 'range' => 'menor a $200,000'],
    ['icon' => '🟡', 'label' => 'Periodo de gracia', 'range' => 'menor a 3 meses'],
  ];

  $initials = function($name){
    $parts = preg_split('/\s+/', trim($name));
    $a = mb_substr($parts[0] ?? '', 0, 1);
    $b = mb_substr($parts[1] ?? '', 0, 1);
    return mb_strtoupper($a.$b);
  };

  $toIntMoney = function($s){
    $n = preg_replace('/[^\d]/', '', (string)$s);
    return (int)($n ?: 0);
  };

  $fmtMoney = fn($num) => '$' . number_format((int)$num, 0, '.', ',');

  $totalAsesores = count($ranking);

  // ===========================
  // ✅ CONTROLES (GET)
  // ===========================
  $vendors = array_values(array_map(fn($r) => $r['name'], $ranking));
  $yearsAllowed = [2025, 2026];
  $monthsAllowed = [
    ['k'=>'01','l'=>'Ene'],['k'=>'02','l'=>'Feb'],['k'=>'03','l'=>'Mar'],['k'=>'04','l'=>'Abr'],
    ['k'=>'05','l'=>'May'],['k'=>'06','l'=>'Jun'],['k'=>'07','l'=>'Jul'],['k'=>'08','l'=>'Ago'],
    ['k'=>'09','l'=>'Sep'],['k'=>'10','l'=>'Oct'],['k'=>'11','l'=>'Nov'],['k'=>'12','l'=>'Dic'],
  ];
  $monthLabel = [];
  foreach($monthsAllowed as $m){ $monthLabel[$m['k']] = $m['l']; }

  $qMode = request()->query('mode', 'total');
  if(!in_array($qMode, ['vendor','total'], true)) $qMode = 'total';
  if($qMode === 'vendor') $qMode = 'total';

  $qVendor = request()->query('vendor', $vendors[0] ?? '');
  $qFromY  = (int)request()->query('from_year', 2025);
  $qFromM  = request()->query('from_month', '01');
  $qToY    = (int)request()->query('to_year', 2025);
  $qToM    = request()->query('to_month', '12');

  if(!in_array($qVendor, $vendors, true)){
    $qVendor = $vendors[0] ?? $qVendor;
  }
  if(!in_array($qFromY, $yearsAllowed, true)) $qFromY = 2025;
  if(!in_array($qToY, $yearsAllowed, true))   $qToY = 2025;

  $validMonths = array_map(fn($m)=>$m['k'], $monthsAllowed);
  if(!in_array($qFromM, $validMonths, true)) $qFromM = '01';
  if(!in_array($qToM, $validMonths, true))   $qToM = '12';

  $ymToIndex = fn($y,$m) => ($y*12) + ((int)$m);
  $fromIdx = $ymToIndex($qFromY, $qFromM);
  $toIdx   = $ymToIndex($qToY, $qToM);

  if($toIdx < $fromIdx){
    [$qFromY,$qToY] = [$qToY,$qFromY];
    [$qFromM,$qToM] = [$qToM,$qFromM];
    $fromIdx = $ymToIndex($qFromY, $qFromM);
    $toIdx   = $ymToIndex($qToY, $qToM);
  }

  $maxSpan = 24;
  if(($toIdx - $fromIdx + 1) > $maxSpan){
    $toIdx = $fromIdx + $maxSpan - 1;
    $tmpY = intdiv($toIdx, 12);
    $tmpM = $toIdx % 12;
    if($tmpM === 0){ $tmpY -= 1; $tmpM = 12; }
    $qToY = $tmpY;
    $qToM = str_pad((string)$tmpM, 2, '0', STR_PAD_LEFT);
  }

  $monthKeys = [];
  for($idx=$fromIdx; $idx <= $toIdx; $idx++){
    $y = intdiv($idx, 12);
    $m = $idx % 12;
    if($m === 0){ $y -= 1; $m = 12; }
    $monthKeys[] = ['y'=>$y, 'm'=>str_pad((string)$m,2,'0',STR_PAD_LEFT)];
  }

  $rangesNumeric = [
    'Fuera de expectativas' => ['min'=>0,        'max'=>200000],
    'Abajo de expectativas' => ['min'=>200000,   'max'=>400000],
    'Cumpliendo expectativas' => ['min'=>400000, 'max'=>800000],
    'Superando expectativas' => ['min'=>800000,  'max'=>1500000],
    'Redefiniendo expectativas' => ['min'=>1500000, 'max'=>2500000],
  ];

  $series = [];

  if($qMode === 'vendor'){
    $selectedRow = null;
    foreach($ranking as $r){
      if($r['name'] === $qVendor){ $selectedRow = $r; break; }
    }
    $selectedStatus = $selectedRow['status'] ?? 'Cumpliendo expectativas';
    if(!isset($rangesNumeric[$selectedStatus])){
      $selectedStatus = 'Cumpliendo expectativas';
    }

    $band = $rangesNumeric[$selectedStatus];
    $bandMin = (int)$band['min'];
    $bandMax = (int)$band['max'];

    $avg = $toIntMoney($selectedRow['promedio'] ?? '$0');
    $center = max($bandMin, min($avg, $bandMax));
    $span = max(1, (int)(($bandMax - $bandMin) * 0.55));
    $lowTarget  = max($bandMin, $center - (int)($span/2));
    $highTarget = min($bandMax, $center + (int)($span/2));

    $seed = abs((int)crc32($qVendor));

    foreach($monthKeys as $i => $ym){
      $t = ($seed + ($ym['y']*100) + (int)$ym['m']*17 + $i*91);
      $noise = ($t % 1000) / 1000;
      $trend = sin(($i+1) * 0.55) * 0.18;
      $val = $lowTarget + ($highTarget - $lowTarget) * $noise;
      $val = $val * (1 + $trend);
      $val = max($bandMin, min((int)$val, $bandMax));
      $series[] = (int)$val;
    }

    $minY = $bandMin;
    $maxY = $bandMax;

    $rangeLabel = $selectedStatus . ' · ' . $fmtMoney($bandMin) . ' – ' . $fmtMoney($bandMax);
    $badgeIcon = $selectedRow['icon'] ?? '•';
    $chartTitle = $qVendor;
    $chartSub = 'Mes con mes (MXN)';

  } else {
    $baseSum = 0;
    foreach($ranking as $r){
      $baseSum += $toIntMoney($r['promedio'] ?? '$0');
    }

    $baseCompany = (int) round($baseSum / max(1, count($ranking)) * count($ranking));

    $seed = abs((int)crc32('VENTAS_TOTALES_EMPRESA'));

    foreach($monthKeys as $i => $ym){
      $t = ($seed + ($ym['y']*100) + (int)$ym['m']*29 + $i*137);
      $noise = ($t % 1000) / 1000;
      $season = sin(($i+1) * 0.45) * 0.10;
      $drift  = cos(($i+1) * 0.22) * 0.06;

      $val = $baseCompany * (0.88 + 0.24*$noise);
      $val = $val * (1 + $season + $drift);

      $series[] = (int) max(0, $val);
    }

    $minSeries = min($series);
    $maxSeries = max($series);
    $pad = max(1, (int)(($maxSeries - $minSeries) * 0.18));
    $minY = max(0, $minSeries - $pad);
    $maxY = $maxSeries + $pad;

    $rangeLabel = 'Promedio/Total empresa (simulado)';
    $badgeIcon = '🏢';
    $chartTitle = 'Ventas Totales';
    $chartSub = 'Mes con mes (MXN)';
  }

  $minV = min($series);
  $maxV = max($series);

  $w = 1180;
  $h = 420;
  $pl = 70;
  $pr = 24;
  $pt = 18;
  $pb = 80;

  $plotW = $w - $pl - $pr;
  $plotH = $h - $pt - $pb;

  $n = count($series);
  $dx = $n > 1 ? ($plotW / ($n - 1)) : 0;

  $mapY = function($v) use ($minY, $maxY, $plotH, $pt){
    $range = max(1, ($maxY - $minY));
    $t = ($v - $minY) / $range;
    return $pt + (1 - $t) * $plotH;
  };

  $points = [];
  for($i=0; $i<$n; $i++){
    $x = $pl + $dx * $i;
    $y = $mapY($series[$i]);
    $points[] = ['x'=>$x, 'y'=>$y, 'v'=>$series[$i]];
  }

  $d = '';
  foreach($points as $i=>$p){
    $d .= ($i===0 ? 'M' : 'L') . number_format($p['x'],2,'.','') . ' ' . number_format($p['y'],2,'.','') . ' ';
  }

  $yTicks = 4;
  $ticks = [];
  for($i=0; $i<=$yTicks; $i++){
    $t = $i / $yTicks;
    $v = (int) round($maxY - $t * ($maxY - $minY));
    $y = $pt + $t * $plotH;
    $ticks[] = ['y'=>$y, 'v'=>$v];
  }

  $xLabels = [];
  foreach($monthKeys as $ym){
    $xLabels[] = ($monthLabel[$ym['m']] ?? $ym['m']) . ' ' . substr((string)$ym['y'], -2);
  }

  // ✅ Labels para TOP5 (estilo imagen)
  $top5Badges = [
    1 => ['p'=>'P1','tag'=>'LEADER'],
    2 => ['p'=>'P2','tag'=>'CHALLENGER'],
    3 => ['p'=>'P3','tag'=>'ON FIRE'],
    4 => ['p'=>'P4','tag'=>'CONSISTENT'],
    5 => ['p'=>'P5','tag'=>'ROOKIE'],
  ];
@endphp

<style>
  .rk{
    --rk-text: var(--text, #0f172a);
    --rk-line: rgba(15,23,42,.10);
    --rk-card: rgba(255,255,255,.96);
    --rk-shadow: 0 16px 40px rgba(15,23,42,.08);
    --rk-shadow2: 0 10px 22px rgba(15,23,42,.06);
  }
  .rk-wrap{ max-width: 1180px; margin: 28px auto 0; padding: 0 16px; }

  .rk-top{ border-radius: 18px 18px 0 0; overflow: hidden; box-shadow: var(--rk-shadow); background: rgba(15,23,42,.88); }
  .rk-top-inner{ padding: 18px; display:flex; align-items:flex-start; justify-content:space-between; gap:18px; flex-wrap:wrap; color:#fff; }
  .rk-brand{ line-height:1.05; min-width:220px; }
  .rk-brand small{ display:block; font-size:18px; opacity:.92; font-weight:600; }
  .rk-brand span{ display:block; font-size:22px; font-weight:750; letter-spacing:-.01em; }

  .rk-kpis{ display:flex; gap:28px; flex-wrap:wrap; justify-content:flex-end; align-items:flex-start; }
  .rk-kpi .k-label{ font-size:12px; opacity:.9; white-space:nowrap; }
  .rk-kpi .k-value{ font-size:26px; font-weight:750; margin-top:2px; letter-spacing:-.01em; }

  .rk-body{
    border: 1px solid var(--rk-line);
    border-top: none;
    border-radius: 0 0 18px 18px;
    background: rgba(255,255,255,.70);
    backdrop-filter: blur(8px);
    padding: 18px;
    box-shadow: var(--rk-shadow2);
  }

  .rk-grid{
    display:grid;
    grid-template-columns: 1fr;
    gap: 18px;
    align-items: stretch;
  }
  @media(min-width: 900px){
    .rk-grid{ grid-template-columns: 1.7fr 1fr; }
  }

  .rk-card{
    background: var(--rk-card);
    border: 1px solid var(--rk-line);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(15,23,42,.05);
    display:flex;
    flex-direction:column;
    min-height: 0;
  }

  .rk-card-head{
    padding: 14px 16px 12px;
    border-bottom: 1px solid var(--rk-line);
    background: rgba(255,255,255,.80);
  }
  .rk-card-head .title{ font-size: 18px; font-weight: 800; letter-spacing: -.01em; color: var(--rk-text); }
  .rk-card-head .sub{ margin-top: 2px; font-size: 13px; color: rgba(15,23,42,.78); }

  .rk-headRow{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 12px;
  }

  .rk-viewToggle{
    display:flex;
    gap:10px;
    align-items:center;
    justify-content:flex-end;
    margin-top: 2px;
  }
  .rk-viewBtn{
    border: 1px solid rgba(15,23,42,.14);
    background: rgba(255,255,255,.75);
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
    cursor: pointer;
    box-shadow: 0 10px 18px rgba(15,23,42,.06);
    transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
    white-space:nowrap;
  }
  .rk-viewBtn:hover{ transform: translateY(-1px); }
  .rk-viewBtn.is-active{
    border-color: rgba(37,99,235,.55);
    box-shadow: 0 14px 22px rgba(37,99,235,.14);
  }

  .rk-table{ width:100%; border-collapse:collapse; font-size:13px; color:var(--rk-text); }
  .rk-table thead th{
    font-weight: 750; font-size: 12.5px;
    padding: 12px 14px;
    background: rgba(15,23,42,.035);
    color: rgba(15,23,42,.92);
  }
  .rk-table tbody td{
    padding: 12px 14px;
    border-top: 1px solid rgba(15,23,42,.07);
    vertical-align: middle;
    color: rgba(15,23,42,.92);
  }
  .rk-table tbody tr:hover{ background: rgba(15,23,42,.02); }

  .rk-num{ width: 34px; font-weight: 800; color: rgba(15,23,42,.92); }

  .rk-avatar{
    width: 34px; height: 34px;
    border-radius: 999px;
    background: rgba(15,23,42,.92);
    color: #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: 850;
    font-size: 12px;
    box-shadow: 0 10px 16px rgba(15,23,42,.18);
  }
  .rk-person{ display:flex; align-items:center; gap:10px; }
  .rk-name{ font-weight: 850; letter-spacing: -.01em; font-size: 12.5px; line-height:1.1; }
  .rk-sucursal{ font-weight: 800; color: rgba(15,23,42,.92); }
  .rk-money{ text-align:right; font-weight: 850; color: rgba(15,23,42,.98); white-space:nowrap; }
  .rk-ital{ font-style: italic; color: rgba(15,23,42,.92); }
  .rk-icon{ width: 24px; display:inline-flex; justify-content:center; }

  .rk-scroll{ flex: 1; min-height: 0; overflow: auto; }

  .rk-footer{
    padding: 12px 16px;
    border-top: 1px solid rgba(15,23,42,.07);
    background: rgba(15,23,42,.015);
    display:flex;
    justify-content:space-between;
    gap: 10px;
    font-size: 13px;
    color: rgba(15,23,42,.88);
    font-weight: 650;
  }

  .rk-right-col{
    height: 100%;
    min-height: 0;
    display:grid;
    grid-template-rows: 1fr 1fr 1fr;
    gap: 18px;
  }

  .rk-chart-card{ margin-top: 18px; }
  .rk-controls{
    padding: 10px 12px;
    border-bottom: 1px solid rgba(15,23,42,.07);
    background: rgba(255,255,255,.88);
  }
  .rk-controls-row{
    display:grid;
    grid-template-columns: 1fr;
    gap: 8px;
    align-items: center;
  }
  @media(min-width: 760px){
    .rk-controls-row{ grid-template-columns: 1fr 1fr; }
  }

  .rk-pill{
    display:flex;
    align-items:center;
    justify-content: space-between;
    gap: 10px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(15,23,42,.015);
    border-radius: 12px;
    padding: 8px 10px;
    font-size: 11.5px;
    font-weight: 850;
    color: rgba(15,23,42,.92);
  }
  .rk-pill .label{ display:flex; align-items:center; gap:8px; white-space:nowrap; }
  .rk-pill .dot{ opacity:.55; font-weight: 900; }

  .rk-select{
    border:none;
    background:transparent;
    outline:none;
    font-weight: 900;
    color: rgba(15,23,42,.92);
    font-size: 11.5px;
    width: 100%;
    min-width: 170px;
    text-align: right;
  }

  .rk-range-pickers{
    display:flex;
    gap: 8px;
    justify-content:flex-end;
    flex-wrap: wrap;
  }
  .rk-mini{
    display:flex;
    align-items:center;
    gap: 6px;
    padding: 6px 8px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(255,255,255,.70);
  }
  .rk-mini .cap{ opacity:.72; font-weight: 950; font-size: 10.5px; }
  .rk-mini select{
    border:none;
    background:transparent;
    outline:none;
    font-weight: 900;
    color: rgba(15,23,42,.92);
    font-size: 11.5px;
  }

  .rk-chart-wrap{ padding: 14px 14px 10px; }
  .rk-chart-meta{
    display:flex;
    align-items:baseline;
    justify-content:space-between;
    gap: 10px;
    padding: 0 2px 10px 2px;
    flex-wrap: wrap;
  }
  .rk-chart-meta .h{ font-size: 14px; font-weight: 950; color: rgba(15,23,42,.96); }
  .rk-chart-meta .sub{ font-size: 12px; font-weight: 850; color: rgba(15,23,42,.70); }

  .rk-badge{
    display:inline-flex;
    align-items:center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(15,23,42,.02);
    font-size: 11.5px;
    font-weight: 900;
    color: rgba(15,23,42,.9);
    white-space: nowrap;
  }

  .rk-svg{ width: 100%; height: auto; display:block; }

  .rk-range{
    padding: 10px 14px 12px;
    border-top: 1px solid rgba(15,23,42,.07);
    background: rgba(15,23,42,.015);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
    font-size: 12px;
    color: rgba(15,23,42,.86);
    font-weight: 900;
    flex-wrap: wrap;
  }
  .rk-range .money{ color: rgba(15,23,42,.96); font-weight: 950; white-space: nowrap; }

  .rk-mini-head{ display:flex; align-items:baseline; justify-content:space-between; gap:10px; }
  .rk-mini-head .title{ font-size: 18px; font-weight: 850; color: var(--rk-text); }
  .rk-mini-head .month{ font-size: 18px; font-weight: 700; color: rgba(15,23,42,.92); }

  .rk-rangos{ width:100%; border-collapse:collapse; font-size:13px; }
  .rk-rangos th{
    text-align:left;
    padding: 12px 14px;
    font-weight: 800;
    background: rgba(15,23,42,.035);
    color: rgba(15,23,42,.92);
  }
  .rk-rangos td{
    padding: 12px 14px;
    border-top: 1px solid rgba(15,23,42,.07);
    color: rgba(15,23,42,.92);
  }

  /* ===========================
     ✅ TOP 5 (IGUAL AL MOCK)
     =========================== */

  /* wrapper para que no pegue con el card */
  .rk-top5-panel{
    padding: 14px;
  }

  .rk-top5-wrap{
    position: relative;
    padding: 16px 16px 18px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,.12);
    background:
      radial-gradient(900px 320px at 30% 0%, rgba(255,255,255,.10), transparent 60%),
      linear-gradient(180deg, rgba(31,41,55,.98) 0%, rgba(2,6,23,.96) 70%, rgba(2,6,23,.94) 100%);
    color:#fff;
    box-shadow: 0 18px 60px rgba(0,0,0,.28);
    overflow:hidden;
  }

  /* brillo superior como el ejemplo */
  .rk-top5-wrap:before{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    background: linear-gradient(180deg, rgba(255,255,255,.12), transparent 48%);
    opacity:.95;
  }

  .rk-top5-head{
    position: relative;
    z-index: 1;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
    margin-bottom: 12px;
  }

  .rk-top5-head .left{
    display:flex;
    align-items:center;
    gap: 10px;
    font-weight: 950;
    letter-spacing: .10em;
    text-transform: uppercase;
    font-size: 12px;
    opacity: .95;
  }

  .rk-top5-head .left .flag{
    opacity:.9;
    transform: translateY(-1px);
  }

  .rk-top5-head .monthPill{
    font-weight: 900;
    font-size: 12px;
    padding: 7px 12px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.18);
    background: linear-gradient(180deg, rgba(255,255,255,.12), rgba(255,255,255,.05));
    box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
    white-space: nowrap;
  }

  .rk-top5-list{
    position: relative;
    z-index: 1;
    display:grid;
    gap: 12px;
  }

.rk-top5-row{
  display: grid;
  grid-template-columns: 140px 1fr auto; /* left | mid | money */
  align-items: center;
  gap: 14px;
}

  /* brillo diagonal del mock */
  .rk-top5-row:after{
    content:"";
    position:absolute;
    top:-60%;
    left:-30%;
    width: 62%;
    height: 220%;
    transform: rotate(18deg);
    background: linear-gradient(90deg, rgba(255,255,255,.18), transparent 70%);
    opacity:.18;
    pointer-events:none;
  }

/* círculo P1..P5 */
.rk-rankCircle{
  width: 36px;
  height: 36px;
  border-radius: 999px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight: 950;
  font-size: 12px;
  letter-spacing: .02em;
  border: 1px solid rgba(255,255,255,.20);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.10),
    0 10px 18px rgba(0,0,0,.22);
}

/* cápsula LEADER/CHALLENGER... */
.rk-tagCapsule{
  height: 26px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding: 0 12px;
  border-radius: 999px;
  font-weight: 950;
  font-size: 10px;
  letter-spacing: .12em;
  text-transform: uppercase;
  border: 1px solid rgba(255,255,255,.18);
  background: linear-gradient(180deg, rgba(255,255,255,.12), rgba(255,255,255,.05));
  box-shadow: inset 0 1px 0 rgba(255,255,255,.10);
  white-space: nowrap;
  opacity:.95;
}


 /* centro */
.rk-top5-mid{
  display:flex;
  align-items:center;
  gap: 12px;
  min-width: 0;
}

/* avatar circular (iniciales) */
.rk-avatarRound{
  width: 34px;
  height: 34px;
  border-radius: 999px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight: 950;
  font-size: 12px;
  border: 1px solid rgba(255,255,255,.22);
  background: rgba(255,255,255,.12);
  box-shadow:
    0 12px 20px rgba(0,0,0,.22),
    inset 0 1px 0 rgba(255,255,255,.10);
  flex: 0 0 auto;
}

  .rk-top5-meta{ min-width:0; }

  .rk-top5-name{
    font-weight: 950;
    font-size: 13px;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 210px;
  }

  .rk-top5-sub{
    margin-top: 3px;
    font-size: 10.5px;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .82;
    font-weight: 900;
  }

/* monto */
.rk-top5-money{
  font-weight: 950;
  font-size: 13px;
  white-space: nowrap;
  opacity: .96;
  align-self: center;
}

  /* Gradientes por posición (como el ejemplo) */
  .rk-top5-row.pos1{ background: linear-gradient(90deg, rgba(245,158,11,.32) 0%, rgba(255,255,255,.05) 55%); }
  .rk-top5-row.pos2{ background: linear-gradient(90deg, rgba(59,130,246,.30) 0%, rgba(255,255,255,.05) 55%); }
  .rk-top5-row.pos3{ background: linear-gradient(90deg, rgba(239,68,68,.30) 0%, rgba(255,255,255,.05) 55%); }
  .rk-top5-row.pos4{ background: linear-gradient(90deg, rgba(168,85,247,.28) 0%, rgba(255,255,255,.05) 55%); }
  .rk-top5-row.pos5{ background: linear-gradient(90deg, rgba(16,185,129,.26) 0%, rgba(255,255,255,.05) 55%); }

  /* El círculo P1 con el mismo color dominante */
  .rk-top5-row.pos1 .rk-rankCircle{ background: rgba(245,158,11,.26); }
  .rk-top5-row.pos2 .rk-rankCircle{ background: rgba(59,130,246,.24); }
  .rk-top5-row.pos3 .rk-rankCircle{ background: rgba(239,68,68,.24); }
  .rk-top5-row.pos4 .rk-rankCircle{ background: rgba(168,85,247,.22); }
  .rk-top5-row.pos5 .rk-rankCircle{ background: rgba(16,185,129,.20); }

  /* Responsive */
  @media(max-width: 420px){
/* izquierda: P1 arriba del tag, centrados */
.rk-top5-left{
  display:flex;
  flex-direction: column;
  align-items: center;     /* <-- antes start; esto centra como tu referencia */
  justify-content: center;
  gap: 6px;
  min-width: 140px;
}    .rk-top5-name{ max-width: 160px; }
  }
</style>

<div class="rk">
  <div class="rk-wrap">

    <div class="rk-top">
      <div class="rk-top-inner">
        <div class="rk-brand">
          <small>Rankings</small>
          <span>Vendedores</span>
        </div>

        <div class="rk-kpis">
          @foreach($kpis as $k)
            <div class="rk-kpi">
              <div class="k-label">{{ $k['label'] }}</div>
              <div class="k-value">{{ $k['value'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="rk-body">
      <div class="rk-grid">

        {{-- LEFT: Ranking + Chart debajo --}}
        <div>

          <div class="rk-card">
            <div class="rk-card-head">
              <div class="title">Ranking de Asesores</div>
              <div class="sub">Vista general</div>
            </div>

            <div class="rk-scroll" style="max-height: 560px;">
              <table class="rk-table">
                <thead>
                  <tr>
                    <th class="rk-num">#</th>
                    <th>Vendedor</th>
                    <th>Sucursal</th>
                    <th style="text-align:right;">Promedio Mensual</th>
                    <th>Desempeño</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ranking as $i => $r)
                    <tr>
                      <td class="rk-num">{{ $i+1 }}</td>
                      <td>
                        <div class="rk-person">
                          <div class="rk-avatar">{{ $initials($r['name']) }}</div>
                          <div class="rk-name">{{ mb_strtoupper($r['name']) }}</div>
                        </div>
                      </td>
                      <td class="rk-sucursal">{{ $r['sucursal'] }}</td>
                      <td class="rk-money">{{ $r['promedio'] }}</td>
                      <td>
                        <span class="rk-icon">{{ $r['icon'] }}</span>
                        <span class="rk-ital">{{ $r['status'] }}</span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="rk-footer">
              <span>Mostrando {{ $totalAsesores }} asesores</span>
              <span>Actualizado: hoy</span>
            </div>
          </div>

          {{-- ✅ SOLO ESTA SECCIÓN SE REEMPLAZA EN CARGA PARCIAL --}}
          <div id="rkChartSection">
            <div class="rk-card rk-chart-card">
              <div class="rk-card-head">
                <div class="rk-headRow">
                  <div>
                    <div class="title">Comparativo mes a mes</div>
                    <div class="sub">{{ $qMode==='total' ? 'Ventas Totales' : 'Ventas por vendedor' }}</div>
                  </div>

                  <div>
                    <div class="rk-viewToggle" aria-label="Cambiar vista">
                      <button type="button"
                              class="rk-viewBtn {{ $qMode==='total' ? 'is-active' : '' }}"
                              data-mode="total">
                        Ventas Totales
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="rk-controls">
                <form method="GET" action="{{ url()->current() }}" id="rkChartForm">
                  <input type="hidden" name="mode" id="rkMode" value="{{ $qMode }}">

                  <div class="rk-controls-row">
                    @if($qMode === 'vendor')
                      <div class="rk-pill">
                        <div class="label">
                          <span>Vendedor</span>
                          <span class="dot">·</span>
                        </div>
                        <select class="rk-select" name="vendor" data-autosubmit>
                          @foreach($vendors as $v)
                            <option value="{{ $v }}" {{ $v === $qVendor ? 'selected' : '' }}>{{ $v }}</option>
                          @endforeach
                        </select>
                      </div>
                    @else
                      <div class="rk-pill" style="justify-content:flex-start;">
                        <div class="label">
                          <span>Empresa</span>
                          <span class="dot">·</span>
                          <span style="font-weight:950; opacity:.88;">Ventas Totales</span>
                        </div>
                      </div>
                    @endif

                    <div class="rk-pill">
                      <div class="label">
                        <span>Filtrado de fechas</span>
                        <span class="dot">·</span>
                      </div>

                      <div class="rk-range-pickers">
                        <div class="rk-mini" title="Desde">
                          <span class="cap">Desde</span>
                          <select name="from_year" data-autosubmit>
                            @foreach($yearsAllowed as $y)
                              <option value="{{ $y }}" {{ $y===$qFromY ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                          </select>
                          <select name="from_month" data-autosubmit>
                            @foreach($monthsAllowed as $m)
                              <option value="{{ $m['k'] }}" {{ $m['k']===$qFromM ? 'selected' : '' }}>{{ $m['l'] }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="rk-mini" title="Hasta">
                          <span class="cap">Hasta</span>
                          <select name="to_year" data-autosubmit>
                            @foreach($yearsAllowed as $y)
                              <option value="{{ $y }}" {{ $y===$qToY ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                          </select>
                          <select name="to_month" data-autosubmit>
                            @foreach($monthsAllowed as $m)
                              <option value="{{ $m['k'] }}" {{ $m['k']===$qToM ? 'selected' : '' }}>{{ $m['l'] }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <div class="rk-chart-wrap">
                <div class="rk-chart-meta">
                  <div>
                    <div class="h">{{ $chartTitle }}</div>
                    <div class="sub">{{ $chartSub }}</div>
                  </div>

                  <div class="rk-badge">
                    <span>{{ $badgeIcon }}</span>
                    <span>{{ $rangeLabel }}</span>
                  </div>
                </div>

                <svg class="rk-svg" viewBox="0 0 {{ $w }} {{ $h }}" role="img" aria-label="Gráfica de ventas mes a mes">
                  <defs>
                    <linearGradient id="rkAreaBg" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0" stop-color="rgba(15,23,42,.06)"/>
                      <stop offset="1" stop-color="rgba(15,23,42,.015)"/>
                    </linearGradient>
                  </defs>

                  <rect x="{{ $pl }}" y="{{ $pt }}" width="{{ $plotW }}" height="{{ $plotH }}" fill="url(#rkAreaBg)" rx="12"/>

                  @foreach($ticks as $t)
                    <line x1="{{ $pl }}" y1="{{ $t['y'] }}" x2="{{ $w-$pr }}" y2="{{ $t['y'] }}" stroke="rgba(15,23,42,.08)" stroke-width="1"/>
                    <text x="{{ $pl-12 }}" y="{{ $t['y']+4 }}" text-anchor="end" font-size="12" fill="rgba(15,23,42,.78)" font-weight="900">
                      {{ $fmtMoney($t['v']) }}
                    </text>
                  @endforeach

                  <line x1="{{ $pl }}" y1="{{ $pt+$plotH }}" x2="{{ $w-$pr }}" y2="{{ $pt+$plotH }}" stroke="rgba(15,23,42,.18)" stroke-width="1"/>

                  @foreach($xLabels as $i => $lab)
                    @php $x = $pl + $dx*$i; @endphp
                    <text x="{{ $x }}" y="{{ $pt+$plotH+44 }}" text-anchor="middle" font-size="12" fill="rgba(15,23,42,.82)" font-weight="900">
                      {{ $lab }}
                    </text>
                  @endforeach

                  <path d="{{ trim($d) }}" fill="none" stroke="rgba(15,23,42,.92)" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/>

                  @foreach($points as $p)
                    <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5.2" fill="#fff" stroke="rgba(15,23,42,.92)" stroke-width="2"/>
                  @endforeach
                </svg>
              </div>

              <div class="rk-range">
                <span>
                  Rango de fechas:
                  <strong>{{ $qFromY }}-{{ $qFromM }}</strong>
                  a
                  <strong>{{ $qToY }}-{{ $qToM }}</strong>
                </span>
                <span class="money">Máximo: {{ $fmtMoney($maxV) }}</span>
              </div>
            </div>
          </div>

        </div>

        {{-- RIGHT: widgets --}}
        <div class="rk-right-col">

          {{-- ✅ FEBRERO --}}
          <div class="rk-card">
            <div class="rk-card-head">
              <div class="rk-mini-head">
                <div class="title">Mejores Asesores</div>
                <div class="month">Febrero</div>
              </div>
            </div>

<div class="rk-top5-panel">
  <div class="rk-top5-head">
    <div class="left"><span class="flag">🏁</span> TOP 5 DEL MES</div>
    <div class="monthPill">Enero</div>
  </div>

  <div class="rk-top5-list">
    @foreach($mejoresEne as $i => $r)
      @php
        $pos = $i + 1;
        $b = $top5Badges[$pos] ?? ['p'=>"P{$pos}", 'tag'=>'TOP'];
      @endphp

      <div class="rk-top5-row pos{{ $pos }}">
        <div class="rk-top5-left">
          <div class="rk-rankCircle">{{ $b['p'] }}</div>
          <div class="rk-tagCapsule">{{ $b['tag'] }}</div>
        </div>

        <div class="rk-top5-mid">
          <div class="rk-avatarRound">{{ $initials($r['name']) }}</div>
          <div class="rk-top5-meta">
            <div class="rk-top5-name">{{ $r['name'] }}</div>
            <div class="rk-top5-sub">{{ $r['sucursal'] }}</div>
          </div>
        </div>

        <div class="rk-top5-money">{{ $r['monto'] }}</div>
      </div>
    @endforeach
  </div>
</div>
          </div>

          {{-- ✅ ENERO (TOP 5 estilo tarjeta) --}}
          <div class="rk-card">
            <div class="rk-card-head">
              <div class="rk-mini-head">
                <div class="title">Mejores Asesores</div>
                <div class="month">Enero</div>
              </div>
            </div>

            <div class="rk-top5-wrap">
              <div class="rk-top5-head">
                <div class="left"><span class="flag">🏁</span> TOP 5 DEL MES</div>
                <div class="monthPill">Enero</div>
              </div>

              <div class="rk-top5-list">
                @foreach($mejoresEne as $i => $r)
                  @php
                    $pos = $i+1;
                    $b = $top5Badges[$pos] ?? ['p'=>"P{$pos}", 'tag'=>'TOP'];
                  @endphp

                  <div class="rk-top5-row pos{{ $pos }}">
                    <div class="rk-top5-left">
                      <div class="rk-rankCircle">{{ $b['p'] }}</div>
                      <div class="rk-tagCapsule">{{ $b['tag'] }}</div>
                    </div>

                    <div class="rk-top5-mid">
                      <div class="rk-avatarRound">{{ $initials($r['name']) }}</div>

                      <div class="rk-top5-meta">
                        <div class="rk-top5-name">{{ $r['name'] }}</div>
                        <div class="rk-top5-sub">{{ $r['sucursal'] }}</div>
                      </div>
                    </div>

                    <div class="rk-top5-money">{{ $r['monto'] }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- RANGOS (igual) --}}
          <div class="rk-card">
            <div class="rk-card-head">
              <div class="title">Rangos</div>
              <div class="sub">Desempeño</div>
            </div>

            <div class="rk-scroll">
              <table class="rk-rangos">
                <thead>
                  <tr>
                    <th>Desempeño</th>
                    <th style="text-align:right;">Rango</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($rangos as $r)
                    <tr>
                      <td>
                        <span class="rk-icon">{{ $r['icon'] }}</span>
                        <span class="rk-ital">{{ $r['label'] }}</span>
                      </td>
                      <td class="rk-money">{{ $r['range'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>

<script>
  (function(){
    var sectionId = 'rkChartSection';

    function bindChartHandlers(){
      var form = document.getElementById('rkChartForm');
      if(!form) return;

      function buildUrlFromForm(){
        var url = new URL(window.location.href);
        var fd = new FormData(form);

        ['mode','vendor','from_year','from_month','to_year','to_month'].forEach(function(k){
          url.searchParams.delete(k);
        });

        fd.forEach(function(v, k){
          if(v !== null && v !== undefined && String(v).length){
            url.searchParams.set(k, v);
          }
        });

        if(url.searchParams.get('mode') === 'total'){
          url.searchParams.delete('vendor');
        }

        return url.toString();
      }

      async function refreshChartPartial(push){
        var currentY = window.scrollY || window.pageYOffset || 0;
        var url = buildUrlFromForm();

        var currSection = document.getElementById(sectionId);
        if(currSection) currSection.style.opacity = '0.55';

        try{
          var res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
          });

          var html = await res.text();
          var parser = new DOMParser();
          var doc = parser.parseFromString(html, 'text/html');

          var nextSection = doc.getElementById(sectionId);
          var currentSection = document.getElementById(sectionId);

          if(nextSection && currentSection){
            currentSection.replaceWith(nextSection);
          }

          if(push){
            history.pushState({rk:true}, '', url);
          }else{
            history.replaceState({rk:true}, '', url);
          }

          window.scrollTo(0, currentY);
          bindChartHandlers();

        }catch(e){
          sessionStorage.setItem('rkScrollY', String(currentY));
          window.location.href = url;
          return;
        }finally{
          var s = document.getElementById(sectionId);
          if(s) s.style.opacity = '';
        }
      }

      form.querySelectorAll('[data-autosubmit]').forEach(function(el){
        el.onchange = null;
      });

      form.querySelectorAll('[data-autosubmit]').forEach(function(el){
        el.addEventListener('change', function(){
          refreshChartPartial(true);
        });
      });

      var modeInput = document.getElementById('rkMode');

      document.querySelectorAll('[data-mode]').forEach(function(btn){
        btn.onclick = null;
        btn.addEventListener('click', function(){
          if(!modeInput) return;

          var nextMode = btn.getAttribute('data-mode') || 'vendor';
          modeInput.value = nextMode;

          document.querySelectorAll('[data-mode]').forEach(function(b){
            b.classList.toggle('is-active', b.getAttribute('data-mode') === nextMode);
          });

          if(nextMode === 'total'){
            var vendorSel = form.querySelector('select[name="vendor"]');
            if(vendorSel) vendorSel.value = '';
          }

          refreshChartPartial(true);
        });
      });

      window.onpopstate = function(){
        refreshChartPartial(false);
      };
    }

    var saved = sessionStorage.getItem('rkScrollY');
    if(saved){
      sessionStorage.removeItem('rkScrollY');
      window.scrollTo(0, parseInt(saved, 10) || 0);
    }

    bindChartHandlers();
  })();
</script>
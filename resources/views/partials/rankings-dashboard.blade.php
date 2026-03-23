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
    ['name' => 'LIZ', 'sucursal' => 'CDMX', 'promedio' => '$1,744,005', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'FABIOLA', 'sucursal' => 'CDMX', 'promedio' => '$1,679,690', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'CAROLINA', 'sucursal' => 'QRO', 'promedio' => '$1,675,266', 'status' => 'Redefiniendo expectativas', 'icon' => '🏆'],
    ['name' => 'ADRIANA', 'sucursal' => 'CDMX', 'promedio' => '$1,325,650', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'YAZMIN', 'sucursal' => 'MTY', 'promedio' => '$1,122,540', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'MAYRA', 'sucursal' => 'AGS', 'promedio' => '$1,030,384', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'TANIA', 'sucursal' => 'CDMX', 'promedio' => '$854,850', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'IGNACIO', 'sucursal' => 'QRO', 'promedio' => '$842,204', 'status' => 'Superando expectativas', 'icon' => '✅'],
    ['name' => 'JESSICA', 'sucursal' => 'MTY', 'promedio' => '$625,640', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'CRISTINA', 'sucursal' => 'AGS', 'promedio' => '$581,981', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'MARISOL', 'sucursal' => 'MTY', 'promedio' => '$530,527', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'AURORA', 'sucursal' => 'MTY', 'promedio' => '$443,620', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'EDUARDO', 'sucursal' => 'CDMX', 'promedio' => '$441,565', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'DANIEL', 'sucursal' => 'CDMX', 'promedio' => '$421,306', 'status' => 'Cumpliendo expectativas', 'icon' => '☑️'],
    ['name' => 'KAHTIA', 'sucursal' => 'AGS', 'promedio' => '$344,108', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'ELIZABETH', 'sucursal' => 'QRO', 'promedio' => '$325,040', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'LORENA', 'sucursal' => 'AGS', 'promedio' => '$279,053', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'SHARON', 'sucursal' => 'QRO', 'promedio' => '$270,408', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'SUSANA', 'sucursal' => 'AGS', 'promedio' => '$259,850', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'ARIADNA', 'sucursal' => 'QRO', 'promedio' => '$201,093', 'status' => 'Abajo de expectativas', 'icon' => '⚠️'],
    ['name' => 'EMILIANO', 'sucursal' => 'MTY', 'promedio' => '$175,975', 'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'XCARET', 'sucursal' => 'QRO', 'promedio' => '$114,835', 'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'ENRIQUE', 'sucursal' => 'QRO', 'promedio' => '$79,130', 'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'MIREYA', 'sucursal' => 'AGS', 'promedio' => '$38,764', 'status' => 'Fuera de expectativas', 'icon' => '❌'],
    ['name' => 'GIOVANNA', 'sucursal' => 'CDMX', 'promedio' => '$0', 'status' => 'Periodo de Gracia', 'icon' => '🟡'],
    ['name' => 'JESUS', 'sucursal' => 'QRO', 'promedio' => '$0', 'status' => 'Periodo de Gracia', 'icon' => '🟡'],
    ['name' => 'ROBERTO', 'sucursal' => 'AGS', 'promedio' => '$0', 'status' => 'Periodo de Gracia', 'icon' => '🟡'],
  ];

  $mejoresFeb = [
    ['name' => 'LIZ', 'sucursal' => 'CDMX', 'monto' => '$1,744,005'],
    ['name' => 'FABIOLA', 'sucursal' => 'CDMX', 'monto' => '$1,679,690'],
    ['name' => 'CAROLINA', 'sucursal' => 'QRO', 'monto' => '$1,675,266'],
    ['name' => 'ADRIANA', 'sucursal' => 'CDMX', 'monto' => '$1,325,650'],
    ['name' => 'YAZMIN', 'sucursal' => 'MTY', 'monto' => '$1,122,540'],
  ];

  $mejoresEne = [
    ['name' => 'ADRIANA', 'sucursal' => 'CDMX', 'monto' => '$3,946,648'],
    ['name' => 'LIZ', 'sucursal' => 'CDMX', 'monto' => '$2,147,042'],
    ['name' => 'FABIOLA', 'sucursal' => 'CDMX', 'monto' => '$1,701,610'],
    ['name' => 'IGNACIO', 'sucursal' => 'QRO', 'monto' => '$1,271,552'],
    ['name' => 'EDUARDO', 'sucursal' => 'CDMX', 'monto' => '$1,129,772'],
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

  $totalAsesores = count($ranking);

  // ✅ Labels para TOP5
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

  .rk-wrap{ max-width: 1180px; margin: 18px auto 0; padding: 0 16px; }

  .rk-top{
    border-radius: 18px 18px 0 0;
    overflow: hidden;
    box-shadow: var(--rk-shadow);
    background: rgba(15,23,42,.88);
  }
  .rk-top-inner{
    padding: 18px;
    display:flex; align-items:flex-start; justify-content:space-between;
    gap:18px; flex-wrap:wrap;
    color:#fff;
  }
  .rk-brand{ line-height:1.05; min-width:220px; }
  .rk-brand small{ display:block; font-size:18px; opacity:.92; font-weight:500; }
  .rk-brand span{ display:block; font-size:22px; font-weight:600; letter-spacing:-.01em; }

  .rk-kpis{ display:flex; gap:28px; flex-wrap:wrap; justify-content:flex-end; align-items:flex-start; }
  .rk-kpi .k-label{ font-size:12px; opacity:.82; white-space:nowrap; font-weight:400; }
  .rk-kpi .k-value{ font-size:26px; font-weight:600; margin-top:2px; letter-spacing:-.01em; }
  .rk-body{
    border: 1px solid var(--rk-line);
    border-top: none;
    border-radius: 0 0 18px 18px;
    background: rgba(255,255,255,.70);
    backdrop-filter: blur(8px);
    padding: 18px;
    box-shadow: var(--rk-shadow2);
  }

  /* ✅ Nuevo layout:
     - Fila 1: Top 5 Feb + Top 5 Ene (2 columnas)
     - Fila 2: Ranking (ancho completo)
  */
  .rk-layout{
    display:grid;
    grid-template-columns: 1fr;
    gap: 18px;
  }
  @media(min-width: 980px){
    .rk-layout{ grid-template-columns: 1fr 1fr; }
    .rk-layout .rk-ranking-full{ grid-column: 1 / -1; }
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
  .rk-mini-head{ display:flex; align-items:baseline; justify-content:space-between; gap:10px; }
  .rk-mini-head .title{ font-size: 18px; font-weight: 600; color: var(--rk-text); letter-spacing:-.01em; }
  .rk-mini-head .month{ font-size: 18px; font-weight: 500; color: rgba(15,23,42,.82); }
  .rk-card-head .sub{ margin-top: 2px; font-size: 13px; color: rgba(15,23,42,.62); font-weight:400; }
  .rk-card-head .title{ font-size: 18px; font-weight: 600; letter-spacing: -.01em; color: var(--rk-text); }

  .rk-scroll{ flex: 1; min-height: 0; overflow: auto; }

  .rk-table{ width:100%; border-collapse:collapse; font-size:13px; color:var(--rk-text); }
  .rk-table thead th{
    font-weight: 500; font-size: 12.5px;
    padding: 12px 14px;
    background: rgba(15,23,42,.035);
    color: rgba(15,23,42,.72);
  }
  .rk-table tbody td{
    padding: 12px 14px;
    border-top: 1px solid rgba(15,23,42,.07);
    vertical-align: middle;
    color: rgba(15,23,42,.76);
    font-weight: 400;
  }
  .rk-table tbody tr:hover{ background: rgba(15,23,42,.02); }

  .rk-num{ width: 34px; font-weight: 500; color: rgba(15,23,42,.78); }
  .rk-avatar{
    width: 34px; height: 34px;
    border-radius: 999px;
    background: rgba(15,23,42,.88);
    color: #fff;
    display:flex; align-items:center; justify-content:center;
    font-weight: 600; font-size: 12px;
    box-shadow: 0 10px 16px rgba(15,23,42,.14);
  }

  .rk-person{ display:flex; align-items:center; gap:10px; }
  .rk-name{ font-weight: 500; letter-spacing: -.01em; font-size: 12.5px; line-height:1.1; }
  .rk-sucursal{ font-weight: 500; color: rgba(15,23,42,.72); }
  .rk-money{ text-align:right; font-weight: 600; color: rgba(15,23,42,.88); white-space:nowrap; }
  .rk-ital{ font-style: italic; color: rgba(15,23,42,.68); font-weight:400; }
  .rk-icon{ width: 24px; display:inline-flex; justify-content:center; }

  .rk-footer{
    padding: 12px 16px;
    border-top: 1px solid rgba(15,23,42,.07);
    background: rgba(15,23,42,.015);
    display:flex;
    justify-content:space-between;
    gap: 10px;
    font-size: 13px;
    color: rgba(15,23,42,.62);
    font-weight: 400;
  }

  /* ===========================
     ✅ TOP 5 en tono claro
     - SOLO P1,P2,P3 con color
     - P4,P5 blanco
     =========================== */
  .rk-top5-wrap{
    position: relative;
    padding: 14px;
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.92);
    box-shadow: 0 10px 22px rgba(15,23,42,.06);
    overflow:hidden;
  }

  .rk-top5-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
    margin-bottom: 12px;
  }
  .rk-top5-head .left{
    display:flex; align-items:center; gap: 10px;
    font-weight: 500;
    letter-spacing: .08em;
    text-transform: uppercase;
    font-size: 11.5px;
    color: rgba(15,23,42,.64);
  }
  .rk-top5-head .monthPill{
    font-weight: 500;
    font-size: 12px;
    padding: 7px 12px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(15,23,42,.02);
    color: rgba(15,23,42,.72);
    white-space: nowrap;
  }

  .rk-top5-list{ display:grid; gap: 10px; }

  .rk-top5-row{
    position: relative;
    display: grid;
    grid-template-columns: 140px 1fr auto;
    align-items: center;
    gap: 14px;
    padding: 10px 10px;
    border-radius: 14px;
    border: 1px solid rgba(15,23,42,.08);
    background: #fff;
    overflow:hidden;
  }

  .rk-top5-left{
    display:flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 140px;
  }

  .rk-rankCircle{
    width: 34px; height: 34px; border-radius: 999px;
    display:flex; align-items:center; justify-content:center;
    font-weight: 600; font-size: 12px;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(15,23,42,.02);
    color: rgba(15,23,42,.82);
  }

  .rk-tagCapsule{
    height: 24px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 0 10px;
    border-radius: 999px;
    font-weight: 500;
    font-size: 10px;
    letter-spacing: .10em;
    text-transform: uppercase;
    border: 1px solid rgba(15,23,42,.12);
    background: rgba(15,23,42,.02);
    color: rgba(15,23,42,.68);
    white-space: nowrap;
  }

  .rk-top5-mid{ display:flex; align-items:center; gap: 12px; min-width: 0; }
  .rk-avatarRound{
    width: 34px; height: 34px; border-radius: 999px;
    display:flex; align-items:center; justify-content:center;
    font-weight: 600; font-size: 12px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(15,23,42,.06);
    color: rgba(15,23,42,.84);
    flex: 0 0 auto;
  }
  .rk-top5-meta{ min-width:0; }
  .rk-top5-name{
    font-weight: 500;
    font-size: 13px;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 210px;
    color: rgba(15,23,42,.86);
  }
  .rk-top5-sub{
    margin-top: 3px;
    font-size: 10.5px;
    letter-spacing: .10em;
    text-transform: uppercase;
    opacity: .72;
    font-weight: 500;
    color: rgba(15,23,42,.60);
  }
  .rk-top5-money{
    font-weight: 600;
    font-size: 13px;
    white-space: nowrap;
    color: rgba(15,23,42,.84);
  }

  /* ✅ SOLO P1,P2,P3 con color */
  .rk-top5-row.pos1{ background: linear-gradient(90deg, rgba(245,158,11,.22) 0%, #fff 62%); border-color: rgba(245,158,11,.18); }
  .rk-top5-row.pos2{ background: linear-gradient(90deg, rgba(59,130,246,.18) 0%, #fff 62%); border-color: rgba(59,130,246,.16); }
  .rk-top5-row.pos3{ background: linear-gradient(90deg, rgba(239,68,68,.16) 0%, #fff 62%); border-color: rgba(239,68,68,.14); }

  .rk-top5-row.pos1 .rk-rankCircle{ background: rgba(245,158,11,.18); border-color: rgba(245,158,11,.18); }
  .rk-top5-row.pos2 .rk-rankCircle{ background: rgba(59,130,246,.14); border-color: rgba(59,130,246,.16); }
  .rk-top5-row.pos3 .rk-rankCircle{ background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.14); }

  /* P4 / P5 SIN color: se quedan en blanco (default) */

  /* ✅ Override tipografía más delgada */
.rk .title,
.rk .month,
.rk .k-value,
.rk .rk-money,
.rk .rk-name,
.rk .rk-sucursal,
.rk .rk-top5-name,
.rk .rk-top5-money,
.rk .rk-tagCapsule,
.rk .rk-rankCircle,
.rk .rk-avatar,
.rk .rk-avatarRound{
  letter-spacing: -0.01em;
}

.rk .title{ font-weight: 600 !important; }
.rk .month{ font-weight: 500 !important; }
.rk .k-value{ font-weight: 600 !important; }
.rk .rk-money,
.rk .rk-top5-money{ font-weight: 600 !important; }
.rk .rk-name,
.rk .rk-top5-name{ font-weight: 500 !important; }
.rk .rk-sucursal,
.rk .rk-top5-sub{ font-weight: 500 !important; }
.rk .rk-tagCapsule,
.rk .rk-rankCircle,
.rk .rk-avatar,
.rk .rk-avatarRound{ font-weight: 600 !important; }
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

      <div class="rk-layout">

        {{-- ✅ TOP 5 FEBRERO (IZQ) --}}
        <div class="rk-card">
          <div class="rk-card-head">
            <div class="rk-mini-head">
              <div class="title">Mejores Asesores</div>
              <div class="month">Marzo</div>
            </div>
          </div>

          <div class="rk-top5-wrap">
            <div class="rk-top5-head">
              <div class="left"><span class="flag">🏁</span> TOP 5 DEL MES</div>
              <div class="monthPill">Marzo</div>
            </div>

            <div class="rk-top5-list">
              @foreach($mejoresFeb as $i => $r)
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

        {{-- ✅ TOP 5 ENERO (DER) --}}
        <div class="rk-card">
          <div class="rk-card-head">
            <div class="rk-mini-head">
              <div class="title">Mejores Asesores</div>
              <div class="month">Febrero</div>
            </div>
          </div>

          <div class="rk-top5-wrap">
            <div class="rk-top5-head">
              <div class="left"><span class="flag">🏁</span> TOP 5 DEL MES</div>
              <div class="monthPill">Febrero</div>
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

        {{-- ✅ RANKING (ANCHO COMPLETO ABAJO) --}}
        <div class="rk-card rk-ranking-full">
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

      </div>

      {{-- =========================================================
           ✅ OCULTO (SIN BORRAR): CHART / COMPARATIVO MES A MES
           Si luego lo quieres reactivar, descomenta este bloque.
      ========================================================== --}}
      {{--
      <div id="rkChartSection">
        ... (todo tu bloque de gráfica aquí)
      </div>
      --}}

      {{-- =========================================================
           ✅ OCULTO (SIN BORRAR): TABLA DE RANGOS
           Si luego lo quieres reactivar, descomenta este bloque.
      ========================================================== --}}
      {{--
      <div class="rk-card" style="margin-top:18px;">
        <div class="rk-card-head">
          <div class="title">Rangos</div>
          <div class="sub">Desempeño</div>
        </div>

        <div class="rk-scroll">
          <table class="rk-table">
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
      --}}

    </div>
  </div>
</div>
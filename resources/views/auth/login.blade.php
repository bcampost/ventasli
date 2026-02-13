@extends('layouts.guest')

@section('content')
@php
  // Cambia el archivo a tu gusto:
  // public/images/li-logo.svg  (recomendado)
    $logo = asset('images/linea-italia.webp');
@endphp

<div class="li-auth">
  <div class="li-auth-wrap">

    <div class="li-auth-panel">
      {{-- Logo centrado --}}
      <div class="li-auth-logoWrap">
        <img src="{{ $logo }}" alt="LI" class="li-auth-logoImg"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        {{-- Fallback si no existe el logo --}}
        <div class="li-auth-logoFallback" style="display:none;">LI</div>
      </div>

      {{-- Título centrado, mismo tamaño --}}
      <div class="li-auth-titleCenter">Portal de Ventas</div>

      <form method="POST" action="{{ route('login') }}" class="li-auth-form">
        @csrf

        @if ($errors->any())
          <div class="li-auth-errors">
            <div class="li-auth-errors-title">Revisa lo siguiente:</div>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="li-auth-field">
          <label for="email">Email</label>
          <input
            id="email" type="email" name="email"
            value="{{ old('email') }}"
            required autofocus autocomplete="username"
            placeholder="tu@empresa.com"
          />
        </div>

        <div class="li-auth-field">
          <label for="password">Password</label>
          <input
            id="password" type="password" name="password"
            required autocomplete="current-password"
            placeholder="••••••••"
          />
        </div>

        <div class="li-auth-row">
          <label class="li-auth-check">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Remember me</span>
          </label>
        </div>

        <div class="li-auth-actions">
          @if (Route::has('password.request'))
            <a class="li-auth-link" href="{{ route('password.request') }}">
              
            </a>
          @else
            <span></span>
          @endif

          <button type="submit" class="li-auth-btn">Log in</button>
        </div>
      </form>
    </div>

  </div>
</div>

<style>
  :root{
    --text:#111827;
    --muted: rgba(17,24,39,.55);
    --line: rgba(17,24,39,.12);
    --card: rgba(255,255,255,.88);
    --shadow: 0 30px 80px rgba(0,0,0,.10);
    --shadow2: 0 12px 26px rgba(0,0,0,.08);
  }

  /* Fondo “solo login” */
  .li-guest-body{
    margin:0;
    background: radial-gradient(circle at 40% 10%, rgba(17,24,39,.06), transparent 55%),
                radial-gradient(circle at 80% 30%, rgba(17,24,39,.05), transparent 60%),
                #f3f4f6;
    min-height:100vh;
  }
  .li-guest-main{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 28px 16px;
  }

  .li-auth{ width: 100%; display:flex; justify-content:center; }
  .li-auth-wrap{ width: 100%; max-width: 520px; }

  .li-auth-panel{
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 18px;
    box-shadow: var(--shadow), var(--shadow2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 26px 26px 22px;
  }

  .li-auth-logoWrap{
    display:flex;
    justify-content:center;
    margin-bottom: 10px;
  }

.li-auth-logoImg{
  width: min(260px, 76vw);
  height: 64px;
  object-fit: contain;
  display:block;

  /* “pill” pro */
  padding: 10px 14px;
  border-radius: 16px;
  background: rgba(255,255,255,.92);
  border: 1px solid var(--line);
  box-shadow: 0 12px 20px rgba(0,0,0,.06);
}

.li-auth-logoFallback{
  width: min(260px, 76vw);
  height: 64px;
  border-radius: 16px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight: 900;
  letter-spacing: .12em;
  font-size: 13px;
  color: rgba(17,24,39,.70);
  background: rgba(255,255,255,.92);
  border: 1px solid var(--line);
  box-shadow: 0 12px 20px rgba(0,0,0,.06);
}

  .li-auth-titleCenter{
    text-align:center;
    font-size: 28px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.02em;
    margin-bottom: 18px;
  }

  .li-auth-field{ margin-bottom: 14px; }
  .li-auth-field label{
    display:block;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 6px;
    font-weight: 700;
  }
  .li-auth-field input{
    width:100%;
    height:46px;
    border-radius:999px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.92);
    padding:0 16px;
    outline:none;
    font-size:14px;
    color: var(--text);
    transition: box-shadow .15s ease, border-color .15s ease;
  }
  .li-auth-field input:focus{
    border-color: rgba(17,24,39,.22);
    box-shadow: 0 0 0 6px rgba(17,24,39,.06);
  }

  .li-auth-row{ margin: 6px 0 10px; }
  .li-auth-check{
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
    user-select:none;
    color: rgba(17,24,39,.78);
    font-size: 13px;
    font-weight: 650;
  }
  .li-auth-check input{
    width:16px; height:16px;
    accent-color:#111827;
  }

  .li-auth-actions{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 14px;
    margin-top: 14px;
  }
  .li-auth-link{
    font-size: 13px;
    color: rgba(17,24,39,.70);
    text-decoration:none;
    border-bottom: 1px dashed rgba(17,24,39,.35);
    padding-bottom: 2px;
  }
  .li-auth-link:hover{
    color: rgba(17,24,39,.95);
    border-bottom-color: rgba(17,24,39,.65);
  }

  .li-auth-btn{
    height: 40px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid rgba(17,24,39,.10);
    background: #111827;
    color:#fff;
    font-weight: 800;
    font-size: 13px;
    cursor:pointer;
    box-shadow: 0 14px 26px rgba(0,0,0,.16);
    transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
  }
  .li-auth-btn:hover{
    transform: translateY(-1px);
    box-shadow: 0 18px 30px rgba(0,0,0,.18);
  }
  .li-auth-btn:active{ transform: translateY(0); opacity:.92; }

  .li-auth-errors{
    background: rgba(225,29,72,.07);
    border: 1px solid rgba(225,29,72,.18);
    color: rgba(225,29,72,.95);
    border-radius: 14px;
    padding: 12px 14px;
    margin-bottom: 14px;
    font-size: 13px;
  }
  .li-auth-errors-title{ font-weight: 900; margin-bottom: 6px; }
  .li-auth-errors ul{ margin:0; padding-left:18px; }
</style>
@endsection
<!doctype html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

<title>Portal Ventas Línea Italia</title>
<link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}?v=3">

  {{-- Si ya tienes tu CSS global en app.blade, aquí carga lo mismo --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="li-guest-body">
  <main class="li-guest-main">
    @yield('content')
  </main>
</body>
</html>
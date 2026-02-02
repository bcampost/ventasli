<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ventasli</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    @include('partials.nav')
  <main class="max-w-7xl mx-auto px-4 py-6">
    @yield('content')
  </main>
  @include('partials.right-quick-menu')
</body>
</html>
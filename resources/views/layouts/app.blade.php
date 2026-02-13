{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        {{-- Page Heading --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    {{-- ✅ SIEMPRE visible (iconos laterales) --}}
    @include('partials.right-quick-menu')

    {{-- ✅ FOOTER SIEMPRE visible --}}
    @include('layouts.footer')

<script>
  window.openCreateNode = function(parentId){
    const input = document.getElementById('create_parent_id');
    const backdrop = document.getElementById('createModalBackdrop');
    const modal = document.getElementById('createModal');

    if (!input || !backdrop || !modal) return;

    input.value = (parentId ?? 0);
    backdrop.classList.remove('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('modal-open');
    document.body.classList.add('no-scroll');
  }

  window.closeCreateNode = function(){
    const backdrop = document.getElementById('createModalBackdrop');
    const modal = document.getElementById('createModal');
    if (!backdrop || !modal) return;

    backdrop.classList.add('hidden');
    modal.classList.remove('modal-open');
    modal.classList.add('hidden');
    document.body.classList.remove('no-scroll');
  }
</script>

</body>
</html>
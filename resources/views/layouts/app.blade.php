<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-bg-base text-content-primary antialiased">

    <x-layout.navbar-public />

    {{-- Mobile menu (controlado por JS) --}}
    <x-layout.mobile-menu />

    {{-- Contenido de la página --}}
    <main id="main-content">
        @yield('content')
    </main>

    <x-layout.footer />

    @stack('scripts')

    <script>
        const menuBtn  = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn?.addEventListener('click', () => {
            mobileMenu?.classList.toggle('hidden');
        });
    </script>

</body>
</html>
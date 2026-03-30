<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-bg-base text-content-primary antialiased">

    <x-layout.navbar-admin />

    <div class="flex min-h-[calc(100vh-52px)]">

        <x-layout.sidebar-admin />

        {{-- Contenido principal --}}
        <main class="flex-1 overflow-x-hidden">

            {{-- Alertas de sesión --}}
            @if (session('success'))
                <div class="px-6 pt-6">
                    <x-ui.alert type="success" :message="session('success')" />
                </div>
            @endif

            @if (session('error'))
                <div class="px-6 pt-6">
                    <x-ui.alert type="danger" :message="session('error')" />
                </div>
            @endif

            <div class="p-6">
                @yield('content')
            </div>

        </main>

    </div>

    @stack('scripts')

</body>
</html>
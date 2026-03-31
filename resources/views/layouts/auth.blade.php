<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-base text-content-primary antialiased">

    <main class="min-h-screen flex flex-col items-center justify-center p-4">
        {{-- Logo superior opcional --}}
        <div class="mb-8">
            <a href="/" class="text-xl font-semibold text-accent tracking-widest uppercase">
                {{ config('app.name') }}
            </a>
        </div>

        @yield('content')
    </main>

</body>
</html>

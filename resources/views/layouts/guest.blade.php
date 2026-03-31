{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Boutique') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-bg-base text-text-primary">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <div class="mb-8">
                <a href="/">
                    <h1 class="text-display text-accent tracking-tight">Boutique</h1>
                    <p class="text-text-secondary text-center uppercase tracking-widest text-[10px]">Catálogo Textil</p>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-bg-surface border border-border-subtle shadow-2xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

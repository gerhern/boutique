@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="w-full max-w-[400px]">

    <div class="bg-bg-surface border border-border-subtle rounded-2xl p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-xl font-semibold">Bienvenido</h1>
            <p class="text-sm text-content-disabled">Ingresa tus datos para continuar.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <x-ui.input
                name="email"
                type="email"
                label="Email"
                placeholder="tu@correo.com"
                :value="old('email')"
                :error="$errors->first('email')"
                required
            />

            <div class="space-y-1">
                <x-ui.input
                    name="password"
                    type="password"
                    label="Contraseña"
                    placeholder="••••••••"
                    :error="$errors->first('password')"
                    required
                />
                <div class="flex justify-end">
                    <a href="{{ route('password.request') }}" class="text-[11px] text-content-disabled hover:text-accent transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <x-ui.button type="submit" variant="primary" class="w-full justify-center py-2.5">
                Iniciar Sesión
            </x-ui.button>
        </form>
    </div>

    {{-- Footer minimalista solo para la página de login --}}
    <div class="mt-6 flex justify-between items-center px-2">
        <a href="{{ route('products.index') }}" class="text-xs text-content-disabled hover:text-content-secondary flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Volver al inicio
        </a>
        <a href="{{ route('register') }}" class="text-xs text-accent font-medium hover:underline">
            Crear cuenta
        </a>
    </div>
</div>
@endsection

@extends('layouts.auth')

@section('title', 'Crear cuenta')

@section('content')
<div class="w-full max-w-[450px]">

    <div class="bg-bg-surface border border-border-subtle rounded-2xl p-8 shadow-sm">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-semibold text-content-primary">Únete a {{ config('app.name') }}</h1>
            <p class="text-sm text-content-disabled mt-1">Crea tu cuenta para empezar a participar.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Nombre Completo --}}
            <x-ui.input
                name="name"
                label="Nombre completo"
                placeholder="Ej. Juan Pérez"
                :value="old('name')"
                :error="$errors->first('name')"
                required
                autofocus
            />

            {{-- Correo Electrónico --}}
            <x-ui.input
                name="email"
                type="email"
                label="Correo electrónico"
                placeholder="tu@correo.com"
                :value="old('email')"
                :error="$errors->first('email')"
                required
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Contraseña --}}
                <x-ui.input
                    name="password"
                    type="password"
                    label="Contraseña"
                    placeholder="••••••••"
                    :error="$errors->first('password')"
                    required
                />

                {{-- Confirmar Contraseña --}}
                <x-ui.input
                    name="password_confirmation"
                    type="password"
                    label="Confirmar"
                    placeholder="••••••••"
                    required
                />
            </div>

            <div class="pt-2">
                <p class="text-[10px] text-content-disabled text-center mb-4">
                    Al registrarte, aceptas nuestros <a href="#" class="text-accent hover:underline">Términos de Servicio</a> y <a href="#" class="text-accent hover:underline">Política de Privacidad</a>.
                </p>

                <x-ui.button type="submit" variant="primary" class="w-full justify-center py-2.5">
                    Crear mi cuenta
                </x-ui.button>
            </div>
        </form>
    </div>

    {{-- Enlace para volver o loguearse --}}
    <div class="mt-6 flex flex-col items-center gap-3">
        <p class="text-xs text-content-secondary">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">
                Inicia sesión
            </a>
        </p>

        <a href="{{ route('products.index') }}" class="text-[11px] text-content-disabled hover:text-content-secondary flex items-center gap-1 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7-7l-7 7 7 7"/>
            </svg>
            Volver a la tienda
        </a>
    </div>
</div>
@endsection

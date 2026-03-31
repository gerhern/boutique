@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="w-full max-w-[400px]">

    <div class="bg-bg-surface border border-border-subtle rounded-2xl p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-content-primary">¿Olvidaste tu contraseña?</h1>
            <p class="text-sm text-content-disabled mt-2 leading-relaxed">
                No hay problema. Dinós tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.
            </p>
        </div>

        {{-- Estado de la sesión (Mensaje de éxito al enviar el correo) --}}
        @if (session('status'))
            <div class="mb-6">
                <x-ui.alert type="success" :message="session('status')" />
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            {{-- Email Address --}}
            <x-ui.input
                name="email"
                type="email"
                label="Correo electrónico"
                placeholder="tu@correo.com"
                :value="old('email')"
                :error="$errors->first('email')"
                required
                autofocus
            />

            <div class="pt-2">
                <x-ui.button type="submit" variant="primary" class="w-full justify-center py-2.5">
                    Enviar enlace de recuperación
                </x-ui.button>
            </div>
        </form>
    </div>

    {{-- Retorno al login --}}
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-xs text-content-secondary hover:text-accent flex items-center justify-center gap-1 transition-colors font-medium">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al inicio de sesión
        </a>
    </div>
</div>
@endsection

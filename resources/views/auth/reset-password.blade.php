@extends('layouts.auth')

@section('title', 'Restablecer contraseña')

@section('content')
<div class="w-full max-w-[400px]">

    <div class="bg-bg-surface border border-border-subtle rounded-2xl p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-content-primary">Nueva contraseña</h1>
            <p class="text-sm text-content-disabled mt-2">
                Ingresa tu correo y la nueva contraseña para recuperar el acceso.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            {{-- @method('PUT')  --}}

            {{-- Token de Restablecimiento (Obligatorio) --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <x-ui.input
                name="email"
                type="email"
                label="Confirmar correo"
                placeholder="tu@correo.com"
                :value="old('email', $request->email)"
                :error="$errors->first('email')"
                required
            />

            {{-- Nueva Contraseña --}}
            <x-ui.input
                name="password"
                type="password"
                label="Nueva contraseña"
                placeholder="••••••••"
                :error="$errors->first('password')"
                required
                autofocus
            />

            {{-- Confirmar Contraseña --}}
            <x-ui.input
                name="password_confirmation"
                type="password"
                label="Repetir contraseña"
                placeholder="••••••••"
                required
            />

            <div class="pt-2">
                <x-ui.button type="submit" variant="primary" class="w-full justify-center py-2.5">
                    Restablecer contraseña
                </x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection

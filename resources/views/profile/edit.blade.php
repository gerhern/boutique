@extends('layouts.app')

@section('title', 'Configuración de Perfil')

@section('content')
    <div class="py-12 bg-bg-base">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Encabezado Manual --}}
            <header class="mb-10">
                <h2 class="text-2xl font-serif font-bold text-content-primary tracking-tight">
                    {{ __('Account Settings') }}
                </h2>
                <p class="text-sm text-content-disabled mt-1 italic">
                    Gestiona tu información personal y preferencias de seguridad.
                </p>
            </header>

            <div class="space-y-8">
                {{-- Sección: Información del Perfil --}}
                <div class="p-8 bg-bg-surface border border-border-subtle shadow-sm rounded-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Sección: Actualizar Contraseña --}}
                <div class="p-8 bg-bg-surface border border-border-subtle shadow-sm rounded-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

        <div class="mb-6">
            <h2 class="text-h2 text-text-primary">Actualizar Contraseña</h2>
            <p class="text-caption text-text-secondary mt-1">
                Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantener la seguridad.
            </p>
        </div>

        {{-- Componente de Alerta para feedback de éxito --}}
        @if (session('status') === 'password-updated')
            <x-ui.alert type="success" class="mb-6">
                <x-slot name="title">Éxito</x-slot>
                Su contraseña ha sido actualizada correctamente.
            </x-ui.alert>
        @endif

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            {{-- Contraseña Actual --}}
            <div class="max-w-xl">
                <x-ui.input
                    type="password"
                    name="current_password"
                    label="Contraseña Actual"
                    placeholder="••••••••"
                    required
                    :error="$errors->updatePassword->first('current_password')"
                />
            </div>

            {{-- Nueva Contraseña --}}
            <div class="max-w-xl">
                <x-ui.input
                    type="password"
                    name="password"
                    label="Nueva Contraseña"
                    placeholder="••••••••"
                    required
                    :error="$errors->updatePassword->first('password')"
                />
            </div>

            {{-- Confirmar Contraseña --}}
            <div class="max-w-xl">
                <x-ui.input
                    type="password"
                    name="password_confirmation"
                    label="Confirmar Nueva Contraseña"
                    placeholder="••••••••"
                    required
                    :error="$errors->updatePassword->first('password_confirmation')"
                />
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-white/5">
                <x-ui.button type="submit" variant="primary" size="md">
                    Guardar Cambios
                </x-button>
            </div>
        </form>

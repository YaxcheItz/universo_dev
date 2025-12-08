@extends('layouts.app')

@section('title', 'Crear Usuario - UniversoDev')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.index') }}" class="text-universo-purple hover:text-universo-purple/80 flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver a Administración
        </a>
    </div>

    <div class="bg-universo-secondary border border-universo-border rounded-lg p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">Crear Nuevo Usuario</h1>
            <p class="text-universo-text-muted mt-2">Los usuarios pueden crear proyectos, equipos y participar en torneos</p>
        </div>

        <form method="POST" action="{{ route('admin.store-usuario') }}" class="space-y-6">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-universo-text mb-2">
                    Nombre Completo <span class="text-red-400">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name') }}"
                    required
                    class="input-field"
                    placeholder="Ej: María García">
                @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-universo-text mb-2">
                    Correo Electrónico <span class="text-red-400">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}"
                    required
                    class="input-field"
                    placeholder="usuario@universodv.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="rol" class="block text-sm font-medium text-universo-text mb-2">
                    Rol / Especialidad <span class="text-red-400">*</span>
                </label>
                <select 
                    name="rol" 
                    id="rol"
                    required
                    onchange="toggleRolPersonalizado()"
                    class="input-field">
                    <option value="">Selecciona un rol</option>
                    @foreach($rolesPredefinidos as $rol)
                        <option value="{{ $rol }}" {{ old('rol') === $rol ? 'selected' : '' }}>{{ $rol }}</option>
                    @endforeach
                    <option value="Otro" {{ old('rol') === 'Otro' ? 'selected' : '' }}>Otro (especificar)</option>
                </select>
                @error('rol')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol Personalizado (oculto inicialmente) -->
            <div id="rol-personalizado-container" class="hidden">
                <label for="rol_personalizado" class="block text-sm font-medium text-universo-text mb-2">
                    Especifica el Rol <span class="text-red-400">*</span>
                </label>
                <input 
                    type="text" 
                    name="rol_personalizado" 
                    id="rol_personalizado"
                    value="{{ old('rol_personalizado') }}"
                    class="input-field"
                    placeholder="Ej: Arquitecto de Software">
                @error('rol_personalizado')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-universo-text mb-2">
                    Contraseña <span class="text-red-400">*</span>
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    required
                    minlength="8"
                    class="input-field"
                    placeholder="Mínimo 8 caracteres">
                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-universo-text mb-2">
                    Confirmar Contraseña <span class="text-red-400">*</span>
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    required
                    minlength="8"
                    class="input-field"
                    placeholder="Repite la contraseña">
            </div>

            <!-- Información adicional -->
            <div class="bg-universo-primary border border-universo-border rounded-lg p-4">
                <div class="flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-purple flex-shrink-0 mt-0.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    <div class="text-sm text-universo-text">
                        <p class="font-medium text-white mb-1">Permisos del Usuario</p>
                        <ul class="space-y-1 text-universo-text-muted">
                            <li>• Puede crear y gestionar proyectos</li>
                            <li>• Puede unirse y crear equipos</li>
                            <li>• Puede participar en torneos</li>
                            <li>• Acceso completo a la plataforma</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <button 
                    type="submit"
                    class="w-48 bg-universo-purple hover:bg-universo-purple/80 text-white font-medium px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" x2="19" y1="8" y2="14"></line>
                        <line x1="22" x2="16" y1="11" y2="11"></line>
                    </svg>
                    Crear Usuario
                </button>
                <a 
                    href="{{ route('admin.index') }}"
                    class="px-6 py-3 border border-universo-border text-universo-text hover:bg-universo-primary/50 rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleRolPersonalizado() {
    const select = document.getElementById('rol');
    const container = document.getElementById('rol-personalizado-container');
    const input = document.getElementById('rol_personalizado');
    
    if (select.value === 'Otro') {
        container.classList.remove('hidden');
        input.required = true;
    } else {
        container.classList.add('hidden');
        input.required = false;
        input.value = '';
    }
}

// Ejecutar al cargar la página por si hay un valor antiguo
document.addEventListener('DOMContentLoaded', function() {
    toggleRolPersonalizado();
});
</script>
@endpush
@endsection
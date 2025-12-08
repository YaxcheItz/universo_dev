@extends('layouts.app')

@section('title', 'Crear Juez - UniversoDev')

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
            <h1 class="text-2xl font-bold text-white">Crear Nuevo Juez</h1>
            <p class="text-universo-text-muted mt-2">Los jueces pueden evaluar proyectos en torneos</p>
        </div>

        <form method="POST" action="{{ route('admin.store-juez') }}" class="space-y-6">
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
                    placeholder="Ej: Dr. Juan Pérez">
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
                    placeholder="juez@universodv.com">
                @error('email')
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-cyan flex-shrink-0 mt-0.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    <div class="text-sm text-universo-text">
                        <p class="font-medium text-white mb-1">Permisos del Juez</p>
                        <ul class="space-y-1 text-universo-text-muted">
                            <li>• Puede evaluar proyectos en torneos</li>
                            <li>• No puede crear equipos </li>
                            <li>• Tiene acceso a la sección de evaluaciones</li>
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
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Crear Juez
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
@endsection
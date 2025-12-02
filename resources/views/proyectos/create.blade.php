@extends('layouts.app')

@section('title', 'Crear Proyecto - UniversoDev')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Crear Nuevo Proyecto</h1>
        <p class="text-universo-text-muted">Comparte tu proyecto con la comunidad</p>
    </div>

    <form action="{{ route('proyectos.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Información Básica -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Información Básica</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-universo-text mb-2">Nombre del Proyecto *</label>
                    <input type="text" name="name" id="name" required class="input-field" value="{{ old('name') }}" placeholder="Ej: Sistema de Gestión Escolar">
                    @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-universo-text mb-2">Descripción *</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required class="input-field" placeholder="Describe tu proyecto, sus objetivos y funcionalidades principales...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Tecnología -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Tecnología</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="lenguaje_principal" class="block text-sm font-medium text-universo-text mb-2">Lenguaje Principal *</label>
                    <select name="lenguaje_principal" id="lenguaje_principal" required class="input-field">
                        <option value="">Seleccionar lenguaje</option>
                        <option value="JavaScript" {{ old('lenguaje_principal') == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Python" {{ old('lenguaje_principal') == 'Python' ? 'selected' : '' }}>Python</option>
                        <option value="Java" {{ old('lenguaje_principal') == 'Java' ? 'selected' : '' }}>Java</option>
                        <option value="PHP" {{ old('lenguaje_principal') == 'PHP' ? 'selected' : '' }}>PHP</option>
                        <option value="C#" {{ old('lenguaje_principal') == 'C#' ? 'selected' : '' }}>C#</option>
                        <option value="C++" {{ old('lenguaje_principal') == 'C++' ? 'selected' : '' }}>C++</option>
                        <option value="Ruby" {{ old('lenguaje_principal') == 'Ruby' ? 'selected' : '' }}>Ruby</option>
                        <option value="Go" {{ old('lenguaje_principal') == 'Go' ? 'selected' : '' }}>Go</option>
                        <option value="Swift" {{ old('lenguaje_principal') == 'Swift' ? 'selected' : '' }}>Swift</option>
                        <option value="Kotlin" {{ old('lenguaje_principal') == 'Kotlin' ? 'selected' : '' }}>Kotlin</option>
                        <option value="TypeScript" {{ old('lenguaje_principal') == 'TypeScript' ? 'selected' : '' }}>TypeScript</option>
                        <option value="Rust" {{ old('lenguaje_principal') == 'Rust' ? 'selected' : '' }}>Rust</option>
                    </select>
                    @error('lenguaje_principal')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-universo-text mb-2">Tecnologías Adicionales</label>
                    <div id="tecnologias-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="tecnologias[]" class="input-field" placeholder="Ej: React, Node.js, MongoDB" value="{{ old('tecnologias.0') }}">
                            <button type="button" onclick="agregarTecnologia()" class="btn-secondary">+</button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-universo-text-muted">Agrega frameworks, librerías o herramientas que utilices</p>
                    @error('tecnologias')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Estado y Fechas -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Estado del Proyecto</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="estado" class="block text-sm font-medium text-universo-text mb-2">Estado *</label>
                    <select name="estado" id="estado" required class="input-field">
                        <option value="Planificación" {{ old('estado', 'Planificación') == 'Planificación' ? 'selected' : '' }}>Planificación</option>
                        <option value="En Desarrollo" {{ old('estado') == 'En Desarrollo' ? 'selected' : '' }}>En Desarrollo</option>
                        <option value="Pruebas" {{ old('estado') == 'Pruebas' ? 'selected' : '' }}>Pruebas</option>
                        <option value="Producción" {{ old('estado') == 'Producción' ? 'selected' : '' }}>Producción</option>
                        <option value="Mantenimiento" {{ old('estado') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="Archivado" {{ old('estado') == 'Archivado' ? 'selected' : '' }}>Archivado</option>
                    </select>
                    @error('estado')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-universo-text mb-2">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="input-field" value="{{ old('fecha_inicio') }}">
                    @error('fecha_inicio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_fin_estimada" class="block text-sm font-medium text-universo-text mb-2">Fecha de Fin Estimada</label>
                    <input type="date" name="fecha_fin_estimada" id="fecha_fin_estimada" class="input-field" value="{{ old('fecha_fin_estimada') }}">
                    @error('fecha_fin_estimada')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Enlaces -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Enlaces</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="repositorio_url" class="block text-sm font-medium text-universo-text mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>
                            URL del Repositorio (GitHub, GitLab, etc.)
                        </span>
                    </label>
                    <input type="url" name="repositorio_url" id="repositorio_url" class="input-field" value="{{ old('repositorio_url') }}" placeholder="https://github.com/usuario/proyecto">
                    @error('repositorio_url')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="demo_url" class="block text-sm font-medium text-universo-text mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            URL de Demo/Sitio Web
                        </span>
                    </label>
                    <input type="url" name="demo_url" id="demo_url" class="input-field" value="{{ old('demo_url') }}" placeholder="https://miproyecto.com">
                    @error('demo_url')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="documentacion_url" class="block text-sm font-medium text-universo-text mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            URL de Documentación
                        </span>
                    </label>
                    <input type="url" name="documentacion_url" id="documentacion_url" class="input-field" value="{{ old('documentacion_url') }}" placeholder="https://docs.miproyecto.com">
                    @error('documentacion_url')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Configuración -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Configuración</h2>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="hidden" name="es_publico" value="0">
                    <input type="checkbox" name="es_publico" id="es_publico" value="1" class="mr-2" {{ old('es_publico', true) ? 'checked' : '' }}>
                    <label for="es_publico" class="text-sm text-universo-text">
                        Proyecto público (visible para toda la comunidad)
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="es_trending" value="0">
                    <input type="checkbox" name="es_trending" id="es_trending" value="1" class="mr-2" {{ old('es_trending') ? 'checked' : '' }}>
                    <label for="es_trending" class="text-sm text-universo-text">
                        Marcar como trending (destacado)
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('proyectos.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Crear Proyecto</button>
        </div>
    </form>
</div>

<script>
function agregarTecnologia() {
    const container = document.getElementById('tecnologias-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="tecnologias[]" class="input-field" placeholder="Ej: Docker, PostgreSQL">
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
    `;
    container.appendChild(div);
}
</script>
@endsection
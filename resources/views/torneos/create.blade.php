@extends('layouts.app')

@section('title', 'Crear Torneo - UniversoDev')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Crear Nuevo Torneo</h1>
        <p class="text-universo-text-muted">Organiza una competencia de programación</p>
    </div>

    <form action="{{ route('torneos.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Información Básica -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Información Básica</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-universo-text mb-2">Nombre del Torneo *</label>
                    <input type="text" name="name" id="name" required class="input-field" value="{{ old('name') }}" placeholder="Ej: Hackathon Web 2024">
                    @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-universo-text mb-2">Descripción *</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required class="input-field" placeholder="Describe el torneo, objetivos y qué se espera de los participantes...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-universo-text mb-2">Categoría *</label>
                        <select name="categoria" id="categoria" required class="input-field">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('categoria')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="dominio" class="block text-sm font-medium text-universo-text mb-2">Dominio *</label>
                        <select name="dominio" id="dominio" required class="input-field">
                            <option value="">Seleccionar dominio</option>
                            @foreach($dominios as $dom)
                                <option value="{{ $dom }}" {{ old('dominio') == $dom ? 'selected' : '' }}>{{ $dom }}</option>
                            @endforeach
                        </select>
                        @error('dominio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="nivel_dificultad" class="block text-sm font-medium text-universo-text mb-2">Nivel de Dificultad *</label>
                    <select name="nivel_dificultad" id="nivel_dificultad" required class="input-field">
                        <option value="">Seleccionar nivel</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel }}" {{ old('nivel_dificultad') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                        @endforeach
                    </select>
                    @error('nivel_dificultad')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Fechas del Torneo</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_registro_inicio" class="block text-sm font-medium text-universo-text mb-2">Inicio de Inscripciones *</label>
                    <input type="date" name="fecha_registro_inicio" id="fecha_registro_inicio" required class="input-field" value="{{ old('fecha_registro_inicio') }}">
                    @error('fecha_registro_inicio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_registro_fin" class="block text-sm font-medium text-universo-text mb-2">Fin de Inscripciones *</label>
                    <input type="date" name="fecha_registro_fin" id="fecha_registro_fin" required class="input-field" value="{{ old('fecha_registro_fin') }}">
                    @error('fecha_registro_fin')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-universo-text mb-2">Inicio del Torneo *</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required class="input-field" value="{{ old('fecha_inicio') }}">
                    @error('fecha_inicio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-universo-text mb-2">Fin del Torneo *</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required class="input-field" value="{{ old('fecha_fin') }}">
                    @error('fecha_fin')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Participantes -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Configuración de Equipos</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="tamano_equipo_min" class="block text-sm font-medium text-universo-text mb-2">Tamaño Mínimo *</label>
                    <input type="number" name="tamano_equipo_min" id="tamano_equipo_min" required min="1" class="input-field" value="{{ old('tamano_equipo_min', 1) }}">
                    @error('tamano_equipo_min')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tamano_equipo_max" class="block text-sm font-medium text-universo-text mb-2">Tamaño Máximo *</label>
                    <input type="number" name="tamano_equipo_max" id="tamano_equipo_max" required min="1" class="input-field" value="{{ old('tamano_equipo_max', 5) }}">
                    @error('tamano_equipo_max')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="max_participantes" class="block text-sm font-medium text-universo-text mb-2">Máximo de Equipos</label>
                    <input type="number" name="max_participantes" id="max_participantes" min="1" class="input-field" value="{{ old('max_participantes') }}" placeholder="Ilimitado">
                    @error('max_participantes')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Criterios y Premios -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Evaluación y Premios</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-universo-text mb-2">Criterios de Evaluación *</label>
                    <div id="criterios-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="criterios_evaluacion[]" class="input-field" placeholder="Ej: Funcionalidad" value="{{ old('criterios_evaluacion.0') }}" required>
                            <button type="button" onclick="agregarCriterio()" class="btn-secondary">+</button>
                        </div>
                    </div>
                    @error('criterios_evaluacion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-universo-text mb-2">Premios *</label>
                    <div id="premios-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="premios[]" class="input-field" placeholder="Ej: 1er Lugar: $1000" value="{{ old('premios.0') }}" required>
                            <button type="button" onclick="agregarPremio()" class="btn-secondary">+</button>
                        </div>
                    </div>
                    @error('premios')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Reglas y Requisitos -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Información Adicional</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="reglas" class="block text-sm font-medium text-universo-text mb-2">Reglas del Torneo</label>
                    <textarea name="reglas" id="reglas" rows="4" class="input-field" placeholder="Especifica las reglas que deben seguir los participantes...">{{ old('reglas') }}</textarea>
                    @error('reglas')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="requisitos" class="block text-sm font-medium text-universo-text mb-2">Requisitos de Participación</label>
                    <textarea name="requisitos" id="requisitos" rows="4" class="input-field" placeholder="Especifica los requisitos para participar...">{{ old('requisitos') }}</textarea>
                    @error('requisitos')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Estado y Visibilidad -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Configuración</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="estado" class="block text-sm font-medium text-universo-text mb-2">Estado *</label>
                    <select name="estado" id="estado" required class="input-field">
                        @foreach($estados as $est)
                            <option value="{{ $est }}" {{ old('estado', 'Próximo') == $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                    @error('estado')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="es_publico" value="0">
                    <input type="checkbox" name="es_publico" id="es_publico" value="1" class="mr-2" {{ old('es_publico', true) ? 'checked' : '' }}>
                    <label for="es_publico" class="text-sm text-universo-text">Torneo público (visible para todos)</label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('torneos.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Crear Torneo</button>
        </div>
    </form>
</div>

<script>
function agregarCriterio() {
    const container = document.getElementById('criterios-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="criterios_evaluacion[]" class="input-field" placeholder="Ej: Creatividad" required>
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
    `;
    container.appendChild(div);
}

function agregarPremio() {
    const container = document.getElementById('premios-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="premios[]" class="input-field" placeholder="Ej: 2do Lugar: $500" required>
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
    `;
    container.appendChild(div);
}
</script>
@endsection

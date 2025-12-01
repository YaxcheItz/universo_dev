@extends('layouts.app')

@section('title', 'Editar Torneo - UniversoDev')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Editar Torneo</h1>
        <p class="text-universo-text-muted">{{ $torneo->name }}</p>
    </div>

    <form action="{{ route('torneos.update', $torneo) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Información Básica -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4">Información Básica</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-universo-text mb-2">Nombre del Torneo *</label>
                    <input type="text" name="name" id="name" required class="input-field" value="{{ old('name', $torneo->name) }}">
                    @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-universo-text mb-2">Descripción *</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required class="input-field">{{ old('descripcion', $torneo->descripcion) }}</textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-universo-text mb-2">Categoría *</label>
                        <select name="categoria" id="categoria" required class="input-field">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', $torneo->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('categoria')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="dominio" class="block text-sm font-medium text-universo-text mb-2">Dominio *</label>
                        <select name="dominio" id="dominio" required class="input-field">
                            @foreach($dominios as $dom)
                                <option value="{{ $dom }}" {{ old('dominio', $torneo->dominio) == $dom ? 'selected' : '' }}>{{ $dom }}</option>
                            @endforeach
                        </select>
                        @error('dominio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="nivel_dificultad" class="block text-sm font-medium text-universo-text mb-2">Nivel de Dificultad *</label>
                    <select name="nivel_dificultad" id="nivel_dificultad" required class="input-field">
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel }}" {{ old('nivel_dificultad', $torneo->nivel_dificultad) == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
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
                    <input type="date" name="fecha_registro_inicio" id="fecha_registro_inicio" required class="input-field" value="{{ old('fecha_registro_inicio', $torneo->fecha_registro_inicio->format('Y-m-d')) }}">
                    @error('fecha_registro_inicio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_registro_fin" class="block text-sm font-medium text-universo-text mb-2">Fin de Inscripciones *</label>
                    <input type="date" name="fecha_registro_fin" id="fecha_registro_fin" required class="input-field" value="{{ old('fecha_registro_fin', $torneo->fecha_registro_fin->format('Y-m-d')) }}">
                    @error('fecha_registro_fin')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-universo-text mb-2">Inicio del Torneo *</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required class="input-field" value="{{ old('fecha_inicio', $torneo->fecha_inicio->format('Y-m-d')) }}">
                    @error('fecha_inicio')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-universo-text mb-2">Fin del Torneo *</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required class="input-field" value="{{ old('fecha_fin', $torneo->fecha_fin->format('Y-m-d')) }}">
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
                    <input type="number" name="tamano_equipo_min" id="tamano_equipo_min" required min="1" class="input-field" value="{{ old('tamano_equipo_min', $torneo->tamano_equipo_min) }}">
                    @error('tamano_equipo_min')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tamano_equipo_max" class="block text-sm font-medium text-universo-text mb-2">Tamaño Máximo *</label>
                    <input type="number" name="tamano_equipo_max" id="tamano_equipo_max" required min="1" class="input-field" value="{{ old('tamano_equipo_max', $torneo->tamano_equipo_max) }}">
                    @error('tamano_equipo_max')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="max_participantes" class="block text-sm font-medium text-universo-text mb-2">Máximo de Equipos</label>
                    <input type="number" name="max_participantes" id="max_participantes" min="1" class="input-field" value="{{ old('max_participantes', $torneo->max_participantes) }}">
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
                        @foreach(old('criterios_evaluacion', $torneo->criterios_evaluacion ?? []) as $index => $criterio)
                            <div class="flex gap-2">
                                <input type="text" name="criterios_evaluacion[]" class="input-field" value="{{ $criterio }}" required>
                                @if($index > 0)
                                    <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
                                @else
                                    <button type="button" onclick="agregarCriterio()" class="btn-secondary">+</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('criterios_evaluacion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-universo-text mb-2">Premios *</label>
                    <div id="premios-container" class="space-y-2">
                        @foreach(old('premios', $torneo->premios ?? []) as $index => $premio)
                            <div class="flex gap-2">
                                <input type="text" name="premios[]" class="input-field" value="{{ $premio }}" required>
                                @if($index > 0)
                                    <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
                                @else
                                    <button type="button" onclick="agregarPremio()" class="btn-secondary">+</button>
                                @endif
                            </div>
                        @endforeach
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
                    <textarea name="reglas" id="reglas" rows="4" class="input-field">{{ old('reglas', $torneo->reglas) }}</textarea>
                    @error('reglas')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="requisitos" class="block text-sm font-medium text-universo-text mb-2">Requisitos de Participación</label>
                    <textarea name="requisitos" id="requisitos" rows="4" class="input-field">{{ old('requisitos', $torneo->requisitos) }}</textarea>
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
                            <option value="{{ $est }}" {{ old('estado', $torneo->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                    @error('estado')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="es_publico" value="0">
                    <input type="checkbox" name="es_publico" id="es_publico" value="1" class="mr-2" {{ old('es_publico', $torneo->es_publico) ? 'checked' : '' }}>
                    <label for="es_publico" class="text-sm text-universo-text">Torneo público (visible para todos)</label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('torneos.show', $torneo) }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
function agregarCriterio() {
    const container = document.getElementById('criterios-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="criterios_evaluacion[]" class="input-field" required>
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
    `;
    container.appendChild(div);
}

function agregarPremio() {
    const container = document.getElementById('premios-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="premios[]" class="input-field" required>
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary">-</button>
    `;
    container.appendChild(div);
}
</script>
@endsection

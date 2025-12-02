@extends('layouts.app')

@section('title', 'Editar Equipo - ' . $equipo->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Título -->
    <div class="card">
        <h1 class="text-2xl font-bold text-universo-text mb-1">Editar Equipo</h1>
        <p class="text-sm text-universo-text-muted">
            Modifica la información de tu equipo
        </p>
    </div>

    <!-- Formulario -->
    <div class="card">
        <form action="{{ route('equipos.update', $equipo) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="label">Nombre del Equipo</label>
                <input type="text"
                       id="name"
                       name="name"
                       class="input-field"
                       value="{{ old('name', $equipo->name) }}"
                       required>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="label">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="3"
                          class="input-field"
                          placeholder="Describe a tu equipo...">{{ old('descripcion', $equipo->descripcion) }}</textarea>
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="label">Estado del Equipo</label>
                <select id="estado" name="estado" class="input-field">
                    <option value="Activo"     {{ $equipo->estado === 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo"   {{ $equipo->estado === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

        <!-- Acepta Miembros -->
            <input type="hidden" name="acepta_miembros" value="0">
            <input type="checkbox" id="acepta_miembros" name="acepta_miembros"
                class="w-4 h-4 text-universo-purple"
                value="1"
                {{ $equipo->acepta_miembros ? 'checked' : '' }}>
            <label for="acepta_miembros" class="text-sm text-universo-text">
                Permitir que nuevos miembros se unan
            </label>


            <!-- Máximo de miembros -->
            <div>
                <label for="max_miembros" class="label">Máximo de miembros</label>
                <input type="number"
                       id="max_miembros"
                       name="max_miembros"
                       min="1"
                       class="input-field"
                       value="{{ old('max_miembros', $equipo->max_miembros) }}">
            </div>

            <!-- Tecnologías -->
            <div>
                <label for="tecnologias" class="label">Tecnologías (separadas por comas)</label>
                <input type="text"
                       id="tecnologias"
                       name="tecnologias"
                       class="input-field"
                       value="{{ implode(', ', $equipo->tecnologias ?? []) }}"
                       placeholder="Laravel, Vue, MySQL, Tailwind">
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('equipos.show', $equipo) }}" class="btn-secondary px-4 py-2">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary px-6 py-2">
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>


@endsection

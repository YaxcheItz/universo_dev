@extends('layouts.app')

@section('title', 'Editar Equipo - ' . $equipo->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="card">
        <h1 class="text-2xl font-bold text-universo-text mb-1">Editar Equipo</h1>
        <p class="text-sm text-universo-text-muted">
            Modifica la información de tu equipo
        </p>
    </div>

    <div class="card">
        <form action="{{ route('equipos.update', $equipo) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="label">Nombre del Equipo</label>
                <input type="text"
                       id="name"
                       name="name"
                       class="input-field"
                       value="{{ old('name', $equipo->name) }}"
                       required>
            </div>

            <div>
                <label for="descripcion" class="label">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="3"
                          class="input-field"
                          placeholder="Describe a tu equipo...">{{ old('descripcion', $equipo->descripcion) }}</textarea>
            </div>

            <div>
                <label for="estado" class="label">Estado del Equipo</label>
                <select id="estado" name="estado" class="input-field">
                    <option value="Activo"     {{ $equipo->estado === 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo"   {{ $equipo->estado === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="acepta_miembros" value="0">
                <input type="checkbox" id="acepta_miembros" name="acepta_miembros"
                    class="w-4 h-4 text-universo-purple"
                    value="1"
                    {{ $equipo->acepta_miembros ? 'checked' : '' }}>
                <label for="acepta_miembros" class="text-sm text-universo-text">
                    Permitir que nuevos miembros se unan
                </label>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="es_publico" value="0">
                <input
                    type="checkbox"
                    id="es_publico"
                    name="es_publico"
                    value="1"
                    class="w-4 h-4 text-universo-purple"
                    {{ old('es_publico', $equipo->es_publico) ? 'checked' : '' }}
                >
                <label for="es_publico" class="text-sm text-universo-text">
                    Equipo público (visible para todos)
                </label>
            </div>

            <div>
                <label for="max_miembros" class="label">Máximo de miembros</label>
                <input type="number"
                       id="max_miembros"
                       name="max_miembros"
                       min="1"
                       class="input-field"
                       value="{{ old('max_miembros', $equipo->max_miembros) }}">
            </div>

            <div>
                <label for="tecnologias" class="label">Tecnologías (separadas por comas)</label>
                <input type="text"
                       id="tecnologias"
                       name="tecnologias"
                       class="input-field"
                       value="{{ implode(', ', $equipo->tecnologias ?? []) }}"
                       placeholder="Laravel, Vue, MySQL, Tailwind">
            </div>

            <div>
                <label class="label">Miembros del Equipo</label>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 h-16">
                            <th >Nombre</th>
                            <th >Rol</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($miembros as $miembro)
                            <tr>
                                <td class="px-4 py-2">{{ $miembro->name }}</td>
                                <td class="px-4 py-2">
                                    @if($miembro->id === $equipo->lider_id)
                                        {{-- El líder tiene su rol fijo --}}
                                        <input type="hidden" name="miembros[{{ $miembro->id }}][rol_equipo]" value="{{ $miembro->pivot->rol_equipo }}">
                                        <span class="input-field">
                                            {{ $miembro->pivot->rol_equipo }} (Líder)
                                        </span>
                                    @else
                                        {{-- Los demás miembros pueden cambiar su rol --}}
                                        <select 
                                            name="miembros[{{ $miembro->id }}][rol_equipo]" 
                                            class="input-field"
                                        >
                                            @foreach($rolesDisponibles as $rol)
                                                @continue($rol === 'Líder de Equipo') 

                                                <option value="{{ $rol }}"
                                                    {{ $miembro->pivot->rol_equipo === $rol ? 'selected' : '' }}>
                                                    {{ $rol }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @endif
                                </td>
                                
                                <td class="px-4 py-2">
                                    @if($miembro->id !== $equipo->lider_id)
                                        <button 
                                            type="button"
                                            onclick="confirmarEliminacion({{ $miembro->id }})"
                                            class="text-red-500">
                                            Eliminar
                                        </button>
                                    @else
                                        <span class="text-green-500">Líder</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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

@foreach($miembros as $miembro)
    @if($miembro->id !== $equipo->lider_id)
        <form 
            id="form-eliminar-{{ $miembro->id }}"
            method="POST" 
            action="{{ route('equipos.removerMiembro', [$equipo->id, $miembro->id]) }}"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endforeach

<script>
  
    function confirmarEliminacion(miembroId) {
        if (confirm('¿Estás seguro de eliminar este miembro?')) {
            const form = document.getElementById('form-eliminar-' + miembroId);

            if (form) {
                form.submit();
            } else {
                console.error('Formulario no encontrado para el miembro:', miembroId);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Deshabilitar por seguridad cualquier opción "Líder de equipo" si existiera
        document
            .querySelectorAll('select[name*="[rol_equipo]"] option')
            .forEach(option => {
                if (option.value === 'Líder de equipo') {
                    option.disabled = true;
                }
            });
    });
</script>


@endsection
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
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Rol</th>
                            <th class="px-4 py-2">Acción</th>
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
                                        <span class="input-field bg-gray-100 cursor-not-allowed inline-block">
                                            {{ $miembro->pivot->rol_equipo }} (Líder)
                                        </span>
                                    @else
                                        {{-- Los demás miembros pueden cambiar su rol --}}
                                        <select 
                                            name="miembros[{{ $miembro->id }}][rol_equipo]" 
                                            class="input-field rol-select"
                                            data-miembro-id="{{ $miembro->id }}"
                                            data-es-lider="false">
                                            @foreach($rolesDisponibles as $rol)
                                                @php
                                                    $selected = ($miembro->pivot->rol_equipo === $rol) ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $rol }}" {{ $selected }}>
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
                                            class="text-red-600 hover:text-red-800">
                                            Eliminar
                                        </button>
                                    @else
                                        <span class="text-gray-500">Líder</span>
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

{{-- Formularios de eliminación FUERA del formulario principal --}}
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
// Función para confirmar y eliminar miembro
function confirmarEliminacion(miembroId) {
    if (confirm('¿Estás seguro de eliminar este miembro?')) {
        document.getElementById('form-eliminar-' + miembroId).submit();
    }
}

// Código existente para manejo de roles
document.addEventListener('DOMContentLoaded', function() {
    const selectsRol = document.querySelectorAll('.rol-select');
    const liderId = {{ $equipo->lider_id }};
    
    // Guardar todas las opciones originales de cada select
    const opcionesOriginales = new Map();
    selectsRol.forEach(select => {
        const miembroId = select.dataset.miembroId;
        const opciones = Array.from(select.options).map(option => ({
            value: option.value,
            text: option.textContent.trim()
        }));
        opcionesOriginales.set(miembroId, opciones);
    });
    
    function actualizarOpcionesDisponibles() {
        // Obtener todos los roles seleccionados
        const rolesSeleccionados = new Map();
        
        selectsRol.forEach(select => {
            const miembroId = select.dataset.miembroId;
            const rolSeleccionado = select.value;
            rolesSeleccionados.set(miembroId, rolSeleccionado);
        });
        
        // Agregar el rol del líder a los roles no disponibles
        const rolLider = document.querySelector('input[name="miembros[' + liderId + '][rol_equipo]"]');
        if (rolLider) {
            rolesSeleccionados.set(liderId.toString(), rolLider.value);
        }
        
        // Actualizar cada select
        selectsRol.forEach(select => {
            const miembroActual = select.dataset.miembroId;
            const valorActual = select.value;
            const opcionesBase = opcionesOriginales.get(miembroActual);
            
            // Limpiar todas las opciones
            select.innerHTML = '';
            
            // Agregar solo las opciones disponibles
            opcionesBase.forEach(opcion => {
                // Verificar si este rol está siendo usado por otro miembro
                let estaUsado = false;
                
                for (const [miembroId, rol] of rolesSeleccionados.entries()) {
                    // No está en uso si:
                    // 1. Es el miembro actual (puede mantener su rol)
                    // 2. El rol no coincide con ningún otro
                    if (miembroId !== miembroActual && rol === opcion.value) {
                        estaUsado = true;
                        break;
                    }
                }
                
                // Solo agregar la opción si no está en uso
                if (!estaUsado) {
                    const option = document.createElement('option');
                    option.value = opcion.value;
                    option.textContent = opcion.text;
                    
                    if (opcion.value === valorActual) {
                        option.selected = true;
                    }
                    
                    select.appendChild(option);
                }
            });
            
            // Si el select quedó vacío (no debería pasar), agregar al menos el valor actual
            if (select.options.length === 0 && valorActual) {
                const option = document.createElement('option');
                option.value = valorActual;
                option.textContent = valorActual;
                option.selected = true;
                select.appendChild(option);
            }
        });
    }
    
    // Ejecutar al cargar la página
    actualizarOpcionesDisponibles();
    
    // Ejecutar cada vez que cambie un select
    selectsRol.forEach(select => {
        select.addEventListener('change', function() {
            actualizarOpcionesDisponibles();
        });
    });
});
</script>

@endsection
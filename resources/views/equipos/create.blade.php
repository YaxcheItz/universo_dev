@extends('layouts.app')

@section('title', 'Crear Equipo - UniversoDev')

@section('content')
<div class="space-y-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Crear Equipo</h1>
        <p class="text-universo-text-muted">Llena los datos para crear tu nuevo equipo</p>
    </div>

    @if ($errors->any())
    <div class="bg-red-500/20 border border-red-400 text-red-300 p-4 rounded mb-6">
        <p class="font-semibold mb-2">Se encontraron errores:</p>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('equipos.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-universo-text mb-1">Nombre del equipo</label>
            <input type="text" name="name" id="name" class="input-field w-full" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium text-universo-text mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="input-field w-full">{{ old('descripcion') }}</textarea>
        </div>

        <div>
            <label for="max_miembros" class="block text-sm font-medium text-universo-text mb-1">Máximo de miembros</label>
            <input type="number" name="max_miembros" id="max_miembros" class="input-field w-full" min="2" max="50" value="{{ old('max_miembros', 5) }}" required>
        </div>

        <div>
            <label for="tecnologias" class="block text-sm font-medium text-universo-text mb-1">Tecnologías (separadas por coma)</label>
            <input type="text" name="tecnologias" id="tecnologias" class="input-field w-full" value="{{ old('tecnologias') }}">
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="es_publico" value="1" @checked(old('es_publico'))>
                Público
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="acepta_miembros" value="1" @checked(old('acepta_miembros'))>
                Acepta miembros
            </label>
        </div>


        <div>
            <h2 class="text-xl font-semibold text-universo-text mb-2">Agregar miembros</h2>
            <div id="miembros-container" class="space-y-4">
                <!-- se agregan dinámicamente los botones -->
            </div>
            <button type="button" id="agregar-miembro" class="btn-secondary">Agregar miembro</button>
        </div>

        <div>
            <button type="submit" class="btn-primary">Crear Equipo</button>
            <a href="{{ route('equipos.index') }}" class="btn-secondary ml-2">Cancelar</a>
        </div>
    </form>
</div>


<script>
    const miembrosContainer = document.getElementById('miembros-container');
    const agregarBtn = document.getElementById('agregar-miembro');

    // excluir  Lider de Equipo
    const roles = @json($rolesDisponibles ?? []).filter(r => r.toLowerCase() !== 'líder de equipo');

    let users = @json($usuarios ?? []);
    const loggedUserId = {{ auth()->id() }};

    // Quitar al usuario actual de la lista de disponibles
    users = users.filter(u => u.id !== loggedUserId);

    function actualizarOpcionesUsuarios() {
        const selects = miembrosContainer.querySelectorAll('select[name*="[user_id]"]');
        const seleccionados = Array.from(selects).map(s => s.value).filter(v => v);
        return users.filter(u => !seleccionados.includes(u.id.toString()));
    }

    agregarBtn.addEventListener('click', () => {
        const index = miembrosContainer.children.length;
        const disponibles = actualizarOpcionesUsuarios();

        if (disponibles.length === 0) {
            alert('No hay más usuarios disponibles para agregar.');
            return;
        }

        const div = document.createElement('div');
        div.classList.add('flex', 'gap-2', 'items-center');

        div.innerHTML = `
            <select name="miembros[${index}][user_id]" class="input-field">
                <option value="">Seleccionar usuario</option>
                ${disponibles.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
            </select>

            <select name="miembros[${index}][rol_equipo]" class="input-field rol-select">
                <option value="">Seleccionar rol</option>
                ${roles.map(r => `<option value="${r}">${r}</option>`).join('')}
            </select>

            <button type="button" class="btn-secondary remove-btn">Eliminar</button>
        `;

        miembrosContainer.appendChild(div);

        div.querySelector('.remove-btn').addEventListener('click', () => div.remove());
    });
</script>




@endsection

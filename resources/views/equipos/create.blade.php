@extends('layouts.app')

@section('title', 'Crear Equipo - UniversoDev')

@section('content')
<div class="space-y-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Crear Equipo</h1>
        <p class="text-universo-text-muted">Llena los datos para crear tu nuevo equipo</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">¡Ups!</strong>
            <span class="block sm:inline">Corrige los siguientes errores:</span>
            <ul class="mt-2 list-disc list-inside">
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

        <!-- Miembros adicionales -->
        <div>
            <h2 class="text-xl font-semibold text-universo-text mb-2">Agregar miembros</h2>
            <div id="miembros-container" class="space-y-4">
                <!-- Aquí se duplicarán los inputs -->
            </div>
            <button type="button" id="agregar-miembro" class="btn-secondary">Agregar miembro</button>
        </div>

        <div>
            <button type="submit" class="btn-primary">Crear Equipo</button>
            <a href="{{ route('equipos.index') }}" class="btn-secondary ml-2">Cancelar</a>
        </div>
    </form>
</div>

<!-- se usa JS para agregar meimbros-->
<script>
    const miembrosContainer = document.getElementById('miembros-container');
    const agregarBtn = document.getElementById('agregar-miembro');

    const roles = @json($rolesDisponibles ?? []); //desde el controlador se toman
    const users = @json(\App\Models\User::select('id','name')->get()); //todos los usuarios disponibles

    agregarBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.classList.add('flex', 'gap-4', 'items-center');

        div.innerHTML = `
            <select name="miembros[][user_id]" class="input-field" required>
                <option value="">Seleccionar usuario</option>
                ${users.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
            </select>
            <select name="miembros[][rol_equipo]" class="input-field" required>
                <option value="">Seleccionar rol</option>
                ${roles.map(r => `<option value="${r}">${r}</option>`).join('')}
            </select>
            <button type="button" class="btn-secondary remove-btn">Eliminar</button>
        `;

        miembrosContainer.appendChild(div);

        div.querySelector('.remove-btn').addEventListener('click', () => {
            div.remove();
        });
    });
</script>
@endsection

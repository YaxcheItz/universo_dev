@extends('layouts.app')

@section('title', 'Detalle del Equipo - ' . $equipo->name)

@section('content')
<div class="space-y-12">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-universo-text mb-1">{{ $equipo->name }}</h1>
            <p class="text-universo-text-muted">{{ $equipo->descripcion ?? 'Sin descripción' }}</p>
        </div>

        @if(Auth::id() === $equipo->lider_id)
            <a href="{{ route('equipos.edit', $equipo) }}"
               class="btn-primary flex items-center gap-2 px-4 py-2 text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5h2m2 0h2m2 0h2m-6 4h6M5 11h14M5 15h14M5 19h14"/>
                </svg>
                Editar Equipo
            </a>
        @endif
    </div>

    <!-- Información general y estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Información General -->
        <div class="backdrop-blur-lg bg-white/5 border border-white/10 p-6 rounded-2xl shadow-lg text-universo-text space-y-4">

            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-xl bg-universo-blue/20 text-universo-blue">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <h2 class="text-xl font-semibold">Información General</h2>
            </div>

            <div class="space-y-3 text-universo-text-muted">

                <p><strong class="text-universo-text">Líder:</strong> {{ $equipo->lider->nombre_completo }}</p>

                <p><strong class="text-universo-text">Miembros:</strong> {{ $equipo->miembros_actuales }}/{{ $equipo->max_miembros }}</p>

                <p class="flex items-center gap-2">
                    <strong class="text-universo-text">Estado:</strong>
                    <span class="px-2 py-1 rounded-lg text-sm font-semibold
                        {{ $equipo->estado === 'Activo' ? 'bg-green-600/30 text-green-300' : 'bg-red-600/30 text-red-300' }}">
                        {{ $equipo->estado }}
                    </span>
                </p>

                <p class="flex items-center gap-2">
                    <strong class="text-universo-text">Acepta miembros:</strong>
                    <span class="px-2 py-1 rounded-lg text-sm font-semibold
                        {{ $equipo->acepta_miembros ? 'bg-green-500/25 text-green-300' : 'bg-gray-500/30 text-gray-300' }}">
                        {{ $equipo->acepta_miembros ? 'Sí' : 'No' }}
                    </span>
                </p>

                <p>
                    <strong class="text-universo-text">Creado el:</strong>
                    {{ $equipo->fecha_creacion->format('d/m/Y') }}
                </p>

            </div>
        </div>

        <!-- Estadísticas -->
        <div class="backdrop-blur-lg bg-white/5 border border-white/10 p-6 rounded-2xl shadow-lg text-universo-text space-y-4">

            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-xl bg-universo-purple/20 text-universo-purple">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <h2 class="text-xl font-semibold">Estadísticas</h2>
            </div>

            <div class="space-y-3 text-universo-text-muted">

                 <p>
                    <strong class="text-universo-text">Tecnologías:</strong>
                    {{ $equipo->tecnologias ? implode(', ', $equipo->tecnologias) : 'N/A' }}
                </p>

                <div class="pt-4 space-y-1">
                    <p><strong class="text-universo-text">Proyectos completados:</strong> {{ $equipo->proyectos_completados }}</p>
                    <p><strong class="text-universo-text">Torneos participados:</strong> {{ $equipo->torneos_participados }}</p>
                    <p><strong class="text-universo-text">Torneos ganados:</strong> {{ $equipo->torneos_ganados }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Miembros -->
    <div>
        <h2 class="text-2xl font-semibold text-universo-text mb-4">Miembros</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($equipo->miembros as $miembro)
                <div class="flex items-center p-4 bg-universo-card rounded-xl shadow-md hover:shadow-xl transition">

                    <div class="w-12 h-12 rounded-full bg-universo-blue flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($miembro->nombre_completo, 0, 1) }}
                    </div>

                    <div class="ml-4 flex-1">
                        <p class="font-semibold text-universo-text">{{ $miembro->nombre_completo }}</p>
                        <span class="text-sm text-universo-text-muted bg-universo-tag px-2 py-1 rounded-full">
                            {{ $miembro->pivot->rol_equipo }}
                        </span>
                    </div>

                    @if(Auth::id() === $equipo->lider_id && $miembro->id !== $equipo->lider_id)
                        <form action="{{ route('equipos.removerMiembro', [$equipo, $miembro]) }}" method="POST" class="ml-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Remover</button>
                        </form>
                    @endif

                </div>
            @endforeach

        </div>
    </div>

    <!-- Unirse al equipo -->
    @if(!$esMiembro && $equipo->acepta_miembros && $equipo->miembros_actuales < $equipo->max_miembros)
        <form action="{{ route('equipos.unirse', $equipo) }}"
              method="POST"
              class="mt-6 p-6 rounded-xl shadow-lg bg-universo-green text-white space-y-3">

            @csrf

            <h2 class="text-lg font-semibold mb-2">Unirse al equipo</h2>

            <label for="rol_equipo" class="block font-medium mb-1">Rol en el equipo:</label>
            <input type="text" name="rol_equipo" id="rol_equipo" class="input-field w-full" required>

            <button type="submit" class="btn-primary mt-2">Unirse al equipo</button>

        </form>
    @endif

</div>
@endsection

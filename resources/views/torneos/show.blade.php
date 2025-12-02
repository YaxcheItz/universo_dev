@extends('layouts.app')

@section('title', $torneo->name . ' - UniversoDev')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Mensajes de éxito y error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 flex-shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <p class="text-green-500 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><line x1="15" x2="9" y1="9" y2="15"></line><line x1="9" x2="15" y1="9" y2="15"></line></svg>
            <p class="text-red-500 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Header con Banner -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                    <h1 class="text-3xl font-bold text-universo-text">{{ $torneo->name }}</h1>
                </div>
                <p class="text-universo-text-muted mb-3">Organizado por <span class="text-universo-purple">{{ $torneo->organizador->name ?? 'Organizador' }}</span></p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $badgeClass = match($torneo->estado) {
                            'Próximo' => 'badge-purple',
                            'En Curso' => 'badge-success',
                            'Inscripciones Abiertas' => 'badge-cyan',
                            'Finalizado' => 'badge-text-muted',
                            default => 'badge-purple'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $torneo->estado }}</span>
                    <span class="badge badge-purple">{{ $torneo->categoria }}</span>
                    <span class="badge badge-cyan">{{ $torneo->nivel_dificultad }}</span>
                </div>
            </div>

            @if(auth()->id() === $torneo->user_id)
                <div class="flex gap-2">
                    <a href="{{ route('torneos.edit', $torneo) }}" class="btn-secondary">Editar</a>
                    <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este torneo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary text-red-400">Eliminar</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-universo-dark rounded-lg">
            <div class="text-center">
                <div class="text-2xl font-bold text-universo-purple">{{ $torneo->participantes_actuales }}</div>
                <div class="text-sm text-universo-text-muted">Equipos Inscritos</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-universo-cyan">{{ $torneo->tamano_equipo_min }}-{{ $torneo->tamano_equipo_max }}</div>
                <div class="text-sm text-universo-text-muted">Miembros por Equipo</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-universo-success">{{ count($torneo->premios ?? []) }}</div>
                <div class="text-sm text-universo-text-muted">Premios</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-universo-warning">{{ $torneo->max_participantes ?? '∞' }}</div>
                <div class="text-sm text-universo-text-muted">Cupos Máximos</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Descripción -->
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Acerca del Torneo</h2>
                <p class="text-universo-text-muted whitespace-pre-line">{{ $torneo->descripcion }}</p>
            </div>

            <!-- Fechas Importantes -->
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Fechas Importantes</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-universo-dark rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-cyan"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span class="text-universo-text">Inicio de Inscripciones</span>
                        </div>
                        <span class="text-universo-text-muted">{{ $torneo->fecha_registro_inicio->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-universo-dark rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-warning"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span class="text-universo-text">Cierre de Inscripciones</span>
                        </div>
                        <span class="text-universo-text-muted">{{ $torneo->fecha_registro_fin->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-universo-dark rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-success"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span class="text-universo-text">Inicio del Torneo</span>
                        </div>
                        <span class="text-universo-text-muted">{{ $torneo->fecha_inicio->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-universo-dark rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-purple"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span class="text-universo-text">Fin del Torneo</span>
                        </div>
                        <span class="text-universo-text-muted">{{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Criterios de Evaluación -->
            @if(!empty($torneo->criterios_evaluacion))
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Criterios de Evaluación</h2>
                <ul class="space-y-2">
                    @foreach($torneo->criterios_evaluacion as $criterio)
                        <li class="flex items-center gap-2 text-universo-text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-success"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            {{ $criterio }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Premios -->
            @if(!empty($torneo->premios))
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Premios</h2>
                <ul class="space-y-2">
                    @foreach($torneo->premios as $premio)
                        <li class="flex items-center gap-2 text-universo-text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-warning"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                            {{ $premio }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Reglas -->
            @if($torneo->reglas)
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Reglas del Torneo</h2>
                <p class="text-universo-text-muted whitespace-pre-line">{{ $torneo->reglas }}</p>
            </div>
            @endif

            <!-- Requisitos -->
            @if($torneo->requisitos)
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Requisitos de Participación</h2>
                <p class="text-universo-text-muted whitespace-pre-line">{{ $torneo->requisitos }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Inscripción -->
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Inscripción</h2>

                {{-- Si el usuario ya tiene un equipo inscrito --}}
                @if($equipoInscrito)
                    <div class="p-4 bg-universo-dark border border-cyan-500 rounded-lg text-center">
                        <p class="text-universo-text-muted mb-2">Equipo inscrito:</p>
                        <p class="text-xl font-bold text-cyan-400 mb-4">{{ $equipoInscrito->name }}</p>
                        
                        @if($torneo->estado == 'Inscripciones Abiertas')
                            <form action="{{ route('torneos.salir', $torneo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres anular la inscripción de tu equipo?');">
                                @csrf
                                <button type="submit" class="w-full btn-danger">
                                    Anular Inscripción
                                </button>
                            </form>
                            <p class="text-xs text-universo-text-muted mt-3">Puedes anular la inscripción mientras el periodo de registro esté abierto.</p>
                        @else
                             <p class="text-sm text-universo-text-muted">Ya no puedes anular la inscripción porque el torneo no está en periodo de registro.</p>
                        @endif
                    </div>

                {{-- Si el periodo de inscripciones está abierto --}}
                @elseif($torneo->estado === 'Inscripciones Abiertas')
                    @if($torneo->max_participantes && $torneo->participantes_actuales >= $torneo->max_participantes)
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-red-400"><circle cx="12" cy="12" r="10"></circle><line x1="15" x2="9" y1="9" y2="15"></line><line x1="9" x2="15" y1="9" y2="15"></line></svg>
                            <p class="text-red-400 font-semibold mb-1">Torneo Lleno</p>
                            <p class="text-universo-text-muted">Este torneo ha alcanzado el máximo de {{ $torneo->max_participantes }} equipos</p>
                        </div>
                    @elseif($equiposDisponibles->count() > 0)
                        <form action="{{ route('torneos.inscribir', $torneo) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="equipo_id" class="block text-sm font-medium text-universo-text mb-2">Selecciona tu Equipo *</label>
                                <select name="equipo_id" id="equipo_id" required class="input-field">
                                    <option value="">Seleccionar equipo...</option>
                                    @foreach($equiposDisponibles as $equipo)
                                        <option value="{{ $equipo->id }}">{{ $equipo->name }} ({{ $equipo->miembros_actuales }} miembros)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="proyecto_id" class="block text-sm font-medium text-universo-text mb-2">Proyecto (Opcional)</label>
                                <select name="proyecto_id" id="proyecto_id" class="input-field">
                                    <option value="">Sin proyecto por ahora</option>
                                    @foreach(auth()->user()->proyectosCreados as $proyecto)
                                        <option value="{{ $proyecto->id }}">{{ $proyecto->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full btn-primary">Inscribir Equipo</button>
                        </form>
                    @else
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-universo-text-muted"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <p class="text-universo-text-muted mb-4">No tienes equipos disponibles para inscribir</p>
                            <a href="{{ route('equipos.create') }}" class="btn-secondary">Crear Equipo</a>
                        </div>
                    @endif

                {{-- Si el torneo no está en inscripciones --}}
                @elseif($torneo->estado === 'Próximo')
                    <div class="text-center py-6">
                        <p class="text-universo-text-muted">Las inscripciones abrirán el {{ $torneo->fecha_registro_inicio->format('d/m/Y') }}</p>
                    </div>
                @elseif($torneo->estado === 'Finalizado')
                    <div class="text-center py-6">
                        <p class="text-universo-text-muted">Este torneo ha finalizado</p>
                        <a href="{{ route('torneos.participantes', $torneo) }}" class="btn-primary mt-4">Ver Ganadores</a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-universo-text-muted">Las inscripciones están cerradas</p>
                    </div>
                @endif
            </div>

            <!-- Participantes -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-universo-text">Participantes</h2>
                    <span class="badge badge-purple">{{ $torneo->participantes_actuales }}</span>
                </div>
                
                @if($torneo->participaciones->count() > 0)
                    <div class="space-y-2">
                        @foreach($torneo->participaciones->take(5) as $participacion)
                            <div class="flex items-center gap-2 p-2 bg-universo-dark rounded">
                                <div class="w-8 h-8 rounded-full bg-universo-purple/20 flex items-center justify-center">
                                    <span class="text-xs font-bold text-universo-text">{{ substr($participacion->equipo->name, 0, 2) }}</span>
                                </div>
                                <span class="text-sm text-universo-text">{{ $participacion->equipo->name }}</span>
                            </div>
                        @endforeach
                        
                        @if($torneo->participaciones->count() > 5)
                            <a href="{{ route('torneos.participantes', $torneo) }}" class="text-sm text-universo-purple hover:underline">Ver todos los participantes</a>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-universo-text-muted text-center py-4">Aún no hay equipos inscritos</p>
                @endif
            </div>

            <!-- Compartir -->
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Compartir</h2>
                <div class="flex gap-2">
                    <button onclick="copiarEnlace()" class="flex-1 btn-secondary text-sm">Copiar Enlace</button>
                    <button class="btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" x2="12" y1="2" y2="15"></line></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copiarEnlace() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        // Crear notificación visual en lugar de alert
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = '¡Enlace copiado al portapapeles!';
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    });
}
</script>
@endsection

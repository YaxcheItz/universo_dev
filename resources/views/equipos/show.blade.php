@extends('layouts.app')

@section('title', 'Detalle del Equipo - ' . $equipo->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="card">
        <div class="flex flex-col lg:flex-row items-start justify-between gap-4">
            
            <div class="flex items-start gap-3 flex-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-universo-purple to-universo-blue flex items-center justify-center text-white font-bold text-lg shadow-lg flex-shrink-0">
                    {{ substr($equipo->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-universo-text mb-1">{{ $equipo->name }}</h1>
                    <p class="text-sm text-universo-text-muted mb-2">{{ $equipo->descripcion ?? 'Sin descripción disponible' }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="badge {{ $equipo->estado === 'Activo' ? 'badge-success' : 'badge-text-muted' }}">
                            {{ $equipo->estado }}
                        </span>
                        @if($equipo->acepta_miembros)
                            <span class="badge badge-cyan">Aceptando Miembros</span>
                        @endif
                    </div>
                    
                    <!-- Tecnologías para mostrar -->
                    @if($equipo->tecnologias)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($equipo->tecnologias as $tech)
                                <span class="px-2 py-0.5 bg-universo-dark text-universo-purple text-xs rounded font-medium">{{ $tech }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-3 lg:flex-shrink-0">
                <div class="text-center">
                    <div class="text-2xl font-bold text-universo-purple">{{ $equipo->miembros_actuales }}/{{ $equipo->max_miembros }}</div>
                    <div class="text-[10px] text-universo-text-muted uppercase tracking-wide">Miembros</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-universo-cyan">{{ $equipo->proyectos_completados }}</div>
                    <div class="text-[10px] text-universo-text-muted uppercase tracking-wide">Proyectos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-universo-success">{{ $equipo->torneos_ganados }}</div>
                    <div class="text-[10px] text-universo-text-muted uppercase tracking-wide">Ganados</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-universo-warning">{{ $equipo->torneos_participados }}</div>
                    <div class="text-[10px] text-universo-text-muted uppercase tracking-wide">Torneos</div>
                </div>
            </div>
            
            @if(Auth::id() === $equipo->lider_id)
                <div class="flex gap-2">
                    <!-- Editar -->
                    <a href="{{ route('equipos.edit', $equipo) }}"
                    class="btn-primary  py-4 text-sm whitespace-nowrap">
                        Editar
                    </a>

                    <!-- Eliminar el equipo completo -->
                    <button
                        type="button"
                        onclick="confirmarEliminacionEquipo()"
                        class=" py-4 text-sm text-red-500 whitespace-nowrap">
                        Eliminar
                    </button>
                </div>
            @endif

            </div>
        </div>
        
        @if(Auth::id() === $equipo->lider_id && $solicitudesPendientes->isNotEmpty())
            <div class="card bg-universo-dark border-universo-purple/20">
                <h2 class="text-xl font-semibold text-universo-text mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-purple"><path d="M14 19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4l2 3h4a2 2 0 0 1 2 2v1l-1 2H9.5"></path></svg>
                    Solicitudes de Ingreso
                    <span class="badge badge-purple">{{ $solicitudesPendientes->count() }}</span>
                </h2>
        
                <div class="space-y-4">
                    @foreach($solicitudesPendientes as $solicitud)
                        <div class="flex items-center justify-between p-4 bg-universo-bg rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-universo-cyan/20 flex items-center justify-center text-universo-cyan font-bold">
                                    {{ substr($solicitud->usuario->name, 0, 1) }}
                                </div>
                                <p class="text-universo-text font-semibold">{{ $solicitud->usuario->name }} quiere unirse</p>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('solicitudes.aceptar', $solicitud) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary text-sm px-4 py-2">Aceptar</button>
                                </form>
                                <form action="{{ route('solicitudes.rechazar', $solicitud) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary text-sm px-4 py-2">Rechazar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-6 lg:order-2">
        
            <div class="card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-universo-warning/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-warning">
                            <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                            <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                            <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                            <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-universo-text-muted uppercase tracking-wide">Líder</p>
                        <p class="text-sm font-bold text-universo-text">{{ $equipo->lider->nombre_completo }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-white/10">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-universo-text-muted">Fundado</span>
                        <span class="text-sm font-semibold text-universo-text">{{ $equipo->fecha_creacion->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

           <!-- Unirse al Equipo -->
            @if(!$esMiembro && $equipo->acepta_miembros && $equipo->miembros_actuales < $equipo->max_miembros)
                <div class="card bg-gradient-to-br from-universo-success/10 to-transparent border-universo-success/20">
                    <div class="text-center mb-4">
                        <div class="inline-flex p-3 rounded-full bg-universo-success/20 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-success">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <line x1="19" x2="19" y1="8" y2="14"></line>
                                <line x1="22" x2="16" y1="11" y2="11"></line>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-universo-text mb-1">¡Únete ahora!</h3>
                        <p class="text-sm text-universo-text-muted">Forma parte del equipo</p>
                    </div>
                    
                    <form action="{{ route('equipos.unirse', $equipo) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="rol_equipo" class="block text-xs font-medium text-universo-text mb-1 uppercase tracking-wide">
                                Selecciona tu Rol
                            </label>
                            <select 
                                name="rol_equipo" 
                                id="rol_equipo" 
                                class="input-field" 
                                required>
                                <option value="" disabled selected>Elige un rol...</option>
                                @foreach($rolesDisponibles as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full btn-primary">Unirse al Equipo</button>
                    </form>
                </div>
            @elseif($esMiembro)
                <div class="card text-center bg-universo-success/5 border-universo-success/20">
                    <div class="inline-flex p-3 rounded-full bg-universo-success/20 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-success">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <p class="text-universo-text mb-1 font-semibold">Miembro Activo</p>
                    <p class="text-sm text-universo-text-muted">Formas parte de este equipo</p>
                </div>
            @elseif(!$equipo->acepta_miembros)
                <div class="card text-center">
                    <div class="inline-flex p-3 rounded-full bg-universo-text-muted/10 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-universo-text-muted">Equipo cerrado</p>
                </div>
            @else
                <div class="card text-center">
                    <div class="inline-flex p-3 rounded-full bg-universo-text-muted/10 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <p class="text-sm text-universo-text-muted">Equipo completo</p>
                </div>
            @endif
            <!-- Compartir -->
            <div class="card">
                <h3 class="text-sm font-semibold text-universo-text mb-3 uppercase tracking-wide">Compartir</h3>
                <button onclick="copiarEnlace()" class="w-full btn-secondary text-sm">
                    Copiar Enlace
                </button>
            </div>
        </div>

    
        <div class="lg:col-span-2 space-y-6 lg:order-1">
            
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-universo-text flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-purple">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Miembros
                    </h2>
                    <span class="badge badge-purple">{{ $equipo->miembros->count() }} / {{ $equipo->max_miembros }}</span>
                </div>
                
                @if($equipo->miembros->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($equipo->miembros as $miembro)
                            <div class="p-4 bg-universo-dark rounded-lg hover:bg-universo-dark/80 transition group">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-universo-purple to-universo-blue flex items-center justify-center text-white font-bold flex-shrink-0 shadow-lg">
                                            {{ substr($miembro->nombre_completo, 0, 1) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="text-sm font-semibold text-universo-text truncate">{{ $miembro->nombre_completo }}</p>
                                                @if($miembro->id === $equipo->lider_id)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-universo-warning flex-shrink-0">
                                                        <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                                        <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                                        <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                                        <path d="M4 22h16"></path>
                                                        <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                                        <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="text-xs text-universo-text-muted truncate">{{ $miembro->pivot->rol_equipo }}</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-universo-text-muted">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <p class="text-sm text-universo-text-muted">Aún no hay miembros en el equipo</p>
                    </div>
                @endif
            </div>

            <!-- Logros en cards horizontales -->
            <div class="grid grid-cols-3 gap-4">
                <div class="card text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="inline-flex p-3 rounded-lg bg-universo-cyan/20 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-cyan">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-universo-text mb-1">{{ $equipo->proyectos_completados }}</p>
                    <p class="text-xs text-universo-text-muted uppercase tracking-wide">Proyectos</p>
                </div>
                <div class="card text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="inline-flex p-3 rounded-lg bg-universo-warning/20 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-warning">
                            <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                            <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-universo-text mb-1">{{ $equipo->torneos_participados }}</p>
                    <p class="text-xs text-universo-text-muted uppercase tracking-wide">Torneos</p>
                </div>
                <div class="card text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="inline-flex p-3 rounded-lg bg-universo-success/20 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-success">
                            <circle cx="8" cy="8" r="6"></circle>
                            <path d="M18.09 10.37A6 6 0 1 1 10.34 18"></path>
                            <path d="M7 6h1v4"></path>
                            <path d="m16.71 13.88.7.71-2.82 2.82"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-universo-text mb-1">{{ $equipo->torneos_ganados }}</p>
                    <p class="text-xs text-universo-text-muted uppercase tracking-wide">Victorias</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copiarEnlace() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('¡Enlace copiado al portapapeles!');
    });
}
function confirmarEliminacionEquipo() {
    const confirmar = confirm(
        'Esta acción eliminará el equipo PERMANENTEMENTE.\n' +
        'Se perderán todos los miembros y datos.\n\n' +
        '¿Deseas continuar?'
    );

    if (confirmar) {
        document.getElementById('form-eliminar-equipo').submit();
    }
}
</script>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif

@if(Auth::id() === $equipo->lider_id)
<form id="form-eliminar-equipo"
      action="{{ route('equipos.destroy', $equipo) }}"
      method="POST"
      class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif


@endsection
@extends('layouts.app')

@section('title', 'Evaluar ' . $torneo->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('evaluaciones.index') }}" class="inline-flex items-center gap-2 text-universo-purple hover:text-purple-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
            Volver a Torneos
        </a>

        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-yellow-500/20 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-universo-text">{{ $torneo->name }}</h1>
                    <p class="text-universo-text-muted">Evaluar participantes del torneo</p>
                </div>
            </div>

            <div class="text-right">
                <div class="text-sm text-universo-text-muted mb-1">Equipos Participantes</div>
                <div class="text-3xl font-bold text-universo-purple">{{ $participaciones->count() }}</div>
            </div>
        </div>

        <!-- Criterios del Torneo -->
        @if(!empty($torneo->criterios_evaluacion))
        <div class="card bg-purple-500/10 border-purple-500/20 mb-6">
            <h3 class="text-lg font-semibold text-universo-text mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Criterios de Evaluación
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($torneo->criterios_evaluacion as $criterio)
                    <div class="flex items-center gap-2 text-sm text-universo-text">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        {{ $criterio }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <p class="text-green-500 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            <p class="text-blue-500 font-medium">{{ session('info') }}</p>
        </div>
    @endif

    <!-- Lista de Participantes -->
    <div class="space-y-4">
        @foreach($participaciones as $participacion)
            <div class="card {{ $participacion->evaluada_por_juez ? 'border-green-500/50 bg-green-500/5' : 'hover:border-yellow-500/50' }} transition-all">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Información del Equipo -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-universo-text mb-2">{{ $participacion->equipo->name ?? 'Equipo' }}</h3>
                                <p class="text-sm text-universo-text-muted mb-3">
                                    Líder: <span class="text-universo-purple">{{ $participacion->equipo->lider->name ?? 'Líder' }}</span>
                                </p>

                                @if($participacion->proyecto)
                                    <div class="flex items-center gap-2 text-sm text-universo-text mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-cyan"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path></svg>
                                        <span>Proyecto: <strong>{{ $participacion->proyecto->name ?? 'Sin nombre' }}</strong></span>
                                    </div>
                                @endif
                            </div>

                            @if($participacion->evaluada_por_juez)
                                <span class="badge badge-success flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Evaluado
                                </span>
                            @endif
                        </div>

                        <!-- Miembros del Equipo -->
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-universo-text mb-2">Miembros ({{ $participacion->equipo->miembros->count() }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($participacion->equipo->miembros as $miembro)
                                    <span class="badge badge-text-muted">{{ $miembro->name ?? 'Miembro' }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Evaluaciones de Otros Jueces -->
                        @php
                            $evaluacionesOtrosJueces = $participacion->evaluaciones->where('juez_id', '!=', $juezId);

                        @endphp

                        @if($evaluacionesOtrosJueces->count() > 0)
                        <div class="mb-4 p-4 bg-universo-dark rounded-lg border border-purple-500/20">
                            <h4 class="text-sm font-semibold text-universo-text mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500"><path d="M17 18a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><circle cx="12" cy="10" r="2"></circle><line x1="8" x2="8" y1="2" y2="4"></line><line x1="16" x2="16" y1="2" y2="4"></line></svg>
                                Evaluaciones de Otros Jueces ({{ $evaluacionesOtrosJueces->count() }})
                            </h4>
                            <div class="space-y-3">
                                @foreach($evaluacionesOtrosJueces as $evaluacion)
                                <div class="p-3 bg-universo-bg rounded-lg border border-universo-border">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-purple-500/20 flex items-center justify-center">
                                                <span class="text-xs font-bold text-purple-400">{{ substr($evaluacion->juez->name ?? 'JZ', 0, 2) }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-universo-text">{{ $evaluacion->juez->name ?? 'Juez' }}</span>
                                        </div>
                                        <span class="text-lg font-bold text-purple-400">{{ number_format($evaluacion->puntaje_total, 1) }}</span>
                                    </div>
                                    @if($evaluacion->comentarios)
                                    <div class="mt-2 text-sm text-universo-text-muted italic border-l-2 border-purple-500/30 pl-3">
                                        "{{ $evaluacion->comentarios }}"
                                    </div>
                                    @endif
                                    <div class="mt-2 text-xs text-universo-text-muted">
                                        Evaluado el {{ $evaluacion->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Estadísticas de Evaluación -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-3 bg-universo-dark rounded-lg">
                            <div class="text-center">
                                <div class="text-lg font-bold text-universo-purple">{{ $participacion->evaluaciones->count() }}</div>
                                <div class="text-xs text-universo-text-muted">Evaluaciones</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-universo-cyan">
                                    {{ $participacion->puntaje_total ? number_format($participacion->puntaje_total, 1) : '--' }}
                                </div>
                                <div class="text-xs text-universo-text-muted">Promedio</div>
                            </div>
                            @if($participacion->evaluada_por_juez && $participacion->evaluacion_juez)
                            <div class="text-center">
                                <div class="text-lg font-bold text-universo-success">{{ number_format($participacion->evaluacion_juez->puntaje_total, 1) }}</div>
                                <div class="text-xs text-universo-text-muted">Tu Calificación</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-col justify-between gap-3 md:w-48">
                        @if($participacion->proyecto)
                            <a href="{{ route('proyectos.show', $participacion->proyecto) }}"
                               class="btn-secondary text-center flex items-center justify-center gap-2"
                               target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                Ver Proyecto
                            </a>
                        @endif

                        @if($participacion->evaluada_por_juez)
                            <button disabled class="btn-secondary opacity-50 cursor-not-allowed flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Ya Evaluado
                            </button>
                        @else
                            <a href="{{ route('evaluaciones.create', $participacion) }}"
                               class="btn-primary text-center flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                Evaluar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($participaciones->count() == 0)
        <div class="card text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <h3 class="text-xl font-semibold text-universo-text mb-2">No hay participantes</h3>
            <p class="text-universo-text-muted">Este torneo no tiene equipos inscritos</p>
        </div>
    @endif
</div>
@endsection

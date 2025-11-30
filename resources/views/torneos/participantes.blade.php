@extends('layouts.app')

@section('title', 'Participantes - ' . $torneo->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('torneos.show', $torneo) }}" class="text-universo-purple hover:underline mb-4 inline-block">← Volver al torneo</a>
        <h1 class="text-3xl font-bold text-universo-text mb-2">Participantes</h1>
        <p class="text-universo-text-muted">{{ $torneo->name }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="card text-center">
            <div class="text-3xl font-bold text-universo-purple mb-2">{{ $participaciones->count() }}</div>
            <div class="text-sm text-universo-text-muted">Equipos Inscritos</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-universo-cyan mb-2">{{ $participaciones->where('estado', 'Inscrito')->count() }}</div>
            <div class="text-sm text-universo-text-muted">Activos</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-universo-success mb-2">{{ $participaciones->where('posicion', '!=', null)->count() }}</div>
            <div class="text-sm text-universo-text-muted">Con Posición</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-universo-warning mb-2">{{ $participaciones->sum(fn($p) => $p->equipo->miembros_actuales) }}</div>
            <div class="text-sm text-universo-text-muted">Participantes Totales</div>
        </div>
    </div>

    <!-- Lista de Participantes -->
    <div class="card">
        <h2 class="text-xl font-semibold text-universo-text mb-6">Equipos Participantes</h2>

        @if($participaciones->count() > 0)
            <div class="space-y-4">
                @foreach($participaciones as $index => $participacion)
                    <div class="p-4 bg-universo-dark rounded-lg hover:bg-universo-dark/80 transition">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Posición -->
                                <div class="flex-shrink-0">
                                    @if($participacion->posicion)
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold
                                            {{ $participacion->posicion == 1 ? 'bg-yellow-500/20 text-yellow-500' : '' }}
                                            {{ $participacion->posicion == 2 ? 'bg-gray-400/20 text-gray-400' : '' }}
                                            {{ $participacion->posicion == 3 ? 'bg-orange-600/20 text-orange-600' : '' }}
                                            {{ $participacion->posicion > 3 ? 'bg-universo-purple/20 text-universo-purple' : '' }}">
                                            {{ $participacion->posicion }}
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-universo-purple/20 flex items-center justify-center text-xl font-bold text-universo-text-muted">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Info del Equipo -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-universo-text">{{ $participacion->equipo->name }}</h3>
                                        @if($participacion->premio_ganado)
                                            <span class="badge badge-warning">{{ $participacion->premio_ganado }}</span>
                                        @endif
                                        @php
                                            $estadoBadge = match($participacion->estado) {
                                                'Inscrito' => 'badge-cyan',
                                                'Participando' => 'badge-success',
                                                'Finalizado' => 'badge-purple',
                                                'Descalificado' => 'badge-text-muted',
                                                default => 'badge-cyan'
                                            };
                                        @endphp
                                        <span class="badge {{ $estadoBadge }}">{{ $participacion->estado }}</span>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-universo-text-muted">
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                            {{ $participacion->equipo->miembros_actuales }} miembros
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                                            {{ $participacion->fecha_inscripcion->format('d/m/Y') }}
                                        </div>
                                        @if($participacion->proyecto)
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4"></path><path d="m6 8-4 4 4 4"></path><path d="m14.5 4-5 16"></path></svg>
                                                {{ $participacion->proyecto->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Puntaje -->
                            @if($participacion->puntaje_total !== null)
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-universo-purple">{{ number_format($participacion->puntaje_total, 1) }}</div>
                                    <div class="text-xs text-universo-text-muted">Puntos</div>
                                </div>
                            @endif

                            <!-- Acciones -->
                            <div class="flex gap-2">
                                <a href="{{ route('equipos.show', $participacion->equipo) }}" class="btn-secondary text-sm">Ver Equipo</a>
                                @if($participacion->proyecto)
                                    <a href="{{ route('proyectos.show', $participacion->proyecto) }}" class="btn-secondary text-sm">Ver Proyecto</a>
                                @endif
                            </div>
                        </div>

                        <!-- Miembros del Equipo (expandible) -->
                        @if($participacion->equipo->miembros->count() > 0)
                            <div class="mt-4 pt-4 border-t border-universo-border">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($participacion->equipo->miembros as $miembro)
                                        <div class="flex items-center gap-2 px-3 py-1 bg-universo-bg rounded-full">
                                            <div class="w-6 h-6 rounded-full bg-universo-purple/20 flex items-center justify-center text-xs font-bold">
                                                {{ substr($miembro->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-sm text-universo-text">{{ $miembro->name ?? 'Usuario' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h3 class="text-xl font-semibold text-universo-text mb-2">No hay participantes aún</h3>
                <p class="text-universo-text-muted">Los equipos inscritos aparecerán aquí</p>
            </div>
        @endif
    </div>

    <!-- Podio (si hay ganadores) -->
    @if($participaciones->where('posicion', '!=', null)->count() >= 3)
        <div class="card mt-8">
            <h2 class="text-xl font-semibold text-universo-text mb-6">🏆 Podio</h2>
            <div class="flex flex-col md:flex-row items-end justify-center gap-6">
                <!-- 2do Lugar -->
                @php $segundo = $participaciones->where('posicion', 2)->first(); @endphp
                @if($segundo)
                    <div class="flex-1 text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-b from-gray-300 to-gray-500 rounded-full flex items-center justify-center">
                            <span class="text-4xl font-bold text-white">2</span>
                        </div>
                        <h3 class="text-lg font-semibold text-universo-text mb-1">{{ $segundo->equipo->name }}</h3>
                        <p class="text-sm text-universo-text-muted">{{ number_format($segundo->puntaje_total, 1) }} pts</p>
                    </div>
                @endif

                <!-- 1er Lugar -->
                @php $primero = $participaciones->where('posicion', 1)->first(); @endphp
                @if($primero)
                    <div class="flex-1 text-center -mt-8">
                        <div class="w-40 h-40 mx-auto mb-4 bg-gradient-to-b from-yellow-300 to-yellow-600 rounded-full flex items-center justify-center border-4 border-yellow-400">
                            <span class="text-5xl font-bold text-white">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-universo-text mb-1">{{ $primero->equipo->name }}</h3>
                        <p class="text-sm text-universo-text-muted">{{ number_format($primero->puntaje_total, 1) }} pts</p>
                        @if($primero->premio_ganado)
                            <p class="text-sm text-universo-warning mt-1">🏆 {{ $primero->premio_ganado }}</p>
                        @endif
                    </div>
                @endif

                <!-- 3er Lugar -->
                @php $tercero = $participaciones->where('posicion', 3)->first(); @endphp
                @if($tercero)
                    <div class="flex-1 text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-b from-orange-400 to-orange-700 rounded-full flex items-center justify-center">
                            <span class="text-4xl font-bold text-white">3</span>
                        </div>
                        <h3 class="text-lg font-semibold text-universo-text mb-1">{{ $tercero->equipo->name }}</h3>
                        <p class="text-sm text-universo-text-muted">{{ number_format($tercero->puntaje_total, 1) }} pts</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

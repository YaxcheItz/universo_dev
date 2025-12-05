@extends('layouts.app')

@section('title', 'Perfil - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Main User Info Card con fondo dinámico -->
    <div class="card p-8 relative" style="background: linear-gradient(135deg, {{ $user->profile_bg_color ?? '#1a1a2e' }} 0%, rgba(88, 44, 131, 0.2) 100%);">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            
            <!-- Foto de perfil -->
            <div class="flex-shrink-0">
                @if ($user->avatar)
                    <img 
                        src="{{ asset('storage/' . $user->avatar) }}" 
                        alt="Foto de {{ $user->name }}" 
                        class="w-32 h-32 rounded-full object-cover border-4 border-universo-purple shadow-xl ring-4 ring-universo-purple/20">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white text-4xl font-bold shadow-xl ring-4 ring-universo-purple/20">
                        {{ substr($user->name, 0, 1) }}{{ substr($user->apellido_paterno, 0, 1) }}
                    </div>
                @endif
            </div>

            <!-- Datos del perfil -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-universo-text mb-2">
                    {{ $user->name }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}
                </h1>
                
                <!-- Apodo -->
                <p class="text-universo-text-muted text-lg md:text-xl mb-3 font-medium">
                    @if ($user->nickname)
                        {{ '@' . $user->nickname }}
                    @else
                        {{ '@' . Str::slug($user->name, '_') }}
                    @endif
                </p>
                
                <span class="badge badge-purple text-base px-4 py-1.5">{{ $user->rol }}</span>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 mt-6 text-universo-text-muted">
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-5 h-5 text-universo-purple flex-shrink-0">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        </svg>
                        <span class="break-all">{{ $user->email }}</span>
                    </div>
                    @if ($user->telefono)
                        <div class="flex items-center justify-center md:justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-5 h-5 text-universo-cyan flex-shrink-0">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.12 12.12 0 0 0 .52 2.76 2 2 0 0 1-.42 2.16L6.76 11.48a14 14 0 1 0 6.31 6.31l1.81-1.81a2 2 0 0 1 2.16-.42 12.12 12.12 0 0 0 2.76.52A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span>{{ $user->telefono }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón Editar Perfil -->
        <!-- MODIFICADO: Botón para editar perfil-->
        <!-- Ruta: route('perfil.edit') -> PerfilController@edit() -> GET /perfil/edit -->
        <a href="{{ route('perfil.edit') }}" class="absolute top-6 right-6 btn-secondary btn-sm flex items-center gap-2 hover:scale-105 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Editar Perfil
        </a>
    </div>

    <!-- Tabbed Navigation -->
    <div class="card p-0 overflow-hidden">
        <div class="flex border-b border-universo-border overflow-x-auto">
            <button onclick="switchTab('reconocimientos')" id="tab-btn-reconocimientos" class="tab-button px-6 py-4 text-universo-purple border-b-2 border-universo-purple text-sm font-medium focus:outline-none whitespace-nowrap transition-colors">
                Reconocimientos
            </button>
            <button onclick="switchTab('torneos-participados')" id="tab-btn-torneos-participados" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Torneos Participados
            </button>
            <button onclick="switchTab('torneos-creados')" id="tab-btn-torneos-creados" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Torneos Creados
            </button>
            <button onclick="switchTab('proyectos')" id="tab-btn-proyectos" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Proyectos
            </button>
            <button onclick="switchTab('equipos')" id="tab-btn-equipos" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Equipos
            </button>
            <button onclick="switchTab('estadisticas')" id="tab-btn-estadisticas" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Estadísticas
            </button>
        </div>
        
        <div class="p-6">
            <!-- Tab: Reconocimientos -->
            <div id="tab-content-reconocimientos" class="tab-content">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-universo-warning">
                        <circle cx="12" cy="8" r="6"></circle>
                        <path d="M15.477 12.89 18.87 21l-3.37-.61-.63-3.02M8.523 12.89 5.13 21l3.37-.61.63-3.02"></path>
                    </svg>
                    Reconocimientos Obtenidos
                </h3>
                <p class="text-universo-text-muted mb-6">Logros y badges conseguidos en la plataforma</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Badge 1 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-purple/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-7 h-7 text-universo-purple">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Primera Contribución</h4>
                            <p class="text-universo-text-muted text-sm">Tu primer commit a un proyecto.</p>
                        </div>
                    </div>
                    
                    <!-- Badge 2 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-success/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-7 h-7 text-universo-success">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89 18.87 21l-3.37-.61-.63-3.02M8.523 12.89 5.13 21l3.37-.61.63-3.02"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Team Player</h4>
                            <p class="text-universo-text-muted text-sm">Completaste 3 proyectos en equipo.</p>
                        </div>
                    </div>
                    
                    <!-- Badge 3 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-cyan/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-7 h-7 text-universo-cyan">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Campeón</h4>
                            <p class="text-universo-text-muted text-sm">Ganaste tu primer torneo.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Torneos Participados -->
            <div id="tab-content-torneos-participados" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-6 h-6 text-universo-warning">
                        <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                        <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                        <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                        <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                    </svg>
                    Torneos Participados
                </h3>
                <p class="text-universo-text-muted mb-6">Historial de torneos en los que has participado ({{ $torneosParticipados->count() }})</p>

                @if($torneosParticipados->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($torneosParticipados as $participacion)
                            @php
                                $torneo = $participacion->torneo;
                                $badgeClass = '';
                                switch ($torneo->estado) {
                                    case 'Próximo': $badgeClass = 'badge-purple'; break;
                                    case 'En Curso': $badgeClass = 'badge-success'; break;
                                    case 'Inscripciones Abiertas': $badgeClass = 'badge-cyan'; break;
                                    case 'Finalizado': $badgeClass = 'badge-text-muted'; break;
                                    default: $badgeClass = 'badge-purple'; break;
                                }
                            @endphp
                            <div class="card p-5 hover:shadow-lg transition-shadow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning flex-shrink-0">
                                                <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                                <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                                <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                                <path d="M4 22h16"></path>
                                                <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                                <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                                            </svg>
                                            <h4 class="text-lg font-semibold text-universo-text truncate">{{ $torneo->name }}</h4>
                                        </div>
                                        <span class="badge {{ $badgeClass }} text-xs whitespace-nowrap flex-shrink-0 ml-2">{{ $torneo->estado }}</span>
                                    </div>

                                    <p class="text-sm text-universo-text-muted mb-3">
                                        {{ $torneo->categoria }} • {{ $torneo->dominio ?? 'Sin Dominio' }}
                                    </p>

                                    @if($participacion->posicion)
                                        <div class="mb-3">
                                            <span class="text-sm font-semibold {{ $participacion->posicion <= 3 ? 'text-universo-warning' : 'text-universo-text' }}">
                                                Posición: #{{ $participacion->posicion }}
                                            </span>
                                            @if($participacion->puntaje_total)
                                                <span class="text-sm text-universo-text-muted ml-2">
                                                    ({{ $participacion->puntaje_total }} pts)
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-4 text-xs text-universo-text-muted mb-3">
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                <path d="M16 2v4"></path>
                                                <path d="M8 2v4"></path>
                                                <path d="M3 10h18"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <circle cx="12" cy="12" r="6"></circle>
                                                <circle cx="12" cy="12" r="2"></circle>
                                            </svg>
                                            <span>{{ $torneo->nivel_dificultad }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('torneos.show', $torneo) }}" class="text-sm text-universo-cyan hover:text-universo-purple transition-colors font-medium inline-flex items-center gap-1">
                                        Ver torneo
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
                                            <path d="M5 12h14"></path>
                                            <path d="m12 5 7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-universo-warning/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning">
                                <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                            </svg>
                        </div>
                        <p class="text-universo-text-muted mb-4">Aún no has participado en ningún torneo</p>
                        <a href="{{ route('torneos.index') }}" class="btn-primary inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            Explorar torneos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab: Torneos Creados -->
            <div id="tab-content-torneos-creados" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag w-6 h-6 text-universo-purple">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                        <line x1="4" x2="4" y1="22" y2="15"></line>
                    </svg>
                    Torneos Creados
                </h3>
                <p class="text-universo-text-muted mb-6">Torneos que has organizado ({{ $torneosCreados->count() }})</p>

                @if($torneosCreados->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($torneosCreados as $torneo)
                            @php
                                $badgeClass = '';
                                switch ($torneo->estado) {
                                    case 'Próximo': $badgeClass = 'badge-purple'; break;
                                    case 'En Curso': $badgeClass = 'badge-success'; break;
                                    case 'Inscripciones Abiertas': $badgeClass = 'badge-cyan'; break;
                                    case 'Finalizado': $badgeClass = 'badge-text-muted'; break;
                                    default: $badgeClass = 'badge-purple'; break;
                                }
                            @endphp
                            <div class="card p-5 hover:shadow-lg transition-shadow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning flex-shrink-0">
                                                <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                                <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                                <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                                <path d="M4 22h16"></path>
                                                <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                                <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                                            </svg>
                                            <h4 class="text-lg font-semibold text-universo-text truncate">{{ $torneo->name }}</h4>
                                        </div>
                                        <span class="badge {{ $badgeClass }} text-xs whitespace-nowrap flex-shrink-0 ml-2">{{ $torneo->estado }}</span>
                                    </div>

                                    <p class="text-sm text-universo-text-muted mb-3">
                                        {{ $torneo->categoria }} • {{ $torneo->dominio ?? 'Sin Dominio' }}
                                    </p>

                                    @if($torneo->descripcion)
                                        <p class="text-sm text-universo-text-muted line-clamp-2 mb-3">
                                            {{ Str::limit($torneo->descripcion, 80) }}
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-4 text-xs text-universo-text-muted mb-3">
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            @if($torneo->max_participantes)
                                                <span>{{ $torneo->participantes_actuales }}/{{ $torneo->max_participantes }}</span>
                                            @else
                                                <span>{{ $torneo->participantes_actuales }} equipos</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                <path d="M16 2v4"></path>
                                                <path d="M8 2v4"></path>
                                                <path d="M3 10h18"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('torneos.show', $torneo) }}" class="flex-1 text-sm text-center text-universo-cyan hover:text-universo-purple transition-colors font-medium inline-flex items-center justify-center gap-1 px-3 py-2 bg-universo-purple/10 rounded-lg hover:bg-universo-purple/20">
                                            Ver detalles
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('torneos.edit', $torneo) }}" class="text-sm text-universo-text hover:text-universo-purple transition-colors font-medium inline-flex items-center justify-center px-3 py-2 bg-universo-secondary rounded-lg hover:bg-universo-purple/20" title="Editar torneo">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-universo-purple/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag text-universo-purple">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" x2="4" y1="22" y2="15"></line>
                            </svg>
                        </div>
                        <p class="text-universo-text-muted mb-4">Aún no has creado ningún torneo</p>
                        <a href="{{ route('torneos.create') }}" class="btn-primary inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Crear torneo
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab: Proyectos -->
            <div id="tab-content-proyectos" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml w-6 h-6 text-universo-purple">
                        <path d="m18 16 4-4-4-4"></path>
                        <path d="m6 8-4 4 4 4"></path>
                        <path d="m14.5 4-5 16"></path>
                    </svg>
                    Proyectos
                </h3>
                <p class="text-universo-text-muted mb-6">Tus proyectos y contribuciones ({{ $user->proyectosCreados->count() }})</p>

                @if($user->proyectosCreados->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->proyectosCreados as $proyecto)
                            <div class="card p-5 hover:shadow-lg transition-shadow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml text-universo-purple flex-shrink-0">
                                                <path d="m18 16 4-4-4-4"></path>
                                                <path d="m6 8-4 4 4 4"></path>
                                                <path d="m14.5 4-5 16"></path>
                                            </svg>
                                            <h4 class="text-lg font-semibold text-universo-text truncate">{{ $proyecto->name }}</h4>
                                        </div>
                                        @if($proyecto->lenguaje_principal)
                                            <span class="badge badge-purple text-xs whitespace-nowrap flex-shrink-0 ml-2">{{ $proyecto->lenguaje_principal }}</span>
                                        @endif
                                    </div>

                                    @if($proyecto->descripcion)
                                        <p class="text-sm text-universo-text-muted line-clamp-2 mb-3">
                                            {{ Str::limit($proyecto->descripcion, 80) }}
                                        </p>
                                    @endif

                                    <!-- Tecnologías -->
                                    @if($proyecto->tecnologias && count($proyecto->tecnologias) > 0)
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach(array_slice($proyecto->tecnologias, 0, 3) as $tech)
                                                <span class="text-xs px-2 py-1 rounded-md bg-universo-purple/10 text-universo-purple">
                                                    {{ $tech }}
                                                </span>
                                            @endforeach
                                            @if(count($proyecto->tecnologias) > 3)
                                                <span class="text-xs px-2 py-1 rounded-md bg-universo-purple/10 text-universo-purple">
                                                    +{{ count($proyecto->tecnologias) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <!-- Estadísticas del proyecto -->
                                    <div class="flex items-center gap-4 text-xs text-universo-text-muted mb-3">
                                        @if($proyecto->estrellas)
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star text-yellow-500">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                <span>{{ $proyecto->estrellas }}</span>
                                            </div>
                                        @endif
                                        @if($proyecto->forks)
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-git-fork text-green-500">
                                                    <circle cx="12" cy="18" r="3"></circle>
                                                    <circle cx="6" cy="6" r="3"></circle>
                                                    <circle cx="18" cy="6" r="3"></circle>
                                                    <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"></path>
                                                    <path d="M12 12v3"></path>
                                                </svg>
                                                <span>{{ $proyecto->forks }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                <path d="M16 2v4"></path>
                                                <path d="M8 2v4"></path>
                                                <path d="M3 10h18"></path>
                                            </svg>
                                            <span>{{ $proyecto->updated_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <!-- Estado del proyecto -->
                                    @if($proyecto->estado)
                                        <div class="mb-3">
                                            <span class="text-xs px-2 py-1 rounded-md {{
                                                $proyecto->estado === 'En Desarrollo' ? 'bg-blue-500/20 text-blue-400' :
                                                ($proyecto->estado === 'Finalizado' ? 'bg-green-500/20 text-green-400' :
                                                ($proyecto->estado === 'Producción' ? 'bg-purple-500/20 text-purple-400' : 'bg-universo-text-muted/20 text-universo-text-muted'))
                                            }}">
                                                {{ $proyecto->estado }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Enlaces -->
                                    <div class="flex gap-2">
                                        @if($proyecto->repositorio_url)
                                            <a href="{{ $proyecto->repositorio_url }}" target="_blank" class="flex-1 text-sm text-center text-universo-cyan hover:text-universo-purple transition-colors font-medium inline-flex items-center justify-center gap-1 px-3 py-2 bg-universo-purple/10 rounded-lg hover:bg-universo-purple/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github">
                                                    <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path>
                                                    <path d="M9 18c-4.51 2-5-2-7-2"></path>
                                                </svg>
                                                Repositorio
                                            </a>
                                        @endif
                                        @if($proyecto->demo_url)
                                            <a href="{{ $proyecto->demo_url }}" target="_blank" class="text-sm text-universo-text hover:text-universo-purple transition-colors font-medium inline-flex items-center justify-center px-3 py-2 bg-universo-secondary rounded-lg hover:bg-universo-purple/20" title="Ver demo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" x2="21" y1="14" y2="3"></line>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-universo-purple/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml text-universo-purple">
                                <path d="m18 16 4-4-4-4"></path>
                                <path d="m6 8-4 4 4 4"></path>
                                <path d="m14.5 4-5 16"></path>
                            </svg>
                        </div>
                        <p class="text-universo-text-muted mb-4">Aún no has creado ningún proyecto</p>
                        <a href="{{ route('proyectos.index') }}" class="btn-primary inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Explorar proyectos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab: Equipos -->
            <div id="tab-content-equipos" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6 text-universo-cyan">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Equipos
                </h3>
                <p class="text-universo-text-muted mb-6">Equipos a los que perteneces ({{ $user->equipos->count() }})</p>

                @if($user->equipos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($user->equipos as $equipo)
                            <div class="card p-5 hover:shadow-lg transition-shadow">
                                <div class="flex items-start gap-4">
                                    <!-- Avatar del equipo -->
                                    <div class="flex-shrink-0">
                                        @if($equipo->avatar)
                                            <img
                                                src="{{ asset('storage/' . $equipo->avatar) }}"
                                                alt="Avatar de {{ $equipo->name }}"
                                                class="w-16 h-16 rounded-lg object-cover border-2 border-universo-purple">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white text-xl font-bold">
                                                {{ substr($equipo->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Información del equipo -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <h4 class="text-lg font-semibold text-universo-text truncate">
                                                {{ $equipo->name }}
                                            </h4>
                                            <span class="badge badge-{{ $equipo->pivot->rol_equipo === 'Líder' ? 'purple' : 'cyan' }} text-xs whitespace-nowrap flex-shrink-0">
                                                {{ $equipo->pivot->rol_equipo ?? 'Miembro' }}
                                            </span>
                                        </div>

                                        @if($equipo->descripcion)
                                            <p class="text-sm text-universo-text-muted line-clamp-2 mb-3">
                                                {{ $equipo->descripcion }}
                                            </p>
                                        @endif

                                        <!-- Información adicional -->
                                        <div class="flex items-center gap-4 text-xs text-universo-text-muted mb-3">
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                <span>{{ $equipo->miembros_actuales }}/{{ $equipo->max_miembros }}</span>
                                            </div>
                                            @if($equipo->pivot->fecha_ingreso)
                                                <div class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                                                        <path d="M8 2v4"></path>
                                                        <path d="M16 2v4"></path>
                                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                        <path d="M3 10h18"></path>
                                                    </svg>
                                                    <span>Desde {{ \Carbon\Carbon::parse($equipo->pivot->fecha_ingreso)->format('M Y') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Tecnologías -->
                                        @if($equipo->tecnologias && count($equipo->tecnologias) > 0)
                                            <div class="flex flex-wrap gap-1 mb-3">
                                                @foreach(array_slice($equipo->tecnologias, 0, 3) as $tech)
                                                    <span class="text-xs px-2 py-1 rounded-md bg-universo-purple/10 text-universo-purple">
                                                        {{ $tech }}
                                                    </span>
                                                @endforeach
                                                @if(count($equipo->tecnologias) > 3)
                                                    <span class="text-xs px-2 py-1 rounded-md bg-universo-purple/10 text-universo-purple">
                                                        +{{ count($equipo->tecnologias) - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Link al equipo -->
                                        <a href="{{ route('equipos.show', $equipo) }}" class="text-sm text-universo-cyan hover:text-universo-purple transition-colors font-medium inline-flex items-center gap-1">
                                            Ver equipo
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-universo-purple/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-universo-purple">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <p class="text-universo-text-muted mb-4">Aún no formas parte de ningún equipo</p>
                        <a href="{{ route('equipos.index') }}" class="btn-primary inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            Explorar equipos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab: Estadísticas -->
            <div id="tab-content-estadisticas" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart w-6 h-6 text-universo-cyan">
                        <line x1="12" x2="12" y1="20" y2="10"></line>
                        <line x1="18" x2="18" y1="20" y2="4"></line>
                        <line x1="6" x2="6" y1="20" y2="16"></line>
                    </svg>
                    Estadísticas
                </h3>
                <p class="text-universo-text-muted mb-6">Tu actividad en la plataforma</p>

                <!-- Estadísticas principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <!-- Proyectos -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-universo-purple/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml text-universo-purple">
                                    <path d="m18 16 4-4-4-4"></path>
                                    <path d="m6 8-4 4 4 4"></path>
                                    <path d="m14.5 4-5 16"></path>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ $estadisticas['proyectos_totales'] }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Proyectos Totales</h4>
                        <p class="text-xs text-universo-text-muted">Proyectos creados</p>
                    </div>

                    <!-- Equipos -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-universo-cyan/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-universo-cyan">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ $estadisticas['equipos_totales'] }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Equipos</h4>
                        <p class="text-xs text-universo-text-muted">Equipos en los que participas</p>
                    </div>

                    <!-- Torneos Participados -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-universo-warning/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning">
                                    <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                    <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                    <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                    <path d="M4 22h16"></path>
                                    <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                    <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ $estadisticas['torneos_participados'] }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Torneos Participados</h4>
                        <p class="text-xs text-universo-text-muted">Competencias completadas</p>
                    </div>

                    <!-- Torneos Creados -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-green-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag text-green-400">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                    <line x1="4" x2="4" y1="22" y2="15"></line>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ $estadisticas['torneos_creados'] }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Torneos Creados</h4>
                        <p class="text-xs text-universo-text-muted">Torneos organizados</p>
                    </div>

                    <!-- Reconocimientos -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award text-yellow-400">
                                    <circle cx="12" cy="8" r="6"></circle>
                                    <path d="M15.477 12.89 18.87 21l-3.37-.61-.63-3.02M8.523 12.89 5.13 21l3.37-.61.63-3.02"></path>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ $estadisticas['reconocimientos_totales'] }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Reconocimientos</h4>
                        <p class="text-xs text-universo-text-muted">Badges obtenidos</p>
                    </div>

                    <!-- Puntos Totales -->
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap text-blue-400">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <span class="text-3xl font-bold text-universo-text">{{ number_format($estadisticas['puntos_totales'] ?? 0) }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-universo-text mb-1">Puntos Totales</h4>
                        <p class="text-xs text-universo-text-muted">Puntos acumulados</p>
                    </div>
                </div>

                <!-- Datos adicionales del perfil -->
                <div class="card p-6">
                    <h4 class="text-lg font-bold text-universo-text mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info text-universo-cyan">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        Información Adicional
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Rol en la Plataforma</h5>
                            <p class="text-universo-text">{{ $user->rol }}</p>
                        </div>

                        @if($user->habilidades && count($user->habilidades) > 0)
                            <div>
                                <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Habilidades</h5>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->habilidades as $habilidad)
                                        <span class="text-xs px-3 py-1 rounded-full bg-universo-purple/10 text-universo-purple border border-universo-purple/20">
                                            {{ $habilidad }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Miembro Desde</h5>
                            <p class="text-universo-text">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Última Actividad</h5>
                            <p class="text-universo-text">{{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}</p>
                        </div>

                        @if($user->proyectos_completados)
                            <div>
                                <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Proyectos Completados</h5>
                                <p class="text-universo-text">{{ $user->proyectos_completados }}</p>
                            </div>
                        @endif

                        @if($user->torneos_ganados)
                            <div>
                                <h5 class="text-sm font-semibold text-universo-text-muted mb-2">Torneos Ganados</h5>
                                <p class="text-universo-text flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-universo-warning">
                                        <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                                        <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                                        <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                                        <path d="M4 22h16"></path>
                                        <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                                        <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                                    </svg>
                                    {{ $user->torneos_ganados }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function switchTab(tabName) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Resetear todos los botones
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('text-universo-purple', 'border-universo-purple');
        button.classList.add('text-universo-text-muted', 'border-transparent');
    });
    
    // Mostrar contenido seleccionado
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Activar botón seleccionado
    const activeButton = document.getElementById('tab-btn-' + tabName);
    activeButton.classList.remove('text-universo-text-muted', 'border-transparent');
    activeButton.classList.add('text-universo-purple', 'border-universo-purple');
}
</script>
@endsection
@extends('layouts.app')

@section('title', 'Torneos - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-universo-text mb-2">Torneos</h1>
            <p class="text-universo-text-muted">Participa en competencias de programación</p>
        </div>
        <a href="{{ route('torneos.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Crear Torneo
        </a>
    </div>

    <!-- Statistics Dashboard -->
    @if(isset($estadisticas))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card bg-gradient-to-br from-cyan-500/10 to-cyan-500/5 border-cyan-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-universo-text-muted mb-1">Torneos Activos</p>
                    <p class="text-3xl font-bold text-cyan-400">{{ $estadisticas['total_activos'] }}</p>
                </div>
                <div class="p-3 bg-cyan-500/20 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-500"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-purple-500/10 to-purple-500/5 border-purple-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-universo-text-muted mb-1">Total Participantes</p>
                    <p class="text-3xl font-bold text-purple-400">{{ $estadisticas['total_participantes'] }}</p>
                </div>
                <div class="p-3 bg-purple-500/20 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-green-500/10 to-green-500/5 border-green-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-universo-text-muted mb-1">Torneos Finalizados</p>
                    <p class="text-3xl font-bold text-green-400">{{ $estadisticas['total_finalizados'] }}</p>
                </div>
                <div class="p-3 bg-green-500/20 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Filter Bar -->
    <div class="card">
        <form method="GET" action="{{ route('torneos.index') }}" class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-purple"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                <h3 class="text-lg font-semibold text-universo-text">Filtros de Búsqueda</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-universo-text-muted mb-2">Buscar</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Nombre del torneo..."
                               class="w-full pl-10 pr-4 py-2 bg-universo-primary border border-universo-border rounded-lg text-universo-text focus:outline-none focus:ring-2 focus:ring-universo-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-2.5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-universo-text-muted mb-2">Categoría</label>
                    <select id="categoria" name="categoria" class="w-full px-4 py-2 bg-universo-primary border border-universo-border rounded-lg text-universo-text focus:outline-none focus:ring-2 focus:ring-universo-purple">
                        <option value="">Todas las categorías</option>
                        <option value="Web Development" {{ request('categoria') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                        <option value="Mobile Development" {{ request('categoria') == 'Mobile Development' ? 'selected' : '' }}>Mobile Development</option>
                        <option value="Game Development" {{ request('categoria') == 'Game Development' ? 'selected' : '' }}>Game Development</option>
                        <option value="Data Science" {{ request('categoria') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                        <option value="Inteligencia Artificial" {{ request('categoria') == 'Inteligencia Artificial' ? 'selected' : '' }}>Inteligencia Artificial</option>
                        <option value="Ciberseguridad" {{ request('categoria') == 'Ciberseguridad' ? 'selected' : '' }}>Ciberseguridad</option>
                        <option value="DevOps" {{ request('categoria') == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                        <option value="Otro" {{ request('categoria') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <!-- Level Filter -->
                <div>
                    <label for="nivel_dificultad" class="block text-sm font-medium text-universo-text-muted mb-2">Nivel</label>
                    <select id="nivel_dificultad" name="nivel_dificultad" class="w-full px-4 py-2 bg-universo-primary border border-universo-border rounded-lg text-universo-text focus:outline-none focus:ring-2 focus:ring-universo-purple">
                        <option value="">Todos los niveles</option>
                        <option value="Principiante" {{ request('nivel_dificultad') == 'Principiante' ? 'selected' : '' }}>Principiante</option>
                        <option value="Intermedio" {{ request('nivel_dificultad') == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                        <option value="Avanzado" {{ request('nivel_dificultad') == 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                        <option value="Experto" {{ request('nivel_dificultad') == 'Experto' ? 'selected' : '' }}>Experto</option>
                    </select>
                </div>

                <!-- State Filter -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-universo-text-muted mb-2">Estado</label>
                    <select id="estado" name="estado" class="w-full px-4 py-2 bg-universo-primary border border-universo-border rounded-lg text-universo-text focus:outline-none focus:ring-2 focus:ring-universo-purple">
                        <option value="">Todos los estados</option>
                        <option value="Inscripciones Abiertas" {{ request('estado') == 'Inscripciones Abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                        <option value="En Curso" {{ request('estado') == 'En Curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="Próximo" {{ request('estado') == 'Próximo' ? 'selected' : '' }}>Próximos</option>
                        <option value="Evaluación" {{ request('estado') == 'Evaluación' ? 'selected' : '' }}>En Evaluación</option>
                        <option value="Finalizado" {{ request('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizados</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    Aplicar Filtros
                </button>
                @if(request()->hasAny(['search', 'categoria', 'nivel_dificultad', 'estado']))
                <a href="{{ route('torneos.index') }}" class="px-6 py-2 bg-universo-secondary border border-universo-border text-universo-text rounded-lg hover:bg-universo-border transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                    Limpiar Filtros
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Filtered Results View -->
    @if(isset($filtrosActivos) && $filtrosActivos)
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-universo-text">Resultados de Búsqueda</h2>
            <span class="px-3 py-1 bg-universo-purple/20 text-universo-purple rounded-full text-sm font-medium">
                {{ $torneosFiltrados->count() }} torneo(s) encontrado(s)
            </span>
        </div>

        @if($torneosFiltrados->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosFiltrados as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
        @else
        <div class="card text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            <h3 class="text-xl font-semibold text-universo-text mb-2">No se encontraron torneos</h3>
            <p class="text-universo-text-muted mb-6">Intenta ajustar los filtros de búsqueda</p>
            <a href="{{ route('torneos.index') }}" class="btn-primary inline-flex items-center gap-2">
                Ver todos los torneos
            </a>
        </div>
        @endif
    </div>
    @else
    <!-- Grouped by State View (Default) -->

    <!-- Inscripciones Abiertas -->
    @if($torneosInscripcionesAbiertas->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-cyan-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-500"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" x2="19" y1="8" y2="14"></line><line x1="22" x2="16" y1="11" y2="11"></line></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-universo-text">Inscripciones Abiertas</h2>
                <p class="text-sm text-universo-text-muted">¡Inscríbete ahora y participa!</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosInscripcionesAbiertas as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
    </div>
    @endif

    <!-- En Curso -->
    @if($torneosEnCurso->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-green-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-universo-text">En Curso</h2>
                <p class="text-sm text-universo-text-muted">Torneos activos en este momento</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosEnCurso as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
    </div>
    @endif

    <!-- Próximos -->
    @if($torneosProximos->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-purple-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-universo-text">Próximos</h2>
                <p class="text-sm text-universo-text-muted">Torneos que comenzarán pronto</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosProximos as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
    </div>
    @endif

    <!-- En Evaluación -->
    @if($torneosEvaluacion->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-yellow-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-universo-text">En Evaluación</h2>
                <p class="text-sm text-universo-text-muted">Proyectos siendo calificados</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosEvaluacion as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
    </div>
    @endif

    <!-- Finalizados -->
    @if($torneosFinalizados->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gray-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-universo-text">Finalizados</h2>
                <p class="text-sm text-universo-text-muted">Torneos completados</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($torneosFinalizados as $torneo)
                @include('torneos.partials.enhanced-card', ['torneo' => $torneo])
            @endforeach
        </div>
    </div>
    @endif

    @if($torneosInscripcionesAbiertas->count() == 0 && $torneosEnCurso->count() == 0 && $torneosProximos->count() == 0 && $torneosEvaluacion->count() == 0 && $torneosFinalizados->count() == 0)
    <div class="card text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
        <h3 class="text-xl font-semibold text-universo-text mb-2">No hay torneos disponibles</h3>
        <p class="text-universo-text-muted mb-6">Sé el primero en crear uno</p>
        <a href="{{ route('torneos.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Crear Torneo
        </a>
    </div>
    @endif
    @endif

</div>
@endsection

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
        @if(auth()->check() && auth()->user()->rol === 'Administrador')
            <a href="{{ route('torneos.create') }}" class="btn-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                Crear Torneo
            </a>
        @endif
    </div>

    <!-- Search and Filters -->
    <form method="GET" action="{{ route('torneos.index') }}" class="flex flex-col md:flex-row items-center gap-4 mb-8">
        <div class="relative flex-grow md:flex-grow-0 md:w-1/2">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            </div>
            <input type="text" name="search" placeholder="Buscar torneos..." class="input-field pl-10 w-full" value="{{ request('search') }}">
        </div>

        <div>
            <label for="categoria" class="sr-only">Categoría</label>
            <select name="categoria" id="categoria" class="input-field">
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

        <div>
            <label for="estado" class="sr-only">Estado</label>
            <select name="estado" id="estado" class="input-field">
                <option value="">Todos los estados</option>
                <option value="Inscripciones Abiertas" {{ request('estado') == 'Inscripciones Abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                <option value="En Curso" {{ request('estado') == 'En Curso' ? 'selected' : '' }}>En Curso</option>
                <option value="Próximo" {{ request('estado') == 'Próximo' ? 'selected' : '' }}>Próximos</option>
                <option value="Evaluación" {{ request('estado') == 'Evaluación' ? 'selected' : '' }}>En Evaluación</option>
                <option value="Finalizado" {{ request('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizados</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary">Filtrar</button>
        </div>
    </form>

    <!-- Tournament List -->
    @if(isset($filtrosActivos) && $filtrosActivos)
        <!-- Filtered Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($torneosFiltrados as $torneo)
                <x-tournament-card :torneo="$torneo" />
            @empty
                <p class="text-universo-text-muted md:col-span-2 lg:col-span-3">No se encontraron torneos con los filtros seleccionados.</p>
            @endforelse
        </div>
    @else
        <!-- All Tournaments Grouped by State -->

        <!-- Inscripciones Abiertas -->
        @if($torneosInscripcionesAbiertas->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-universo-text mb-4">Inscripciones Abiertas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($torneosInscripcionesAbiertas as $torneo)
                    <x-tournament-card :torneo="$torneo" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- En Curso -->
        @if($torneosEnCurso->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-universo-text mb-4">En Curso</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($torneosEnCurso as $torneo)
                    <x-tournament-card :torneo="$torneo" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Próximos -->
        @if($torneosProximos->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-universo-text mb-4">Próximos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($torneosProximos as $torneo)
                    <x-tournament-card :torneo="$torneo" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- En Evaluación -->
        @if($torneosEvaluacion->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-universo-text mb-4">En Evaluación</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($torneosEvaluacion as $torneo)
                    <x-tournament-card :torneo="$torneo" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Finalizados -->
        @if($torneosFinalizados->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-universo-text mb-4">Finalizados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($torneosFinalizados as $torneo)
                    <x-tournament-card :torneo="$torneo" />
                @endforeach
            </div>
        </div>
        @endif

        @if($torneosInscripcionesAbiertas->count() == 0 && $torneosEnCurso->count() == 0 && $torneosProximos->count() == 0 && $torneosEvaluacion->count() == 0 && $torneosFinalizados->count() == 0)
        <p class="text-universo-text-muted">No hay torneos disponibles.</p>
        @endif
    @endif

</div>
@endsection

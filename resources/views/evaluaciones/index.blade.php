@extends('layouts.app')

@section('title', 'Torneos en Evaluación - Juez')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-3 bg-yellow-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-universo-text">Torneos en Evaluación</h1>
                <p class="text-universo-text-muted">Panel de evaluación para jueces</p>
            </div>
        </div>

        <div class="card bg-blue-500/10 border-blue-500/20">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                <div>
                    <p class="text-blue-500 font-semibold mb-1">Instrucciones para Jueces</p>
                    <p class="text-sm text-blue-400">
                        Evalúa cada proyecto según los criterios establecidos. Tu calificación será promediada con la de otros jueces para determinar el puntaje final.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <p class="text-green-500 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><circle cx="12" cy="12" r="10"></circle><line x1="15" x2="9" y1="9" y2="15"></line><line x1="9" x2="15" y1="9" y2="15"></line></svg>
            <p class="text-red-500 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Lista de Torneos -->
    @if($torneos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($torneos as $torneo)
                <div class="card hover:border-yellow-500/50 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-universo-text mb-2">{{ $torneo->name }}</h3>
                            <p class="text-sm text-universo-text-muted mb-3">Organizado por {{ $torneo->organizador->name }}</p>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="badge badge-warning">En Evaluación</span>
                                <span class="badge badge-purple">{{ $torneo->categoria }}</span>
                                <span class="badge badge-cyan">{{ $torneo->nivel_dificultad }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4 p-3 bg-universo-dark rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-universo-purple">{{ $torneo->participantes_actuales }}</div>
                            <div class="text-xs text-universo-text-muted">Equipos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-universo-cyan">{{ count($torneo->criterios_evaluacion ?? []) }}</div>
                            <div class="text-xs text-universo-text-muted">Criterios</div>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-universo-text-muted">Finalizado:</span>
                            <span class="text-universo-text">{{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('evaluaciones.show', $torneo) }}" class="btn-primary w-full text-center flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        Evaluar Participantes
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="card text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
            <h3 class="text-xl font-semibold text-universo-text mb-2">No hay torneos en evaluación</h3>
            <p class="text-universo-text-muted">Los torneos en evaluación aparecerán aquí</p>
        </div>
    @endif
</div>
@endsection

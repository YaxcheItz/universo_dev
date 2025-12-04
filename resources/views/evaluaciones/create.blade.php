@extends('layouts.app')

@section('title', 'Evaluar ' . $participacion->equipo->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('evaluaciones.show', $participacion->torneo) }}" class="inline-flex items-center gap-2 text-universo-purple hover:text-purple-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
            Volver a Participantes
        </a>

        <div class="card bg-gradient-to-r from-purple-500/10 to-cyan-500/10 border-purple-500/20 mb-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-purple-500/20 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-universo-text mb-1">Evaluar Equipo</h1>
                    <p class="text-lg text-universo-purple font-semibold mb-2">{{ $participacion->equipo->name }}</p>
                    <p class="text-sm text-universo-text-muted">
                        Torneo: <span class="text-universo-text">{{ $participacion->torneo->name }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Proyecto -->
    @if($participacion->proyecto)
    <div class="card mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-cyan"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path></svg>
                    {{ $participacion->proyecto->name }}
                </h3>
                <p class="text-sm text-universo-text-muted mb-3">{{ $participacion->proyecto->descripcion }}</p>

                @if($participacion->proyecto->tecnologias)
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($participacion->proyecto->tecnologias as $tech)
                        <span class="badge badge-cyan">{{ $tech }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="flex gap-2">
            @if($participacion->proyecto->url_repositorio)
                <a href="{{ $participacion->proyecto->url_repositorio }}" target="_blank" class="btn-secondary text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>
                    Repositorio
                </a>
            @endif
            @if($participacion->proyecto->url_demo)
                <a href="{{ $participacion->proyecto->url_demo }}" target="_blank" class="btn-secondary text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                    Demo
                </a>
            @endif
            <a href="{{ route('proyectos.show', $participacion->proyecto) }}" target="_blank" class="btn-secondary text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                Ver Detalles
            </a>
        </div>
    </div>
    @endif

    <!-- Formulario de Evaluación -->
    <form action="{{ route('evaluaciones.store', $participacion) }}" method="POST">
        @csrf

        <div class="card mb-6">
            <h3 class="text-xl font-semibold text-universo-text mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-warning"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Criterios de Evaluación
            </h3>

            <div class="space-y-6">
                @foreach($criterios as $index => $criterio)
                    <div class="p-4 bg-universo-dark rounded-lg border border-universo-border">
                        <label for="criterio_{{ $index }}" class="block text-sm font-semibold text-universo-text mb-3">
                            {{ $criterio }}
                        </label>

                        <div class="flex items-center gap-4">
                            <input
                                type="range"
                                id="criterio_{{ $index }}"
                                name="calificaciones[{{ $criterio }}]"
                                min="0"
                                max="100"
                                value="50"
                                class="flex-1 h-2 bg-universo-card rounded-lg appearance-none cursor-pointer accent-purple-500"
                                oninput="updateValue(this.value, {{ $index }})"
                                required
                            >
                            <div class="flex items-center gap-2">
                                <span id="value_{{ $index }}" class="text-2xl font-bold text-universo-purple w-16 text-right">50</span>
                                <span class="text-universo-text-muted">/100</span>
                            </div>
                        </div>

                        <div class="flex justify-between mt-2 text-xs text-universo-text-muted">
                            <span>0 - Muy deficiente</span>
                            <span>50 - Regular</span>
                            <span>100 - Excelente</span>
                        </div>

                        <script>
                            function updateValue(value, index) {
                                document.getElementById('value_' + index).textContent = value;
                            }
                        </script>

                        @error('calificaciones.' . $criterio)
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                @if(empty($criterios))
                    <div class="text-center py-8 text-universo-text-muted">
                        <p>No hay criterios de evaluación definidos para este torneo.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comentarios -->
        <div class="card mb-6">
            <label for="comentarios" class="block text-sm font-semibold text-universo-text mb-3">
                Comentarios Adicionales (Opcional)
            </label>
            <textarea
                name="comentarios"
                id="comentarios"
                rows="5"
                class="input-field resize-none"
                placeholder="Escribe comentarios constructivos sobre el proyecto del equipo..."
            ></textarea>
            <p class="text-xs text-universo-text-muted mt-2">
                Estos comentarios son privados y solo serán visibles para otros jueces y organizadores.
            </p>
            @error('comentarios')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2" {{ empty($criterios) ? 'disabled' : '' }}>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Guardar Evaluación
            </button>
            <a href="{{ route('evaluaciones.show', $participacion->torneo) }}" class="btn-secondary flex items-center justify-center gap-2 px-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

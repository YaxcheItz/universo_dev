@php
    $now = \Carbon\Carbon::now();
    $esNuevo = $torneo->created_at->diffInDays($now) <= 7;
    $porcentajeOcupacion = $torneo->max_participantes > 0 ? ($torneo->participantes_actuales / $torneo->max_participantes) * 100 : 0;
    $casiLleno = $porcentajeOcupacion >= 80 && $porcentajeOcupacion < 100;
    $lleno = $torneo->participantes_actuales >= $torneo->max_participantes;

    // Determinar fecha objetivo para countdown según el estado
    $targetDate = null;
    $countdownLabel = '';
    if ($torneo->estado === 'Inscripciones Abiertas') {
        $targetDate = $torneo->fecha_registro_fin;
        $countdownLabel = 'Cierre de inscripciones';
    } elseif ($torneo->estado === 'Próximo') {
        $targetDate = $torneo->fecha_registro_inicio;
        $countdownLabel = 'Inicia registro';
    } elseif ($torneo->estado === 'En Curso') {
        $targetDate = $torneo->fecha_fin;
        $countdownLabel = 'Finaliza en';
    }
@endphp

<div class="card hover:border-universo-purple/50 transition group relative overflow-hidden">
    <!-- Badges superiores -->
    <div class="flex gap-2 mb-3 flex-wrap">
        @if($esNuevo)
        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-bold rounded-full flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            NUEVO
        </span>
        @endif

        @if($casiLleno && !$lleno)
        <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-xs font-bold rounded-full animate-pulse">
            ÚLTIMOS CUPOS
        </span>
        @endif

        @if($lleno)
        <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs font-bold rounded-full">
            LLENO
        </span>
        @endif

        <!-- Estado del torneo -->
        <span class="px-2 py-1 text-xs font-medium rounded-full
            @if($torneo->estado === 'Inscripciones Abiertas') bg-cyan-500/20 text-cyan-400
            @elseif($torneo->estado === 'En Curso') bg-green-500/20 text-green-400
            @elseif($torneo->estado === 'Próximo') bg-purple-500/20 text-purple-400
            @elseif($torneo->estado === 'Evaluación') bg-yellow-500/20 text-yellow-400
            @else bg-gray-500/20 text-gray-400
            @endif">
            {{ $torneo->estado }}
        </span>
    </div>

    <!-- Título del torneo -->
    <h3 class="text-xl font-bold text-universo-text mb-2 group-hover:text-universo-purple transition">
        {{ $torneo->name }}
    </h3>

    <!-- Descripción -->
    <p class="text-universo-text-muted text-sm mb-4 line-clamp-2">
        {{ $torneo->description }}
    </p>

    <!-- Información del torneo -->
    <div class="space-y-2 mb-4">
        <div class="flex items-center gap-2 text-sm text-universo-text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span><strong>Categoría:</strong> {{ $torneo->categoria }}</span>
        </div>

        <div class="flex items-center gap-2 text-sm text-universo-text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span><strong>Nivel:</strong> {{ $torneo->nivel_dificultad }}</span>
        </div>

        @if($torneo->premio)
        <div class="flex items-center gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
            <span class="text-amber-400 font-semibold">{{ $torneo->premio }}</span>
        </div>
        @endif
    </div>

    <!-- Countdown Timer -->
    @if($targetDate)
    <div class="mb-4 p-3 bg-universo-secondary/50 border border-universo-border rounded-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-universo-text-muted">{{ $countdownLabel }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-purple"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="countdown-timer text-universo-text font-mono text-sm font-semibold"
             data-target="{{ $targetDate->toIso8601String() }}">
            Calculando...
        </div>
    </div>
    @endif

    <!-- Quota Indicator -->
    <div class="mb-4">
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="text-universo-text-muted">Cupos disponibles</span>
            <span class="font-semibold
                @if($porcentajeOcupacion >= 90) text-red-400
                @elseif($porcentajeOcupacion >= 70) text-amber-400
                @else text-green-400
                @endif">
                {{ $torneo->participantes_actuales }}/{{ $torneo->max_participantes }}
            </span>
        </div>
        <div class="w-full bg-universo-secondary rounded-full h-2 overflow-hidden">
            <div class="h-full transition-all duration-500 rounded-full
                @if($porcentajeOcupacion >= 90) bg-red-500
                @elseif($porcentajeOcupacion >= 70) bg-amber-500
                @else bg-green-500
                @endif"
                style="width: {{ min($porcentajeOcupacion, 100) }}%">
            </div>
        </div>
    </div>

    <!-- Fechas -->
    <div class="text-xs text-universo-text-muted mb-4 space-y-1">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
            <span>Registro: {{ \Carbon\Carbon::parse($torneo->fecha_registro_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($torneo->fecha_registro_fin)->format('d/m/Y') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Torneo: {{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($torneo->fecha_fin)->format('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Botón -->
    <a href="{{ route('torneos.show', $torneo) }}"
       class="block w-full text-center px-4 py-2 bg-gradient-to-r from-universo-purple to-universo-cyan text-white rounded-lg font-medium hover:opacity-90 transition group-hover:shadow-lg group-hover:shadow-universo-purple/20">
        Ver Detalles
    </a>
</div>

@push('scripts')
<script>
    // Countdown Timer Logic
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(timer => {
            const target = new Date(timer.dataset.target);
            const now = new Date();
            const diff = target - now;

            if (diff <= 0) {
                timer.textContent = 'Finalizado';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            if (days > 0) {
                timer.textContent = `${days}d ${hours}h ${minutes}m`;
            } else if (hours > 0) {
                timer.textContent = `${hours}h ${minutes}m ${seconds}s`;
            } else {
                timer.textContent = `${minutes}m ${seconds}s`;
            }
        });
    }

    // Update immediately and then every second
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>
@endpush

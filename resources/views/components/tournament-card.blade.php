<div class="card flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-6 h-6 text-universo-warning"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                <h3 class="text-xl font-semibold text-universo-text">{{ $torneo->nombre }}</h3>
            </div>
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
            <span class="badge {{ $badgeClass }}">{{ $torneo->estado }}</span>
        </div>
        <p class="text-universo-text-muted text-sm mb-2">{{ $torneo->categoria }} • {{ $torneo->dominio ?? 'Sin Dominio' }}</p>
        <p class="text-universo-text-muted mb-4 text-sm">{{ Str::limit($torneo->descripcion, 100) }}</p>
    </div>

    <div>
        <div class="flex items-center gap-4 text-universo-text-muted text-sm mb-4">
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                {{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($torneo->fecha_fin)->format('d/m/Y') }}
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>
                {{ $torneo->participantes_actuales }} participantes
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round w-4 h-4"><path d="M18 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="10" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Equipos de {{ $torneo->tamano_equipo_min }}-{{ $torneo->tamano_equipo_max }} personas
            </div>
        </div>
        <a href="{{ route('torneos.show', $torneo) }}" class="w-full btn-primary mt-4 block text-center">Ver Detalles y Participar</a>
    </div>
</div>
<div class="card flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-6 h-6 text-universo-warning"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                <h3 class="text-xl font-semibold text-universo-text">{{ $torneo->name }}</h3>
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
        <p class="text-universo-text-muted mb-4 text-sm">{{ Str::limit($torneo->descripcion ?? 'Sin descripción', 100) }}</p>
    </div>

    <div>
        <div class="flex flex-wrap items-center gap-4 text-universo-text-muted text-sm mb-4">
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                <span class="text-xs">{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</span>
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                @if($torneo->max_participantes)
                    <span class="text-xs {{ $torneo->participantes_actuales >= $torneo->max_participantes ? 'text-red-400 font-bold' : '' }}">
                        {{ $torneo->participantes_actuales }}/{{ $torneo->max_participantes }}
                        @if($torneo->participantes_actuales >= $torneo->max_participantes)
                            <span class="text-red-400">(Lleno)</span>
                        @endif
                    </span>
                @else
                    <span class="text-xs">{{ $torneo->participantes_actuales }} equipos</span>
                @endif
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target w-4 h-4"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                <span class="text-xs">{{ $torneo->nivel_dificultad }}</span>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('torneos.show', $torneo) }}" class="flex-1 btn-primary text-center">Ver Detalles</a>
            @if(auth()->id() === $torneo->user_id)
                <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" onsubmit="return confirm('¿Estas seguro de eliminar este torneo?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-400 px-3" title="Eliminar torneo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

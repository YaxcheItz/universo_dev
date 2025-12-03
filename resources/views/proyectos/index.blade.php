@extends('layouts.app')

@section('title', 'Proyectos - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-universo-text mb-2">Proyectos</h1>
            <p class="text-universo-text-muted">Explora y comparte proyectos de la comunidad</p>
        </div>
        <a href="{{ route('proyectos.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Nuevo Proyecto
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <form action="{{ route('proyectos.index') }}" method="GET" class="flex gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar por nombre o lenguaje..." 
                    class="input-field pl-10 w-full"
                    value="{{ request('search') }}"
                >
            </div>
            <button type="submit" class="btn-primary">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('proyectos.index') }}" class="btn-secondary">
                    Limpiar
                </a>
            @endif
        </form>
        
        @if(request('search'))
            <p class="text-universo-text-muted mt-2">
                Resultados para: <span class="text-universo-text font-semibold">"{{ request('search') }}"</span>
                ({{ $proyectos->total() }} {{ $proyectos->total() === 1 ? 'resultado' : 'resultados' }})
            </p>
        @endif
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($proyectos as $proyecto)
            <!-- Proyecto Card con click para editar -->
            <div 
                class="card hover:border-universo-purple transition-all cursor-pointer group"
                onclick="window.location.href='{{ route('proyectos.edit', $proyecto) }}'"
            >
                <!-- Header del Proyecto -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-universo-text group-hover:text-universo-purple transition-colors">
                            {{ $proyecto->name }}
                        </h3>
                        <p class="text-sm text-universo-text-muted">
                            por {{ $proyecto->creador->name ?? 'Desconocido' }}
                        </p>
                    </div>
                    
                    <!-- Badge de Estado -->
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($proyecto->estado === 'Producción') bg-green-500/20 text-green-400
                        @elseif($proyecto->estado === 'En Desarrollo') bg-blue-500/20 text-blue-400
                        @elseif($proyecto->estado === 'Planificación') bg-yellow-500/20 text-yellow-400
                        @else bg-gray-500/20 text-gray-400
                        @endif">
                        {{ $proyecto->estado }}
                    </span>
                </div>

                <!-- Descripción -->
                <p class="text-universo-text-muted text-sm mb-4 line-clamp-3">
                    {{ $proyecto->descripcion }}
                </p>

                <!-- Lenguaje y Tecnologías -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-universo-purple/20 text-universo-purple text-xs rounded-full font-medium">
                        {{ $proyecto->lenguaje_principal }}
                    </span>
                    @if($proyecto->tecnologias && count($proyecto->tecnologias) > 0)
                        @foreach(array_slice($proyecto->tecnologias, 0, 2) as $tech)
                            <span class="px-3 py-1 bg-universo-card-bg text-universo-text-muted text-xs rounded-full">
                                {{ $tech }}
                            </span>
                        @endforeach
                        @if(count($proyecto->tecnologias) > 2)
                            <span class="px-3 py-1 bg-universo-card-bg text-universo-text-muted text-xs rounded-full">
                                +{{ count($proyecto->tecnologias) - 2 }}
                            </span>
                        @endif
                    @endif
                </div>

                <!-- Valoración con Estrellas -->
                <div class="flex items-center gap-4 text-sm text-universo-text-muted border-t border-universo-border pt-3">
                    <div class="flex items-center gap-1">
                        @php
                            $promedio = round($proyecto->promedio_valoracion * 2) / 2; // Redondear a .5
                            $estrellas_llenas = floor($promedio);
                            $media_estrella = ($promedio - $estrellas_llenas) >= 0.5;
                            $estrellas_vacias = 5 - $estrellas_llenas - ($media_estrella ? 1 : 0);
                        @endphp
                        
                        <!-- Estrellas llenas -->
                        @for($i = 0; $i < $estrellas_llenas; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-400">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                        
                        <!-- Media estrella -->
                        @if($media_estrella)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-400">
                                <defs>
                                    <linearGradient id="half-{{ $proyecto->id }}">
                                        <stop offset="50%" stop-color="currentColor" />
                                        <stop offset="50%" stop-color="transparent" />
                                    </linearGradient>
                                </defs>
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="url(#half-{{ $proyecto->id }})"></polygon>
                            </svg>
                        @endif
                        
                        <!-- Estrellas vacías -->
                        @for($i = 0; $i < $estrellas_vacias; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                        
                        <span class="ml-1 font-medium text-universo-text">
                            {{ number_format($proyecto->promedio_valoracion, 1) }}
                        </span>
                        <span class="text-universo-text-muted">
                            ({{ $proyecto->total_valoraciones }})
                        </span>
                    </div>
                    
                    <!-- Botón para valorar (solo si no es el creador y está autenticado) -->
                    @auth
                        @if(Auth::id() !== $proyecto->user_id)
                            <button 
                                onclick="event.stopPropagation(); abrirModalValoracion({{ $proyecto->id }}, '{{ $proyecto->name }}', {{ $proyecto->valoracionDeUsuario(Auth::id())?->puntuacion ?? 0 }})"
                                class="ml-auto text-universo-purple hover:text-universo-purple/80 transition-colors"
                                title="Valorar proyecto"
                            >
                                @if($proyecto->yaValoradoPor(Auth::id()))
                                    Editar valoración
                                @else
                                    Valorar
                                @endif
                            </button>
                        @endif
                    @endauth
                </div>

                <!-- Hover Effect -->
                <div class="absolute inset-0 border-2 border-transparent group-hover:border-universo-purple rounded-lg pointer-events-none transition-all"></div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-universo-text-muted opacity-50"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                <p class="text-universo-text-muted text-lg mb-2">
                    @if(request('search'))
                        No se encontraron proyectos que coincidan con tu búsqueda
                    @else
                        No hay proyectos para mostrar
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('proyectos.index') }}" class="text-universo-purple hover:underline">
                        Ver todos los proyectos
                    </a>
                @else
                    <a href="{{ route('proyectos.create') }}" class="text-universo-purple hover:underline">
                        Crea el primer proyecto
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $proyectos->appends(['search' => request('search')])->links() }}
    </div>

</div>

<!-- Modal de Valoración -->
<div id="modal-valoracion" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="cerrarModalValoracion()">
    <div class="bg-universo-card-bg border border-universo-border rounded-lg p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <h3 class="text-xl font-bold text-universo-text mb-4">Valorar Proyecto</h3>
        <p class="text-universo-text-muted mb-4" id="modal-proyecto-nombre"></p>
        
        <form id="form-valoracion" method="POST" action="">
            @csrf
            <input type="hidden" name="proyecto_id" id="valoracion-proyecto-id">
            
            <!-- Estrellas para valorar -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-universo-text mb-2">Puntuación</label>
                <div class="flex gap-2" id="estrellas-valoracion">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="seleccionarEstrella({{ $i }})" class="estrella-btn" data-valor="{{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted hover:text-yellow-400 transition-colors">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="puntuacion" id="puntuacion-input" required>
            </div>
            
            <!-- Comentario opcional -->
            <div class="mb-4">
                <label for="comentario" class="block text-sm font-medium text-universo-text mb-2">Comentario (opcional)</label>
                <textarea name="comentario" id="comentario" rows="3" class="input-field" placeholder="Comparte tu opinión sobre este proyecto..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalValoracion()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Enviar Valoración</button>
            </div>
        </form>
    </div>
</div>

<script>
let puntuacionSeleccionada = 0;

function abrirModalValoracion(proyectoId, proyectoNombre, valoracionActual = 0) {
    document.getElementById('modal-valoracion').classList.remove('hidden');
    document.getElementById('modal-valoracion').classList.add('flex');
    document.getElementById('modal-proyecto-nombre').textContent = proyectoNombre;
    document.getElementById('valoracion-proyecto-id').value = proyectoId;
    document.getElementById('form-valoracion').action = `/proyectos/${proyectoId}/valorar`;
    
    // Si ya tiene valoración, mostrarla
    if (valoracionActual > 0) {
        seleccionarEstrella(valoracionActual);
    } else {
        puntuacionSeleccionada = 0;
        actualizarEstrellas();
    }
}

function cerrarModalValoracion() {
    document.getElementById('modal-valoracion').classList.add('hidden');
    document.getElementById('modal-valoracion').classList.remove('flex');
    puntuacionSeleccionada = 0;
    document.getElementById('comentario').value = '';
    actualizarEstrellas();
}

function seleccionarEstrella(valor) {
    puntuacionSeleccionada = valor;
    document.getElementById('puntuacion-input').value = valor;
    actualizarEstrellas();
}

function actualizarEstrellas() {
    const botones = document.querySelectorAll('.estrella-btn');
    botones.forEach((boton, index) => {
        const svg = boton.querySelector('svg');
        if (index < puntuacionSeleccionada) {
            svg.setAttribute('fill', 'currentColor');
            svg.classList.remove('text-universo-text-muted');
            svg.classList.add('text-yellow-400');
        } else {
            svg.setAttribute('fill', 'none');
            svg.classList.add('text-universo-text-muted');
            svg.classList.remove('text-yellow-400');
        }
    });
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalValoracion();
    }
});
</script>

<!-- Nota visual para el usuario -->
<div class="fixed bottom-4 right-4 bg-universo-card-bg border border-universo-border rounded-lg p-4 shadow-lg max-w-sm hidden" id="edit-hint">
    <p class="text-sm text-universo-text mb-1">💡 <strong>Tip:</strong></p>
    <p class="text-xs text-universo-text-muted">Haz click en cualquier proyecto para editarlo</p>
</div>

<script>
// Mostrar hint al cargar la página
window.addEventListener('load', function() {
    const hint = document.getElementById('edit-hint');
    if (hint && {{ $proyectos->count() > 0 ? 'true' : 'false' }}) {
        setTimeout(() => {
            hint.classList.remove('hidden');
            setTimeout(() => {
                hint.classList.add('hidden');
            }, 5000);
        }, 1000);
    }
});
</script>
@endsection
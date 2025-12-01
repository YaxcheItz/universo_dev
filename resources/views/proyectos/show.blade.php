@extends('layouts.app')

@section('title', $proyecto->name . ' - UniversoDev')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header con acciones -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('proyectos.index') }}" class="text-universo-purple hover:underline flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver a Proyectos
        </a>
        
        @if(Auth::id() === $proyecto->user_id)
            <div class="flex gap-2">
                <a href="{{ route('proyectos.edit', $proyecto) }}" class="btn-secondary">
                    Editar
                </a>
                <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-400 hover:text-red-300">
                        Eliminar
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Información Principal -->
    <div class="card mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-universo-text mb-2">{{ $proyecto->name }}</h1>
                <div class="flex items-center gap-4 text-sm text-universo-text-muted">
                    <span>Creado por <strong class="text-universo-text">{{ $proyecto->creador->name ?? 'Desconocido' }}</strong></span>
                    <span>•</span>
                    <span>{{ $proyecto->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <!-- Badge de Estado -->
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($proyecto->estado === 'Producción') bg-green-500/20 text-green-400
                @elseif($proyecto->estado === 'En Desarrollo') bg-blue-500/20 text-blue-400
                @elseif($proyecto->estado === 'Planificación') bg-yellow-500/20 text-yellow-400
                @else bg-gray-500/20 text-gray-400
                @endif">
                {{ $proyecto->estado }}
            </span>
        </div>

        <!-- Descripción -->
        <p class="text-universo-text mb-6 leading-relaxed">{{ $proyecto->descripcion }}</p>

        <!-- Valoración -->
        <div class="p-4 bg-universo-bg rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @php
                        $promedio = round($proyecto->promedio_valoracion * 2) / 2;
                        $estrellas_llenas = floor($promedio);
                        $media_estrella = ($promedio - $estrellas_llenas) >= 0.5;
                        $estrellas_vacias = 5 - $estrellas_llenas - ($media_estrella ? 1 : 0);
                    @endphp
                    
                    <div class="flex items-center gap-1">
                        @for($i = 0; $i < $estrellas_llenas; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" class="text-yellow-400">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                        @if($media_estrella)
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-400">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endif
                        @for($i = 0; $i < $estrellas_vacias; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-text-muted">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                    </div>
                    
                    <span class="text-2xl font-bold text-universo-text">
                        {{ number_format($proyecto->promedio_valoracion, 1) }}
                    </span>
                    <span class="text-universo-text-muted">
                        ({{ $proyecto->total_valoraciones }} {{ $proyecto->total_valoraciones === 1 ? 'valoración' : 'valoraciones' }})
                    </span>
                </div>

                @auth
                    @if(Auth::id() !== $proyecto->user_id)
                        <button 
                            onclick="abrirModalValoracion({{ $proyecto->id }}, '{{ $proyecto->name }}', {{ $proyecto->valoracionDeUsuario(Auth::id())?->puntuacion ?? 0 }})"
                            class="btn-primary text-sm"
                        >
                            @if($proyecto->yaValoradoPor(Auth::id()))
                                Editar mi valoración
                            @else
                                Valorar proyecto
                            @endif
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="md:col-span-2 space-y-6">
            <!-- Tecnología -->
            <div class="card">
                <h2 class="text-xl font-semibold text-universo-text mb-4">Tecnología</h2>
                
                <div class="mb-4">
                    <span class="text-sm text-universo-text-muted">Lenguaje Principal</span>
                    <div class="mt-2">
                        <span class="px-4 py-2 bg-universo-purple/20 text-universo-purple rounded-lg font-medium inline-block">
                            {{ $proyecto->lenguaje_principal }}
                        </span>
                    </div>
                </div>

                @if($proyecto->tecnologias && count($proyecto->tecnologias) > 0)
                    <div>
                        <span class="text-sm text-universo-text-muted">Tecnologías Adicionales</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($proyecto->tecnologias as $tech)
                                <span class="px-3 py-1 bg-universo-card-bg text-universo-text rounded-lg text-sm">
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Enlaces -->
            @if($proyecto->repositorio_url || $proyecto->demo_url || $proyecto->documentacion_url)
                <div class="card">
                    <h2 class="text-xl font-semibold text-universo-text mb-4">Enlaces</h2>
                    <div class="space-y-3">
                        @if($proyecto->repositorio_url)
                            <a href="{{ $proyecto->repositorio_url }}" target="_blank" class="flex items-center gap-3 text-universo-purple hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>
                                Ver Repositorio
                            </a>
                        @endif
                        
                        @if($proyecto->demo_url)
                            <a href="{{ $proyecto->demo_url }}" target="_blank" class="flex items-center gap-3 text-universo-purple hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                Ver Demo
                            </a>
                        @endif
                        
                        @if($proyecto->documentacion_url)
                            <a href="{{ $proyecto->documentacion_url }}" target="_blank" class="flex items-center gap-3 text-universo-purple hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                Ver Documentación
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Columna Lateral -->
        <div class="space-y-6">
            <!-- Información del Proyecto -->
            <div class="card">
                <h3 class="text-lg font-semibold text-universo-text mb-4">Información</h3>
                <div class="space-y-3 text-sm">
                    @if($proyecto->fecha_inicio)
                        <div>
                            <span class="text-universo-text-muted">Fecha de Inicio</span>
                            <p class="text-universo-text font-medium">{{ $proyecto->fecha_inicio->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    
                    @if($proyecto->fecha_fin_estimada)
                        <div>
                            <span class="text-universo-text-muted">Fecha Fin Estimada</span>
                            <p class="text-universo-text font-medium">{{ $proyecto->fecha_fin_estimada->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    
                    @if($proyecto->fecha_fin_real)
                        <div>
                            <span class="text-universo-text-muted">Fecha Fin Real</span>
                            <p class="text-universo-text font-medium">{{ $proyecto->fecha_fin_real->format('d/m/Y') }}</p>
                        </div>
                    @endif

                    <div>
                        <span class="text-universo-text-muted">Visibilidad</span>
                        <p class="text-universo-text font-medium">
                            {{ $proyecto->es_publico ? '🌐 Público' : '🔒 Privado' }}
                        </p>
                    </div>

                    @if($proyecto->es_trending)
                        <div>
                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">
                                🔥 Trending
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalValoracion();
    }
});
</script>
@endsection
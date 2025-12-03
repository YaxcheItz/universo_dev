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

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

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
                    placeholder="Buscar por nombre, creador o lenguaje..." 
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
            <!-- Proyecto Card -->
            <div class="card hover:border-universo-purple transition-all group relative">
                <!-- Header del Proyecto -->
                <div 
                    class="cursor-pointer"
                    onclick="window.location.href='{{ route('proyectos.show', $proyecto) }}'"
                >
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
                </div>

                <!-- Valoración con Estrellas -->
                <div class="flex items-center justify-between text-sm border-t border-universo-border pt-3">
                    <div class="flex items-center gap-1">
                        @php
                            $promedio = round($proyecto->promedio_valoracion * 2) / 2;
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
                    
                    <!-- Botón para valorar -->
                    @auth
                        @if(Auth::id() !== $proyecto->user_id)
                            <button 
                                onclick="event.stopPropagation(); abrirModalValoracion({{ $proyecto->id }}, '{{ addslashes($proyecto->name) }}', {{ $proyecto->valoracionDeUsuario(Auth::id())?->puntuacion ?? 0 }})"
                                class="text-universo-purple hover:text-universo-purple/80 transition-colors text-xs font-medium"
                                title="Valorar proyecto"
                            >
                                @if($proyecto->yaValoradoPor(Auth::id()))
                                    Editar valoracion
                                @else
                                    Valorar
                                @endif
                            </button>
                        @else
                            <button 
                                onclick="event.stopPropagation(); window.location.href='{{ route('proyectos.edit', $proyecto) }}'"
                                class="text-universo-purple hover:text-universo-purple/80 transition-colors text-xs font-medium"
                                title="Editar proyecto"
                            >
                                Editar valoracion
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-universo-text-muted hover:text-universo-purple transition-colors text-xs">
                            Inicia sesión para valorar
                        </a>
                    @endauth
                </div>
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
        {{ $proyectos->appends(['search' => request('search')])->links('components.pagination') }}
    </div>

</div>

<!-- Incluir el componente del modal -->
<x-modal-valoracion />

@endsection
@extends('layouts.app')

@section('title', $proyecto->name . ' - UniversoDev')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Botón para volver arriba -->
    <div class="mb-6">
        <a href="{{ route('proyectos.index') }}" class="inline-flex items-center gap-2 text-universo-text hover:text-universo-primary transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span class="font-medium">Regresar a Proyectos</span>
        </a>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg backdrop-blur-sm">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-500/10 to-rose-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg backdrop-blur-sm">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Header del Proyecto con Gradiente -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-universo-card-bg via-universo-card-bg to-universo-primary/5 border-2 border-universo-border/50 bg-clip-padding backdrop-blur-sm mb-6">
        <!-- Gradiente decorativo -->
        <div class="absolute inset-0 bg-gradient-to-br from-universo-primary/10 via-transparent to-universo-accent/10 opacity-50"></div>

        <div class="relative p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-universo-primary to-universo-accent flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <path d="M2 20h20"></path><path d="M5 20V8l6-6m6 14V4L5 16"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-universo-text">
                            {{ $proyecto->name }}
                        </h1>
                    </div>
                    <div class="flex items-center gap-2 text-universo-text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Creado por <span class="text-universo-primary font-semibold">{{ $proyecto->creador->nombre_completo }}</span></span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>{{ $proyecto->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- Botón de Editar (solo para creador) -->
                @if($isCreador)
                    <a href="{{ route('proyectos.edit', $proyecto) }}" class="px-4 py-2 bg-gradient-to-r from-universo-primary to-universo-accent text-white rounded-lg hover:shadow-lg hover:shadow-universo-primary/50 transition-all flex items-center gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-12 transition-transform">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Editar Proyecto
                    </a>
                @endif
            </div>

            <!-- Descripción -->
            <p class="text-universo-text mb-4 leading-relaxed">{{ $proyecto->descripcion }}</p>

            <!-- Badges con iconos -->
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="px-3 py-1.5 bg-gradient-to-r from-universo-primary/20 to-universo-primary/10 text-universo-primary rounded-lg font-medium flex items-center gap-1.5 border border-universo-primary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline>
                    </svg>
                    {{ $proyecto->lenguaje_principal }}
                </span>
                <span class="px-3 py-1.5 bg-gradient-to-r from-universo-accent/20 to-universo-accent/10 text-universo-accent rounded-lg font-medium flex items-center gap-1.5 border border-universo-accent/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    {{ $proyecto->estado }}
                </span>
                @if($proyecto->tecnologias)
                    @foreach($proyecto->tecnologias as $tech)
                        <span class="px-3 py-1.5 bg-universo-bg-secondary text-universo-text rounded-lg text-sm border border-universo-border hover:border-universo-primary/50 transition-colors">
                            {{ $tech }}
                        </span>
                    @endforeach
                @endif
            </div>

            <!-- Enlaces con gradientes -->
            @if($proyecto->repositorio_url || $proyecto->demo_url || $proyecto->documentacion_url)
                <div class="flex flex-wrap gap-3 pt-4 border-t border-universo-border/50">
                    @if($proyecto->repositorio_url)
                        <a href="{{ $proyecto->repositorio_url }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-gray-600/20 to-gray-700/20 hover:from-gray-600/30 hover:to-gray-700/30 text-universo-text rounded-lg border border-gray-600/30 hover:border-gray-500 transition-all flex items-center gap-2 group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform">
                                <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path>
                            </svg>
                            <span>Repositorio</span>
                        </a>
                    @endif
                    @if($proyecto->demo_url)
                        <a href="{{ $proyecto->demo_url }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-blue-600/20 to-cyan-600/20 hover:from-blue-600/30 hover:to-cyan-600/30 text-blue-400 rounded-lg border border-blue-600/30 hover:border-blue-500 transition-all flex items-center gap-2 group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform">
                                <circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <span>Demo en Vivo</span>
                        </a>
                    @endif
                    @if($proyecto->documentacion_url)
                        <a href="{{ $proyecto->documentacion_url }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-green-600/20 to-emerald-600/20 hover:from-green-600/30 hover:to-emerald-600/30 text-green-400 rounded-lg border border-green-600/30 hover:border-green-500 transition-all flex items-center gap-2 group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            <span>Documentación</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Información del Equipo con Gradiente -->
    @if($proyecto->equipo)
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-universo-card-bg via-universo-card-bg to-universo-accent/5 border-2 border-universo-border/50 backdrop-blur-sm mb-6">
            <div class="absolute inset-0 bg-gradient-to-br from-universo-accent/5 via-transparent to-universo-primary/5 opacity-50"></div>

            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-universo-accent to-universo-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-universo-text">
                        Equipo de Trabajo
                    </h2>
                </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-universo-text mb-2">{{ $proyecto->equipo->name }}</h3>
                @if($proyecto->equipo->descripcion)
                    <p class="text-universo-text-muted mb-3">{{ $proyecto->equipo->descripcion }}</p>
                @endif
            </div>

                <!-- Líder del Equipo -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-universo-text mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-400">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        Líder del Equipo
                    </h4>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-universo-primary/10 to-transparent rounded-lg border border-universo-primary/30 hover:border-universo-primary/50 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-universo-primary to-universo-accent flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ $proyecto->equipo->lider->iniciales }}
                        </div>
                        <div class="flex-1">
                            <p class="text-universo-text font-semibold">{{ $proyecto->equipo->lider->nombre_completo }}</p>
                            <p class="text-sm text-universo-text-muted">{{ $proyecto->equipo->lider->email }}</p>
                        </div>
                        <span class="px-3 py-1 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-500/30">
                            Líder
                        </span>
                    </div>
                </div>

                <!-- Miembros del Equipo -->
                @if($proyecto->equipo->miembros->count() > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-universo-text mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-accent">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Miembros del Equipo ({{ $proyecto->equipo->miembros->count() }})
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($proyecto->equipo->miembros as $miembro)
                                <div class="flex items-center gap-3 p-3 bg-universo-bg-secondary/50 rounded-lg border border-universo-border hover:border-universo-accent/50 transition-colors group">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-universo-bg-secondary to-universo-accent/20 flex items-center justify-center text-universo-accent font-semibold border-2 border-universo-accent/30 group-hover:border-universo-accent transition-colors">
                                        {{ $miembro->iniciales }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-universo-text font-medium">{{ $miembro->nombre_completo }}</p>
                                        <p class="text-sm text-universo-text-muted">{{ $miembro->pivot->rol_equipo ?? 'Miembro' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Sección de Archivos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Columna Izquierda: Archivos Pendientes (solo para líder/creador) -->
        @if($canAccept)
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-universo-card-bg via-universo-card-bg to-yellow-500/5 border-2 border-universo-border/50 backdrop-blur-sm">
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 via-transparent to-orange-500/5 opacity-50"></div>

                <div class="relative p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <circle cx="12" cy="12" r="10"></circle><path d="M12 8v4"></path><path d="M12 16h.01"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-universo-text">
                            Archivos Pendientes de Aprobación
                        </h2>
                    </div>

                    @forelse($pending_files as $file)
                        <div class="mb-3 p-4 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 hover:border-yellow-500/50 rounded-lg transition-all group">
                            <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="text-universo-text font-semibold">{{ $file->filename }}</p>
                                <p class="text-sm text-universo-text-muted">
                                    Subido por: <span class="text-universo-primary">{{ $file->user->nombre_completo }}</span>
                                </p>
                                <p class="text-xs text-universo-text-muted">
                                    {{ $file->created_at->diffForHumans() }} • {{ number_format($file->size / 1024, 2) }} KB
                                </p>
                                @if($file->comentario)
                                    <p class="text-sm text-universo-text mt-2 italic">"{{ $file->comentario }}"</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2 mt-3">
                            <form action="{{ route('proyectos.files.accept', [$proyecto, $file]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Aceptar
                                </button>
                            </form>

                            <form action="{{ route('proyectos.files.reject', [$proyecto, $file]) }}" method="POST" class="inline" onsubmit="return confirm('¿Rechazar este archivo? Se eliminará permanentemente.')">
                                @csrf
                                <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Rechazar
                                </button>
                            </form>

                            <form action="{{ route('proyectos.files.delete', [$proyecto, $file]) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('¿Eliminar este archivo? (Rollback)')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    Rollback
                                </button>
                            </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-universo-text-muted">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-500/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500/50">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                            </div>
                            <p class="font-medium">No hay archivos pendientes de aprobación</p>
                            <p class="text-sm mt-1">Los archivos aparecerán aquí cuando los miembros suban contenido</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Columna Derecha: Archivos Aceptados (todos pueden ver) -->
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-universo-card-bg via-universo-card-bg to-green-500/5 border-2 border-universo-border/50 backdrop-blur-sm {{ !$canAccept ? 'lg:col-span-2' : '' }}">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 via-transparent to-emerald-500/5 opacity-50"></div>

            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-universo-text">
                        Archivos del Proyecto
                    </h2>
                </div>

                @forelse($accepted_files as $file)
                    <div class="mb-3 p-4 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/30 hover:border-green-500/50 rounded-lg transition-all group">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-universo-text font-semibold">{{ $file->filename }}</p>
                            <p class="text-sm text-universo-text-muted">
                                Subido por: <span class="text-universo-primary">{{ $file->user->nombre_completo }}</span>
                            </p>
                            <p class="text-xs text-universo-text-muted">
                                {{ $file->created_at->diffForHumans() }} • {{ number_format($file->size / 1024, 2) }} KB
                            </p>
                            @if($file->comentario)
                                <p class="text-sm text-universo-text mt-2 italic">"{{ $file->comentario }}"</p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('proyectos.files.download', [$proyecto->id, $file->id]) }}"
                                class="px-4 py-2 bg-gradient-to-r from-blue-600/20 to-cyan-600/20 hover:from-blue-600/30 hover:to-cyan-600/30 text-blue-400 rounded-lg border border-blue-600/30 hover:border-blue-500 transition-all flex items-center gap-2 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v10"></path>
                                        <path d="M5 12l7 7 7-7"></path>
                                        <path d="M5 19h14"></path>
                                    </svg>
                                    Descargar
                            </a>

                            @if($canAccept)
                                <form action="{{ route('proyectos.files.delete', [$proyecto, $file]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este archivo? (Rollback)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Rollback
                                    </button>
                                </form>
                            @endif
                        </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-universo-text-muted">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-500/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-green-500/50">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                        </div>
                        <p class="font-medium">Aún no hay archivos en este proyecto</p>
                        <p class="text-sm mt-1">Sé el primero en contribuir subiendo un archivo</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Formulario para Subir Archivos (solo para miembros del equipo) -->
    @if($isMember && !$canAccept)
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-universo-card-bg via-universo-card-bg to-blue-500/5 border-2 border-universo-border/50 backdrop-blur-sm mt-6">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-transparent to-cyan-500/5 opacity-50"></div>

            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-universo-text">
                        Subir Archivo
                    </h2>
                </div>

                <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    Los archivos que subas deberán ser aprobados por el líder del equipo antes de ser visibles.
                </p>
            </div>

            <form action="{{ route('proyectos.files.upload', $proyecto) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="file" class="block text-sm font-medium text-universo-text mb-2">Seleccionar Archivo *</label>
                    <input type="file" name="file" id="file" required class="input-field">
                    <p class="mt-1 text-xs text-universo-text-muted">Tamaño máximo: 10 MB</p>
                    @error('file')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="comentario" class="block text-sm font-medium text-universo-text mb-2">Comentario (opcional)</label>
                    <textarea name="comentario" id="comentario" rows="3" class="input-field" placeholder="Describe brevemente los cambios o el propósito de este archivo..."></textarea>
                    @error('comentario')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:shadow-lg hover:shadow-blue-500/50 transition-all flex items-center gap-2 group font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-y-1 transition-transform">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Subir Archivo
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

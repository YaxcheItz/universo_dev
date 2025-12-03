@extends('layouts.app')

@section('title', 'Perfil - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Main User Info Card con fondo dinámico -->
    <div class="card p-8 relative" style="background: linear-gradient(135deg, {{ $user->profile_bg_color ?? '#1a1a2e' }} 0%, rgba(88, 44, 131, 0.2) 100%);">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            
            <!-- Foto de perfil -->
            <div class="flex-shrink-0">
                @if ($user->avatar)
                    <img 
                        src="{{ asset('storage/' . $user->avatar) }}" 
                        alt="Foto de {{ $user->name }}" 
                        class="w-32 h-32 rounded-full object-cover border-4 border-universo-purple shadow-xl ring-4 ring-universo-purple/20">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white text-4xl font-bold shadow-xl ring-4 ring-universo-purple/20">
                        {{ substr($user->name, 0, 1) }}{{ substr($user->apellido_paterno, 0, 1) }}
                    </div>
                @endif
            </div>

            <!-- Datos del perfil -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-universo-text mb-2">
                    {{ $user->name }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}
                </h1>
                
                <!-- Apodo -->
                <p class="text-universo-text-muted text-lg md:text-xl mb-3 font-medium">
                    @if ($user->nickname)
                        {{ '@' . $user->nickname }}
                    @else
                        {{ '@' . Str::slug($user->name, '_') }}
                    @endif
                </p>
                
                <span class="badge badge-purple text-base px-4 py-1.5">{{ $user->rol }}</span>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 mt-6 text-universo-text-muted">
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-5 h-5 text-universo-purple flex-shrink-0">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        </svg>
                        <span class="break-all">{{ $user->email }}</span>
                    </div>
                    @if ($user->telefono)
                        <div class="flex items-center justify-center md:justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-5 h-5 text-universo-cyan flex-shrink-0">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.12 12.12 0 0 0 .52 2.76 2 2 0 0 1-.42 2.16L6.76 11.48a14 14 0 1 0 6.31 6.31l1.81-1.81a2 2 0 0 1 2.16-.42 12.12 12.12 0 0 0 2.76.52A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span>{{ $user->telefono }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón Editar Perfil -->
        <!-- MODIFICADO: Botón para editar perfil-->
        <!-- Ruta: route('perfil.edit') -> PerfilController@edit() -> GET /perfil/edit -->
        <a href="{{ route('perfil.edit') }}" class="absolute top-6 right-6 btn-secondary btn-sm flex items-center gap-2 hover:scale-105 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Editar Perfil
        </a>
    </div>

    <!-- Tabbed Navigation -->
    <div class="card p-0 overflow-hidden">
        <div class="flex border-b border-universo-border overflow-x-auto">
            <button onclick="switchTab('reconocimientos')" id="tab-btn-reconocimientos" class="tab-button px-6 py-4 text-universo-purple border-b-2 border-universo-purple text-sm font-medium focus:outline-none whitespace-nowrap transition-colors">
                Reconocimientos
            </button>
            <button onclick="switchTab('torneos-participados')" id="tab-btn-torneos-participados" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Torneos Participados
            </button>
            <button onclick="switchTab('torneos-creados')" id="tab-btn-torneos-creados" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Torneos Creados
            </button>
            <button onclick="switchTab('proyectos')" id="tab-btn-proyectos" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Proyectos
            </button>
            <button onclick="switchTab('equipos')" id="tab-btn-equipos" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Equipos
            </button>
            <button onclick="switchTab('estadisticas')" id="tab-btn-estadisticas" class="tab-button px-6 py-4 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition-colors text-sm font-medium focus:outline-none whitespace-nowrap">
                Estadísticas
            </button>
        </div>
        
        <div class="p-6">
            <!-- Tab: Reconocimientos -->
            <div id="tab-content-reconocimientos" class="tab-content">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-universo-warning">
                        <circle cx="12" cy="8" r="6"></circle>
                        <path d="M15.477 12.89 18.87 21l-3.37-.61-.63-3.02M8.523 12.89 5.13 21l3.37-.61.63-3.02"></path>
                    </svg>
                    Reconocimientos Obtenidos
                </h3>
                <p class="text-universo-text-muted mb-6">Logros y badges conseguidos en la plataforma</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Badge 1 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-purple/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-7 h-7 text-universo-purple">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Primera Contribución</h4>
                            <p class="text-universo-text-muted text-sm">Tu primer commit a un proyecto.</p>
                        </div>
                    </div>
                    
                    <!-- Badge 2 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-success/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-7 h-7 text-universo-success">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89 18.87 21l-3.37-.61-.63-3.02M8.523 12.89 5.13 21l3.37-.61.63-3.02"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Team Player</h4>
                            <p class="text-universo-text-muted text-sm">Completaste 3 proyectos en equipo.</p>
                        </div>
                    </div>
                    
                    <!-- Badge 3 -->
                    <div class="card p-4 flex items-center gap-4 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 rounded-full bg-universo-cyan/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-7 h-7 text-universo-cyan">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold text-base">Campeón</h4>
                            <p class="text-universo-text-muted text-sm">Ganaste tu primer torneo.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Torneos Participados -->
            <div id="tab-content-torneos-participados" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2">Torneos Participados</h3>
                <p class="text-universo-text-muted mb-6">Historial de torneos en los que has participado</p>
                <div class="text-center py-12 text-universo-text-muted">
                    <p>Próximamente contenido de torneos participados...</p>
                </div>
            </div>

            <!-- Tab: Torneos Creados -->
            <div id="tab-content-torneos-creados" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2">Torneos Creados</h3>
                <p class="text-universo-text-muted mb-6">Torneos que has organizado</p>
                <div class="text-center py-12 text-universo-text-muted">
                    <p>Próximamente contenido de torneos creados...</p>
                </div>
            </div>

            <!-- Tab: Proyectos -->
            <div id="tab-content-proyectos" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2">Proyectos</h3>
                <p class="text-universo-text-muted mb-6">Tus proyectos y contribuciones</p>
                <div class="text-center py-12 text-universo-text-muted">
                    <p>Próximamente contenido de proyectos...</p>
                </div>
            </div>

            <!-- Tab: Equipos -->
            <div id="tab-content-equipos" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2">Equipos</h3>
                <p class="text-universo-text-muted mb-6">Equipos a los que perteneces</p>
                <div class="text-center py-12 text-universo-text-muted">
                    <p>Próximamente contenido de equipos...</p>
                </div>
            </div>

            <!-- Tab: Estadísticas -->
            <div id="tab-content-estadisticas" class="tab-content hidden">
                <h3 class="text-xl md:text-2xl font-bold text-universo-text mb-2">Estadísticas</h3>
                <p class="text-universo-text-muted mb-6">Tu actividad en la plataforma</p>
                <div class="text-center py-12 text-universo-text-muted">
                    <p>Próximamente estadísticas detalladas...</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function switchTab(tabName) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Resetear todos los botones
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('text-universo-purple', 'border-universo-purple');
        button.classList.add('text-universo-text-muted', 'border-transparent');
    });
    
    // Mostrar contenido seleccionado
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Activar botón seleccionado
    const activeButton = document.getElementById('tab-btn-' + tabName);
    activeButton.classList.remove('text-universo-text-muted', 'border-transparent');
    activeButton.classList.add('text-universo-purple', 'border-universo-purple');
}
</script>
@endsection
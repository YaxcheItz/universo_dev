@extends('layouts.app')

@section('title', 'Asignar Jueces a Torneos - UniversoDev')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.index') }}" class="text-universo-purple hover:text-universo-purple/80 flex items-center gap-2 mb-4 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Volver a Administración
            </a>
            <h1 class="text-3xl font-bold text-white">Asignar Jueces a Torneos</h1>
            <p class="text-universo-text-muted mt-2">Gestiona qué jueces evaluarán cada torneo</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-universo-text-muted text-sm">Total Torneos</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $torneos->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-universo-purple/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-purple">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-universo-text-muted text-sm">Jueces Disponibles</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $jueces->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-universo-cyan/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-cyan">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-universo-text-muted text-sm">Asignaciones Totales</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $torneos->sum(fn($t) => $t->jueces->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-400">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($jueces->isEmpty())
        <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-300 px-6 py-4 rounded-lg">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                </svg>
                <div>
                    <p class="font-medium">No hay jueces disponibles</p>
                    <p class="text-sm mt-1">Primero debes <a href="{{ route('admin.crear-juez') }}" class="underline hover:text-yellow-200">crear jueces</a> para poder asignarlos a torneos.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de Torneos -->
    <div class="space-y-4">
        @forelse($torneos as $torneo)
            <div class="bg-universo-secondary border border-universo-border rounded-lg overflow-hidden">
                <!-- Header del Torneo -->
                <div class="p-6 border-b border-universo-border">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-white">{{ $torneo->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($torneo->estado === 'Inscripciones Abiertas') bg-green-500/20 text-green-300
                                    @elseif($torneo->estado === 'En Curso') bg-blue-500/20 text-blue-300
                                    @elseif($torneo->estado === 'Evaluación') bg-purple-500/20 text-purple-300
                                    @elseif($torneo->estado === 'Finalizado') bg-gray-500/20 text-gray-300
                                    @else bg-yellow-500/20 text-yellow-300
                                    @endif">
                                    {{ $torneo->estado }}
                                </span>
                            </div>
                            <p class="text-universo-text-muted text-sm">
                                Organizado por: {{ $torneo->organizador->name }}
                            </p>
                            <p class="text-universo-text text-sm mt-1">
                                📅 {{ $torneo->fecha_inicio->format('d/m/Y') }} - {{ $torneo->fecha_fin->format('d/m/Y') }}
                            </p>
                        </div>

                        <button 
                            onclick="toggleAsignacion({{ $torneo->id }})"
                            class="px-4 py-2 bg-universo-cyan hover:bg-universo-cyan/80 text-white rounded-lg transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <line x1="19" x2="19" y1="8" y2="14"></line>
                                <line x1="22" x2="16" y1="11" y2="11"></line>
                            </svg>
                            Asignar Jueces
                        </button>
                    </div>

                    <!-- Jueces Asignados -->
                    @if($torneo->jueces->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-universo-border">
                            <p class="text-sm text-universo-text-muted mb-3">Jueces asignados ({{ $torneo->jueces->count() }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($torneo->jueces as $juez)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-universo-primary border border-universo-border rounded-lg">
                                        @if($juez->avatar)
                                            <img src="{{ asset('storage/' . $juez->avatar) }}" alt="{{ $juez->name }}" class="w-6 h-6 rounded-full object-cover">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-universo-cyan to-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($juez->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="text-sm text-white">{{ $juez->name }}</span>
                                        <form method="POST" action="{{ route('admin.remover-juez-torneo') }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                                            <input type="hidden" name="juez_id" value="{{ $juez->id }}">
                                            <button type="submit" onclick="return confirm('¿Remover este juez del torneo?')" class="text-red-400 hover:text-red-300 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="18" x2="6" y1="6" y2="18"></line>
                                                    <line x1="6" x2="18" y1="6" y2="18"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-4 pt-4 border-t border-universo-border">
                            <p class="text-sm text-universo-text-muted italic">Sin jueces asignados</p>
                        </div>
                    @endif
                </div>

                <!-- Formulario de Asignación (oculto por defecto) -->
                <div id="asignacion-{{ $torneo->id }}" class="p-6 bg-universo-primary hidden">
                    <form method="POST" action="{{ route('admin.store-asignacion-jueces') }}">
                        @csrf
                        <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                        
                        <label class="block text-sm font-medium text-universo-text mb-3">
                            Busca y selecciona los jueces para este torneo:
                        </label>

                        @if($jueces->isEmpty())
                            <p class="text-universo-text-muted text-sm">No hay jueces disponibles para asignar.</p>
                        @else
                            <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-300 px-4 py-3 rounded-lg mb-4 text-sm">
                                <strong>Nota:</strong> Los cambios reemplazarán completamente la asignación actual. Desmarca todos para eliminar todos los jueces.
                            </div>

                            <!-- Buscador -->
                            <div class="mb-4">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-universo-text-muted">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                    <input
                                        type="text"
                                        id="buscarJuez-{{ $torneo->id }}"
                                        placeholder="Buscar juez por nombre o email..."
                                        class="w-full pl-10 pr-4 py-2 bg-universo-primary border border-universo-border rounded-lg text-white placeholder-universo-text-muted focus:border-universo-cyan focus:outline-none [color-scheme:dark]"
                                        onkeyup="filtrarJueces({{ $torneo->id }})">
                                </div>
                            </div>

                            <div id="listaJueces-{{ $torneo->id }}" class="space-y-2 mb-4 max-h-60 overflow-y-auto">
                                @foreach($jueces as $juez)
                                    <label class="juez-item flex items-center gap-3 p-3 bg-universo-secondary border border-universo-border rounded-lg hover:bg-universo-secondary/80 cursor-pointer transition" data-nombre="{{ strtolower($juez->name) }}" data-email="{{ strtolower($juez->email) }}">
                                        <input
                                            type="checkbox"
                                            name="jueces[]"
                                            value="{{ $juez->id }}"
                                            {{ $torneo->jueces->contains($juez->id) ? 'checked' : '' }}
                                            class="w-4 h-4 text-universo-cyan bg-universo-primary border-universo-border rounded focus:ring-2 focus:ring-universo-cyan">

                                        <div class="flex items-center gap-2 flex-1">
                                            @if($juez->avatar)
                                                <img src="{{ asset('storage/' . $juez->avatar) }}" alt="{{ $juez->name }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-universo-cyan to-blue-500 flex items-center justify-center text-white font-bold">
                                                    {{ substr($juez->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-white font-medium">{{ $juez->name }}</p>
                                                <p class="text-universo-text-muted text-xs">{{ $juez->email }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-universo-cyan hover:bg-universo-cyan/80 text-white rounded-lg transition">
                                    Guardar Asignación
                                </button>
                                <button
                                    type="button"
                                    onclick="toggleAsignacion({{ $torneo->id }})"
                                    class="px-6 py-2 border border-universo-border text-universo-text hover:bg-universo-secondary rounded-lg transition">
                                    Cancelar
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-universo-secondary border border-universo-border rounded-lg p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-universo-text-muted mb-4">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                    <path d="M4 22h16"></path>
                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                </svg>
                <p class="text-universo-text-muted">No hay torneos disponibles</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function toggleAsignacion(torneoId) {
    const elemento = document.getElementById('asignacion-' + torneoId);
    elemento.classList.toggle('hidden');
}

function filtrarJueces(torneoId) {
    const buscador = document.getElementById('buscarJuez-' + torneoId);
    const lista = document.getElementById('listaJueces-' + torneoId);
    const textoBusqueda = buscador.value.toLowerCase();
    const items = lista.querySelectorAll('.juez-item');

    items.forEach(item => {
        const nombre = item.getAttribute('data-nombre');
        const email = item.getAttribute('data-email');

        if (nombre.includes(textoBusqueda) || email.includes(textoBusqueda)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection
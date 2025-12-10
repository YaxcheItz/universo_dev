@extends('layouts.app')

@section('title', 'Panel de Administración - UniversoDev')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Panel de Administración</h1>
            <p class="text-universo-text-muted mt-2">Gestiona usuarios y jueces de la plataforma</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-universo-text-muted text-sm">Total Usuarios</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalUsuarios }}</p>
                </div>
                <div class="w-12 h-12 bg-universo-purple/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-purple">
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
                    <p class="text-universo-text-muted text-sm">Total Jueces</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalJueces }}</p>
                </div>
                <div class="w-12 h-12 bg-universo-cyan/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-cyan">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="w-full">
                    <p class="text-universo-text-muted text-sm mb-3">Acciones Rápidas</p>
                    <div class="flex flex-col gap-2">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.crear-usuario') }}" class="flex-1 px-3 py-1.5 bg-universo-purple hover:bg-universo-purple/80 text-white text-sm rounded-lg transition text-center">
                                + Usuario
                            </a>
                            <a href="{{ route('admin.crear-juez') }}" class="flex-1 px-3 py-1.5 bg-universo-cyan hover:bg-universo-cyan/80 text-white text-sm rounded-lg transition text-center">
                                + Juez
                            </a>
                        </div>
                        <a href="{{ route('admin.asignar-jueces') }}" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition text-center flex items-center justify-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                            Asignar Jueces
                        </a>
                        <a href="{{ route('admin.reportes') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition text-center flex items-center justify-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg overflow-hidden">
        <div class="border-b border-universo-border">
            <div class="flex">
                <button onclick="cambiarTab('usuarios')" id="tab-usuarios" class="px-6 py-4 text-white border-b-2 border-universo-purple font-medium">
                    Todos los Usuarios
                </button>
                <button onclick="cambiarTab('jueces')" id="tab-jueces" class="px-6 py-4 text-universo-text-muted hover:text-white border-b-2 border-transparent font-medium transition">
                    Jueces
                </button>
            </div>
        </div>

        <!-- Contenido Tab Usuarios -->
        <div id="content-usuarios" class="p-6">
            <!-- Barra de búsqueda -->
            <div class="mb-8">
                <form action="{{ route('admin.index') }}" method="GET" class="flex gap-4">
                    <input type="hidden" name="tab" value="usuarios">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="busqueda_usuarios"
                            placeholder="Buscar por nombre o email..."
                            class="w-full pl-10 px-4 py-2.5 bg-black/50 border-2 border-universo-purple rounded-lg text-white placeholder-universo-text-muted focus:outline-none focus:ring-2 focus:ring-universo-purple focus:border-universo-purple"
                            value="{{ request('busqueda_usuarios') ?? '' }}"
                        >
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-universo-purple hover:bg-universo-purple/80 text-white rounded-lg transition font-medium">
                        Buscar
                    </button>
                    @if(request('busqueda_usuarios'))
                        <a href="{{ route('admin.index') }}?tab=usuarios" class="px-6 py-2.5 bg-universo-secondary border border-universo-border hover:bg-universo-primary text-white rounded-lg transition font-medium">
                            Limpiar
                        </a>
                    @endif
                </form>

                @if(request('busqueda_usuarios'))
                    <p class="text-universo-text-muted mt-2">
                        Resultados para: <span class="text-universo-text font-semibold">"{{ request('busqueda_usuarios') }}"</span>
                        ({{ $usuarios->total() }} {{ $usuarios->total() === 1 ? 'resultado' : 'resultados' }})
                    </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-universo-border">
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Usuario</th>
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Email</th>
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Rol</th>
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha de Registro</th>
                            <th class="text-right py-3 px-4 text-universo-text-muted font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr class="border-b border-universo-border hover:bg-universo-primary/50 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @if($usuario->avatar)
                                        <img src="{{ asset('storage/' . $usuario->avatar) }}" alt="{{ $usuario->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white font-bold">
                                            {{ substr($usuario->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-white font-medium">{{ $usuario->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-universo-text">{{ $usuario->email }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $usuario->rol === 'Juez' ? 'bg-universo-cyan/20 text-universo-cyan' : 'bg-universo-purple/20 text-universo-purple' }}">
                                    {{ $usuario->rol }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-universo-text">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-4 text-right">
                                <form method="POST" action="{{ route('admin.eliminar-usuario', $usuario->id) }}" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-universo-text-muted">
                                No hay usuarios registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $usuarios->links() }}
            </div>
        </div>

        <!-- Contenido Tab Jueces -->
        <div id="content-jueces" class="p-6 hidden">
            <!-- Barra de búsqueda -->
            <div class="mb-8">
                <form action="{{ route('admin.index') }}" method="GET" class="flex gap-4">
                    <input type="hidden" name="tab" value="jueces">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="busqueda"
                            placeholder="Buscar por nombre o email..."
                            class="w-full pl-10 px-4 py-2.5 bg-black/50 border-2 border-universo-purple rounded-lg text-white placeholder-universo-text-muted focus:outline-none focus:ring-2 focus:ring-universo-purple focus:border-universo-purple"
                            value="{{ $busqueda ?? '' }}"
                        >
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-universo-purple hover:bg-universo-purple/80 text-white rounded-lg transition font-medium">
                        Buscar
                    </button>
                    @if($busqueda)
                        <a href="{{ route('admin.index') }}?tab=jueces" class="px-6 py-2.5 bg-universo-secondary border border-universo-border hover:bg-universo-primary text-white rounded-lg transition font-medium">
                            Limpiar
                        </a>
                    @endif
                </form>

                @if($busqueda)
                    <p class="text-universo-text-muted mt-2">
                        Resultados para: <span class="text-universo-text font-semibold">"{{ $busqueda }}"</span>
                        ({{ $jueces->count() }} {{ $jueces->count() === 1 ? 'resultado' : 'resultados' }})
                    </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-universo-border">
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Juez</th>
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Email</th>
                            <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha de Registro</th>
                            <th class="text-right py-3 px-4 text-universo-text-muted font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jueces as $juez)
                        <tr class="border-b border-universo-border hover:bg-universo-primary/50 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @if($juez->avatar)
                                        <img src="{{ asset('storage/' . $juez->avatar) }}" alt="{{ $juez->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-universo-cyan to-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($juez->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-white font-medium">{{ $juez->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-universo-text">{{ $juez->email }}</td>
                            <td class="py-4 px-4 text-universo-text">{{ $juez->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-4 text-right">
                                <form method="POST" action="{{ route('admin.eliminar-usuario', $juez->id) }}" onsubmit="return confirm('¿Estás seguro de eliminar este juez?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-universo-text-muted">
                                No hay jueces registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cambiarTab(tab) {
    // Ocultar todos los contenidos
    document.getElementById('content-usuarios').classList.add('hidden');
    document.getElementById('content-jueces').classList.add('hidden');

    // Resetear estilos de tabs
    document.getElementById('tab-usuarios').classList.remove('text-white', 'border-universo-purple');
    document.getElementById('tab-usuarios').classList.add('text-universo-text-muted', 'border-transparent');
    document.getElementById('tab-jueces').classList.remove('text-white', 'border-universo-purple');
    document.getElementById('tab-jueces').classList.add('text-universo-text-muted', 'border-transparent');

    // Mostrar contenido seleccionado
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.remove('text-universo-text-muted', 'border-transparent');
    document.getElementById('tab-' + tab).classList.add('text-white', 'border-universo-purple');
}

// Si hay una búsqueda activa o parámetro tab, mostrar la pestaña correspondiente
document.addEventListener('DOMContentLoaded', function() {
    const busqueda = '{{ $busqueda ?? '' }}';
    const tab = '{{ request("tab") ?? "" }}';
    const busquedaUsuarios = '{{ request("busqueda_usuarios") ?? "" }}';

    if (busqueda || tab === 'jueces') {
        cambiarTab('jueces');
    } else if (busquedaUsuarios || tab === 'usuarios') {
        cambiarTab('usuarios');
    }
});
</script>
@endpush
@endsection
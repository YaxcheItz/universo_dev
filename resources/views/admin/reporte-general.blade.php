@extends('layouts.app')

@section('title', 'Reporte General - Administración')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Reporte General del Sistema</h1>
            <p class="text-universo-text-muted mt-2">Estadísticas completas de la plataforma</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-universo-purple hover:bg-universo-purple/80 text-white rounded-lg transition">
                Imprimir / PDF
            </button>
            <a href="{{ route('admin.reportes') }}" class="px-4 py-2 bg-universo-secondary hover:bg-universo-border text-white rounded-lg transition border border-universo-border">
                Volver
            </a>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Usuarios</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalUsuarios }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Jueces</p>
            <p class="text-3xl font-bold text-universo-cyan mt-2">{{ $totalJueces }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Torneos</p>
            <p class="text-3xl font-bold text-green-500 mt-2">{{ $totalTorneos }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Torneos Activos</p>
            <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $torneosActivos }}</p>
        </div>
    </div>

    <!-- Usuarios por Rol -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Usuarios por Rol</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Rol</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Total</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuariosPorRol as $rol)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $rol->rol }}</td>
                        <td class="py-3 px-4 text-white">{{ $rol->total }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">
                            {{ number_format(($rol->total / $totalUsuarios) * 100, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Torneos por Estado -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Torneos por Estado</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Estado</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Total</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($torneosPorEstado as $estado)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($estado->estado == 'Inscripciones Abiertas') bg-green-500/20 text-green-500
                                @elseif($estado->estado == 'En Curso') bg-yellow-500/20 text-yellow-500
                                @elseif($estado->estado == 'Finalizado') bg-gray-500/20 text-gray-400
                                @elseif($estado->estado == 'Evaluación') bg-blue-500/20 text-blue-500
                                @else bg-purple-500/20 text-purple-500
                                @endif">
                                {{ $estado->estado }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-white">{{ $estado->total }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">
                            {{ number_format(($estado->total / $totalTorneos) * 100, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Usuarios -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Top 10 Usuarios con Más Puntos</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Posición</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Nombre</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Rol</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Puntos</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Torneos Ganados</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Proyectos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsuarios as $index => $usuario)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full
                                @if($index == 0) bg-yellow-500/20 text-yellow-500
                                @elseif($index == 1) bg-gray-400/20 text-gray-400
                                @elseif($index == 2) bg-orange-500/20 text-orange-500
                                @else bg-universo-border text-universo-text-muted
                                @endif font-bold text-sm">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-white">{{ $usuario->name }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $usuario->rol }}</td>
                        <td class="py-3 px-4 text-universo-purple font-semibold">{{ number_format($usuario->puntos_total) }}</td>
                        <td class="py-3 px-4 text-white">{{ $usuario->torneos_ganados }}</td>
                        <td class="py-3 px-4 text-white">{{ $usuario->proyectos_completados }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estadísticas Adicionales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Participaciones</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalParticipaciones }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Evaluaciones</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalEvaluaciones }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Evaluaciones Pendientes</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $evaluacionesPendientes }}</p>
        </div>
    </div>

    <!-- Torneos Recientes -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Torneos Recientes (Últimos 10)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Nombre</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Organizador</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Estado</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Participantes</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha Creación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($torneosRecientes as $torneo)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $torneo->name }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $torneo->organizador->name }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($torneo->estado == 'Inscripciones Abiertas') bg-green-500/20 text-green-500
                                @elseif($torneo->estado == 'En Curso') bg-yellow-500/20 text-yellow-500
                                @elseif($torneo->estado == 'Finalizado') bg-gray-500/20 text-gray-400
                                @elseif($torneo->estado == 'Evaluación') bg-blue-500/20 text-blue-500
                                @else bg-purple-500/20 text-purple-500
                                @endif">
                                {{ $torneo->estado }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-white">{{ $torneo->participantes_actuales }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $torneo->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    button, a {
        display: none !important;
    }
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Reporte de Torneos - Administración')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Reporte de Torneos</h1>
            <p class="text-universo-text-muted mt-2">Estadísticas y análisis de torneos</p>
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

    <!-- Filtros -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Filtros</h3>
        <form method="GET" action="{{ route('admin.reportes.torneos') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Estado</label>
                <select name="estado" class="w-full px-3 py-2 bg-universo-primary border border-universo-border rounded-lg text-white focus:border-universo-purple focus:outline-none">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Categoría</label>
                <select name="categoria" class="w-full px-3 py-2 bg-universo-primary border border-universo-border rounded-lg text-white focus:border-universo-purple focus:outline-none">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Fecha Inicio Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full px-3 py-2 bg-universo-primary border border-universo-border rounded-lg text-white focus:border-universo-purple focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Fecha Fin Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full px-3 py-2 bg-universo-primary border border-universo-border rounded-lg text-white focus:border-universo-purple focus:outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-universo-purple hover:bg-universo-purple/80 text-white rounded-lg transition">
                    Filtrar
                </button>
                <a href="{{ route('admin.reportes.torneos') }}" class="px-4 py-2 bg-universo-secondary hover:bg-universo-border text-white rounded-lg transition border border-universo-border">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Torneos</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalTorneos }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Participantes</p>
            <p class="text-3xl font-bold text-green-500 mt-2">{{ $totalParticipantes }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Promedio Participantes</p>
            <p class="text-3xl font-bold text-universo-cyan mt-2">{{ number_format($promedioParticipantes, 1) }}</p>
        </div>
    </div>

    <!-- Tabla de Torneos -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Lista de Torneos ({{ $totalTorneos }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Nombre</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Organizador</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Categoría</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Estado</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Nivel</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Participantes</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha Inicio</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha Fin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($torneos as $torneo)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $torneo->name }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $torneo->organizador->name }}</td>
                        <td class="py-3 px-4 text-white">{{ $torneo->categoria }}</td>
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
                        <td class="py-3 px-4 text-universo-text-muted">{{ $torneo->nivel_dificultad }}</td>
                        <td class="py-3 px-4 text-white font-semibold">
                            {{ $torneo->participantes_actuales }}
                            @if($torneo->max_participantes)
                            / {{ $torneo->max_participantes }}
                            @endif
                        </td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ \Carbon\Carbon::parse($torneo->fecha_fin)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    button, a, form {
        display: none !important;
    }
}
</style>
@endsection

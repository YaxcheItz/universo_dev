@extends('layouts.app')

@section('title', 'Reporte de Evaluaciones - Administración')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Reporte de Evaluaciones</h1>
            <p class="text-universo-text-muted mt-2">Análisis de evaluaciones y calificaciones</p>
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
        <form method="GET" action="{{ route('admin.reportes.evaluaciones') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Torneo</label>
                <select name="torneo_id" class="input-field">
                    <option value="">Todos</option>
                    @foreach($torneos as $torneo)
                    <option value="{{ $torneo->id }}" {{ request('torneo_id') == $torneo->id ? 'selected' : '' }}>{{ $torneo->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Juez</label>
                <select name="juez_id" class="input-field">
                    <option value="">Todos</option>
                    @foreach($jueces as $juez)
                    <option value="{{ $juez->id }}" {{ request('juez_id') == $juez->id ? 'selected' : '' }}>{{ $juez->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Fecha Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="input-field">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 btn-primary">
                    Filtrar
                </button>
                <a href="{{ route('admin.reportes.evaluaciones') }}" class="px-4 py-2.5 bg-universo-secondary border border-universo-border hover:bg-universo-primary text-white rounded-lg transition font-medium">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Evaluaciones</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalEvaluaciones }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Promedio Puntaje</p>
            <p class="text-3xl font-bold text-universo-purple mt-2">{{ number_format($promedioPuntaje, 1) }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Puntaje Máximo</p>
            <p class="text-3xl font-bold text-green-500 mt-2">{{ number_format($puntajeMaximo, 1) }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Puntaje Mínimo</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ number_format($puntajeMinimo, 1) }}</p>
        </div>
    </div>

    <!-- Evaluaciones por Juez -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Evaluaciones por Juez</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Juez</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Total Evaluaciones</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluacionesPorJuez as $item)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $item->juez->name }}</td>
                        <td class="py-3 px-4 text-white font-semibold">{{ $item->total }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">
                            {{ $totalEvaluaciones > 0 ? number_format(($item->total / $totalEvaluaciones) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Evaluaciones -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Lista de Evaluaciones ({{ $totalEvaluaciones }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Torneo</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Equipo</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Juez</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Puntaje Total</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Comentarios</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluaciones as $evaluacion)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $evaluacion->torneoParticipacion->torneo->name }}</td>
                        <td class="py-3 px-4 text-white">{{ $evaluacion->torneoParticipacion->equipo->name }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $evaluacion->juez->name }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                @if($evaluacion->puntaje_total >= 90) bg-green-500/20 text-green-500
                                @elseif($evaluacion->puntaje_total >= 70) bg-blue-500/20 text-blue-500
                                @elseif($evaluacion->puntaje_total >= 50) bg-yellow-500/20 text-yellow-500
                                @else bg-red-500/20 text-red-500
                                @endif">
                                {{ number_format($evaluacion->puntaje_total, 1) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-universo-text-muted">
                            @if($evaluacion->comentarios)
                            {{ Str::limit($evaluacion->comentarios, 50) }}
                            @else
                            <span class="text-gray-500">Sin comentarios</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $evaluacion->created_at->format('d/m/Y H:i') }}</td>
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

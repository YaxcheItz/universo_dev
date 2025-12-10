@extends('layouts.app')

@section('title', 'Reporte de Usuarios - Administración')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Reporte de Usuarios</h1>
            <p class="text-universo-text-muted mt-2">Análisis detallado de usuarios registrados</p>
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
        <form method="GET" action="{{ route('admin.reportes.usuarios') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Rol</label>
                <select name="rol" class="input-field">
                    <option value="">Todos</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol }}" {{ request('rol') == $rol ? 'selected' : '' }}>{{ $rol }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-universo-text-muted mb-2">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="input-field">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 btn-primary">
                    Filtrar
                </button>
                <a href="{{ route('admin.reportes.usuarios') }}" class="px-4 py-2.5 bg-universo-secondary border border-universo-border hover:bg-universo-primary text-white rounded-lg transition font-medium">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Usuarios</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalUsuarios }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Total Puntos</p>
            <p class="text-3xl font-bold text-universo-purple mt-2">{{ number_format($totalPuntos) }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Promedio Puntos</p>
            <p class="text-3xl font-bold text-universo-cyan mt-2">{{ number_format($promedioPuntos, 0) }}</p>
        </div>
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
            <p class="text-universo-text-muted text-sm">Torneos Ganados</p>
            <p class="text-3xl font-bold text-green-500 mt-2">{{ $totalTorneosGanados }}</p>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-universo-secondary border border-universo-border rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Lista de Usuarios ({{ $totalUsuarios }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-universo-border">
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Nombre</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Rol</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Puntos</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Torneos Ganados</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Proyectos</th>
                        <th class="text-left py-3 px-4 text-universo-text-muted font-medium">Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr class="border-b border-universo-border/50">
                        <td class="py-3 px-4 text-white">{{ $usuario->name }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $usuario->email }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-universo-border text-white">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-universo-purple font-semibold">{{ number_format($usuario->puntos_total) }}</td>
                        <td class="py-3 px-4 text-white">{{ $usuario->torneos_ganados }}</td>
                        <td class="py-3 px-4 text-white">{{ $usuario->proyectos_completados }}</td>
                        <td class="py-3 px-4 text-universo-text-muted">{{ $usuario->created_at->format('d/m/Y') }}</td>
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

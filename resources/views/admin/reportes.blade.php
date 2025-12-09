@extends('layouts.app')

@section('title', 'Reportes - Administración')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Reportes del Sistema</h1>
            <p class="text-universo-text-muted mt-2">Genera y visualiza reportes estadísticos de la plataforma</p>
        </div>
        <a href="{{ route('admin.index') }}" class="px-4 py-2 bg-universo-secondary hover:bg-universo-border text-white rounded-lg transition border border-universo-border">
            Volver al Panel
        </a>
    </div>

    <!-- Grid de Tarjetas de Reportes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Reporte General -->
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6 hover:border-universo-purple transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-universo-purple/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-purple">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Reporte General</h3>
            <p class="text-universo-text-muted mb-4">Estadísticas completas del sistema, usuarios, torneos y evaluaciones</p>
            <a href="{{ route('admin.reportes.general') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-universo-purple hover:bg-universo-purple/80 text-white rounded-lg transition">
                Ver Reporte
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>

        <!-- Reporte de Usuarios -->
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6 hover:border-universo-cyan transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-universo-cyan/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-universo-cyan">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Reporte de Usuarios</h3>
            <p class="text-universo-text-muted mb-4">Análisis detallado de usuarios registrados, roles y actividad</p>
            <a href="{{ route('admin.reportes.usuarios') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-universo-cyan hover:bg-universo-cyan/80 text-white rounded-lg transition">
                Ver Reporte
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>

        <!-- Reporte de Torneos -->
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6 hover:border-green-500 transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Reporte de Torneos</h3>
            <p class="text-universo-text-muted mb-4">Estadísticas de torneos, participaciones y estados</p>
            <a href="{{ route('admin.reportes.torneos') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                Ver Reporte
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>

        <!-- Reporte de Evaluaciones -->
        <div class="bg-universo-secondary border border-universo-border rounded-lg p-6 hover:border-yellow-500 transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-500">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Reporte de Evaluaciones</h3>
            <p class="text-universo-text-muted mb-4">Análisis de evaluaciones realizadas por jueces y calificaciones</p>
            <a href="{{ route('admin.reportes.evaluaciones') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                Ver Reporte
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

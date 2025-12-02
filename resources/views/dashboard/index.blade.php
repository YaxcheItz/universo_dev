@extends('layouts.app')

@section('title', 'Dashboard - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">
            ¡Bienvenido, {{ auth()->user()->name }}!
        </h1>
        <p class="text-universo-text-muted">
            Aquí está un resumen de la actividad en UniversoDev.
        </p>
    </div>

    <!-- Statistic Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card title="Proyectos Totales" value="{{ $estadisticas['proyectos_totales'] }}" iconColor="text-universo-purple">
            <x-slot name="icon">
                <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"></path>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Torneos Totales" value="{{ $estadisticas['torneos_totales'] }}" iconColor="text-universo-warning">
            <x-slot name="icon">
                <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Equipos Totales" value="{{ $estadisticas['equipos_totales'] }}" iconColor="text-universo-success">
            <x-slot name="icon">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Usuarios Totales" value="{{ $estadisticas['usuarios_totales'] }}" iconColor="text-pink-500">
            <x-slot name="icon">
                <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
            </x-slot>
        </x-stat-card>
    </div>

    <!-- Torneos Activos Section -->
    <div class="card">
        <h3 class="text-xl font-bold text-universo-text mb-4">Torneos Activos</h3>
        
        @if($torneosActivos->isNotEmpty())
            <div class="mt-4 space-y-3">
                @foreach($torneosActivos as $torneo)
                    <a href="{{ route('torneos.show', $torneo) }}" class="flex items-center space-x-3 p-3 rounded-md hover:bg-universo-dark transition">
                        <div class="w-8 h-8 rounded-full bg-universo-warning/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-universo-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <p class="text-universo-text text-sm font-semibold">{{ $torneo->name }}</p>
                            <p class="text-universo-text-muted text-xs">Finaliza el: {{ $torneo->fecha_fin->format('d/m/Y') }}</p>
                        </div>
                        @php
                            $badgeClass = $torneo->estado == 'Inscripciones Abiertas' ? 'badge-cyan' : 'badge-success';
                        @endphp
                        <span class="badge {{ $badgeClass }} text-xs">{{ $torneo->estado }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-universo-text-muted text-center py-4">No hay torneos activos en este momento.</p>
        @endif
    </div>

</div>
@endsection

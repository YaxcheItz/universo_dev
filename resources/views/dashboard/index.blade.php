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
            Aquí está tu actividad en UniversoDev
        </p>
    </div>

    <!-- Statistic Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card title="Proyectos" value="12" iconColor="text-universo-purple">
            <x-slot name="icon">
                <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"></path>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Torneos Activos" value="3" iconColor="text-universo-warning">
            <x-slot name="icon">
                <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path><path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path><path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Equipos" value="5" iconColor="text-universo-success">
            <x-slot name="icon">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle>
            </x-slot>
        </x-stat-card>
        <x-stat-card title="Publicaciones" value="28" iconColor="text-pink-500"> {{-- Using a direct pink for now --}}
            <x-slot name="icon">
                <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
            </x-slot>
        </x-stat-card>
    </div>

    <!-- Actividad Reciente Section -->
    <div class="card">
        <h3 class="text-xl font-bold text-universo-text mb-4">Actividad Reciente</h3>
        <p class="text-universo-text-muted">Últimas actualizaciones de la comunidad (contenido placeholder)</p>
        {{-- Aquí iría la lógica para mostrar la actividad reciente --}}
        <div class="mt-4 space-y-3">
            <div class="flex items-center space-x-3 p-3 rounded-md hover:bg-universo-dark transition">
                <div class="w-8 h-8 rounded-full bg-universo-purple/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-universo-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-universo-text text-sm">
                    <span class="text-universo-purple">Juan Pérez</span> compartió el proyecto <span class="text-universo-cyan">Mi Primer CRUD</span>
                </p>
                <span class="text-universo-text-muted text-xs ml-auto">hace 5 min</span>
            </div>
            <div class="flex items-center space-x-3 p-3 rounded-md hover:bg-universo-dark transition">
                <div class="w-8 h-8 rounded-full bg-universo-success/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-universo-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-universo-text text-sm">
                    <span class="text-universo-success">María García</span> se unió al equipo <span class="text-universo-purple">DevMasters</span>
                </p>
                <span class="text-universo-text-muted text-xs ml-auto">hace 1 hora</span>
            </div>
        </div>
    </div>

</div>
@endsection

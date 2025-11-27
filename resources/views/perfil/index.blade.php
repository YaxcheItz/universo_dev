@extends('layouts.app')

@section('title', 'Perfil - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Main User Info Card -->
    <div class="card p-8 relative">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white text-4xl font-bold flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}{{ substr(auth()->user()->apellido_paterno, 0, 1) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-universo-text mb-1">{{ auth()->user()->name }} {{ auth()->user()->apellido_paterno }}</h1>
                <p class="text-universo-text-muted text-lg mb-2">@devmaster</p> {{-- Placeholder for username/handle --}}
                <span class="badge badge-purple text-base">{{ auth()->user()->rol }}</span>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mt-4 text-universo-text-muted">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-5 h-5"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-5 h-5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.12 12.12 0 0 0 .52 2.76 2 2 0 0 1-.42 2.16L6.76 11.48a14 14 0 1 0 6.31 6.31l1.81-1.81a2 2 0 0 1 2.16-.42 12.12 12.12 0 0 0 2.76.52A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>{{ auth()->user()->telefono ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 md:col-span-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5"><path d="M12 12a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path><path d="M19 10c0 7-7 12-7 12s-7-5-7-12a7 7 0 0 1 14 0z"></path></svg>
                        <span>{{ auth()->user()->direccion ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('perfil.edit') }}" class="absolute top-6 right-6 btn-secondary btn-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Editar Perfil
        </a>
    </div>

    <!-- Tabbed Navigation -->
    <div class="card p-0">
        <div class="flex border-b border-universo-border overflow-x-auto">
            <button class="px-6 py-3 text-universo-purple border-b-2 border-universo-purple text-sm font-medium focus:outline-none">Reconocimientos</button>
            <button class="px-6 py-3 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition text-sm font-medium focus:outline-none">Torneos Participados</button>
            <button class="px-6 py-3 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition text-sm font-medium focus:outline-none">Torneos Creados</button>
            <button class="px-6 py-3 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition text-sm font-medium focus:outline-none">Proyectos</button>
            <button class="px-6 py-3 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition text-sm font-medium focus:outline-none">Equipos</button>
            <button class="px-6 py-3 text-universo-text-muted hover:text-universo-text border-b-2 border-transparent hover:border-universo-border transition text-sm font-medium focus:outline-none">Estadísticas</button>
        </div>
        <div class="p-6">
            <!-- Content for Reconocimientos Tab -->
            <div id="tab-reconocimientos">
                <h3 class="text-xl font-bold text-universo-text mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-universo-warning"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89C17.152 14.28 18 16.29 18 18c0 1.554-1.127 2.842-2.818 3.064M8.523 12.89C6.848 14.28 6 16.29 6 18c0 1.554 1.127 2.842 2.818 3.064"></path></svg>
                    Reconocimientos Obtenidos
                </h3>
                <p class="text-universo-text-muted mb-6">Logros y badges conseguidos en la plataforma</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Placeholder Recognition Cards --}}
                    <div class="card flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-universo-purple/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-6 h-6 text-universo-purple"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold">Primera Contribución</h4>
                            <p class="text-universo-text-muted text-sm">Tu primer commit a un proyecto.</p>
                        </div>
                    </div>
                    <div class="card flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-universo-success/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-universo-success"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89C17.152 14.28 18 16.29 18 18c0 1.554-1.127 2.842-2.818 3.064M8.523 12.89C6.848 14.28 6 16.29 6 18c0 1.554 1.127 2.842 2.818 3.064"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-universo-text font-semibold">Team Player</h4>
                            <p class="text-universo-text-muted text-sm">Completaste 3 proyectos en equipo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

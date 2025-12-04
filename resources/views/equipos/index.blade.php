@extends('layouts.app')

@section('title', 'Equipos - UniversoDev')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-universo-text mb-2">Equipos</h1>
            <p class="text-universo-text-muted">Colabora con otros desarrolladores</p>
        </div>
        <a href="{{ route('equipos.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Crear Equipo
        </a>
    </div>

    {{-- Solicitudes Pendientes --}}
    @if($solicitudesPendientes->count() > 0)
        <div class="card bg-universo-warning/10 border-universo-warning/20 mb-6">
            <h2 class="text-universo-text font-semibold mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                Solicitudes Pendientes ({{ $solicitudesPendientes->count() }})
            </h2>
            <div class="space-y-2">
                @foreach($solicitudesPendientes as $solicitud)
                <div class="flex justify-between items-center p-3 bg-universo-dark/10 rounded">
                    <div>
                        <p class="text-sm text-universo-text">
                            <strong>{{ $solicitud->user->nombre_completo }}</strong> desea unirse a 
                            <strong class="text-universo-accent">{{ $solicitud->equipo->name }}</strong> 
                            como <em class="text-universo-text-muted">{{ $solicitud->rol_equipo }}</em>
                        </p>
                    </div>
                    <div class="flex gap-2">
                       <form action="{{ route('equipos.solicitudes.aceptar', $solicitud) }}" method="POST">
                            @csrf
                            <input type="hidden" name="accion" value="Aceptar">
                            <button type="submit" class="btn-success btn-sm">Aceptar</button>
                        </form>

                        <form action="{{ route('equipos.solicitudes.rechazar', $solicitud) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="accion" value="Rechazar">
                            <button type="submit" class="btn-danger btn-sm">Rechazar</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ========== MIS EQUIPOS (LÍDER) ========== --}}
    @if($misEquiposLider->count() > 0)
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-secondary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h2 class="text-2xl font-bold text-universo-text">Equipos que Lidero ({{ $misEquiposLider->count() }})</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($misEquiposLider as $equipo)
                    <x-team-card :equipo="$equipo" :esLider="true" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- ========== MIS EQUIPOS (MIEMBRO) ========== --}}
    @if($misEquiposMiembro->count() > 0)
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-secondary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h2 class="text-2xl font-bold text-universo-text">Equipos donde Participo ({{ $misEquiposMiembro->count() }})</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($misEquiposMiembro as $equipo)
                    <x-team-card :equipo="$equipo" :esMiembro="true" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- ========== BUSCAR EQUIPOS ========== --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            <h2 class="text-2xl font-bold text-universo-text">Descubre Equipos</h2>
        </div>

        {{-- Barra de búsqueda y filtros --}}
        <form method="GET" action="{{ route('equipos.index') }}" class="flex flex-col md:flex-row items-center gap-4 mb-6">
            <div class="relative flex-grow md:flex-grow-0 md:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
                <input type="text" name="search" placeholder="Buscar equipos..." class="input-field pl-10 w-full" value="{{ request('search') }}">
            </div>

            <div>
                <label for="acepta" class="sr-only">Acepta miembros</label>
                <select name="acepta_miembros" id="acepta" class="input-field">
                    <option value="">Todos los equipos</option>
                    <option value="1" @selected(request('acepta_miembros') == '1')>Acepta Miembros</option>
                    <option value="0" @selected(request('acepta_miembros') == '0')>No Acepta Miembros</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Filtrar</button>
                @if(request('search') || request('acepta_miembros'))
                    <a href="{{ route('equipos.index') }}" class="btn-secondary">Limpiar</a>
                @endif
            </div>
        </form>

        {{-- Listado de equipos públicos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($equipos as $equipo)
                <x-team-card :equipo="$equipo" />
            @empty
                <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-universo-text-muted mb-4"><circle cx="12" cy="12" r="10"></circle><path d="m16 16-4-4-4 4"></path><path d="M12 12V8"></path></svg>
                    <p class="text-universo-text-muted text-lg">No hay equipos disponibles con los filtros seleccionados.</p>
                    @if(request('search') || request('acepta_miembros'))
                        <a href="{{ route('equipos.index') }}" class="text-universo-accent hover:underline mt-2 inline-block">Limpiar filtros</a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($equipos->hasPages())
            <div class="mt-8">
                {{ $equipos->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
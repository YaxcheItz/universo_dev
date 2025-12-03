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

    <!--Barra busqueda y filtro  -->
    <form method="GET" action="{{ route('equipos.index') }}" class="flex flex-col md:flex-row items-center gap-4 mb-8">
        <div class="relative flex-grow md:flex-grow-0 md:w-1/2">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            </div>
            <input type="text" name="search" placeholder="Buscar equipos..." class="input-field pl-10 w-full" value="{{ request('search') }}">
        </div>

        <div>
            <label for="acepta" class="sr-only">Acepta miembros</label>
            <select name="acepta_miembros" id="acepta" class="input-field">
                <option value="">Filtros</option>
                <option value="1" @selected(request('acepta_miembros') == '1')>Sí Acepta Miembros</option>
                <option value="0" @selected(request('acepta_miembros') == '0')>No Acepta Miembros</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary">Filtrar</button>
        </div>
    </form>
    @if($solicitudesPendientes->count() > 0)
        <div class="card bg-universo-warning/10 border-universo-warning/20 mb-6">
            <h2 class="text-universo-text font-semibold mb-2">Solicitudes Pendientes</h2>
            <div class="space-y-2">
                @foreach($solicitudesPendientes as $solicitud)
                <div class="flex justify-between items-center p-2 bg-universo-dark/10 rounded">
                    <div>
                        <p class="text-sm text-universo-text">
                            {{ $solicitud->user->nombre_completo }} desea unirse a <strong>{{ $solicitud->equipo->name }}</strong> como <em>{{ $solicitud->rol_equipo }}</em>
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



    <!--Listado -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($equipos as $equipo)
            <x-team-card :equipo="$equipo" />
        @empty
            <p class="text-universo-text-muted md:col-span-2 lg:col-span-3">No hay equipos para mostrar.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $equipos->links() }}
    </div>

</div>
@endsection

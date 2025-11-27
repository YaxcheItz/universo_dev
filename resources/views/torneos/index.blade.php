@extends('layouts.app')

@section('title', 'Torneos - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-universo-text mb-2">Torneos</h1>
            <p class="text-universo-text-muted">Participa en competencias de programación</p>
        </div>
        <a href="{{ route('torneos.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Crear Torneo
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-8">
        <form action="{{ route('torneos.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4 items-end">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
                <input type="text" name="search" placeholder="Buscar torneos..." class="input-field pl-10 w-full" value="{{ request('search') }}">
            </div>

            <div>
                <label for="categoria" class="sr-only">Categoría</label>
                <select name="categoria" id="categoria" class="input-field">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria }}" @selected(request('categoria') == $categoria)>{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado" class="sr-only">Estado</label>
                <select name="estado" id="estado" class="input-field">
                    <option value="">Todos los estados</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') == $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="nivel" class="sr-only">Nivel</label>
                <select name="nivel" id="nivel" class="input-field">
                    <option value="">Todos los niveles</option>
                    @foreach ($niveles as $nivel)
                        <option value="{{ $nivel }}" @selected(request('nivel') == $nivel)>{{ $nivel }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary w-full md:w-auto">Filtrar</button>
            @if(request()->filled('search') || request()->filled('categoria') || request()->filled('estado') || request()->filled('nivel'))
                <a href="{{ route('torneos.index') }}" class="btn-secondary w-full md:w-auto">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tournaments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($torneos as $torneo)
            <x-tournament-card :torneo="$torneo" />
        @empty
            <p class="text-universo-text-muted md:col-span-2 lg:col-span-3">No hay torneos para mostrar.</p>
        @endforelse
    </div>

    <!-- Pagination (Placeholder) -->
    <div class="mt-8">
        {{ $torneos->links() }}
    </div>

</div>
@endsection

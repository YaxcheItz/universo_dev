@extends('layouts.app')

@section('title', 'Proyectos - UniversoDev')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-universo-text mb-2">Proyectos</h1>
            <p class="text-universo-text-muted">Explora y comparte proyectos de la comunidad</p>
        </div>
        <a href="{{ route('proyectos.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Nuevo Proyecto
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-universo-text-muted"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            </div>
            <input type="text" placeholder="Buscar proyectos..." class="input-field pl-10 w-full">
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($proyectos as $proyecto)
            <x-project-card :proyecto="$proyecto" />
        @empty
            <p class="text-universo-text-muted md:col-span-2 lg:col-span-3">No hay proyectos para mostrar.</p>
        @endforelse
    </div>

    <!-- Pagination (Placeholder) -->
    <div class="mt-8">
        {{ $proyectos->links() }}
    </div>

</div>
@endsection

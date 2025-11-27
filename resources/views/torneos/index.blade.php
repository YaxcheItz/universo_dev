@extends('layouts.app')

@section('title', 'Torneos - UniversoDev')

@section('content')
<div class="space-y-8">

    <h1 class="text-3xl font-bold text-white">
        ¡Bienvenido, {{ auth()->user()->nombre }}!
    </h1>

    <p class="text-universo-text-muted">
        Seccion torneos para prueba
    </p>

    <div class="card">
        <p class="text-white">
            Aquí irá el resumen cuando estén listas las relaciones y migraciones.
        </p>
    </div>

</div>
@endsection

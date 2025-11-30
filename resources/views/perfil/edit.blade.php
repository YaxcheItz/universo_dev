@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold mb-8 text-white">Editar Perfil</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg">
            <ul class="text-red-200 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-200">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nombre -->
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Nombre</label>
            <input 
                type="text" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500"
                required>
            @error('name')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Apellido Paterno -->
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Apellido Paterno</label>
            <input 
                type="text" 
                name="apellido_paterno" 
                value="{{ old('apellido_paterno', $user->apellido_paterno) }}" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500"
                required>
            @error('apellido_paterno')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Apellido Materno -->
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Apellido Materno</label>
            <input 
                type="text" 
                name="apellido_materno" 
                value="{{ old('apellido_materno', $user->apellido_materno) }}" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500"
                required>
            @error('apellido_materno')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Apodo -->
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Apodo / Username</label>
            <input 
                type="text" 
                name="nickname" 
                value="{{ old('nickname', $user->nickname) }}" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500"
                placeholder="Tu apodo único">
            @error('nickname')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Teléfono -->
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Teléfono</label>
            <input 
                type="text" 
                name="telefono" 
                value="{{ old('telefono', $user->telefono) }}" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500"
                placeholder="Tu número de teléfono">
            @error('telefono')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foto de perfil -->
        <div class="pt-4 border-t border-gray-600">
            <label class="block text-sm font-medium text-gray-200 mb-4">Foto de perfil</label>
            
            @if ($user->avatar)
                <div class="mb-4">
                    <p class="text-sm text-gray-300 mb-2">Foto actual:</p>
                    <img 
                        src="{{ asset('storage/' . $user->avatar) }}" 
                        alt="Foto de perfil" 
                        class="h-24 w-24 rounded-full object-cover border-2 border-purple-500">
                </div>
            @endif

            <input 
                type="file" 
                name="avatar" 
                accept="image/*" 
                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:border-purple-500 file:bg-purple-600 file:text-white file:px-4 file:py-2 file:border-0 file:rounded-lg file:cursor-pointer">
            <p class="text-sm text-gray-400 mt-1">JPG, PNG, GIF. Máximo 4MB.</p>
            @error('avatar')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Color de fondo del perfil -->
        <div class="pt-4 border-t border-gray-600">
            <label class="block text-sm font-medium text-gray-200 mb-2">Color de fondo del perfil</label>
            <div class="flex items-center gap-4">
                <input 
                    type="color" 
                    name="profile_bg_color" 
                    value="{{ old('profile_bg_color', $user->profile_bg_color ?? '#1a1a2e') }}" 
                    class="w-20 h-12 rounded-lg cursor-pointer border-2 border-purple-500">
                <p class="text-sm text-gray-400">Elige un color para tu fondo</p>
            </div>
            @error('profile_bg_color')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-8 border-t border-gray-600">
            <button 
                type="submit" 
                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                Guardar cambios
            </button>
            <a 
                href="{{ route('perfil.index') }}" 
                class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

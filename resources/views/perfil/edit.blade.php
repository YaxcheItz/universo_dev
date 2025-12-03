@extends('layouts.app')

@section('title', 'Editar Perfil - UniversoDev')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Editar Perfil</h1>
        <p class="text-universo-text-muted">Actualiza tu información personal y preferencias</p>
    </div>

    @if ($errors->any())
        <div class="card mb-6 p-4 bg-red-500/10 border-red-500">
            <ul class="text-red-400 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="card mb-6 p-4 bg-green-500/10 border-green-500">
            <p class="text-green-400 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- MODIFICADO: Formulario de edición de perfil -->
    <!-- Envía datos a: route('perfil.update') -> PerfilController@update() -> PUT /perfil -->
    <!-- enctype="multipart/form-data" permite subir archivos (avatar) -->
    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Información Personal -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-5 h-5 text-universo-purple">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Información Personal
            </h2>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-universo-text mb-2">Nombre *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="input-field"
                            required>
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label for="apellido_paterno" class="block text-sm font-medium text-universo-text mb-2">Apellido Paterno *</label>
                        <input
                            type="text"
                            id="apellido_paterno"
                            name="apellido_paterno"
                            value="{{ old('apellido_paterno', $user->apellido_paterno) }}"
                            class="input-field"
                            required>
                        @error('apellido_paterno')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label for="apellido_materno" class="block text-sm font-medium text-universo-text mb-2">Apellido Materno *</label>
                        <input
                            type="text"
                            id="apellido_materno"
                            name="apellido_materno"
                            value="{{ old('apellido_materno', $user->apellido_materno) }}"
                            class="input-field"
                            required>
                        @error('apellido_materno')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Apodo -->
                <div>
                    <label for="nickname" class="block text-sm font-medium text-universo-text mb-2">Apodo / Username</label>
                    <input
                        type="text"
                        id="nickname"
                        name="nickname"
                        value="{{ old('nickname', $user->nickname) }}"
                        class="input-field"
                        placeholder="Tu apodo único">
                    @error('nickname')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-universo-text mb-2">Teléfono</label>
                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        value="{{ old('telefono', $user->telefono) }}"
                        class="input-field"
                        placeholder="Tu número de teléfono">
                    @error('telefono')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Personalización -->
        <div class="card">
            <h2 class="text-xl font-semibold text-universo-text mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palette w-5 h-5 text-universo-cyan">
                    <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"></circle>
                    <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"></circle>
                    <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"></circle>
                    <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"></circle>
                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path>
                </svg>
                Personalización de Perfil
            </h2>

            <div class="space-y-6">
                <!-- Foto de perfil -->
                <div>
                    <label class="block text-sm font-medium text-universo-text mb-3">Foto de perfil</label>

                    @if ($user->avatar)
                        <div class="mb-4 flex items-center gap-4">
                            <img
                                src="{{ asset('storage/' . $user->avatar) }}"
                                alt="Foto de perfil actual"
                                class="h-24 w-24 rounded-full object-cover border-4 border-universo-purple shadow-lg ring-4 ring-universo-purple/20">
                            <div>
                                <p class="text-sm text-universo-text font-medium">Foto actual</p>
                                <p class="text-xs text-universo-text-muted">Sube una nueva imagen para cambiarla</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <label for="avatar" class="btn-secondary cursor-pointer inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" x2="12" y1="3" y2="15"></line>
                            </svg>
                            Seleccionar imagen
                        </label>
                        <span id="file-name" class="text-sm text-universo-text-muted">JPG, PNG, GIF. Máximo 2MB.</span>
                    </div>

                    <input
                        type="file"
                        id="avatar"
                        name="avatar"
                        accept="image/*"
                        class="hidden"
                        onchange="updateFileName(this)">

                    @error('avatar')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color de fondo del perfil -->
                <div>
                    <label for="profile_bg_color" class="block text-sm font-medium text-universo-text mb-3">Color de fondo del perfil</label>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <input
                                type="color"
                                id="profile_bg_color"
                                name="profile_bg_color"
                                value="{{ old('profile_bg_color', $user->profile_bg_color ?? '#1a1a2e') }}"
                                class="w-16 h-16 rounded-lg cursor-pointer border-4 border-universo-purple shadow-lg">
                        </div>
                        <div>
                            <p class="text-sm text-universo-text font-medium">Personaliza el color de fondo de tu perfil</p>
                            <p class="text-xs text-universo-text-muted">Este color se mostrará en el banner de tu perfil</p>
                        </div>
                    </div>
                    @error('profile_bg_color')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- MODIFICADO: Botones de acción del formulario -->
        <div class="flex gap-4">
            <!-- Botón Cancelar: Regresa a la vista del perfil sin guardar cambios -->
            <a
                href="{{ route('perfil.index') }}"
                class="flex-1 btn-secondary text-center">
                Cancelar
            </a>
            <!-- Botón Guardar: Envía el formulario a PerfilController@update() -->
            <button
                type="submit"
                class="flex-1 btn-primary flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save">
                    <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path>
                    <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                    <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                </svg>
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameSpan = document.getElementById('file-name');
    if (fileName) {
        fileNameSpan.textContent = fileName;
        fileNameSpan.classList.remove('text-universo-text-muted');
        fileNameSpan.classList.add('text-universo-cyan', 'font-medium');
    } else {
        fileNameSpan.textContent = 'JPG, PNG, GIF. Máximo 2MB.';
        fileNameSpan.classList.add('text-universo-text-muted');
        fileNameSpan.classList.remove('text-universo-cyan', 'font-medium');
    }
}
</script>
@endsection

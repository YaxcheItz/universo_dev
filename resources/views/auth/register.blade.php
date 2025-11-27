<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UniversoDev</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-universo-purple" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 9V5h2v4h4v2h-4v4H9v-4H5V9h4z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white">Únete a UniversoDev</h2>
            <p class="mt-2 text-universo-text-muted">Completa el formulario para crear tu cuenta</p>
        </div>

        <!-- Registration Form -->
        <div class="card">
            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-purple" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        Información Personal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Nombre *</label>
                            <input name="nombre" type="text" required class="input-field" value="{{ old('nombre') }}">
                            @error('nombre')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Apellido Paterno *</label>
                            <input name="apellido_paterno" type="text" required class="input-field" value="{{ old('apellido_paterno') }}">
                            @error('apellido_paterno')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Apellido Materno *</label>
                            <input name="apellido_materno" type="text" required class="input-field" value="{{ old('apellido_materno') }}">
                            @error('apellido_materno')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Credenciales -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-cyan" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Credenciales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-universo-text mb-2">Email *</label>
                            <input name="email" type="email" required class="input-field" value="{{ old('email') }}">
                            @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Contraseña *</label>
                            <input name="password" type="password" required class="input-field">
                            @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Confirmar Contraseña *</label>
                            <input name="password_confirmation" type="password" required class="input-field">
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-success" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        Información de Contacto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Teléfono</label>
                            <input name="telefono" type="tel" class="input-field" value="{{ old('telefono') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Ciudad</label>
                            <input name="ciudad" type="text" class="input-field" value="{{ old('ciudad') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Estado</label>
                            <input name="estado" type="text" class="input-field" value="{{ old('estado') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Código Postal</label>
                            <input name="codigo_postal" type="text" class="input-field" value="{{ old('codigo_postal') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-universo-text mb-2">Dirección</label>
                            <input name="direccion" type="text" class="input-field" value="{{ old('direccion') }}">
                        </div>
                    </div>
                </div>

                <!-- Información Profesional -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-warning" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        Información Profesional
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Rol *</label>
                            <select name="rol" required class="input-field">
                                <option value="">Selecciona un rol</option>
                                <option value="Desarrollador">Desarrollador</option>
                                <option value="Team Leader">Team Leader</option>
                                <option value="Product Manager">Product Manager</option>
                                <option value="Designer">Designer</option>
                                <option value="DevOps">DevOps</option>
                                <option value="QA Engineer">QA Engineer</option>
                                <option value="Data Scientist">Data Scientist</option>
                            </select>
                            @error('rol')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Usuario de GitHub</label>
                            <input name="github_username" type="text" class="input-field" value="{{ old('github_username') }}">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-universo-border">
                    <a href="{{ route('login') }}" class="text-universo-text-muted hover:text-universo-text transition">
                        ← Volver al login
                    </a>
                    <button type="submit" class="btn-primary">
                        Crear Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
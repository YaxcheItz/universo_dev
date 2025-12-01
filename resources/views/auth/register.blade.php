<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UniversoDev</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-universo-dark text-universo-text">
    <div class="max-w-4xl mx-auto w-full space-y-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-universo-purple"><path d="m18 16 4-4-4-4"></path><path d="m6 8-4 4 4 4"></path><path d="m14.5 4-5 16"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-universo-text">Únete a UniversoDev</h2>
            <p class="mt-2 text-universo-text-muted">Completa el formulario para crear tu cuenta</p>
        </div>

        <!-- Registration Form -->
        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-universo-text mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        Información Personal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-universo-text mb-2">Nombre *</label>
                            <input id="name" name="name" type="text" required class="input-field" value="{{ old('name') }}">
                            @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-universo-text mb-2">Apellido Paterno *</label>
                            <input id="apellido_paterno" name="apellido_paterno" type="text" required class="input-field" value="{{ old('apellido_paterno') }}">
                            @error('apellido_paterno')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-universo-text mb-2">Apellido Materno *</label>
                            <input id="apellido_materno" name="apellido_materno" type="text" required class="input-field" value="{{ old('apellido_materno') }}">
                            @error('apellido_materno')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Credenciales -->
                <div>
                    <h3 class="text-lg font-semibold text-universo-text mb-4 flex items-center">
                         <svg class="w-5 h-5 mr-2 text-universo-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-3.582 3-8 3S0 12.657 0 11V3a1 1 0 011-1h2c1.657 0 3 1.343 3 3v6h6z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11c0 1.657-3.582 3-8 3s-8-1.343-8-3V3a1 1 0 011-1h2c1.657 0 3 1.343 3 3v6h6z"></path></svg>
                        Credenciales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-universo-text mb-2">Correo Electrónico *</label>
                            <input id="email" name="email" type="email" required class="input-field" value="{{ old('email') }}">
                            @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-universo-text mb-2">Contraseña *</label>
                            <input id="password" name="password" type="password" required class="input-field">
                            @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-universo-text mb-2">Confirmar Contraseña *</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="input-field">
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-universo-text mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Información de Contacto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <h3 class="text-lg font-semibold text-universo-text mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-universo-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                        Información Profesional
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Rol o Profesión *</label>
                            <input name="rol" type="text" required class="input-field" value="{{ old('rol') }}">

                        </div>
                        <div>
                            <label class="block text-sm font-medium text-universo-text mb-2">Nombre de Usuario</label>
                            <input name="username" type="text" class="input-field" value="{{ old('username') }}">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-8">
                    <a href="{{ route('login') }}" class="text-universo-text-muted hover:text-universo-text font-medium transition">
                        ¿Ya tienes una cuenta?
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
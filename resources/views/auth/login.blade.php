<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - UniversoDev</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-universo-purple" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 9V5h2v4h4v2h-4v4H9v-4H5V9h4z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white">UniversoDev</h2>
            <p class="mt-2 text-universo-text-muted">Plataforma de colaboración para desarrolladores</p>
        </div>

        <!-- Login Form -->
        <div class="card">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-universo-text mb-2">
                        Correo Electrónico
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="input-field @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}"
                        placeholder="tu@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-universo-text mb-2">
                        Contraseña
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required 
                        class="input-field @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-universo-purple focus:ring-universo-purple border-universo-border rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-universo-text">
                            Recordarme
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="text-universo-purple hover:text-purple-400 transition">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <button type="submit" class="w-full btn-primary">
                    Iniciar Sesión
                </button>

                <div class="text-center text-sm text-universo-text-muted">
                    ¿No tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-universo-purple hover:text-purple-400 transition font-medium">
                        Regístrate aquí
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
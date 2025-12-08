<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UniversoDev - Plataforma de Colaboración')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <!-- Navbar -->
    <nav class="bg-universo-secondary border-b border-universo-border sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-universo-purple" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 9V5h2v4h4v2h-4v4H9v-4H5V9h4z"/>
                        </svg>
                        <span class="text-xl font-bold text-white">UniversoDev</span>
                    </a>

                    <!-- menu de navegacion-->
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('dashboard') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('dashboard') ? 'text-universo-purple' : '' }}">
                            Inicio
                        </a>
                        <a href="{{ route('proyectos.index') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('proyectos.*') ? 'text-universo-purple' : '' }}">
                            Proyectos
                        </a>
                        <a href="{{ route('torneos.index') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('torneos.*') ? 'text-universo-purple' : '' }}">
                            Torneos
                        </a>

                        @if(auth()->user()->rol !== 'Juez')
                        <a href="{{ route('equipos.index') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('equipos.*') ? 'text-universo-purple' : '' }}">
                            Equipos
                        </a>
                        @endif

                        @if(auth()->user()->rol === 'Juez')
                        <a href="{{ route('evaluaciones.index') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('evaluaciones.*') ? 'text-universo-purple' : '' }} flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Evaluaciones
                        </a>
                        @endif
                        @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('admin.index') }}" class="text-universo-text hover:text-white transition {{ request()->routeIs('admin.*') ? 'text-universo-purple' : '' }} flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                
                            </svg>
                            Administración
                        </a>
                        @endif
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('perfil.index') }}" class="flex items-center space-x-2 text-universo-text hover:text-white transition">
                        @if(auth()->user()->avatar)
                            <img
                                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                alt="Foto de {{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover border-2 border-universo-purple ring-2 ring-universo-purple/20">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-universo-purple to-universo-cyan flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden md:block">{{ auth()->user()->name }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-universo-text-muted hover:text-universo-text transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-universo-success/20 border border-universo-success/30 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-universo-secondary border-t border-universo-border mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-universo-text-muted">
                <p>&copy; {{ date('Y') }} UniversoDev. Plataforma de colaboración para desarrolladores.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
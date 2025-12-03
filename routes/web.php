<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Página de inicio (redirige al login o dashboard según autenticación)
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});



// Rutas de autenticación (Guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout (Authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Proyectos
    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
    Route::get('/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
    Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{proyecto}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');
    
    // Torneos
    Route::get('/torneos', [TorneoController::class, 'index'])->name('torneos.index');
    Route::get('/torneos/create', [TorneoController::class, 'create'])->name('torneos.create');
    Route::post('/torneos', [TorneoController::class, 'store'])->name('torneos.store');
    Route::get('/torneos/{torneo}', [TorneoController::class, 'show'])->name('torneos.show');
    Route::get('/torneos/{torneo}/edit', [TorneoController::class, 'edit'])->name('torneos.edit');
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update'])->name('torneos.update');
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy'])->name('torneos.destroy');
    Route::post('/torneos/{torneo}/inscribir', [TorneoController::class, 'inscribir'])->name('torneos.inscribir');
    Route::post('/torneos/{torneo}/salir', [TorneoController::class, 'salir'])->name('torneos.salir');
    Route::get('/torneos/{torneo}/participantes', [TorneoController::class, 'participantes'])->name('torneos.participantes');
    
    // Equipos
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/create', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::get('/equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::post('/equipos/{equipo}/unirse', [EquipoController::class, 'unirse'])->name('equipos.unirse');
    Route::post('/equipos/{equipo}/salir', [EquipoController::class, 'salir'])->name('equipos.salir');
    Route::post('/equipos/{equipo}/agregar-miembro', [EquipoController::class, 'agregarMiembro'])->name('equipos.agregarMiembro');
    Route::delete('/equipos/{equipo}/remover/{user}', [EquipoController::class, 'removerMiembro'])->name('equipos.removerMiembro');
    
    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

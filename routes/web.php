<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;


// Página de inicio (redirige al login o dashboard según autenticación)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
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
        Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
        Route::get('/torneos', [TorneoController::class, 'index'])->name('torneos.index');
        Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
        Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
});


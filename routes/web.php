<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipoSolicitudController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
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
    Route::post('/proyectos/{proyecto}/files', [ProyectoController::class, 'uploadFile'])->name('proyectos.files.upload');
    Route::post('proyectos/{proyecto}/files/{file}/accept', [ProyectoController::class, 'acceptFile'])->name('proyectos.files.accept');
    Route::post('proyectos/{proyecto}/files/{file}/reject', [ProyectoController::class, 'rejectFile'])->name('proyectos.files.reject');
    Route::delete('proyectos/{proyecto}/files/{file}', [ProyectoController::class, 'deleteFile'])->name('proyectos.files.delete');
    Route::get('/proyectos/{proyecto}/files/{file}/download', [ProyectoController::class, 'download'])->name('proyectos.files.download');

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
    Route::post('/equipos/{equipo}/solicitar-unirse', [EquipoController::class, 'solicitarUnirse'])->name('equipos.solicitarUnirse');
    Route::post('/equipos/{equipo}/agregar-miembro', [EquipoController::class, 'agregarMiembro'])->name('equipos.agregarMiembro');
    Route::post('/equipos/{equipo}/remover/{user}', [EquipoController::class, 'removerMiembro'])->name('equipos.removerMiembro');

    // Solicitudes de Equipo (Magali)
    Route::post('/equipos/{equipo}/solicitar', [EquipoController::class, 'solicitar'])->name('equipos.solicitar');
    Route::post('/solicitudes/{solicitud}/manejar', [EquipoController::class, 'manejarSolicitud'])->name('solicitudes.manejar');
    Route::post('/equipos/solicitudes/{solicitud}/aceptar', [EquipoController::class, 'manejarSolicitud'])->name('equipos.solicitudes.aceptar');
    Route::delete('/equipos/solicitudes/{solicitud}/rechazar', [EquipoController::class, 'manejarSolicitud'])->name('equipos.solicitudes.rechazar');

    // Solicitudes de Equipo (EquipoSolicitudController)
    Route::post('/solicitudes/{equipoSolicitud}/aceptar', [EquipoSolicitudController::class, 'aceptar'])->name('solicitudes.aceptar');
    Route::post('/solicitudes/{equipoSolicitud}/rechazar', [EquipoSolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Usuario (búsqueda)
    Route::get('/usuarios/buscar', [UserController::class, 'buscar'])->name('usuarios.buscar');

    // Certificados
    Route::get('/torneos/{torneo}/certificado/{participacion}', [CertificadoController::class, 'descargar'])->name('certificados.descargar');

    // Evaluaciones (solo para Jueces)
    Route::get('/evaluaciones', [EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::get('/evaluaciones/{torneo}', [EvaluacionController::class, 'show'])->name('evaluaciones.show');
    Route::get('/evaluaciones/{participacion}/evaluar', [EvaluacionController::class, 'create'])->name('evaluaciones.create');
    Route::post('/evaluaciones/{participacion}/evaluar', [EvaluacionController::class, 'store'])->name('evaluaciones.store');
});

//rutas para administracion
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Crear Juez
    Route::get('/crear-juez', [AdminController::class, 'crearJuez'])->name('crear-juez');
    Route::post('/crear-juez', [AdminController::class, 'storeJuez'])->name('store-juez');
    
    // Crear Usuario
    Route::get('/crear-usuario', [AdminController::class, 'crearUsuario'])->name('crear-usuario');
    Route::post('/crear-usuario', [AdminController::class, 'storeUsuario'])->name('store-usuario');
    
    // Eliminar Usuario
    Route::delete('/eliminar-usuario/{id}', [AdminController::class, 'eliminarUsuario'])->name('eliminar-usuario');
});
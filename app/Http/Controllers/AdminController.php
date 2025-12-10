<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Torneo;
use App\Models\Evaluacion;
use App\Models\TorneoParticipacion;
use App\Notifications\UserAccountNotification;
use App\Notifications\JudgeTournamentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Roles predefinidos
    private $rolesPredefinidos = [
        'Desarrollador Frontend',
        'Desarrollador Backend',
        'Desarrollador Full Stack',
        'Diseñador UI/UX',
        'DevOps',
        'QA Tester',
        'Project Manager',
        'Data Scientist'
    ];

    public function index(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        // Búsqueda de usuarios
        $busquedaUsuarios = $request->get('busqueda_usuarios');
        $usuarios = User::where('rol', '!=', 'Administrador')
            ->when($busquedaUsuarios, function ($query, $busquedaUsuarios) {
                return $query->where(function ($q) use ($busquedaUsuarios) {
                    $q->where('name', 'like', '%' . $busquedaUsuarios . '%')
                      ->orWhere('email', 'like', '%' . $busquedaUsuarios . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Búsqueda de jueces
        $busqueda = $request->get('busqueda');
        $jueces = User::where('rol', 'Juez')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->where('name', 'like', '%' . $busqueda . '%')
                      ->orWhere('email', 'like', '%' . $busqueda . '%');
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        $totalUsuarios = User::where('rol', '!=', 'Administrador')->count();
        $totalJueces = User::where('rol', 'Juez')->count();

        return view('admin.index', compact('usuarios', 'jueces', 'totalUsuarios', 'totalJueces', 'busqueda'));
    }

    public function crearJuez()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        return view('admin.crear-juez');
    }

    public function storeJuez(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $juez = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'Juez',
        ]);

        // Enviar notificación por correo
        $juez->notify(new UserAccountNotification($juez, auth()->user(), 'juez_creado'));

        return redirect()->route('admin.index')
            ->with('success', 'Juez creado exitosamente. Se ha enviado un correo de bienvenida.');
    }

    public function crearUsuario()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        return view('admin.crear-usuario', [
            'rolesPredefinidos' => $this->rolesPredefinidos
        ]);
    }

    public function storeUsuario(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|max:255',
            'rol_personalizado' => 'nullable|string|max:255',
        ]);

        $rol = $request->rol === 'Otro' && $request->rol_personalizado 
            ? $request->rol_personalizado 
            : $request->rol;

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $rol,
        ]);

        // Enviar notificación por correo
        $usuario->notify(new UserAccountNotification($usuario, auth()->user(), 'usuario_creado'));

        return redirect()->route('admin.index')
            ->with('success', 'Usuario creado exitosamente. Se ha enviado un correo de bienvenida.');
    }

    public function eliminarUsuario($id)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $usuario = User::findOrFail($id);

        if ($usuario->rol === 'Administrador') {
            return redirect()->route('admin.index')
                ->with('error', 'No se puede eliminar un administrador');
        }

        // Enviar notificación antes de eliminar
        $usuario->notify(new UserAccountNotification($usuario, auth()->user(), 'usuario_eliminado'));

        $usuario->forceDelete();//eliminacion forzada sobre soft de modelo

        return redirect()->route('admin.index')
            ->with('success', 'Usuario eliminado exitosamente. Se ha enviado un correo de notificación.');
    }

    public function asignarJueces()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $torneos = \App\Models\Torneo::with(['organizador', 'jueces'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $jueces = User::where('rol', 'Juez')->get();

        return view('admin.asignar-jueces', compact('torneos', 'jueces'));
    }

    public function storeAsignacionJueces(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:users,id',
        ]);

        $torneo = \App\Models\Torneo::findOrFail($request->torneo_id);

        // Validar que el torneo no esté en Evaluación o Finalizado
        if (in_array($torneo->estado, ['Evaluación', 'Finalizado'])) {
            return back()->with('error', 'No se pueden asignar jueces a un torneo en estado "' . $torneo->estado . '".');
        }

        $juecesAnteriores = $torneo->jueces->pluck('id')->toArray();

        if (!$request->has('jueces') || empty($request->jueces)) {
            // Notificar a los jueces que fueron removidos
            foreach ($juecesAnteriores as $juezId) {
                $juez = User::find($juezId);
                if ($juez) {
                    $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));
                }
            }
            
            $torneo->jueces()->detach();
            return back()->with('success', 'Todos los jueces han sido removidos y notificados por correo.');
        }

        $juecesValidos = User::whereIn('id', $request->jueces)
            ->where('rol', 'Juez')
            ->pluck('id')
            ->toArray();

        if (count($juecesValidos) !== count($request->jueces)) {
            return back()->with('error', 'Algunos de los usuarios seleccionados no son jueces válidos');
        }

        // Determinar jueces nuevos y removidos
        $juecesNuevos = array_diff($juecesValidos, $juecesAnteriores);
        $juecesRemovidos = array_diff($juecesAnteriores, $juecesValidos);

        // Sincronizar jueces
        $torneo->jueces()->sync($juecesValidos);

        // Notificar a los jueces nuevos
        foreach ($juecesNuevos as $juezId) {
            $juez = User::find($juezId);
            if ($juez) {
                $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'asignado'));
            }
        }

        // Notificar a los jueces removidos
        foreach ($juecesRemovidos as $juezId) {
            $juez = User::find($juezId);
            if ($juez) {
                $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));
            }
        }

        return back()->with('success', 'Jueces asignados exitosamente. Se han enviado correos de notificación.');
    }

    public function removerJuezTorneo(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'juez_id' => 'required|exists:users,id',
        ]);

        $torneo = \App\Models\Torneo::findOrFail($request->torneo_id);
        $juez = User::findOrFail($request->juez_id);

        // Validar que el torneo no esté en Evaluación o Finalizado
        if (in_array($torneo->estado, ['Evaluación', 'Finalizado'])) {
            return back()->with('error', 'No se pueden remover jueces de un torneo en estado "' . $torneo->estado . '".');
        }

        $torneo->jueces()->detach($request->juez_id);

        // Notificar al juez removido
        $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));

        return back()->with('success', 'Juez removido del torneo. Se ha enviado un correo de notificación.');
    }

    /**
     * Mostrar página de reportes
     */
    public function reportes()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        return view('admin.reportes');
    }

    /**
     * Generar reporte general del sistema
     */
    public function reporteGeneral()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        // Estadísticas generales
        $totalUsuarios = User::count();
        $totalJueces = User::where('rol', 'Juez')->count();
        $totalAdministradores = User::where('rol', 'Administrador')->count();
        $totalDesarrolladores = User::whereIn('rol', [
            'Desarrollador Frontend',
            'Desarrollador Backend',
            'Desarrollador Full Stack',
            'Desarrollador'
        ])->count();

        // Torneos
        $totalTorneos = Torneo::count();
        $torneosActivos = Torneo::whereIn('estado', ['Inscripciones Abiertas', 'En Curso'])->count();
        $torneosFinalizados = Torneo::where('estado', 'Finalizado')->count();
        $totalParticipaciones = TorneoParticipacion::count();

        // Evaluaciones
        $totalEvaluaciones = Evaluacion::count();
        $evaluacionesPendientes = TorneoParticipacion::whereDoesntHave('evaluaciones')->count();

        // Usuarios por rol
        $usuariosPorRol = User::select('rol', DB::raw('count(*) as total'))
            ->groupBy('rol')
            ->orderBy('total', 'desc')
            ->get();

        // Torneos por estado
        $torneosPorEstado = Torneo::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Top 10 usuarios con más puntos
        $topUsuarios = User::orderBy('puntos_total', 'desc')
            ->limit(10)
            ->get(['name', 'email', 'rol', 'puntos_total', 'torneos_ganados', 'proyectos_completados']);

        // Torneos recientes
        $torneosRecientes = Torneo::with('organizador')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reporte-general', compact(
            'totalUsuarios',
            'totalJueces',
            'totalAdministradores',
            'totalDesarrolladores',
            'totalTorneos',
            'torneosActivos',
            'torneosFinalizados',
            'totalParticipaciones',
            'totalEvaluaciones',
            'evaluacionesPendientes',
            'usuariosPorRol',
            'torneosPorEstado',
            'topUsuarios',
            'torneosRecientes'
        ));
    }

    /**
     * Generar reporte de usuarios
     */
    public function reporteUsuarios(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $query = User::query();

        // Filtros opcionales
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        // Estadísticas
        $totalUsuarios = $usuarios->count();
        $totalPuntos = $usuarios->sum('puntos_total');
        $promedioPuntos = $usuarios->avg('puntos_total');
        $totalTorneosGanados = $usuarios->sum('torneos_ganados');

        $roles = User::select('rol')->distinct()->pluck('rol');

        return view('admin.reporte-usuarios', compact(
            'usuarios',
            'totalUsuarios',
            'totalPuntos',
            'promedioPuntos',
            'totalTorneosGanados',
            'roles'
        ));
    }

    /**
     * Generar reporte de torneos
     */
    public function reporteTorneos(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $query = Torneo::with(['organizador', 'participaciones']);

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_fin', '<=', $request->fecha_hasta);
        }

        $torneos = $query->orderBy('fecha_inicio', 'desc')->get();

        // Estadísticas
        $totalTorneos = $torneos->count();
        $totalParticipantes = $torneos->sum('participantes_actuales');
        $promedioParticipantes = $torneos->avg('participantes_actuales');

        $estados = ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación', 'Finalizado'];
        $categorias = Torneo::select('categoria')->distinct()->pluck('categoria');

        return view('admin.reporte-torneos', compact(
            'torneos',
            'totalTorneos',
            'totalParticipantes',
            'promedioParticipantes',
            'estados',
            'categorias'
        ));
    }

    /**
     * Generar reporte de evaluaciones
     */
    public function reporteEvaluaciones(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $query = Evaluacion::with(['juez', 'torneoParticipacion.torneo', 'torneoParticipacion.equipo']);

        // Filtros opcionales
        if ($request->filled('torneo_id')) {
            $query->whereHas('torneoParticipacion', function($q) use ($request) {
                $q->where('torneo_id', $request->torneo_id);
            });
        }

        if ($request->filled('juez_id')) {
            $query->where('juez_id', $request->juez_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $evaluaciones = $query->orderBy('created_at', 'desc')->get();

        // Estadísticas
        $totalEvaluaciones = $evaluaciones->count();
        $promedioPuntaje = $evaluaciones->avg('puntaje_total');
        $puntajeMaximo = $evaluaciones->max('puntaje_total');
        $puntajeMinimo = $evaluaciones->min('puntaje_total');

        // Evaluaciones por juez
        $evaluacionesPorJuez = Evaluacion::select('juez_id', DB::raw('count(*) as total'))
            ->groupBy('juez_id')
            ->with('juez')
            ->get();

        $torneos = Torneo::orderBy('fecha_inicio', 'desc')->get(['id', 'name']);
        $jueces = User::where('rol', 'Juez')->get(['id', 'name']);

        return view('admin.reporte-evaluaciones', compact(
            'evaluaciones',
            'totalEvaluaciones',
            'promedioPuntaje',
            'puntajeMaximo',
            'puntajeMinimo',
            'evaluacionesPorJuez',
            'torneos',
            'jueces'
        ));
    }
}
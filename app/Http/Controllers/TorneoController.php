<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\TorneoParticipacion;
use App\Models\EquipoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TorneoController extends Controller
{
    /**
     * Mostrar lista de torneos
     */
    public function index(Request $request)
    {
        // Base query
        $query = Torneo::with('organizador')->publicos();

        // Aplicar filtros si existen
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('nivel_dificultad')) {
            $query->where('nivel_dificultad', $request->nivel_dificultad);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Si hay filtros activos, mostrar todos en una vista
        $filtrosActivos = $request->hasAny(['search', 'categoria', 'nivel_dificultad', 'estado']);

        if ($filtrosActivos) {
            $torneosFiltrados = $query->orderBy('fecha_inicio', 'desc')->get();

            // Estadísticas generales
            $estadisticas = [
                'total_activos' => Torneo::whereIn('estado', ['Inscripciones Abiertas', 'En Curso'])->count(),
                'total_participantes' => Torneo::sum('participantes_actuales'),
                'total_finalizados' => Torneo::where('estado', 'Finalizado')->count(),
            ];

            return view('torneos.index', compact('torneosFiltrados', 'estadisticas', 'filtrosActivos'));
        }

        // Obtener torneos agrupados por estado (vista normal)
        $torneosInscripcionesAbiertas = Torneo::with('organizador')
            ->publicos()
            ->where('estado', 'Inscripciones Abiertas')
            ->orderBy('fecha_registro_fin', 'asc')
            ->get();

        $torneosEnCurso = Torneo::with('organizador')
            ->publicos()
            ->where('estado', 'En Curso')
            ->orderBy('fecha_fin', 'asc')
            ->get();

        $torneosProximos = Torneo::with('organizador')
            ->publicos()
            ->where('estado', 'Próximo')
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        $torneosEvaluacion = Torneo::with('organizador')
            ->publicos()
            ->where('estado', 'Evaluación')
            ->orderBy('fecha_fin', 'desc')
            ->get();

        $torneosFinalizados = Torneo::with('organizador')
            ->publicos()
            ->where('estado', 'Finalizado')
            ->orderBy('fecha_fin', 'desc')
            ->limit(6)
            ->get();

        // Estadísticas generales
        $estadisticas = [
            'total_activos' => $torneosInscripcionesAbiertas->count() + $torneosEnCurso->count(),
            'total_participantes' => Torneo::sum('participantes_actuales'),
            'total_finalizados' => Torneo::where('estado', 'Finalizado')->count(),
        ];

        return view('torneos.index', compact(
            'torneosInscripcionesAbiertas',
            'torneosEnCurso',
            'torneosProximos',
            'torneosEvaluacion',
            'torneosFinalizados',
            'estadisticas'
        ));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = ['Frontend', 'Backend', 'Full-Stack', 'Mobile', 'DevOps', 'Data Science', 'Machine Learning', 'Game Development', 'Blockchain', 'IoT', 'Ciberseguridad'];
        $dominios = ['Web', 'Mobile', 'Desktop', 'Cloud', 'Embedded', 'AI/ML', 'Blockchain'];
        $niveles = ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];
        $estados = ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación', 'Finalizado'];

        return view('torneos.create', compact('categorias', 'dominios', 'niveles', 'estados'));
    }

    /**
     * Guardar nuevo torneo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string',
            'dominio' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_registro_inicio' => 'required|date',
            'fecha_registro_fin' => 'required|date|after:fecha_registro_inicio',
            'tamano_equipo_min' => 'required|integer|min:1',
            'tamano_equipo_max' => 'required|integer|gte:tamano_equipo_min',
            'max_participantes' => 'nullable|integer|min:1',
            'nivel_dificultad' => 'required|string',
            'criterios_evaluacion' => 'required|array',
            'premios' => 'required|array',
            'reglas' => 'nullable|string',
            'requisitos' => 'nullable|string',
            'estado' => 'required|string',
            'es_publico' => 'boolean',
        ]);

        // --- Custom Validation: Ensure 'Inscripciones Abiertas' is not set if registration end date is in the past ---
        $fechaRegistroFin = \Carbon\Carbon::parse($request->input('fecha_registro_fin'));
        $now = \Carbon\Carbon::now();

        if ($request->input('estado') === 'Inscripciones Abiertas' && $fechaRegistroFin->isPast()) {
            return back()->withErrors(['estado' => 'No puedes establecer el estado "Inscripciones Abiertas" si la fecha de fin de registro ya ha pasado. Por favor, selecciona "En Curso" o "Próximo" según corresponda.'])->withInput();
        }
        // --- End Custom Validation ---

        $validated['user_id'] = Auth::id();
        $validated['participantes_actuales'] = 0;
        $validated['es_publico'] = $request->input('es_publico', 0) == 1;

        // Los torneos se crean siempre con estado "Inscripciones Abiertas"
        // El comando actualiza el estado automáticamente según las fechas
        $validated['estado'] = 'Inscripciones Abiertas';

        $torneo = Torneo::create($validated);

        return redirect()->route('torneos.show', $torneo)
            ->with('success', '¡Torneo creado exitosamente!');
    }

    /**
     * Mostrar detalles del torneo
     */
    public function show(Torneo $torneo)
    {
        $torneo->load(['organizador', 'participaciones.equipo.lider', 'participaciones.proyecto']);

        $user = Auth::user();
        $equipoInscrito = null;

        // Buscar si el usuario ya tiene un equipo inscrito
        $participacionExistente = $torneo->participaciones()
            ->whereHas('equipo', function ($query) use ($user) {
                $query->where('lider_id', $user->id);
            })
            ->with('equipo')
            ->first();
        
        if ($participacionExistente) {
            $equipoInscrito = $participacionExistente->equipo;
        }

        // Equipos del usuario que pueden inscribirse (y que no están ya inscritos)
        $equiposDisponibles = Equipo::where('lider_id', $user->id)
            ->where('estado', 'Activo')
            ->whereDoesntHave('torneoParticipaciones', function($query) use ($torneo) {
                $query->where('torneo_id', $torneo->id);
            })
            ->get();

        return view('torneos.show', compact('torneo', 'equiposDisponibles', 'equipoInscrito'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Torneo $torneo)
    {
        // Verificar que el usuario sea el organizador
        if ($torneo->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este torneo');
        }

        $categorias = ['Frontend', 'Backend', 'Full-Stack', 'Mobile', 'DevOps', 'Data Science', 'Machine Learning', 'Game Development', 'Blockchain', 'IoT', 'Ciberseguridad'];
        $dominios = ['Web', 'Mobile', 'Desktop', 'Cloud', 'Embedded', 'AI/ML', 'Blockchain'];
        $niveles = ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];
        $estados = ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación', 'Finalizado'];

        return view('torneos.edit', compact('torneo', 'categorias', 'dominios', 'niveles', 'estados'));
    }

    /**
     * Actualizar torneo
     */
    public function update(Request $request, Torneo $torneo)
    {
        // Verificar que el usuario sea el organizador
        if ($torneo->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este torneo');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string',
            'dominio' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_registro_inicio' => 'required|date',
            'fecha_registro_fin' => 'required|date|after:fecha_registro_inicio',
            'tamano_equipo_min' => 'required|integer|min:1',
            'tamano_equipo_max' => 'required|integer|gte:tamano_equipo_min',
            'max_participantes' => 'nullable|integer|min:1',
            'nivel_dificultad' => 'required|string',
            'criterios_evaluacion' => 'required|array',
            'premios' => 'required|array',
            'reglas' => 'nullable|string',
            'requisitos' => 'nullable|string',
            'estado' => 'required|string',
            'es_publico' => 'boolean',
        ]);

        $validated['es_publico'] = $request->input('es_publico', 0) == 1;

        $torneo->update($validated);

        return redirect()->route('torneos.show', $torneo)
            ->with('success', 'Torneo actualizado exitosamente');
    }

    /**
     * Eliminar torneo
     */
    public function destroy(Torneo $torneo)
    {
        // Verificar que el usuario sea el organizador
        if ($torneo->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este torneo');
        }

        $torneo->delete();

        return redirect()->route('torneos.index')
            ->with('success', 'Torneo eliminado exitosamente');
    }

    /**
     * Inscribir equipo en torneo
     */
    public function inscribir(Request $request, Torneo $torneo)
    {
        // VALIDACIÓN 0: Los jueces no pueden inscribirse en torneos
        if (Auth::user()->rol === 'Juez') {
            return back()->with('error', 'Los jueces no pueden inscribirse en torneos. Tu rol es evaluar proyectos.');
        }

        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $equipo = Equipo::findOrFail($request->equipo_id);

        // VALIDACIÓN 1: Verificar que el usuario sea el líder del equipo
        if ($equipo->lider_id !== Auth::id()) {
            return back()->with('error', 'Solo el líder del equipo puede inscribirlo');
        }

        // VALIDACIÓN 2: Verificar que el estado sea "Inscripciones Abiertas"
        if ($torneo->estado !== 'Inscripciones Abiertas') {
            return back()->with('error', 'Las inscripciones no están abiertas para este torneo. Estado actual: ' . $torneo->estado);
        }

        // VALIDACIÓN 3: Verificar que esté dentro del período de inscripciones
        $hoy = now();
        if ($hoy < $torneo->fecha_registro_inicio) {
            return back()->with('error', 'Las inscripciones aún no han iniciado. Inician el ' . $torneo->fecha_registro_inicio->format('d/m/Y'));
        }
        if ($hoy > $torneo->fecha_registro_fin) {
            return back()->with('error', 'El período de inscripciones ha finalizado');
        }

        // VALIDACIÓN 4: Verificar que el torneo no esté lleno
        if ($torneo->max_participantes && $torneo->participantes_actuales >= $torneo->max_participantes) {
            return back()->with('error', 'El torneo ha alcanzado el máximo de participantes (' . $torneo->max_participantes . ' equipos)');
        }

        // VALIDACIÓN 5: Verificar que el equipo no esté ya inscrito
        if ($torneo->participaciones()->where('equipo_id', $equipo->id)->exists()) {
            return back()->with('error', 'El equipo ya está inscrito en este torneo');
        }

        // VALIDACIÓN 6: Verificar tamaño del equipo
        $cantidadMiembros = $equipo->miembros()->count();
        if ($cantidadMiembros < $torneo->tamano_equipo_min || $cantidadMiembros > $torneo->tamano_equipo_max) {
            return back()->with('error', "El equipo debe tener entre {$torneo->tamano_equipo_min} y {$torneo->tamano_equipo_max} miembros. Tu equipo tiene {$cantidadMiembros} miembros");
        }

        // Crear participación
        TorneoParticipacion::create([
            'torneo_id' => $torneo->id,
            'equipo_id' => $equipo->id,
            'proyecto_id' => $request->proyecto_id,
            'fecha_inscripcion' => now(),
            'estado' => 'Inscrito',
        ]);

        // Incrementar contador de participantes
        $torneo->increment('participantes_actuales');

        return back()->with('success', '¡Equipo inscrito exitosamente en el torneo!');
    }

    /**
     * Anular inscripción de un equipo en el torneo
     */
    public function salir(Request $request, Torneo $torneo)
    {
        $user = Auth::user();

        // Buscar la participación del equipo liderado por el usuario actual
        $participacion = TorneoParticipacion::where('torneo_id', $torneo->id)
            ->whereHas('equipo', function ($query) use ($user) {
                $query->where('lider_id', $user->id);
            })
            ->first();

        // VALIDACIÓN 1: Verificar que el equipo esté realmente inscrito
        if (!$participacion) {
            return back()->with('error', 'Tu equipo no está inscrito en este torneo.');
        }
        
        // VALIDACIÓN 2: Solo se puede salir si las inscripciones están abiertas
        if ($torneo->estado !== 'Inscripciones Abiertas') {
            return back()->with('error', 'No puedes salir del torneo porque las inscripciones ya han cerrado o el torneo está en curso.');
        }

        // Eliminar participación
        $participacion->delete();

        // Decrementar contador de participantes
        $torneo->decrement('participantes_actuales');

        return back()->with('success', 'Has anulado la inscripción de tu equipo exitosamente.');
    }

    /**
     * Ver participantes del torneo
     */
    public function participantes(Torneo $torneo)
    {
        $participaciones = $torneo->participaciones()
            ->with(['equipo.lider', 'equipo.miembros', 'proyecto'])
            ->orderBy('puntaje_total', 'desc')
            ->orderBy('posicion', 'asc')
            ->get();

        $user = Auth::user();

        // Inicializar variables por defecto
        $userYaEnTorneo = false;
        $userTeamIds = [];
        $userPendingRequestTeamIds = [];

        // Solo procesar si el usuario está autenticado
        if ($user) {
            // Obtener IDs de equipos del usuario que están participando en ESTE torneo
            $equiposDelUsuarioEnTorneo = $user->equipos()
                ->whereHas('torneoParticipaciones', function($query) use ($torneo) {
                    $query->where('torneo_id', $torneo->id);
                })
                ->pluck('equipos.id')
                ->toArray();

            // Si el usuario ya está en un equipo del torneo, no puede solicitar unirse a otros
            $userYaEnTorneo = count($equiposDelUsuarioEnTorneo) > 0;

            // IDs de todos los equipos del usuario (para verificar si ya es miembro)
            $userTeamIds = $user->equipos->pluck('id')->toArray();

            // IDs de equipos donde el usuario tiene solicitudes pendientes
            $userPendingRequestTeamIds = EquipoSolicitud::where('user_id', $user->id)
                ->where('estado', 'pendiente')
                ->pluck('equipo_id')
                ->toArray();
        }

        return view('torneos.participantes', compact('torneo', 'participaciones', 'userTeamIds', 'userPendingRequestTeamIds', 'userYaEnTorneo'));
    }
}
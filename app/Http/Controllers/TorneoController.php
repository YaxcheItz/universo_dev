<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\TorneoParticipacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TorneoController extends Controller
{
    /**
     * Mostrar lista de torneos
     */
    public function index(Request $request)
    {
        $query = Torneo::with('organizador')
            ->publicos()
            ->orderBy('fecha_inicio', 'desc');

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por nivel
        if ($request->filled('nivel')) {
            $query->where('nivel_dificultad', $request->nivel);
        }

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $torneos = $query->paginate(12);

        // Opciones para filtros
        $categorias = ['Frontend', 'Backend', 'Full-Stack', 'Mobile', 'DevOps', 'Data Science', 'Machine Learning', 'Game Development', 'Blockchain', 'IoT', 'Ciberseguridad'];
        $estados = ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación', 'Finalizado'];
        $niveles = ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];

        return view('torneos.index', compact('torneos', 'categorias', 'estados', 'niveles'));
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

        $validated['user_id'] = Auth::id();
        $validated['participantes_actuales'] = 0;
        $validated['es_publico'] = $request->input('es_publico', 0) == 1;

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

        // Equipos del usuario que pueden inscribirse
        $equiposDisponibles = Auth::user()->equiposLiderados()
            ->where('estado', 'Activo')
            ->whereDoesntHave('torneoParticipaciones', function($query) use ($torneo) {
                $query->where('torneo_id', $torneo->id);
            })
            ->get();

        return view('torneos.show', compact('torneo', 'equiposDisponibles'));
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
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
        ]);

        $equipo = Equipo::findOrFail($request->equipo_id);

        // Verificar que el usuario sea el líder del equipo
        if ($equipo->lider_id !== Auth::id()) {
            return back()->with('error', 'Solo el líder del equipo puede inscribirlo');
        }

        // Verificar que el equipo no esté ya inscrito
        if ($torneo->participaciones()->where('equipo_id', $equipo->id)->exists()) {
            return back()->with('error', 'El equipo ya está inscrito en este torneo');
        }

        // Verificar tamaño del equipo
        $cantidadMiembros = $equipo->miembros()->count();
        if ($cantidadMiembros < $torneo->tamano_equipo_min || $cantidadMiembros > $torneo->tamano_equipo_max) {
            return back()->with('error', "El equipo debe tener entre {$torneo->tamano_equipo_min} y {$torneo->tamano_equipo_max} miembros");
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
     * Ver participantes del torneo
     */
    public function participantes(Torneo $torneo)
    {
        $participaciones = $torneo->participaciones()
            ->with(['equipo.lider', 'equipo.miembros', 'proyecto'])
            ->orderBy('puntaje_total', 'desc')
            ->orderBy('posicion', 'asc')
            ->get();

        return view('torneos.participantes', compact('torneo', 'participaciones'));
    }
}
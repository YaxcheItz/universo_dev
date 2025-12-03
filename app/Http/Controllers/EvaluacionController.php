<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\TorneoParticipacion;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    /**
     * Mostrar torneos en evaluación (solo para jueces)
     */
    public function index()
    {
        // Verificar que el usuario sea juez
        if (Auth::user()->rol !== 'Juez') {
            abort(403, 'Solo los jueces tienen acceso a esta sección');
        }

        $torneos = Torneo::where('estado', 'Evaluación')
            ->with('organizador')
            ->orderBy('fecha_fin', 'desc')
            ->get();

        return view('evaluaciones.index', compact('torneos'));
    }

    /**
     * Mostrar participantes de un torneo para evaluar
     */
    public function show(Torneo $torneo)
    {
        // Verificar que el usuario sea juez
        if (Auth::user()->rol !== 'Juez') {
            abort(403, 'Solo los jueces tienen acceso a esta sección');
        }

        // Verificar que el torneo esté en evaluación
        if ($torneo->estado !== 'Evaluación') {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Este torneo no está en evaluación');
        }

        $participaciones = $torneo->participaciones()
            ->with(['equipo.lider', 'equipo.miembros', 'proyecto', 'evaluaciones'])
            ->get();

        // Verificar qué participaciones ya fueron evaluadas por este juez
        $juezId = Auth::id();
        $participaciones = $participaciones->map(function ($participacion) use ($juezId) {
            $participacion->evaluada_por_juez = $participacion->evaluaciones
                ->where('juez_id', $juezId)
                ->isNotEmpty();
            $participacion->evaluacion_juez = $participacion->evaluaciones
                ->where('juez_id', $juezId)
                ->first();
            return $participacion;
        });

        return view('evaluaciones.show', compact('torneo', 'participaciones'));
    }

    /**
     * Mostrar formulario de evaluación para una participación
     */
    public function create(TorneoParticipacion $participacion)
    {
        // Verificar que el usuario sea juez
        if (Auth::user()->rol !== 'Juez') {
            abort(403, 'Solo los jueces tienen acceso a esta sección');
        }

        $participacion->load(['torneo', 'equipo.miembros', 'proyecto']);

        // Verificar que el torneo esté en evaluación
        if ($participacion->torneo->estado !== 'Evaluación') {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Este torneo no está en evaluación');
        }

        // Verificar si ya evaluó esta participación
        $evaluacionExistente = Evaluacion::where('torneo_participacion_id', $participacion->id)
            ->where('juez_id', Auth::id())
            ->first();

        if ($evaluacionExistente) {
            return redirect()->route('evaluaciones.show', $participacion->torneo)
                ->with('info', 'Ya evaluaste este equipo anteriormente');
        }

        $criterios = $participacion->torneo->criterios_evaluacion;

        return view('evaluaciones.create', compact('participacion', 'criterios'));
    }

    /**
     * Guardar evaluación
     */
    public function store(Request $request, TorneoParticipacion $participacion)
    {
        // Verificar que el usuario sea juez
        if (Auth::user()->rol !== 'Juez') {
            abort(403, 'Solo los jueces tienen acceso a esta sección');
        }

        // Verificar que el torneo esté en evaluación
        if ($participacion->torneo->estado !== 'Evaluación') {
            return back()->with('error', 'Este torneo no está en evaluación');
        }

        // Verificar si ya evaluó esta participación
        $evaluacionExistente = Evaluacion::where('torneo_participacion_id', $participacion->id)
            ->where('juez_id', Auth::id())
            ->exists();

        if ($evaluacionExistente) {
            return back()->with('error', 'Ya evaluaste este equipo anteriormente');
        }

        $validated = $request->validate([
            'calificaciones' => 'required|array',
            'calificaciones.*' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string',
        ]);

        // Calcular el puntaje total (promedio de todos los criterios)
        $calificaciones = $validated['calificaciones'];
        $puntajeTotal = array_sum($calificaciones) / count($calificaciones);

        // Crear la evaluación
        Evaluacion::create([
            'torneo_participacion_id' => $participacion->id,
            'juez_id' => Auth::id(),
            'calificaciones' => $calificaciones,
            'puntaje_total' => $puntajeTotal,
            'comentarios' => $validated['comentarios'] ?? null,
        ]);

        // Actualizar el puntaje total de la participación (promedio de todas las evaluaciones)
        $this->actualizarPuntajeParticipacion($participacion);

        return redirect()->route('evaluaciones.show', $participacion->torneo)
            ->with('success', '¡Evaluación guardada exitosamente!');
    }

    /**
     * Actualizar el puntaje total de una participación basado en todas las evaluaciones
     */
    private function actualizarPuntajeParticipacion(TorneoParticipacion $participacion)
    {
        $evaluaciones = $participacion->evaluaciones;

        if ($evaluaciones->count() > 0) {
            $promedioTotal = $evaluaciones->avg('puntaje_total');
            $participacion->puntaje_total = $promedioTotal;
            $participacion->save();
        }
    }
}

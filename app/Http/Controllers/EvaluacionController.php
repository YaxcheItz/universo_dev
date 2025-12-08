<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\TorneoParticipacion;
use App\Models\Evaluacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TorneoCalificado;

class EvaluacionController extends Controller
{
    /**
     * Mostrar torneos en evaluación (solo para jueces)
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->esJuez()) {
            abort(403, 'Solo los jueces pueden acceder a esta sección');
        }

        // Obtener torneos en evaluación donde el usuario está asignado como juez
        $torneos = Torneo::where('estado', 'Evaluación')
            ->whereHas('jueces', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
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
        if (!$this->estaAsignadoComoJuez($torneo)) {
            abort(403, 'No estás asignado como juez en este torneo');
        }

        if ($torneo->estado !== 'Evaluación') {
            return redirect()->route('evaluaciones.index')
                ->with('error', 'Este torneo no está en evaluación');
        }

        $participaciones = $torneo->participaciones()
            ->with(['equipo.lider', 'equipo.miembros', 'proyecto', 'evaluaciones.juez'])
            ->get();

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

        return view('evaluaciones.show', compact(
            'torneo',
            'participaciones',
            'juezId'
        ));
    }

    /**
     * Mostrar formulario de evaluación para una participación
     */
    public function create(TorneoParticipacion $participacion)
    {  
        if (!$this->estaAsignadoComoJuez($participacion->torneo)) {
            abort(403, 'No puedes evaluar este torneo');
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
        if (!$this->estaAsignadoComoJuez($participacion->torneo)) {
            abort(403, 'No puedes evaluar este torneo');
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

        // Verificar si todos los jueces han evaluado todas las participaciones
        $this->verificarFinalizacionTorneo($participacion->torneo);

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

    /**
     * Verificar si todos los jueces han evaluado todas las participaciones y finalizar el torneo
     */
    private function verificarFinalizacionTorneo(Torneo $torneo)
    {
        // CAMBIO IMPORTANTE: Obtener jueces ASIGNADOS al torneo (no todos los jueces del sistema)
        $totalJueces = $torneo->jueces()->count();

        if ($totalJueces == 0) {
            // No hay jueces asignados, no se puede finalizar automáticamente
            return;
        }

        // Obtener total de participaciones del torneo
        $totalParticipaciones = $torneo->participaciones()->count();

        if ($totalParticipaciones == 0) {
            // No hay participaciones, no se puede finalizar
            return;
        }

        // Total de evaluaciones que deberían existir (jueces asignados * participaciones)
        $evaluacionesEsperadas = $totalJueces * $totalParticipaciones;

        // Total de evaluaciones actuales del torneo
        $evaluacionesActuales = Evaluacion::whereIn('torneo_participacion_id',
            $torneo->participaciones()->pluck('id')
        )->count();

        // Si todas las evaluaciones están completas, finalizar el torneo
        if ($evaluacionesActuales >= $evaluacionesEsperadas) {
            $torneo->estado = 'Finalizado';
            $torneo->save();

            // Actualizar las posiciones de los equipos según su puntaje
            $this->actualizarPosiciones($torneo);

            // Disparar evento para notificaciones
            event(new TorneoCalificado($torneo));
        }
    }

    /**
     * Actualizar las posiciones de los equipos según su puntaje final
     */
    private function actualizarPosiciones(Torneo $torneo)
    {
        // Obtener todas las participaciones ordenadas por puntaje (mayor a menor)
        $participaciones = $torneo->participaciones()
            ->orderBy('puntaje_total', 'desc')
            ->get();

        // Asignar posiciones
        $posicion = 1;
        foreach ($participaciones as $participacion) {
            $participacion->posicion = $posicion;
            $participacion->save();
            $posicion++;
        }
    }

    /**
     * Verificar si el usuario actual está asignado como juez en el torneo
     */
    private function estaAsignadoComoJuez(Torneo $torneo): bool
    {
        return $torneo->jueces()
            ->where('users.id', Auth::id())
            ->exists();
    }
}
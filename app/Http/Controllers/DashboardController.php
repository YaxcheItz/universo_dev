<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Torneo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {/*
        $user = Auth::user();

        // Estadísticas del usuario
        $estadisticas = [
            'proyectos_totales' => $user->proyectosCreados()->count(),
            'equipos_totales' => $user->equipos()->count(),
            'torneos_participados' => $user->equipos()
                ->with('torneoParticipaciones')
                ->get()
                ->pluck('torneoParticipaciones')
                ->flatten()
                ->count(),
            'reconocimientos_totales' => $user->reconocimientos()->count(),
        ];

        // Proyectos trending
        $proyectosTrending = Proyecto::trending()
            ->publico()
            ->orderBy('estrellas', 'desc')
            ->limit(5)
            ->get();

        // Torneos activos
        $torneosActivos = Torneo::activos()
            ->publicos()
            ->orderBy('fecha_inicio', 'asc')
            ->limit(3)
            ->get();

        // Actividad reciente (últimos proyectos del usuario)
        $actividadReciente = $user->proyectosCreados()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Equipos del usuario
        $equipos = $user->equipos()
            ->where('estado', 'Activo')
            ->limit(3)
            ->get();

        return view('dashboard.index', compact(
            'estadisticas',
            'proyectosTrending',
            'torneosActivos',
            'actividadReciente',
            'equipos'
        ));
    }*/
     return view('dashboard.index', [
        'estadisticas' => [],
        'proyectosTrending' => [],
        'torneosActivos' => [],
    ]);
        
}
}
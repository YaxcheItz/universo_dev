<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Torneo;
use App\Models\User;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas Globales
        $estadisticas = [
            'proyectos_totales' => Proyecto::count(),
            'torneos_totales' => Torneo::count(),
            'equipos_totales' => Equipo::count(),
            'usuarios_totales' => User::count(),
        ];

        // Torneos activos para mostrar en el dashboard
        $torneosActivos = Torneo::where('estado', 'Inscripciones Abiertas')
            ->orWhere('estado', 'En Curso')
            ->orderBy('fecha_inicio', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'estadisticas',
            'torneosActivos'
        ));
    }
}
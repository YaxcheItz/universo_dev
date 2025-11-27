<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class ProyectoController extends Controller
{
    public function index()
    {
        // Obtener todos los proyectos para mostrar en la vista
                $proyectos = Proyecto::paginate(9);

        return view('proyectos.index', compact('proyectos'));
    }
}

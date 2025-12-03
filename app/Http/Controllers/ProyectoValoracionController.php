<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\ProyectoValoracion;
use Illuminate\Support\Facades\Auth;

class ProyectoValoracionController extends Controller
{

    
    /**
     * Guardar o actualizar una valoración
     */
    public function store(Request $request, Proyecto $proyecto)
    {
        // No puedes valorar tu propio proyecto
        if ($proyecto->user_id === Auth::id()) {
            return back()->with('error', 'No puedes valorar tu propio proyecto');
        }

        // Validar la entrada
        $validated = $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        // Crear o actualizar la valoración
        ProyectoValoracion::updateOrCreate(
            [
                'proyecto_id' => $proyecto->id,
                'user_id' => Auth::id(),
            ],
            [
                'puntuacion' => $validated['puntuacion'],
                'comentario' => $validated['comentario'] ?? null,
            ]
        );

        return back()->with('success', 'Valoración guardada exitosamente');
    }


    /**
     * Eliminar una valoración
     */
    public function destroy(Proyecto $proyecto)
    {
        $valoracion = ProyectoValoracion::where('proyecto_id', $proyecto->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$valoracion) {
            return back()->with('error', 'No has valorado este proyecto');
        }

        $valoracion->delete();

        return back()->with('success', 'Valoración eliminada exitosamente');
    }

    /**
     * Obtener valoraciones de un proyecto (para AJAX)
     */
    public function index(Proyecto $proyecto)
    {
        $valoraciones = ProyectoValoracion::with('usuario')
            ->where('proyecto_id', $proyecto->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($valoraciones);
    }

    /**
     * Obtener la valoración del usuario actual para un proyecto
     */
    public function show(Proyecto $proyecto)
    {
        $valoracion = ProyectoValoracion::where('proyecto_id', $proyecto->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$valoracion) {
            return response()->json(['valoracion' => null]);
        }

        return response()->json(['valoracion' => $valoracion]);
    }
}
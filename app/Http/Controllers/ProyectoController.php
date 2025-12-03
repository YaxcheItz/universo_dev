<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use App\Models\ProyectoValoracion;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->input('search');
    
    $query = Proyecto::with(['creador', 'valoraciones']);
    
    // Aplicar búsqueda si existe
    if ($search) {
        $query->where(function ($q) use ($search) {
            // Buscar por nombre del proyecto
            $q->where('name', 'like', '%' . $search . '%')
              // Buscar por lenguaje principal
              ->orWhere('lenguaje_principal', 'like', '%' . $search . '%')
              // Buscar por nombre del creador
              ->orWhereHas('creador', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%');
              });
        });
    }
        // Obtener todos los proyectos para mostrar en la vista
        $proyectos = $query->paginate(9);

        return view('proyectos.index', compact('proyectos'));

        
    }

    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lenguaje_principal' => 'required|string',
            'tecnologias' => 'nullable|array',
            'estado' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $proyecto = Proyecto::create($validated);

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto creado exitosamente');
    }

    public function show(Proyecto $proyecto)
    {
        $proyecto->load('creador', 'equipo');
        return view('proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este proyecto');
        }
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este proyecto');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lenguaje_principal' => 'required|string',
            'tecnologias' => 'nullable|array',
            'estado' => 'required|string',
        ]);

        $proyecto->update($validated);

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Proyecto actualizado exitosamente');
    }

    public function destroy(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este proyecto');
        }

        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }
}

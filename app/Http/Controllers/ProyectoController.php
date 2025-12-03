<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyecto::with('creador');

        // Si hay búsqueda, filtrar
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('lenguaje_principal', 'like', "%{$search}%");
            });
        }

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

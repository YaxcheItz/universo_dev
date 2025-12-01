<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\User;
use App\Models\EquipoMiembro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    /**
     * Mostrar lista de equipos
     */
    public function index(Request $request)
    {
        $query = Equipo::with('lider')
            ->activos()
            ->publicos()
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('acepta_miembros') && $request->acepta_miembros == '1') {
            $query->aceptanMiembros();
        }

        $equipos = $query->paginate(12);

        return view('equipos.index', compact('equipos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $rolesDisponibles = [
            'Líder de Equipo',
            'Programador Frontend',
            'Programador Backend',
            'Full-Stack',
            'Android',
            'iOS',
            'DevOps',
            'Data Scientist',
            'ML Engineer',
            'QA Engineer',
            'UI/UX Designer',
            'Product Manager'
        ];

        return view('equipos.create', compact('rolesDisponibles'));
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipos',
            'descripcion' => 'nullable|string',
            'max_miembros' => 'required|integer|min:2|max:50',
            'tecnologias' => 'nullable|array',
            'es_publico' => 'boolean',
            'acepta_miembros' => 'boolean',
            'miembros' => 'nullable|array',
            'miembros.*.user_id' => 'required|exists:users,id',
            'miembros.*.rol_equipo' => 'required|string',
        ]);

        // Crear el equipo
        $equipo = Equipo::create([
            'name' => $validated['name'],
            'descripcion' => $validated['descripcion'] ?? null,
            'lider_id' => Auth::id(),
            'max_miembros' => $validated['max_miembros'],
            'miembros_actuales' => 1,
            'tecnologias' => $validated['tecnologias'] ?? [],
            'es_publico' => $request->has('es_publico'),
            'acepta_miembros' => $request->has('acepta_miembros'),
            'estado' => 'Activo',
            'fecha_creacion' => now(),
        ]);

        // Agregar al líder como miembro
        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => Auth::id(),
            'rol_equipo' => 'Líder de Equipo',
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        // Agregar miembros adicionales si se proporcionaron
        if (isset($validated['miembros'])) {
            foreach ($validated['miembros'] as $miembro) {
                if ($miembro['user_id'] != Auth::id()) {
                    EquipoMiembro::create([
                        'equipo_id' => $equipo->id,
                        'user_id' => $miembro['user_id'],
                        'rol_equipo' => $miembro['rol_equipo'],
                        'fecha_ingreso' => now(),
                        'estado' => 'Activo',
                    ]);
                    $equipo->increment('miembros_actuales');
                }
            }
        }

        return redirect()->route('equipos.show', $equipo)
            ->with('success', '¡Equipo creado exitosamente!');
    }

    /**
     * Mostrar detalles del equipo
     */
    public function show(Equipo $equipo)
    {
        $equipo->load(['lider', 'miembros', 'proyectos', 'torneoParticipaciones.torneo']);

        // verificar si el usuario actual es miembro del equipo
        $esMiembro = $equipo->miembros->contains('id', Auth::id());//para poder eliminar si es lider
        return view('equipos.show', compact('equipo', 'esMiembro'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este equipo');
        }

        $rolesDisponibles = [
            'Líder de Equipo',
            'Programador Frontend',
            'Programador Backend',
            'Full-Stack',
            'Android',
            'iOS',
            'DevOps',
            'Data Scientist',
            'ML Engineer',
            'QA Engineer',
            'UI/UX Designer',
            'Product Manager'
        ];

        return view('equipos.edit', compact('equipo', 'rolesDisponibles'));
    }

    /**
     * Actualizar equipo
     */
    public function update(Request $request, Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este equipo');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipos,name,' . $equipo->id,
            'descripcion' => 'nullable|string',
            'max_miembros' => 'required|integer|min:2|max:50',
            'tecnologias' => 'nullable|array',
            'es_publico' => 'boolean',
            'acepta_miembros' => 'boolean',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        $validated['es_publico'] = $request->has('es_publico');
        $validated['acepta_miembros'] = $request->has('acepta_miembros');

        $equipo->update($validated);

        return redirect()->route('equipos.show', $equipo)
            ->with('success', 'Equipo actualizado exitosamente');
    }

    /**
     * Eliminar equipo
     */
    public function destroy(Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este equipo');
        }

        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

    /**
     * Unirse a un equipo
     */
    public function unirse(Request $request, Equipo $equipo)
    {
        if (!$equipo->acepta_miembros) {
            return back()->with('error', 'Este equipo no está aceptando nuevos miembros');
        }

        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno');
        }

        if ($equipo->miembros->contains('id', Auth::id())) {
            return back()->with('error', 'Ya eres miembro de este equipo');
        }

        $request->validate([
            'rol_equipo' => 'required|string',
        ]);

        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => Auth::id(),
            'rol_equipo' => $request->rol_equipo,
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        $equipo->increment('miembros_actuales');

        return back()->with('success', '¡Te has unido al equipo exitosamente!');
    }

    /**
     * Agregar miembro (solo líder)
     */
    public function agregarMiembro(Request $request, Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'Solo el líder puede agregar miembros');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rol_equipo' => 'required|string',
        ]);

        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno');
        }

        if ($equipo->miembros->contains('id', $request->user_id)) {
            return back()->with('error', 'Este usuario ya es miembro del equipo');
        }

        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => $request->user_id,
            'rol_equipo' => $request->rol_equipo,
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        $equipo->increment('miembros_actuales');

        return back()->with('success', 'Miembro agregado exitosamente');
    }

    /**
     * Remover miembro (solo líder)
     */
    public function removerMiembro(Equipo $equipo, User $user)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'Solo el líder puede remover miembros');
        }

        if ($user->id === $equipo->lider_id) {
            return back()->with('error', 'No puedes removerte a ti mismo como líder');
        }

        $miembro = EquipoMiembro::where('equipo_id', $equipo->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$miembro) {
            return back()->with('error', 'Este usuario no es miembro del equipo');
        }

        $miembro->update([
            'estado' => 'Retirado',
            'fecha_salida' => now(),
        ]);

        $equipo->decrement('miembros_actuales');

        return back()->with('success', 'Miembro removido del equipo');
    }
}

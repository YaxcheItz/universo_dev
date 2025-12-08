<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Roles predefinidos
    private $rolesPredefinidos = [
        'Desarrollador Frontend',
        'Desarrollador Backend',
        'Desarrollador Full Stack',
        'Diseñador UI/UX',
        'DevOps',
        'QA Tester',
        'Project Manager',
        'Data Scientist'
    ];

    public function index()
    {
        // Verificar que el usuario sea administrador
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        $usuarios = User::where('rol', '!=', 'Administrador')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $jueces = User::where('rol', 'Juez')->get();
        $totalUsuarios = User::where('rol', '!=', 'Administrador')->count();
        $totalJueces = User::where('rol', 'Juez')->count();

        return view('admin.index', compact('usuarios', 'jueces', 'totalUsuarios', 'totalJueces'));
    }

    public function crearJuez()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        return view('admin.crear-juez');
    }

    public function storeJuez(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'Juez',
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Juez creado exitosamente');
    }

    public function crearUsuario()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        return view('admin.crear-usuario', [
            'rolesPredefinidos' => $this->rolesPredefinidos
        ]);
    }

    public function storeUsuario(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|max:255',
            'rol_personalizado' => 'nullable|string|max:255',
        ]);

        // Si seleccionó "Otro", usar el rol personalizado
        $rol = $request->rol === 'Otro' && $request->rol_personalizado 
            ? $request->rol_personalizado 
            : $request->rol;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $rol,
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function eliminarUsuario($id)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $usuario = User::findOrFail($id);

        // No permitir eliminar administradores
        if ($usuario->rol === 'Administrador') {
            return redirect()->route('admin.index')
                ->with('error', 'No se puede eliminar un administrador');
        }

        $usuario->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    /**
     * Mostrar formulario para asignar jueces a torneos
     */
    public function asignarJueces()
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $torneos = \App\Models\Torneo::with(['organizador', 'jueces'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $jueces = User::where('rol', 'Juez')->get();

        return view('admin.asignar-jueces', compact('torneos', 'jueces'));
    }

    /**
     * Asignar jueces a un torneo específico
     */
    public function storeAsignacionJueces(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'jueces' => 'nullable|array', // Cambiado a nullable para permitir eliminar todos
            'jueces.*' => 'exists:users,id',
        ]);

        $torneo = \App\Models\Torneo::findOrFail($request->torneo_id);

        // Si no se envió el array de jueces o está vacío, desvincular todos
        if (!$request->has('jueces') || empty($request->jueces)) {
            $torneo->jueces()->detach(); // Elimina todos los jueces
            return back()->with('success', 'Todos los jueces han sido removidos del torneo');
        }

        // Verificar que todos los IDs correspondan a jueces válidos
        $juecesValidos = User::whereIn('id', $request->jueces)
            ->where('rol', 'Juez')
            ->pluck('id')
            ->toArray();

        if (count($juecesValidos) !== count($request->jueces)) {
            return back()->with('error', 'Algunos de los usuarios seleccionados no son jueces válidos');
        }

        // Sincronizar jueces (elimina los no seleccionados y agrega los nuevos)
        $torneo->jueces()->sync($juecesValidos);

        return back()->with('success', 'Jueces asignados exitosamente al torneo');
    }

    /**
     * Eliminar un juez específico de un torneo
     */
    public function removerJuezTorneo(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'juez_id' => 'required|exists:users,id',
        ]);

        $torneo = \App\Models\Torneo::findOrFail($request->torneo_id);
        $torneo->jueces()->detach($request->juez_id);

        return back()->with('success', 'Juez removido del torneo exitosamente');
    }
}
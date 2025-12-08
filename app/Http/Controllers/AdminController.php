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
}
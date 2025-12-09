<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserAccountNotification;
use App\Notifications\JudgeTournamentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $juez = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'Juez',
        ]);

        // Enviar notificación por correo
        $juez->notify(new UserAccountNotification($juez, auth()->user(), 'juez_creado'));

        return redirect()->route('admin.index')
            ->with('success', 'Juez creado exitosamente. Se ha enviado un correo de bienvenida.');
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

        $rol = $request->rol === 'Otro' && $request->rol_personalizado 
            ? $request->rol_personalizado 
            : $request->rol;

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $rol,
        ]);

        // Enviar notificación por correo
        $usuario->notify(new UserAccountNotification($usuario, auth()->user(), 'usuario_creado'));

        return redirect()->route('admin.index')
            ->with('success', 'Usuario creado exitosamente. Se ha enviado un correo de bienvenida.');
    }

    public function eliminarUsuario($id)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $usuario = User::findOrFail($id);

        if ($usuario->rol === 'Administrador') {
            return redirect()->route('admin.index')
                ->with('error', 'No se puede eliminar un administrador');
        }

        // Enviar notificación antes de eliminar
        $usuario->notify(new UserAccountNotification($usuario, auth()->user(), 'usuario_eliminado'));

        $usuario->forceDelete();//eliminacion forzada sobre soft de modelo

        return redirect()->route('admin.index')
            ->with('success', 'Usuario eliminado exitosamente. Se ha enviado un correo de notificación.');
    }

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

    public function storeAsignacionJueces(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:users,id',
        ]);

        $torneo = \App\Models\Torneo::findOrFail($request->torneo_id);
        $juecesAnteriores = $torneo->jueces->pluck('id')->toArray();

        if (!$request->has('jueces') || empty($request->jueces)) {
            // Notificar a los jueces que fueron removidos
            foreach ($juecesAnteriores as $juezId) {
                $juez = User::find($juezId);
                if ($juez) {
                    $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));
                }
            }
            
            $torneo->jueces()->detach();
            return back()->with('success', 'Todos los jueces han sido removidos y notificados por correo.');
        }

        $juecesValidos = User::whereIn('id', $request->jueces)
            ->where('rol', 'Juez')
            ->pluck('id')
            ->toArray();

        if (count($juecesValidos) !== count($request->jueces)) {
            return back()->with('error', 'Algunos de los usuarios seleccionados no son jueces válidos');
        }

        // Determinar jueces nuevos y removidos
        $juecesNuevos = array_diff($juecesValidos, $juecesAnteriores);
        $juecesRemovidos = array_diff($juecesAnteriores, $juecesValidos);

        // Sincronizar jueces
        $torneo->jueces()->sync($juecesValidos);

        // Notificar a los jueces nuevos
        foreach ($juecesNuevos as $juezId) {
            $juez = User::find($juezId);
            if ($juez) {
                $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'asignado'));
            }
        }

        // Notificar a los jueces removidos
        foreach ($juecesRemovidos as $juezId) {
            $juez = User::find($juezId);
            if ($juez) {
                $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));
            }
        }

        return back()->with('success', 'Jueces asignados exitosamente. Se han enviado correos de notificación.');
    }

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
        $juez = User::findOrFail($request->juez_id);
        
        $torneo->jueces()->detach($request->juez_id);

        // Notificar al juez removido
        $juez->notify(new JudgeTournamentNotification($juez, $torneo, auth()->user(), 'removido'));

        return back()->with('success', 'Juez removido del torneo. Se ha enviado un correo de notificación.');
    }
}
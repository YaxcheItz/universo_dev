<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    /**
     * Mostrar perfil del usuario autenticado
     */
    public function index()
    {
        $user = Auth::user();
        
        // Cargar relaciones
        $user->load([
            'proyectosCreados' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(6);
            },
            'equipos' => function($query) {
                $query->whereHas('lider', function($q) {
                    // Solo equipos activos
                })->orWhere('equipos.estado', 'Activo')
                ->wherePivot('estado', 'Activo')
                ->limit(4);
            },
            'reconocimientos' => function($query) {
                $query->orderBy('pivot_fecha_obtencion', 'desc');
            }
        ]);

        // Obtener torneos participados (a través de equipos)
        $torneosParticipados = collect();
        foreach ($user->equipos as $equipo) {
            $participaciones = $equipo->torneoParticipaciones()
                ->with('torneo')
                ->get();
            $torneosParticipados = $torneosParticipados->merge($participaciones);
        }
        
        $torneosParticipados = $torneosParticipados->unique('torneo_id')->take(6);

        // Obtener torneos creados
        $torneosCreados = $user->torneosOrganizados()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Estadísticas generales
        $estadisticas = [
            'proyectos_totales' => $user->proyectosCreados()->count(),
            'equipos_totales' => $user->equipos()->count(),
            'torneos_participados' => $torneosParticipados->count(),
            'torneos_creados' => $torneosCreados->count(),
            'reconocimientos_totales' => $user->reconocimientos()->count(),
            'puntos_totales' => $user->puntos_total,
        ];

        return view('perfil.index', compact('user', 'estadisticas', 'torneosParticipados', 'torneosCreados'));
    }

    /**
     * Mostrar perfil de otro usuario
     */
    public function show(User $user)
    {
        // Cargar relaciones
        $user->load([
            'proyectosCreados' => function($query) {
                $query->publico()->orderBy('created_at', 'desc')->limit(6);
            },
            'equipos' => function($query) {
                $query->where('equipos.estado', 'Activo')
                ->where('equipos.es_publico', true)
                ->wherePivot('estado', 'Activo')
                ->limit(4);
            },
            'reconocimientos' => function($query) {
                $query->orderBy('pivot_fecha_obtencion', 'desc');
            }
        ]);

        // Obtener torneos participados (a través de equipos)
        $torneosParticipados = collect();
        foreach ($user->equipos as $equipo) {
            $participaciones = $equipo->torneoParticipaciones()
                ->with('torneo')
                ->whereHas('torneo', function($query) {
                    $query->where('es_publico', true);
                })
                ->get();
            $torneosParticipados = $torneosParticipados->merge($participaciones);
        }
        
        $torneosParticipados = $torneosParticipados->unique('torneo_id')->take(6);

        // Obtener torneos creados
        $torneosCreados = $user->torneosOrganizados()
            ->publicos()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Estadísticas generales
        $estadisticas = [
            'proyectos_totales' => $user->proyectosCreados()->publico()->count(),
            'equipos_totales' => $user->equipos()->publicos()->count(),
            'torneos_participados' => $torneosParticipados->count(),
            'torneos_creados' => $torneosCreados->count(),
            'reconocimientos_totales' => $user->reconocimientos()->count(),
            'puntos_totales' => $user->puntos_total,
        ];

        return view('perfil.show', compact('user', 'estadisticas', 'torneosParticipados', 'torneosCreados'));
    }

    /**
     * Actualizar perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:255',
            'rol' => 'required|string',
            'biografia' => 'nullable|string|max:1000',
            'github_username' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'habilidades' => 'nullable|array',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Actualizar avatar si se proporciona
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Actualizar contraseña si se proporciona
        if ($request->filled('new_password')) {
            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
            }

            $validated['password'] = Hash::make($request->new_password);
        }

        // Remover campos que no se deben actualizar directamente
        unset($validated['current_password'], $validated['new_password'], $validated['new_password_confirmation']);

        $user->update($validated);

        return back()->with('success', 'Perfil actualizado exitosamente');
    }
}

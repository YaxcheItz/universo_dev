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
     * Mostrar formulario de edición de perfil
     *
     * MODIFICADO: Esta función maneja la visualización del formulario
     * cuando el usuario hace clic en el botón "Editar Perfil"
     * Ubicación del botón: resources/views/perfil/index.blade.php:64
     * Ruta: route('perfil.edit') -> GET /perfil/edit
     */
    //celis aqui mostramos el formulario para editar el perfil
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
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
 *
 * MODIFICADO: Esta función procesa el formulario de edición de perfil
 * cuando el usuario guarda los cambios desde resources/views/perfil/edit.blade.php
 * Ruta: route('perfil.update') -> PUT /perfil
 *
 * Campos que valida y actualiza:
 * - name, apellido_paterno, apellido_materno (requeridos)
 * - nickname (opcional, debe ser único)
 * - telefono (opcional)
 * - avatar (opcional, imagen de hasta 2MB)
 * - profile_bg_color (opcional, color hexadecimal para fondo del perfil)
 */
public function update(Request $request)
{
    $user = Auth::user();

    // MODIFICADO: Validación de todos los campos del formulario de edición
    $validated = $request->validate([
        'name'               => 'required|string|max:255',
        'apellido_paterno'   => 'required|string|max:255',
        'apellido_materno'   => 'required|string|max:255',
        'nickname'           => ['nullable', 'string', 'max:50', Rule::unique('users', 'nickname')->ignore($user->id)],
        'telefono'           => 'nullable|string|max:20',
        'avatar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'profile_bg_color'   => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
    ]);

    // MODIFICADO: Manejo de la foto de perfil - elimina la anterior y guarda la nueva
    if ($request->hasFile('avatar')) {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $avatarPath;
    }

    // MODIFICADO: Actualizar todos los datos validados en la base de datos
    $user->update($validated);

    return back()->with('success', 'Perfil actualizado exitosamente');
}
}
<?php

namespace App\Http\Controllers;

use App\Models\EquipoSolicitud;
use App\Models\EquipoMiembro;
use App\Models\Equipo; // For updating miembros_actuales
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserGroupStatusChanged;

class EquipoSolicitudController extends Controller
{
    /**
     * Aceptar una solicitud de unión a un equipo.
     */
    public function aceptar(EquipoSolicitud $equipoSolicitud)
    {
        // 1. Autorización: Solo el líder del equipo puede aceptar solicitudes
        if (Auth::id() !== $equipoSolicitud->equipo->lider_id) {
            abort(403, 'No tienes permiso para aceptar solicitudes para este equipo.');
        }

        // 2. Validar que el usuario no sea un juez
        $usuario = $equipoSolicitud->usuario;
        if ($usuario->rol === 'Juez') {
            $equipoSolicitud->delete();
            return back()->with('error', 'Los jueces no pueden ser parte de equipos. Su rol es evaluar proyectos.');
        }

        // 3. Validar que el equipo no esté lleno
        $equipo = $equipoSolicitud->equipo;
        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno. No se puede añadir más miembros.');
        }

        // 4. Validar que el usuario no sea ya miembro del equipo
        if ($equipo->miembros->contains($equipoSolicitud->user_id)) {
            $equipoSolicitud->delete(); // Eliminar la solicitud si ya es miembro
            return back()->with('error', 'El usuario ya es miembro de este equipo.');
        }

        // 5. Añadir el usuario como miembro del equipo
        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => $equipoSolicitud->user_id,
            'rol_equipo' => 'Miembro', // Rol por defecto, podría ser configurable
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        // 6. Incrementar el contador de miembros del equipo
        $equipo->increment('miembros_actuales');

        // 7. Disparar evento de notificación
        event(new UserGroupStatusChanged($usuario, $equipo, 'accepted'));

        // 8. Eliminar la solicitud
        $equipoSolicitud->delete();

        return back()->with('success', 'Solicitud aceptada. El usuario ha sido añadido al equipo.');
    }

    /**
     * Rechazar una solicitud de unión a un equipo.
     */
    public function rechazar(EquipoSolicitud $equipoSolicitud)
    {
        // 1. Autorización: Solo el líder del equipo puede rechazar solicitudes
        if (Auth::id() !== $equipoSolicitud->equipo->lider_id) {
            abort(403, 'No tienes permiso para rechazar solicitudes para este equipo.');
        }

        // 2. Obtener datos antes de eliminar
        $usuario = $equipoSolicitud->usuario;
        $equipo = $equipoSolicitud->equipo;

        // 3. Disparar evento de notificación
        event(new UserGroupStatusChanged($usuario, $equipo, 'rejected'));

        // 4. Eliminar la solicitud
        $equipoSolicitud->delete();

        return back()->with('success', 'Solicitud rechazada.');
    }
}

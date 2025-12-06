<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\TorneoParticipacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadoController extends Controller
{
    /**
     * Descargar certificado de participación en torneo
     */
    public function descargar(Torneo $torneo, TorneoParticipacion $participacion)
    {
        // Verificar que el torneo esté finalizado
        if ($torneo->estado !== 'Finalizado') {
            return back()->with('error', 'El torneo aún no ha sido finalizado.');
        }

        // Verificar que la participación pertenece al torneo
        if ($participacion->torneo_id !== $torneo->id) {
            abort(404);
        }

        // Verificar que el usuario es miembro del equipo
        $equipo = $participacion->equipo;
        $user = Auth::user();

        if (!$equipo->esMiembro($user) && $equipo->lider_id !== $user->id) {
            abort(403, 'No tienes permiso para descargar este certificado.');
        }

        // Solo generar certificados para los 3 primeros lugares
        if ($participacion->posicion > 3) {
            return back()->with('error', 'Solo se generan certificados para los 3 primeros lugares.');
        }

        // Determinar el lugar
        $lugar = $this->obtenerNombreLugar($participacion->posicion);

        // Datos para el certificado
        $data = [
            'torneo' => $torneo,
            'participacion' => $participacion,
            'equipo' => $equipo,
            'usuario' => $user,
            'lugar' => $lugar,
            'fecha_finalizacion' => now()->format('d/m/Y'),
        ];

        // Generar PDF
        $pdf = Pdf::loadView('certificados.torneo', $data);
        $pdf->setPaper('a4', 'landscape');

        // Nombre del archivo
        $nombreArchivo = "Certificado_{$torneo->name}_{$equipo->name}_{$user->name}.pdf";
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);

        // Descargar el PDF
        return $pdf->download($nombreArchivo);
    }

    /**
     * Obtener el nombre del lugar
     */
    private function obtenerNombreLugar($posicion)
    {
        return match($posicion) {
            1 => 'Primer Lugar',
            2 => 'Segundo Lugar',
            3 => 'Tercer Lugar',
            default => $posicion . '° Lugar',
        };
    }
}

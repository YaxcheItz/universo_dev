<?php

namespace App\Console\Commands;

use App\Models\Torneo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActualizarEstadosTorneos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'torneos:actualizar-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente el estado de los torneos según sus fechas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando actualización de estados de torneos...');
        $ahora = Carbon::now();
        $actualizados = 0;

        // Obtener todos los torneos que no estén finalizados
        $torneos = Torneo::whereIn('estado', ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación'])->get();

        foreach ($torneos as $torneo) {
            $estadoAnterior = $torneo->estado;
            $nuevoEstado = $this->determinarEstado($torneo, $ahora);

            if ($estadoAnterior !== $nuevoEstado) {
                $torneo->estado = $nuevoEstado;
                $torneo->save();
                $actualizados++;

                $this->line("✓ {$torneo->name}: {$estadoAnterior} → {$nuevoEstado}");
            }
        }

        if ($actualizados > 0) {
            $this->info("\n✓ {$actualizados} torneo(s) actualizado(s)");
        } else {
            $this->info("\nℹ No hay torneos que necesiten actualización");
        }

        return Command::SUCCESS;
    }

    /**
     * Determina el estado correcto del torneo según sus fechas
     */
    private function determinarEstado(Torneo $torneo, Carbon $ahora): string
    {
        // Si ya está finalizado, no cambiar
        if ($torneo->estado === 'Finalizado') {
            return 'Finalizado';
        }

        // Lógica de estados basada en fechas:

        // 1. Próximo: Antes de que inicien las inscripciones
        if ($ahora->isBefore($torneo->fecha_registro_inicio)) {
            return 'Próximo';
        }

        // 2. Inscripciones Abiertas: Durante el período de registro (incluye todo el día de la fecha de fin)
        $fechaRegistroFinCompleta = Carbon::parse($torneo->fecha_registro_fin)->endOfDay();
        if ($ahora->isBetween($torneo->fecha_registro_inicio, $fechaRegistroFinCompleta)) {
            return 'Inscripciones Abiertas';
        }

        // 3. En Curso: Desde el inicio del torneo hasta su fin (incluye todo el día de la fecha de fin)
        $fechaFinCompleta = Carbon::parse($torneo->fecha_fin)->endOfDay();
        if ($ahora->isBetween($torneo->fecha_inicio, $fechaFinCompleta)) {
            return 'En Curso';
        }

        // 4. Evaluación: Después del fin del torneo (durante 30 días para evaluar)
        if ($ahora->isAfter($fechaFinCompleta) && $ahora->diffInDays($torneo->fecha_fin) <= 2) {
            return 'Evaluación';
        }

        // 5. Finalizado: Más de 30 días después del fin
        if ($ahora->diffInDays($torneo->fecha_fin) > 2) {
            return 'Finalizado';
        }

        // Default: mantener estado actual
        return $torneo->estado;
    }
}

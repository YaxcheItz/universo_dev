<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\User;

class TorneoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear torneos de prueba 
        $torneos = Torneo::factory(3)->create();
        
        // Obtener IDs de jueces por defecto (Juez1 y Juez2)
        $juecesIds = User::where('rol', 'Juez')
            ->whereIn('email', [
                'juez1@gmail.com',
                'juez2@gmail.com',
            ])
            ->pluck('id');

        // Verificar que existan los jueces antes de asignar
        if ($juecesIds->isNotEmpty()) {
            // Asignar jueces a cada torneo
            foreach ($torneos as $torneo) {
                $torneo->jueces()->sync($juecesIds);
            }
            
            $this->command->info('Torneos creados y jueces asignados correctamente');
        } else {
            $this->command->warn('No se encontraron jueces con emails juez1@gmail.com o juez2@gmail.com');
        }
    }
}
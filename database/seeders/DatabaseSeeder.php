<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Proyecto;
use App\Models\Equipo;
use App\Models\Torneo;
use App\Models\EquipoMiembro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el usuario principal de prueba y 9 más
        $users = User::factory(9)->create();
        $mainUser = User::factory()->create([
            'name' => 'Test',
            'apellido_paterno' => 'User',
            'email' => 'test@example.com',
        ]);
        $users->push($mainUser);

        // Crear 20 proyectos, asignándolos a usuarios aleatorios
        Proyecto::factory(20)->recycle($users)->create();

        // Crear 5 torneos, asignándolos a usuarios aleatorios
        Torneo::factory(5)->recycle($users)->create();

        // Crear 8 equipos
        Equipo::factory(8)->recycle($users)->create()->each(function ($equipo) use ($users) {
            // Añadir al líder como miembro
            if (!EquipoMiembro::where('equipo_id', $equipo->id)->where('user_id', $equipo->lider_id)->exists()) {
                EquipoMiembro::create([
                    'equipo_id' => $equipo->id,
                    'user_id' => $equipo->lider_id,
                    'rol_equipo' => 'Líder de Equipo',
                    'fecha_ingreso' => now(),
                    'estado' => 'Activo',
                ]);
                $equipo->increment('miembros_actuales');
            }

            // Añadir entre 1 y 4 miembros adicionales
            $miembrosAdicionales = $users->where('id', '!=', $equipo->lider_id)->random(rand(1, 4));
            
            foreach ($miembrosAdicionales as $miembro) {
                 if ($equipo->miembros_actuales < $equipo->max_miembros) {
                    EquipoMiembro::create([
                        'equipo_id' => $equipo->id,
                        'user_id' => $miembro->id,
                        'rol_equipo' => 'Miembro',
                        'fecha_ingreso' => now(),
                        'estado' => 'Activo',
                    ]);
                    $equipo->increment('miembros_actuales');
                }
            }
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear únicamente el usuario principal de prueba
        User::factory()->create([
            'name' => 'Prueba',
            'apellido_paterno' => 'User',
            'email' => 'prueba1@gmail.com',
        ]);

        // Crear 3 jueces para evaluación de torneos
        User::factory()->create([
            'name' => 'Juez 1',
            'apellido_paterno' => 'Martinez',
            'email' => 'juez1@gmail.com',
            'rol' => 'Juez',
        ]);

        User::factory()->create([
            'name' => 'Juez 2',
            'apellido_paterno' => 'Gonzalez',
            'email' => 'juez2@gmail.com',
            'rol' => 'Juez',
        ]);
         User::factory()->create([
            'name' => 'Administrador',
            'apellido_paterno' => 'User',
            'email' => 'admi@gmail.com',
            'rol' => 'Administrador',
        ]);
        $this->call(TorneoSeeder::class);
        
    }
}

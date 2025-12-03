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
            'name' => 'Test',
            'apellido_paterno' => 'User',
            'email' => 'test@example.com',
        ]);

        // Crear 3 jueces para evaluación de torneos
        User::factory()->create([
            'name' => 'Juez 1',
            'apellido_paterno' => 'Martinez',
            'email' => 'juez1@test.com',
            'rol' => 'Juez',
        ]);

        User::factory()->create([
            'name' => 'Juez 2',
            'apellido_paterno' => 'Gonzalez',
            'email' => 'juez2@test.com',
            'rol' => 'Juez',
        ]);

        User::factory()->create([
            'name' => 'Juez 3',
            'apellido_paterno' => 'Rodriguez',
            'email' => 'juez3@test.com',
            'rol' => 'Juez',
        ]);
    }
}

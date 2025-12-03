<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Proyecto;

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
        Proyecto::factory()->count(8)->create();
    }
}

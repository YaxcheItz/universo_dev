<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear únicamente el usuario principal de prueba
        User::updateOrCreate(
            ['email' => 'prueba1@gmail.com'],
            [
                'name' => 'Prueba',
                'apellido_paterno' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Crear 3 jueces para evaluación de torneos
        User::updateOrCreate(
            ['email' => 'juez1@gmail.com'],
            [
                'name' => 'Juez 1',
                'apellido_paterno' => 'Martinez',
                'rol' => 'Juez',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'juez2@gmail.com'],
            [
                'name' => 'Juez 2',
                'apellido_paterno' => 'Gonzalez',
                'rol' => 'Juez',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admi@gmail.com'],
            [
                'name' => 'Administrador',
                'apellido_paterno' => 'User',
                'rol' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}

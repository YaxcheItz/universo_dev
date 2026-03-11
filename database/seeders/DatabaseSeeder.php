<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear únicamente el usuario principal de prueba
        $user = User::updateOrCreate(
            ['email' => 'prueba1@gmail.com'],
            [
                'name' => 'Prueba',
                'apellido_paterno' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'rol' => 'Desarrollador',
            ]
        );

        // 2. Crear 3 jueces para evaluación de torneos
        $juez1 = User::updateOrCreate(
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

        $admin = User::updateOrCreate(
            ['email' => 'admi@gmail.com'],
            [
                'name' => 'Administrador',
                'apellido_paterno' => 'User',
                'rol' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Crear Datos de Prueba (Seeders Realistas)
        
        // Torneo: Hackathon de Primavera (En Curso)
        $torneoEnCurso = Torneo::updateOrCreate(
            ['name' => 'Hackathon de Primavera 2026'],
            [
                'descripcion' => 'Únete a nuestro hackathon de primavera enfocado en soluciones sustentables usando Laravel y tecnologías web.',
                'user_id' => $admin->id,
                'categoria' => 'Web Development',
                'dominio' => 'Sustentabilidad',
                'fecha_inicio' => Carbon::now()->subDays(2),
                'fecha_fin' => Carbon::now()->addDays(5),
                'fecha_registro_inicio' => Carbon::now()->subDays(10),
                'fecha_registro_fin' => Carbon::now()->subDays(1),
                'tamano_equipo_min' => 2,
                'tamano_equipo_max' => 5,
                'max_participantes' => 100,
                'nivel_dificultad' => 'Intermedio',
                'criterios_evaluacion' => json_encode(['Innovación' => 30, 'Diseño' => 20, 'Código' => 50]),
                'premios' => json_encode(['1er Lugar' => 'MacBook Pro', '2do Lugar' => 'Monitor 4K', '3er Lugar' => 'Licencia Laravel Forge']),
                'reglas' => 'El proyecto debe ser open source y usar repositorios públicos.',
                'estado' => 'En Curso',
                'es_publico' => true,
            ]
        );

        // Torneo: Reto de Inteligencia Artificial (Próximo)
        $torneoProximo = Torneo::updateOrCreate(
            ['name' => 'AI Innovators Challenge'],
            [
                'descripcion' => 'Desarrolla modelos de machine learning para predecir tendencias de mercado.',
                'user_id' => $admin->id,
                'categoria' => 'Inteligencia Artificial',
                'dominio' => 'Finanzas',
                'fecha_inicio' => Carbon::now()->addDays(15),
                'fecha_fin' => Carbon::now()->addDays(30),
                'fecha_registro_inicio' => Carbon::now()->addDays(1),
                'fecha_registro_fin' => Carbon::now()->addDays(14),
                'tamano_equipo_min' => 1,
                'tamano_equipo_max' => 4,
                'max_participantes' => 50,
                'nivel_dificultad' => 'Avanzado',
                'criterios_evaluacion' => json_encode(['Precisión' => 60, 'Optimización' => 40]),
                'premios' => json_encode(['1er Lugar' => '$5000 USD', '2do Lugar' => '$2500 USD']),
                'estado' => 'Próximo',
                'es_publico' => true,
            ]
        );

        // Equipo: Los Coders
        $equipo = Equipo::updateOrCreate(
            ['name' => 'Los Innovadores'],
            [
                'descripcion' => 'Equipo apasionado por el desarrollo backend y la arquitectura de sistemas.',
                'lider_id' => $user->id,
                'max_miembros' => 5,
                'miembros_actuales' => 3,
                'tecnologias' => json_encode(['Laravel', 'Vue.js', 'Tailwind CSS', 'MySQL']),
                'estado' => 'Activo',
                'fecha_creacion' => Carbon::now()->subMonths(1)->toDateString(),
            ]
        );

        // Asignar juez al torneo en curso
        $torneoEnCurso->jueces()->syncWithoutDetaching([$juez1->id]);
    }
}

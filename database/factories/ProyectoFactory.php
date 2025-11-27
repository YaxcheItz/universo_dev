<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proyecto>
 */
class ProyectoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lenguajes = ['JavaScript', 'Python', 'Java', 'PHP', 'C#', 'C++', 'Ruby', 'Go', 'Swift', 'Kotlin', 'TypeScript', 'Rust'];
        $estados = ['Planificación', 'En Desarrollo', 'Pruebas', 'Producción', 'Mantenimiento', 'Archivado'];

        return [
            'name' => $this->faker->bs(),
            'descripcion' => $this->faker->paragraph(3),
            'user_id' => User::factory(),
            'lenguaje_principal' => $this->faker->randomElement($lenguajes),
            'tecnologias' => $this->faker->randomElements($lenguajes, $this->faker->numberBetween(1, 4)),
            'estado' => $this->faker->randomElement($estados),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'repositorio_url' => 'https://github.com/YaxcheItz/universo_dev',
            'estrellas' => $this->faker->numberBetween(0, 5000),
            'forks' => $this->faker->numberBetween(0, 1000),
            'contribuidores' => $this->faker->numberBetween(1, 25),
            'commits' => $this->faker->numberBetween(10, 500),
            'es_publico' => true,
            'es_trending' => $this->faker->boolean(20), // 20% chance of being trending
        ];
    }
}
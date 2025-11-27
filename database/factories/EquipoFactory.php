<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tecnologias = ['JavaScript', 'Python', 'Java', 'PHP', 'C#', 'C++', 'Ruby', 'Go', 'Swift', 'Kotlin', 'TypeScript', 'Rust'];
        $estados = ['Activo', 'Inactivo', 'Buscando Miembros'];
        
        return [
            'name' => $this->faker->unique()->word() . ' Coders',
            'descripcion' => $this->faker->sentence(),
            'lider_id' => User::factory(),
            'max_miembros' => $this->faker->numberBetween(2, 10),
            'miembros_actuales' => 1,
            'tecnologias' => $this->faker->randomElements($tecnologias, $this->faker->numberBetween(1, 3)),
            'estado' => $this->faker->randomElement($estados),
            'es_publico' => true,
            'acepta_miembros' => $this->faker->boolean(),
            'proyectos_completados' => $this->faker->numberBetween(0, 20),
            'torneos_participados' => $this->faker->numberBetween(0, 10),
            'torneos_ganados' => $this->faker->numberBetween(0, 3),
            'fecha_creacion' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
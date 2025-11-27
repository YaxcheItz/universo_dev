<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Torneo>
 */
class TorneoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = ['Frontend', 'Backend', 'Full-Stack', 'Mobile', 'DevOps', 'Data Science', 'Machine Learning', 'Game Development', 'Blockchain', 'IoT', 'Ciberseguridad'];
        $dominios = ['Web', 'Mobile', 'Desktop', 'Cloud', 'Embedded', 'AI/ML', 'Blockchain'];
        $niveles = ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];
        $estados = ['Próximo', 'Inscripciones Abiertas', 'En Curso', 'Evaluación', 'Finalizado'];

        return [
            'nombre' => 'Hackathon ' . $this->faker->city . ' ' . $this->faker->year(),
            'descripcion' => $this->faker->paragraph(4),
            'user_id' => User::factory(),
            'categoria' => $this->faker->randomElement($categorias),
            'dominio' => $this->faker->randomElement($dominios),
            'fecha_inicio' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'fecha_registro_inicio' => $this->faker->dateTimeBetween('now', '+1 week'),
            'fecha_registro_fin' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'tamano_equipo_min' => 1,
            'tamano_equipo_max' => $this->faker->numberBetween(2, 8),
            'nivel_dificultad' => $this->faker->randomElement($niveles),
            'criterios_evaluacion' => ['Originalidad', 'Impacto', 'Calidad del Código', 'Presentación'],
            'premios' => ['1er Lugar: $1000', '2do Lugar: $500', '3er Lugar: $250'],
            'estado' => $this->faker->randomElement($estados),
            'es_publico' => true,
            'participantes_actuales' => $this->faker->numberBetween(5, 50),
        ];
    }
}
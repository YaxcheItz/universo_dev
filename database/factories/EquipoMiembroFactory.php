<?php

namespace Database\Factories;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EquipoMiembro>
 */
class EquipoMiembroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['Miembro', 'Colaborador', 'Tester', 'Diseñador'];

        return [
            'equipo_id' => Equipo::factory(),
            'user_id' => User::factory(),
            'rol_equipo' => $this->faker->randomElement($roles),
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
            'contribuciones' => $this->faker->numberBetween(0, 100),
        ];
    }
}
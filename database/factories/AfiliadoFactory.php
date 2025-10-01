<?php

namespace Database\Factories;

use App\Models\Afiliado;
use App\Models\Comunidad;
use App\Models\Expedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Afiliado>
 */
class AfiliadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => $this->faker->name(), // mejor que sentence(6) para nombres
            'paterno' => $this->faker->lastName(), // no usar paragraph, eso genera texto largo
            'materno' => $this->faker->lastName(),
            'ci' => $this->faker->unique()->numerify('########'), // genera un CI numérico
            // 'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'estado' => 'activo',
            'user_id' => 1, // si siempre es el mismo user
            'comunidad_id' => Comunidad::inRandomOrder()->value('id'), // selecciona id válido
            'expedido_id' => Expedido::inRandomOrder()->value('id'), // selecciona id válido
        ];
    }
}

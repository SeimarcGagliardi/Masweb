<?php

namespace Database\Factories;

use App\Models\Terzista;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerzistaFactory extends Factory
{
    protected $model = Terzista::class;

    public function definition(): array
    {
        return [
            'ragione_sociale' => $this->faker->company(),
            'piva' => $this->faker->numerify('###########'),
            'indirizzo' => $this->faker->address(),
            'contatti' => ['tel' => $this->faker->phoneNumber()],
            'attivo' => true,
        ];
    }
}

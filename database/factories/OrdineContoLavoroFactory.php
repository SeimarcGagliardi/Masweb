<?php

namespace Database\Factories;

use App\Models\OrdineContoLavoro;
use App\Models\Terzista;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdineContoLavoroFactory extends Factory
{
    protected $model = OrdineContoLavoro::class;

    public function definition(): array
    {
        return [
            'terzista_id' => Terzista::factory(),
            'stato' => 'Inviato',
            'data_invio' => $this->faker->date(),
            'data_rientro_prevista' => $this->faker->optional()->date(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}

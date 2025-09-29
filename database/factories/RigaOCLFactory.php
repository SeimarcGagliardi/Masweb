<?php

namespace Database\Factories;

use App\Models\Articolo;
use App\Models\OrdineContoLavoro;
use App\Models\RigaOCL;
use Illuminate\Database\Eloquent\Factories\Factory;

class RigaOCLFactory extends Factory
{
    protected $model = RigaOCL::class;

    public function definition(): array
    {
        return [
            'ordine_id' => OrdineContoLavoro::factory(),
            'articolo_id' => Articolo::factory(),
            'qta' => $this->faker->randomFloat(3, 1, 50),
            'lotto' => $this->faker->optional()->bothify('LOT-####'),
            'stato_riga' => 'Inviata',
            'qta_rientrata' => 0,
            'scarto' => 0,
        ];
    }
}

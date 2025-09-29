<?php

namespace Database\Factories;

use App\Models\Ubicazione;
use Illuminate\Database\Eloquent\Factories\Factory;

class UbicazioneFactory extends Factory
{
    protected $model = Ubicazione::class;

    public function definition(): array
    {
        return [
            'magazzino_id' => null,
            'codice' => strtoupper($this->faker->bothify('UBI##')),
            'descrizione' => $this->faker->streetName(),
            'attiva' => true,
        ];
    }
}

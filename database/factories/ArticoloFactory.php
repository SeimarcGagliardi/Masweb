<?php // database/factories/ArticoloFactory.php
namespace Database\Factories;
use App\Models\Articolo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticoloFactory extends Factory {
  protected $model = Articolo::class;
  public function definition(){
    return [
      'codice' => strtoupper($this->faker->bothify('ART###')),
      'descrizione' => $this->faker->words(3, true),
      'unita_misura' => 'PZ',
      'attivo' => true,
    ];
  }
}

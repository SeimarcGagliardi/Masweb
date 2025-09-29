<?php // database/factories/MagazzinoFactory.php
namespace Database\Factories;
use App\Models\Magazzino;
use Illuminate\Database\Eloquent\Factories\Factory;

class MagazzinoFactory extends Factory {
  protected $model = Magazzino::class;
  public function definition(){
    return [
      'codice' => strtoupper($this->faker->bothify('MAG#')),
      'descrizione' => $this->faker->city(),
      'attivo' => true,
    ];
  }
}

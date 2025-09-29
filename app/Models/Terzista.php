<?php // app/Models/Terzista.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Terzista extends Model {
    protected $table="terzisti";
  protected $fillable=['ragione_sociale','piva','indirizzo','contatti','attivo'];
  protected $casts=['contatti'=>'array'];
}

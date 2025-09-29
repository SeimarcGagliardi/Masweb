<?php // app/Models/Terzista.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terzista extends Model {
    use HasFactory;
    protected $table="terzisti";
  protected $fillable=['ragione_sociale','piva','indirizzo','contatti','attivo'];
  protected $casts=['contatti'=>'array'];
}

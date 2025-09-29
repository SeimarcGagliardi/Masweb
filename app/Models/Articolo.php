<?php // app/Models/Articolo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Articolo extends Model {
  use HasFactory;
  protected $table="articoli";
  protected $fillable = ['codice','descrizione','unita_misura','barcode','lotto_obbligatorio','attivo'];
  public function movimenti(){ return $this->hasMany(Movimento::class); }
}

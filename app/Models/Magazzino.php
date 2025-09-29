<?php // app/Models/Magazzino.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Magazzino extends Model {
    use HasFactory;
    protected $table="magazzini";
  protected $fillable = ['codice','descrizione','indirizzo','attivo'];
  public function ubicazioni(){ return $this->hasMany(Ubicazione::class); }
}

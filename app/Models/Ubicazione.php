<?php // app/Models/Ubicazione.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicazione extends Model {
    protected $table="ubicazioni";
  protected $fillable=['magazzino_id','codice','descrizione','attiva'];
  public function magazzino(){ return $this->belongsTo(Magazzino::class); }
}

<?php // app/Models/Movimento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movimento extends Model {
    use HasFactory;
    protected $table = 'movimenti';
    protected $fillable = [
        'tipo','articolo_id','qta',
        'magazzino_orig','ubicazione_orig',
        'magazzino_dest','ubicazione_dest',
        'lotto','riferimento','utente_id','note','link_logico',
    ];
  protected $casts=['qta'=>'decimal:3'];

  public function articolo(){ return $this->belongsTo(Articolo::class); }
  public function origineMag(){ return $this->belongsTo(Magazzino::class,'magazzino_orig'); }
  public function destinazioneMag(){ return $this->belongsTo(Magazzino::class,'magazzino_dest'); }
}

<?php // app/Models/OrdineContoLavoro.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdineContoLavoro extends Model {
  protected $table='ordine_conto_lavoro';
  protected $fillable=['terzista_id','stato','data_invio','data_rientro_prevista','note'];
  public function righe(){ return $this->hasMany(RigaOCL::class,'ordine_id'); }
}

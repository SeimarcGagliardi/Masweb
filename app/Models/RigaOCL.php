<?php // app/Models/RigaOCL.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RigaOCL extends Model {
  use HasFactory;
  protected $table='righe_ocl';
  protected $fillable=['ordine_id','articolo_id','qta','lotto','stato_riga','qta_rientrata','scarto'];
  public function ordine(){ return $this->belongsTo(OrdineContoLavoro::class,'ordine_id'); }
  public function articolo(){ return $this->belongsTo(Articolo::class); }
}

<?php
// Modello: Articolo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articolo extends Model
{
    protected $guarded = [];
    protected $table = "articoli";
    
    public function tipologia()
    {
        return $this->belongsTo(TipologiaArticolo::class, 'tipologia_articoli_id');
    }

    public function modelloCapo()
    {
        return $this->belongsTo(ModelloCapo::class, 'modelli_capo_id');
    }
}

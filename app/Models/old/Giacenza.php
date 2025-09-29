<?php

// Modello: Giacenza
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giacenza extends Model
{
    protected $guarded = [];
    protected $table = "giacenze";
    
    public function articolo()
    {
        return $this->belongsTo(Articolo::class);
    }

    public function magazzino()
    {
        return $this->belongsTo(Magazzino::class);
    }
}

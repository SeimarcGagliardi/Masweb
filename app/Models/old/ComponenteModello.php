<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponenteModello extends Model
{
    protected $guarded = [];
    protected $table = "componenti_modello";
    
    public function modelloCapo()
    {
        return $this->belongsTo(ModelloCapo::class, 'modelli_capo_id');
    }
}


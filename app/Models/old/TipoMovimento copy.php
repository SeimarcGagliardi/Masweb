<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimento extends Model
{
    protected $guarded = [];

    protected $table = "tipo_movimento";
    
    public function magazzino1()
    {
        return $this->belongsTo(Magazzino::class, 'magazzino1');
    }

    public function magazzino2()
    {
        return $this->belongsTo(Magazzino::class, 'magazzino2');
    }
}
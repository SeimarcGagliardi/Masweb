<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelloCapo extends Model
{
    protected $guarded = [];
    protected $table = "modelli_capo";
    public function componenti()
    {
        return $this->hasMany(ComponenteModello::class);
    }
}
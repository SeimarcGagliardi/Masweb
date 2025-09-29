<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimentazioneMagazzino extends Model
{
    protected $table = 'movimentazioni_magazzino';
    protected $guarded = [];

    protected $casts = [
        'componente' => 'array',
        'data_movimento' => 'datetime',
    ];

    public function articolo()
    {
        return $this->belongsTo(Articolo::class);
    }

    public function magazzino()
    {
        return $this->belongsTo(Magazzino::class);
    }

    public function tipoMovimento()
    {
        return $this->belongsTo(TipoMovimento::class);
    }

    public function operatore()
    {
        return $this->belongsTo(UtentePersonale::class, 'operatore_id');
    }

    public function padre()
    {
        return $this->belongsTo(self::class, 'movimentazioni_magazzino_id_padre');
    }

    public function figli()
    {
        return $this->hasMany(self::class, 'movimentazioni_magazzino_id_padre');
    }
}

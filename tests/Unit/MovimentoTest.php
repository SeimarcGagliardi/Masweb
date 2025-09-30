<?php

namespace Tests\Unit;

use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Movimento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovimentoTest extends TestCase
{
    use RefreshDatabase;

    public function test_imposta_correttamente_riferimenti_magazzini(): void
    {
        $utente = User::factory()->create();
        $articolo = Articolo::factory()->create();
        $magazzinoOrig = Magazzino::factory()->create();
        $magazzinoDest = Magazzino::factory()->create();

        $out = Movimento::create([
            'tipo' => 'TRASF',
            'articolo_id' => $articolo->id,
            'qta' => 1,
            'magazzino_orig' => $magazzinoOrig->id,
            'utente_id' => $utente->id,
        ]);

        $in = Movimento::create([
            'tipo' => 'TRASF',
            'articolo_id' => $articolo->id,
            'qta' => 1,
            'magazzino_dest' => $magazzinoDest->id,
            'utente_id' => $utente->id,
        ]);

        $this->assertSame($magazzinoOrig->id, $out->origineMag->id);
        $this->assertSame($magazzinoDest->id, $in->destinazioneMag->id);
    }
}

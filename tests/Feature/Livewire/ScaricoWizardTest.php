<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movimenti\ScaricoWizard;
use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Ubicazione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScaricoWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_prelievo_generates_scarico_movement(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        Livewire::actingAs($user)
            ->test(ScaricoWizard::class)
            ->set('contesto.tipo', 'prelievo')
            ->set('contesto.magazzino_id', $magazzino->id)
            ->set('contesto.ubicazione_id', $ubicazione->id)
            ->set('contesto.commessa', 'JOB-1')
            ->call('next')
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '4')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors()
            ->assertRedirect(route('movimenti.scarico'));

        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'SCARICO',
            'magazzino_orig' => $magazzino->id,
            'ubicazione_orig' => $ubicazione->id,
        ]);
    }

    public function test_reso_generates_carico_movement(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        Livewire::actingAs($user)
            ->test(ScaricoWizard::class)
            ->set('contesto.tipo', 'reso')
            ->set('contesto.magazzino_id', $magazzino->id)
            ->set('contesto.ubicazione_id', $ubicazione->id)
            ->call('next')
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '2')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'CARICO',
            'magazzino_dest' => $magazzino->id,
            'ubicazione_dest' => $ubicazione->id,
        ]);
    }
}

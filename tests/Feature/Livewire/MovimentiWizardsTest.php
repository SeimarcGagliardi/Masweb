<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movimenti\CaricoWizard;
use App\Livewire\Movimenti\ContoLavoroWizard;
use App\Livewire\Movimenti\ScaricoWizard;
use App\Livewire\Movimenti\TransferWizard;
use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Terzista;
use App\Models\Ubicazione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MovimentiWizardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_carico_wizard_advances_only_with_valid_data(): void
    {
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        $component = Livewire::test(CaricoWizard::class);

        $component->call('next')
            ->assertHasErrors(['contesto.magazzino_id'])
            ->assertSet('step', 1);

        $component
            ->set('contesto.magazzino_id', $magazzino->id)
            ->set('contesto.ubicazione_id', $ubicazione->id)
            ->call('next')
            ->assertSet('step', 2);

        $component
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '12.5')
            ->set('righe.0.lotto', 'LOT-1')
            ->call('next')
            ->assertSet('step', 3);
    }

    public function test_scarico_wizard_handles_validation_and_progress(): void
    {
        $this->actingAs(User::factory()->create());

        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        $component = Livewire::test(ScaricoWizard::class)
            ->set('contesto.tipo', 'prelievo')
            ->set('contesto.magazzino_id', $magazzino->id)
            ->set('contesto.ubicazione_id', $ubicazione->id)
            ->set('contesto.operatore', 'Tester Operatore');

        $component->call('next')
            ->assertSet('step', 2);

        $component
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '3')
            ->call('next')
            ->assertSet('step', 3);
    }

    public function test_transfer_wizard_advances_through_steps(): void
    {
        $this->actingAs(User::factory()->create());

        $origineMag = Magazzino::factory()->create();
        $destMag = Magazzino::factory()->create();
        $origineUbic = Ubicazione::factory()->create(['magazzino_id' => $origineMag->id]);
        $destUbic = Ubicazione::factory()->create(['magazzino_id' => $destMag->id]);
        $articolo = Articolo::factory()->create();

        $component = Livewire::test(TransferWizard::class)
            ->set('origine.magazzino_id', $origineMag->id)
            ->set('origine.ubicazione_id', $origineUbic->id)
            ->call('next')
            ->assertSet('step', 2)
            ->set('destinazione.magazzino_id', $destMag->id)
            ->set('destinazione.ubicazione_id', $destUbic->id)
            ->call('next')
            ->assertSet('step', 3);

        $component
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '8')
            ->call('next')
            ->assertSet('step', 4);
    }

    public function test_conto_lavoro_invio_flow_advances(): void
    {
        $this->actingAs(User::factory()->create());

        $terzista = Terzista::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        $component = Livewire::test(ContoLavoroWizard::class)
            ->set('invio.terzista_id', $terzista->id)
            ->set('invio.magazzino_id', $magazzino->id)
            ->set('invio.ubicazione_id', $ubicazione->id)
            ->set('invio.data_invio', now()->toDateString())
            ->call('next')
            ->assertSet('step', 2);

        $component
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '5')
            ->call('next')
            ->assertSet('step', 3);
    }
}

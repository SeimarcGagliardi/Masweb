<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movimenti\ContoLavoroWizard;
use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\OrdineContoLavoro;
use App\Models\Terzista;
use App\Models\Ubicazione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContoLavoroWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_invio_creates_order_and_movements(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $terzista = Terzista::factory()->create();
        $articolo = Articolo::factory()->create();
        $dataInvio = now()->toDateString();

        Livewire::actingAs($user)
            ->test(ContoLavoroWizard::class)
            ->set('invio.terzista_id', $terzista->id)
            ->set('invio.magazzino_id', $magazzino->id)
            ->set('invio.ubicazione_id', $ubicazione->id)
            ->set('invio.data_invio', $dataInvio)
            ->call('next')
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '8')
            ->set('righe.0.lotto', 'LOT-INVIO')
            ->set('righe.0.componenti', 'Fodera, bottoni')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors()
            ->assertRedirect(route('conto-lavoro.wizard'));

        $this->assertDatabaseHas('ordine_conto_lavoro', [
            'terzista_id' => $terzista->id,
            'stato' => 'Inviato',
        ]);
        $this->assertDatabaseHas('righe_ocl', [
            'lotto' => 'LOT-INVIO',
            'qta' => 8,
        ]);
        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'USC_CL',
            'magazzino_orig' => $magazzino->id,
            'ubicazione_orig' => $ubicazione->id,
        ]);
    }

    public function test_rientro_updates_quantities_and_creates_movements(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();
        $terzista = Terzista::factory()->create();

        $ordine = OrdineContoLavoro::factory()->create(['terzista_id' => $terzista->id]);
        $riga = $ordine->righe()->create([
            'articolo_id' => $articolo->id,
            'qta' => 10,
            'lotto' => 'LOT-OUT',
        ]);

        Livewire::actingAs($user)
            ->test(ContoLavoroWizard::class)
            ->call('switchFase', 'rientro')
            ->set('rientro.ordine_id', $ordine->id)
            ->set('rientro.magazzino_id', $magazzino->id)
            ->set('rientro.ubicazione_id', $ubicazione->id)
            ->call('next')
            ->set('rientroRighe.0.qta_rientro', '6')
            ->set('rientroRighe.0.scarto', '1')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('righe_ocl', [
            'id' => $riga->id,
            'qta_rientrata' => 6,
            'scarto' => 1,
            'stato_riga' => 'Parziale',
        ]);

        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'ENT_CL',
            'magazzino_dest' => $magazzino->id,
            'ubicazione_dest' => $ubicazione->id,
        ]);

        $this->assertDatabaseHas('ordine_conto_lavoro', [
            'id' => $ordine->id,
            'stato' => 'Parziale',
        ]);
    }
}

<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movimenti\TransferWizard;
use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Ubicazione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TransferWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_ubicazione_required_when_magazzino_has_locations(): void
    {
        $user = User::factory()->create();
        $magOrig = Magazzino::factory()->create();
        Ubicazione::factory()->create(['magazzino_id' => $magOrig->id, 'attiva' => true]);

        Livewire::actingAs($user)
            ->test(TransferWizard::class)
            ->set('origine.magazzino_id', $magOrig->id)
            ->call('next')
            ->assertHasErrors(['origine.ubicazione_id' => 'required']);
    }

    public function test_transfer_persists_movements_with_ubicazioni(): void
    {
        $user = User::factory()->create();
        $magOrig = Magazzino::factory()->create();
        $magDest = Magazzino::factory()->create();
        $ubiOrig = Ubicazione::factory()->create(['magazzino_id' => $magOrig->id, 'attiva' => true]);
        $ubiDest = Ubicazione::factory()->create(['magazzino_id' => $magDest->id, 'attiva' => true]);
        $articolo = Articolo::factory()->create();

        Livewire::actingAs($user)
            ->test(TransferWizard::class)
            ->set('origine.magazzino_id', $magOrig->id)
            ->set('origine.ubicazione_id', $ubiOrig->id)
            ->call('next')
            ->set('destinazione.magazzino_id', $magDest->id)
            ->set('destinazione.ubicazione_id', $ubiDest->id)
            ->call('next')
            ->set("righe.0.articolo_id", $articolo->id)
            ->set('righe.0.qta', '12.500')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors()
            ->assertRedirect(route('movimenti.transfer'));

        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'TRASF',
            'magazzino_orig' => $magOrig->id,
            'ubicazione_orig' => $ubiOrig->id,
        ]);
        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'TRASF',
            'magazzino_dest' => $magDest->id,
            'ubicazione_dest' => $ubiDest->id,
        ]);
    }
}

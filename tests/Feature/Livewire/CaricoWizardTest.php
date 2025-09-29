<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movimenti\CaricoWizard;
use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Ubicazione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CaricoWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_magazzino_with_ubicazione_requires_selection(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);

        Livewire::actingAs($user)
            ->test(CaricoWizard::class)
            ->set('contesto.magazzino_id', $magazzino->id)
            ->call('next')
            ->assertHasErrors(['contesto.ubicazione_id' => 'required']);
    }

    public function test_carico_creates_movement(): void
    {
        $user = User::factory()->create();
        $magazzino = Magazzino::factory()->create();
        $ubicazione = Ubicazione::factory()->create(['magazzino_id' => $magazzino->id]);
        $articolo = Articolo::factory()->create();

        Livewire::actingAs($user)
            ->test(CaricoWizard::class)
            ->set('contesto.magazzino_id', $magazzino->id)
            ->set('contesto.ubicazione_id', $ubicazione->id)
            ->set('contesto.commessa', 'ORD-100')
            ->call('next')
            ->set('righe.0.articolo_id', $articolo->id)
            ->set('righe.0.qta', '5')
            ->set('righe.0.lotto', 'LOT123')
            ->call('next')
            ->call('conferma')
            ->assertHasNoErrors()
            ->assertRedirect(route('movimenti.carico'));

        $this->assertDatabaseHas('movimenti', [
            'tipo' => 'CARICO',
            'magazzino_dest' => $magazzino->id,
            'ubicazione_dest' => $ubicazione->id,
            'lotto' => 'LOT123',
        ]);
    }
}

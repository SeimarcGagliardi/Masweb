<?php

namespace Tests\Feature;

use App\Models\Articolo;
use App\Models\Magazzino;
use App\Models\Movimento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TransferWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_salva_movimenti_collegati_con_link_logico(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('movimenti.transfer');
        $role = Role::findOrCreate('Operatore');
        $role->givePermissionTo('movimenti.transfer');
        $user->assignRole($role);

        $this->actingAs($user);

        $articolo = Articolo::factory()->create();
        $magazzinoOrig = Magazzino::factory()->create();
        $magazzinoDest = Magazzino::factory()->create();

        $link = (string) Str::uuid();
        Movimento::create(['tipo' => 'TRASF','articolo_id' => $articolo->id,'qta' => 5,'magazzino_orig' => $magazzinoOrig->id,'link_logico' => $link]);
        Movimento::create(['tipo' => 'TRASF','articolo_id' => $articolo->id,'qta' => 5,'magazzino_dest' => $magazzinoDest->id,'link_logico' => $link]);

        $rows = Movimento::where('link_logico', $link)->get();

        $this->assertCount(2, $rows);
        $this->assertEquals(['TRASF'], $rows->pluck('tipo')->unique()->all());
        $this->assertSame($articolo->id, $rows->pluck('articolo_id')->unique()->first());
    }
}

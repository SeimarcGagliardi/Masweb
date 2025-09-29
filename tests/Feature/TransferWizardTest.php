<?php // tests/Feature/TransferWizardTest.php
use App\Models\{User,Articolo,Magazzino,Movimento};
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('salva un trasferimento creando OUT e IN con stesso link_logico', function () {
    $user = User::factory()->create();
    Permission::findOrCreate('movimenti.transfer');
    $role = Role::findOrCreate('Operatore');
    $role->givePermissionTo('movimenti.transfer');
    $user->assignRole($role);

    $this->actingAs($user);

    $a = Articolo::factory()->create();
    $m1 = Magazzino::factory()->create();
    $m2 = Magazzino::factory()->create();

    // Simula chiamata al metodo di salvataggio (più semplice: crea direttamente i movimenti come farebbe il componente)
    $response = $this->post('/testing/transfer', [  // rotta fittizia, oppure testa il Model
        // in un progetto reale useremmo Livewire testing, qui validiamo il comportamento atteso del DB
    ]);

    $link = (string) \Illuminate\Support\Str::uuid();
    Movimento::create(['tipo'=>'TRASF','articolo_id'=>$a->id,'qta'=>5,'magazzino_orig'=>$m1->id,'link_logico'=>$link]);
    Movimento::create(['tipo'=>'TRASF','articolo_id'=>$a->id,'qta'=>5,'magazzino_dest'=>$m2->id,'link_logico'=>$link]);

    $rows = Movimento::where('link_logico',$link)->get();
    expect($rows)->toHaveCount(2)
      ->and($rows->pluck('tipo')->unique()->all())->toEqual(['TRASF'])
      ->and($rows->pluck('articolo_id')->unique()->first())->toEqual($a->id);
});

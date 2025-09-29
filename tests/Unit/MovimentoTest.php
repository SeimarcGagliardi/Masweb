<?php // tests/Unit/MovimentoTest.php
use App\Models\{Movimento,Articolo,Magazzino};

it('imposta correttamente i riferimenti a magazzini origine/destinazione', function(){
  $a = Articolo::factory()->create();
  $mo = Magazzino::factory()->create();
  $md = Magazzino::factory()->create();

  $out = Movimento::create(['tipo'=>'TRASF','articolo_id'=>$a->id,'qta'=>1,'magazzino_orig'=>$mo->id]);
  $in  = Movimento::create(['tipo'=>'TRASF','articolo_id'=>$a->id,'qta'=>1,'magazzino_dest'=>$md->id]);

  expect($out->origineMag->id)->toBe($mo->id)
    ->and($in->destinazioneMag->id)->toBe($md->id);
});

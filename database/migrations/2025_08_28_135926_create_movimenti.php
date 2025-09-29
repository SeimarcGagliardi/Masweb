<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('movimenti')) {
      Schema::create('movimenti', function (Blueprint $t) {
        $t->id();
        $t->enum('tipo', ['CARICO','SCARICO','TRASF','USC_CL','ENT_CL']); // logica MAS
        if (Schema::hasTable('tipo_movimento')) {
          $t->unsignedBigInteger('tipo_mov_id')->nullable(); // mappa vs tabella esistente
          $t->foreign('tipo_mov_id')->references('id')->on('tipo_movimento')->nullOnDelete();
        }

        $t->foreignId('articolo_id')->constrained('articoli');
        $t->decimal('qta',14,3);

        $t->foreignId('magazzino_orig')->nullable()->constrained('magazzini');
        $t->foreignId('ubicazione_orig')->nullable()->constrained('ubicazioni');

        $t->foreignId('magazzino_dest')->nullable()->constrained('magazzini');
        $t->foreignId('ubicazione_dest')->nullable()->constrained('ubicazioni');

        $t->string('lotto')->nullable();
        $t->string('riferimento')->nullable(); // es. doc esterno
        $t->foreignId('utente_id')->nullable()->constrained('users');
        $t->text('note')->nullable();
        $t->uuid('link_logico')->nullable()->index(); // OUT+IN dello stesso trasferimento
        $t->timestamps();

        $t->index(['articolo_id','magazzino_orig']);
        $t->index(['articolo_id','magazzino_dest']);
        $t->index(['tipo']);
        $t->index(['lotto']);
      });
    }
  }

  public function down(): void {
    Schema::dropIfExists('movimenti');
  }
};

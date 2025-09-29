<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('ordine_conto_lavoro')) {
      Schema::create('ordine_conto_lavoro', function (Blueprint $t) {
        $t->id();
        $t->foreignId('terzista_id')->constrained('terzisti');
        $t->enum('stato',['Inviato','Parziale','Chiuso'])->default('Inviato');
        $t->date('data_invio')->nullable();
        $t->date('data_rientro_prevista')->nullable();
        $t->text('note')->nullable();
        $t->timestamps();
        $t->index(['stato']);
      });
    }

    if (!Schema::hasTable('righe_ocl')) {
      Schema::create('righe_ocl', function (Blueprint $t) {
        $t->id();
        $t->foreignId('ordine_id')->constrained('ordine_conto_lavoro')->cascadeOnDelete();
        $t->foreignId('articolo_id')->constrained('articoli');
        $t->decimal('qta',14,3);
        $t->string('lotto')->nullable();
        $t->enum('stato_riga',['Inviata','Parziale','Chiusa'])->default('Inviata');
        $t->decimal('qta_rientrata',14,3)->default(0);
        $t->decimal('scarto',14,3)->default(0);
        $t->timestamps();
        $t->index(['ordine_id','stato_riga']);
      });
    }
  }

  public function down(): void {
    Schema::dropIfExists('righe_ocl');
    Schema::dropIfExists('ordine_conto_lavoro');
  }
};

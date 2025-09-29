<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    // magazzini: assicurare campi
    Schema::table('magazzini', function (Blueprint $t) {
      if (!Schema::hasColumn('magazzini','codice')) $t->string('codice')->unique()->after('id');
      else $t->unique('codice','magazzini_codice_unique');
      if (!Schema::hasColumn('magazzini','descrizione')) $t->string('descrizione')->after('codice');
      if (!Schema::hasColumn('magazzini','indirizzo')) $t->string('indirizzo')->nullable()->after('descrizione');
      if (!Schema::hasColumn('magazzini','attivo')) $t->boolean('attivo')->default(true)->after('indirizzo');
    });

    // ubicazioni (nuova)
    if (!Schema::hasTable('ubicazioni')) {
      Schema::create('ubicazioni', function (Blueprint $t) {
        $t->id();
        $t->foreignId('magazzino_id')->constrained('magazzini')->cascadeOnDelete();
        $t->string('codice');
        $t->string('descrizione')->nullable();
        $t->boolean('attiva')->default(true);
        $t->timestamps();
        $t->unique(['magazzino_id','codice']);
        $t->index(['magazzino_id','attiva']);
      });
    }
  }

  public function down(): void {
    if (Schema::hasTable('ubicazioni')) Schema::dropIfExists('ubicazioni');
    // non rimuovo i campi aggiunti a magazzini (backward compat stabilità)
  }
};

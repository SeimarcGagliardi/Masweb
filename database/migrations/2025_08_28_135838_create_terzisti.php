<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('terzisti')) {
      Schema::create('terzisti', function (Blueprint $t) {
        $t->id();
        $t->string('ragione_sociale');
        $t->string('piva',20)->nullable();
        $t->string('indirizzo')->nullable();
        $t->json('contatti')->nullable(); // {"tel":"...", "email":"..."}
        $t->boolean('attivo')->default(true);
        $t->timestamps();

        $t->index(['ragione_sociale']);
        $t->index(['attivo']);
      });
    }
  }
  public function down(): void {
    Schema::dropIfExists('terzisti');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('articoli', function (Blueprint $t) {
      if (!Schema::hasColumn('articoli','unita_misura')) $t->string('unita_misura',10)->default('PZ')->after('descrizione');
      if (!Schema::hasColumn('articoli','barcode')) $t->string('barcode')->nullable()->after('unita_misura');
      if (!Schema::hasColumn('articoli','lotto_obbligatorio')) $t->boolean('lotto_obbligatorio')->default(false)->after('barcode');
      if (!Schema::hasColumn('articoli','attivo')) $t->boolean('attivo')->default(true)->after('lotto_obbligatorio');

      // indici utili
      if (!Schema::hasColumn('articoli','codice')) $t->string('codice')->unique()->after('id');
      else $t->unique('codice','articoli_codice_unique');
      $t->index(['descrizione']);
      $t->index(['barcode']);
    });
  }

  public function down(): void {
    Schema::table('articoli', function (Blueprint $t) {
      if (Schema::hasColumn('articoli','attivo')) $t->dropColumn('attivo');
      if (Schema::hasColumn('articoli','lotto_obbligatorio')) $t->dropColumn('lotto_obbligatorio');
      if (Schema::hasColumn('articoli','barcode')) { $t->dropIndex(['barcode']); $t->dropColumn('barcode'); }
      if (Schema::hasColumn('articoli','unita_misura')) $t->dropColumn('unita_misura');
      // non rimuovo codice unico per non rompere dati già referenziati
    });
  }
};

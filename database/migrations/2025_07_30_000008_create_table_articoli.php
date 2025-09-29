<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articoli', function (Blueprint $table) {
            $table->id();
            $table->string('descrizione');
            $table->foreignId('tipologia_articoli_id')->constrained('tipologia_articoli');
            $table->foreignId('modelli_capo_id')->nullable()->constrained('modelli_capo');
            $table->foreignId('elenco_taglie_id')->nullable()->constrained('elenco_taglie');
            $table->foreignId('elenco_colori_id')->nullable()->constrained('elenco_colori');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articoli', function (Blueprint $table) {
            //
        });
    }
};

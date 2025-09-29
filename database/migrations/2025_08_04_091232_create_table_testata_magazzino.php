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
        Schema::create('testata_magazzino', function (Blueprint $table) {
            $table->id();
            $table->string('descrizione');
            $table->foreignId('anagrafica_id')->constrained('anagrafiche');
            $table->foreignId('tipo_movimento_id')->constrained('tipo_movimento');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testata_magazzino', function (Blueprint $table) {
            //
        });
    }
};

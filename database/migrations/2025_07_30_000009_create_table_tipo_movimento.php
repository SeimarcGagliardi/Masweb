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
        Schema::create('tipo_movimento', function (Blueprint $table) {
            $table->id();
            $table->string('descrizione');
            $table->tinyInteger('segno1'); // +1 o -1
            $table->foreignId('magazzino1')->nullable()->constrained('magazzini');
            $table->tinyInteger('segno2')->nullable(); // +1 o -1 opzionale
            $table->foreignId('magazzino2')->nullable()->constrained('magazzini');
            $table->string('tipo_movimento_segno', 10)->default('segno1');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_movimento', function (Blueprint $table) {
            //
        });
    }
};

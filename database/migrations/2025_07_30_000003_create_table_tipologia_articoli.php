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
        Schema::create('tipologia_articoli', function (Blueprint $table) {
            $table->id();
            $table->string('descrizione');
            $table->boolean('fl_bagno')->default(false);
            $table->boolean('fl_taglie')->default(false);
            $table->boolean('fl_colori')->default(false);
            $table->boolean('fl_modelli')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipologia_articoli', function (Blueprint $table) {
            //
        });
    }
};

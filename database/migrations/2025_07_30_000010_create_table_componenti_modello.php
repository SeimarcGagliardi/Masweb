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
        Schema::create('componenti_modello', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelli_capo_id')->constrained('modelli_capo');
            $table->string('descrizione');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('componenti_modello', function (Blueprint $table) {
            //
        });
    }
};

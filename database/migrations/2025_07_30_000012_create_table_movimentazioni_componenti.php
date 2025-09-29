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
        Schema::create('movimentazioni_componenti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimentazioni_magazzino_id')->constrained('movimentazioni_magazzino');
            $table->foreignId('componenti_modello_id')->constrained('componenti_modello');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentazioni_componenti', function (Blueprint $table) {
            //
        });
    }
};

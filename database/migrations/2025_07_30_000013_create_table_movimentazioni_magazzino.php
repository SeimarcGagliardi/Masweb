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
        Schema::create('movimentazioni_magazzino', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articolo_id')->constrained('articoli');
            $table->foreignId('magazzino_id')->constrained('magazzini');
            $table->unsignedBigInteger('movimentazioni_magazzino_id_padre')->nullable();
            $table->foreign('movimentazioni_magazzino_id_padre', 'mov_mag_padre_fk')
                  ->references('id')
                  ->on('movimentazioni_magazzino');
            $table->foreignId('tipo_movimento_id')->constrained('tipo_movimento');
            $table->decimal('quantita', 10, 3);
            $table->string('udm', 10)->default('KG');
            $table->string('ubicazione', 100)->nullable();
            $table->string('bagno', 50)->nullable();
            $table->foreignId('taglia_id')->nullable()->constrained('taglie');
            $table->foreignId('colore_id')->nullable()->constrained('colori');
            $table->foreignId('riferimento_modello')->nullable()->constrained('modelli_capo');
            $table->datetime('data_movimento');
            $table->foreignId('operatore_id')->nullable()->constrained('utenti_personale');
            $table->string('foto_riferimento')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentazioni_magazzino', function (Blueprint $table) {
            $table->foreignId('testata_magazzino_id')->constrained('movimentazioni_magazzino');

        });
    }
};

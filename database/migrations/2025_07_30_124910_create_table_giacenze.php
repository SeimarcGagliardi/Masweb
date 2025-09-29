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
        Schema::create('giacenze', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articolo_id')->constrained('articoli');
            $table->string('ubicazione', 100)->nullable();
            $table->foreignId('magazzino_id')->constrained('magazzini');
            $table->string('bagno', 50)->nullable();
            $table->decimal('giacenza', 10, 3)->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('giacenze', function (Blueprint $table) {
            //
        });
    }
};

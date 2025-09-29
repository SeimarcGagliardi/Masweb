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
        Schema::create('taglie', function (Blueprint $table) {
            $table->id();
            $table->integer('ordinamento')->default(0);
            $table->string('descrizione');
            $table->foreignId('elenco_taglie_id')->constrained('elenco_taglie');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taglie', function (Blueprint $table) {
            //
        });
    }
};

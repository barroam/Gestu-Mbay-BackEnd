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
        Schema::create('engrais_ressources', function (Blueprint $table) {
            $table->id();  
            $table->string('variete');
            $table->integer('quantite');
            $table->foreignId('ressource_id')->constrained()->unique();
            $table->foreignId('engrais_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engrais_ressources');
    }
};

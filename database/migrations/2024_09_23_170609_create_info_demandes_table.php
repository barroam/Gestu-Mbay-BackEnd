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
        Schema::create('info_demandes', function (Blueprint $table) {
            $table->id();
            $table->enum('demandeur', ['individuel', 'groupe', 'association']);
            $table->string('nom_demandeur');
            $table->string('adresse');
            $table->string('cin/ninea');
            $table->integer('contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_demandes');
    }
};

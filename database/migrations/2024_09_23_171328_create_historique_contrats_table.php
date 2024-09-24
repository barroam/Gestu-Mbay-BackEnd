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
        Schema::create('historique_contrats', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('etat', ['en_cours', 'terminer', 'annuler'])->default('en_cours');
            $table->string('objectif');
            $table->string('mode_paiement');
            $table->string('nature_paiement');
            $table->integer('quantite');
            $table->text('presvu');
            $table->text('force_majeure');
            $table->foreignId('projet_id')->constrained(); 
            $table->foreignId('contrat_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_contrats');
    }
};

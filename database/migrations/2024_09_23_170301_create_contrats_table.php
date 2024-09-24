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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->enum('etat', ['en_cours', 'terminer', 'annuler'])->default('en_cours');
            $table->date('date');
            $table->string('objectif');
            $table->string('mode_paiement');
            $table->string('nature_paiement');
            $table->integer('quantite');
            $table->text('preavu');
            $table->text('force_majeure');
            $table->foreignId('projet_id')->constrained();
            $table->foreignId('ressource_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};

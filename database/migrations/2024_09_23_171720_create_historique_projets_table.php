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
        Schema::create('historique_projets', function (Blueprint $table) {
            $table->id();
            $table->string('type_activite');
            $table->date('date');
            $table->enum('etat', ['en_cours', 'terminer', 'annuler'])->default('en_cours');
            $table->string('attentes');
            $table->string('obstacles');
            $table->string('solutions');
            $table->date('date_fin')->nullable();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_projets');
    }
};

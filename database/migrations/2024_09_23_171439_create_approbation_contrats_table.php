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
        Schema::create('approbation_contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained()->unique();
            $table->boolean('approuve');
            $table->text('description');
            $table->foreignId('user_id')->constrained(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approbation_contrats');
    }
};

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
        Schema::table('projets', function (Blueprint $table) {
            //
            $table->text('attentes')->change();
            $table->text('obstacles')->change();
            $table->text('solutions')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            //
            $table->string('attentes', 255)->change();
            $table->string('obstacles', 255)->change();
            $table->string('solutions', 255)->change();
        });
    }
};

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
        Schema::create('pers_role', function (Blueprint $table) {
            // Clés primaires composites
            $table->string('code_bureau', 20);
            $table->string('code_pers', 20);
            $table->unsignedBigInteger('code_role');

            // Autres colonnes
            $table->dateTime('date_debut_role');
            $table->dateTime('date_fin_role')->nullable();
            $table->tinyInteger('satut_role')->default(1); // 1 = actif, 0 = inactif
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();

            // Clés étrangères
            $table->foreign('code_bureau')
                ->references('code_bureau')
                ->on('bureau')
                ->onDelete('cascade');

            $table->foreign('code_pers')
                ->references('code_pers')
                ->on('personnel')
                ->onDelete('cascade');

            $table->foreign('code_role')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            // Clé primaire composite
            $table->primary(['code_bureau', 'code_pers', 'code_role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pers_role');
    }
};

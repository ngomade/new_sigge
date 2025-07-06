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
        // Supprimer l'ancienne clé primaire composite
        Schema::table('entretien_reparation', function (Blueprint $table) {
            // $table->dropColumn(['code_equip', 'id_pers_lab']);
            $table->dropForeign(['code_equip']);
            $table->dropPrimary(['code_equip', 'id_pers_lab']);
            $table->dropForeign(['id_pers_lab']);
            $table->id()->first();

            $table->foreign('code_equip')->references('code_equip')->on('equipements')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entretien_reparation', function (Blueprint $table) {
            // Supprimer les nouvelles clés étrangères
            $table->dropForeign(['code_equip']);
            $table->dropForeign(['id_pers_lab']);

            // Supprimer la colonne id auto-incrémentée
            $table->dropColumn('id');

            // Restaurer la clé primaire composite
            $table->primary(['code_equip', 'id_pers_lab']);

            // Restaurer les anciennes clés étrangères
            $table->foreign('code_equip')->references('code_equip')->on('equipements')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');
        });
    }
};

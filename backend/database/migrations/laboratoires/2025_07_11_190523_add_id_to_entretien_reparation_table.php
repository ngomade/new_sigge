<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la colonne 'id' existe déjà
        if (! Schema::hasColumn('entretien_reparation', 'id')) {
            Schema::table('entretien_reparation', function (Blueprint $table) {
                // Ajouter la colonne id auto-incrémentée en premier
                $table->id()->first();

                // Ajouter un index sur les colonnes fréquemment utilisées
                $table->index(['code_equip', 'statut_entretien']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('entretien_reparation', function (Blueprint $table) {
            // Supprimer l'index
            $table->dropIndex(['code_equip', 'statut_entretien']);

            // Supprimer la colonne ID
            if (Schema::hasColumn('entretien_reparation', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
};

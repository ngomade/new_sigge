<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la colonne 'id' existe déjà
        if (!Schema::hasColumn('laboratoire_pers_lab', 'id')) {
            Schema::table('laboratoire_pers_lab', function (Blueprint $table) {
                // Ajouter la colonne id auto-incrémentée en premier
                $table->id()->first();
                
                // Ajouter des index pour améliorer les performances
                $table->index(['code_lab', 'statut']);
                $table->index(['id_pers_lab']);
                $table->index(['id_user_externe']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('laboratoire_pers_lab', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['code_lab', 'statut']);
            $table->dropIndex(['id_pers_lab']);
            $table->dropIndex(['id_user_externe']);
            
            // Supprimer la colonne ID
            if (Schema::hasColumn('laboratoire_pers_lab', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
};
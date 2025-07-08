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
        Schema::table('laboratoire_pers_lab', function (Blueprint $table) {
            // Supprimer toutes les contraintes existantes
            $table->dropForeign(['code_lab']);
            $table->dropForeign(['id_pers_lab']);
            $table->dropForeign(['id_rl']);
            $table->dropForeign(['id_user_externe']);

            // Supprimer la clé primaire composite existante
            $table->dropPrimary(['code_lab', 'id_pers_lab']);

            // Supprimer les index existants
            try {
                $table->dropIndex(['statut']);
            } catch (\Exception $e) {}

            // Ajouter la colonne id auto-incrémentée en première position
            $table->bigIncrements('id')->first();

            // Rendre id_pers_lab nullable (pour les externes)
            $table->string('id_pers_lab')->nullable()->change();

            // Recréer les contraintes étrangères avec les bonnes options
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('set null');
            $table->foreign('id_rl')->references('id_rl')->on('role_labo')->onDelete('set null');
            $table->foreign('id_user_externe')->references('id_user_ext')->on('user_externe')->onDelete('set null');

            // Ajouter des index pour optimiser les requêtes
            $table->index(['code_lab', 'id_pers_lab']);
            $table->index(['code_lab', 'id_user_externe']);
            $table->index(['statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratoire_pers_lab', function (Blueprint $table) {
            // Supprimer d'abord les contraintes étrangères
            $table->dropForeign(['code_lab']);
            $table->dropForeign(['id_pers_lab']);
            $table->dropForeign(['id_rl']);
            $table->dropForeign(['id_user_externe']);

            // Ensuite supprimer les index ajoutés
            try {
                $table->dropIndex(['code_lab', 'id_pers_lab']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['code_lab', 'id_user_externe']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['statut']);
            } catch (\Exception $e) {}

            // Supprimer la colonne id auto-incrémentée
            $table->dropColumn('id');

            // Remettre id_pers_lab NOT NULL
            $table->string('id_pers_lab')->nullable(false)->change();

            // Restaurer la clé primaire d'origine (code_lab + id_pers_lab)
            $table->primary(['code_lab', 'id_pers_lab']);

            // Restaurer les contraintes étrangères d'origine
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');
            $table->foreign('id_rl')->references('id_rl')->on('role_labo')->onDelete('set null');
            $table->foreign('id_user_externe')->references('id_user_ext')->on('user_externe')->onDelete('set null');
        });
    }
};

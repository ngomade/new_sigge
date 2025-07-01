<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajout des nouveaux champs à la table laboratoire
        Schema::table('laboratoire', function (Blueprint $table) {
            $table->string('logo_labo')->nullable();
            $table->string('sigle', 20)->nullable();
            $table->string('axes_recherche');
            $table->string('email_labo')->nullable();
            $table->string('tel_labo')->nullable();
            $table->string('adresse_labo')->nullable();
        });

        // Exemple d'ajout de champ à projet_labo (à adapter selon tes besoins)
        Schema::table('projet_labo', function (Blueprint $table) {
            $table->enum("statut_projet", ["En cours", "Terminé", "En pause", "Annulé"])->default("En cours");
            $table->date('debut_projet')->default(now());
            $table->date('fin_projet')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laboratoire', function (Blueprint $table) {
            $table->dropColumn([
                'sigle', 'logo_labo',
                'email_labo', 'tel_labo','adresse_labo',
                'axes_recherche'
            ]);
        });
        Schema::table('projet_labo', function (Blueprint $table) {
            $table->dropColumn(['statut_projet', 'debut_projet', 'fin_projet']);
        });
    }
};

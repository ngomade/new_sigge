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
        // Supprimer la table existante
        Schema::dropIfExists('participer_projet');

        // Recréer la table avec la nouvelle structure
        Schema::create('participer_projet', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->integer('code_projet')->unsigned(); // Correspond au type de projet_labo.code_projet
            $table->string('id_pers_lab')->nullable(); // Participant interne (nullable)
            $table->unsignedBigInteger('id_user_ext')->nullable(); // Participant externe (nullable)
            $table->string('role', 100)->nullable();
            $table->date('debut_participation');
            $table->date('fin_participation')->nullable();
            $table->timestamps();

            // Clés étrangères
            $table->foreign('code_projet')->references('code_projet')->on('projet_labo')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');
            $table->foreign('id_user_ext')->references('id_user_ext')->on('user_externe')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['code_projet']);
            $table->index(['id_pers_lab']);
            $table->index(['id_user_ext']);
            $table->index(['debut_participation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participer_projet');
    }
};

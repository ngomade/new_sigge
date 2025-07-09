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
        Schema::create('laboratoire_pers_lab', function (Blueprint $table) {
            $table->string('code_lab', 10);
            $table->string('id_pers_lab', 50);
            $table->unsignedBigInteger('id_user_externe')->nullable();
            $table->unsignedBigInteger('id_rl')->nullable();
            $table->date('date_affectation')->default(now());
            $table->date('date_fin_affectation')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('inactif');
            $table->timestamps();

            // Clé primaire composite
            $table->primary(['code_lab', 'id_pers_lab']);

            // Clés étrangères
            $table->foreign('id_user_externe')->references('id_user_ext')->on('user_externe')->onDelete('set null');
            $table->foreign('id_rl')->references('id_rl')->on('role_labo')->onDelete('set null');
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('cascade');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoire_pers_lab');
    }
};

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
        Schema::create('laboratoire_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('code_lab', 10);
            $table->string('token', 64)->unique();
            $table->unsignedBigInteger('id_rl')->nullable();
            $table->date('date_fin_affectation');
            $table->timestamp('date_expiration');
            $table->enum('statut', ['actif', 'utilise', 'expire'])->default('actif');
            $table->string('created_by', 50); // ID de l'admin qui a créé l'invitation
            $table->timestamps();

            // Clés étrangères
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('cascade');
            $table->foreign('id_rl')->references('id_rl')->on('role_labo')->onDelete('set null');
            $table->foreign('created_by')->references('id_pers_lab')->on('pers_lab')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['token']);
            $table->index(['statut']);
            $table->index(['date_expiration']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoire_invitations');
    }
};

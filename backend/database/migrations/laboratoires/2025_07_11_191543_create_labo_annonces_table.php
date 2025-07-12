<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labo_annonces', function (Blueprint $table) {
            $table->id();
            $table->string('code_lab'); // laboratoire concerné
            $table->string('id_admin'); // expéditeur (admin)
            $table->string('titre')->nullable();
            $table->text('contenu');
            $table->string('fichier')->nullable(); // pour un fichier joint éventuel
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_pers_lab')->on('pers_lab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labo_annonces');
    }
};

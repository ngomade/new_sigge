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
        Schema::create('fichier_requetes', function (Blueprint $table) {
            $table->string('id_fichier',32)->primary();
            $table->string('chemin',180);
             $table->string('code_requete');
            $table->foreign('code_requete')->references('code_requete')->on('requetes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichier_requtes');
    }
};

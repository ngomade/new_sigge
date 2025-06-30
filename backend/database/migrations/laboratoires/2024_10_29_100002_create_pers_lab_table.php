<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pers_lab', function (Blueprint $table) {
            $table->string('id_pers_lab')->primary();
            $table->string('type_pers_lab', 100); // ou id_personnel selon ton modèle
            $table->string('code_lab', 10);
            $table->date('date_entree')->nullable();
            $table->date('date_sortie')->nullable();
            $table->string('statut', 50)->nullable();
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pers_lab');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id('code_publi');
            $table->string('titre_publi', 255);
            $table->text('resume')->nullable();
            $table->string('type_publi', 100);
            $table->string('domaine', 100)->nullable();
            $table->string('tags')->nullable();
            $table->string('id_pers_lab'); // créateur
            $table->timestamps();

            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};

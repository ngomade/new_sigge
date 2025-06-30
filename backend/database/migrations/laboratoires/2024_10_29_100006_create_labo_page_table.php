<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labo_page', function (Blueprint $table) {
            $table->id('id_page');
            $table->string('code_lab', 10);
            $table->string('titre', 255);
            $table->string('slug', 100);
            $table->longText('contenu');
            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labo_page');
    }
};

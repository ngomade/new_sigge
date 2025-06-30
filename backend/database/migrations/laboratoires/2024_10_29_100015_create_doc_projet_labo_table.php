<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doc_projet_labo', function (Blueprint $table) {
            $table->id('id_doc');
            $table->unsignedInteger('code_projet');
            $table->string('titre_doc', 255);
            $table->string('path', 255);
            $table->timestamps();

            $table->foreign('code_projet')->references('code_projet')->on('projet_labo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_projet_labo');
    }
};

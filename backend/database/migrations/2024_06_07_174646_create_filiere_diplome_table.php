<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('filiere_diplome', function (Blueprint $table) {
            $table->id();
            $table->string('filiere_code', 20);
            $table->unsignedBigInteger('code_dip');
            $table->unsignedBigInteger('code_serie');
            $table->timestamps();

            $table->foreign('filiere_code')->references('code_filiere')->on('filiere')->onDelete('cascade');
            $table->foreign('code_dip')->references('code_dip')->on('diplome')->onDelete('cascade');
            $table->foreign('code_serie')->references('code_serie')->on('serie')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('filiere_diplome');
    }
};

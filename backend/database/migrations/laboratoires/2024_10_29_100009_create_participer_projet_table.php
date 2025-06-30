<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participer_projet', function (Blueprint $table) {
            $table->unsignedInteger('code_projet');
            $table->string('id_pers_lab');
            $table->string('role', 100)->nullable();
            $table->date('debut_participation');
            $table->date('fin_participation');
            $table->timestamps();

            $table->foreign('code_projet')->references('code_projet')->on('projet_labo');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab');

            $table->primary(['code_projet', 'id_pers_lab']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participer_projet');
    }
};

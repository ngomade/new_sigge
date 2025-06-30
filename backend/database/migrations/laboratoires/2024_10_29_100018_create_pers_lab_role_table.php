<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pers_lab_role', function (Blueprint $table) {
            $table->string('id_pers_lab');
            $table->unsignedBigInteger('id_rl');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();

            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab');
            $table->foreign('id_rl')->references('id_rl')->on('role_labo');
            $table->primary(['id_rl', 'id_pers_lab']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pers_lab_role');
    }
};

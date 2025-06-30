<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_agent', function (Blueprint $table) {
            $table->unsignedBigInteger('code_equip');
            $table->string('id_pers_lab');
            $table->date('debut_reserv');
            $table->date('fin_reserv');
            $table->string('statut', 50)->default('en attente');
            $table->timestamps();

            $table->foreign('code_equip')->references('code_equip')->on('equipements');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_agent');
    }
};

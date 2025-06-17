<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscription', function (Blueprint $table) {
            $table->char('code_ins', 32)->primary();
            $table->char('code_user',32);
             $table->foreign('code_user')->references('code_user')->on('users')->onDelete('cascade');
            $table->smallInteger('code_annee');
             $table->foreign('code_annee')->references('code_annee')->on('anneescolaire')->onDelete('cascade');
            $table->dateTime('date_ins');
            $table->smallInteger('statut_ins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscription');
    }
};

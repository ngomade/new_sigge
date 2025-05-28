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
        Schema::create('sessionconcour', function (Blueprint $table) {
            $table->id('id')->primary();
            // $table->string('code_pers');
            $table->year('annee');
            $table->date('debut');
            $table->date('cloture');
            $table->timestamps();

            // $table->foreign('code_pers')->references('code_pers')->on('personnel')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessionconcour');
    }
};

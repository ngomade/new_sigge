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
        Schema::create('session_concours', function (Blueprint $table) {
            $table->id();
            $table->string('code_pers')->nullable();
            $table->string('ad_code')->nullable();
            $table->year('annee');
            $table->date('debut');
            $table->date('cloture');
            $table->timestamps();

            $table->foreign('code_pers')->references('code_pers')->on('personnel')->onDelete('cascade');
            $table->foreign('ad_code')->references('code_pers')->on('personnel')->onDelete('cascade');
            //  $table->foreign('ca_code')->references('ca_code')->on('candidat')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_concours');
    }
};

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
        Schema::create('slide', function (Blueprint $table) {
            $table->id('id_slide');
            $table->string('first_title', 128);
            $table->string('second_title', 150);
            $table->string('photo', 180);
             $table->string('code_pers', 32);
            $table->timestamps();
             $table->foreign('code_pers')->references('code_pers')->on('personnel')->onDelete('cascade'); // Replace `some_table` with the correct table name
        });
    }

    public function down()
    {
        Schema::dropIfExists('slide');
    }
};

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
        Schema::create('presentation', function (Blueprint $table) {
            $table->increments('code_pres');
            $table->string('code_bureau', 128);
            $table->foreign('code_bureau')->references('code_bureau')->on('bureau')->onDelete('cascade');
            $table->string('photo_chef', 128);
            $table->text('message_chef', 2000);
            $table->text('cursus_ing', 2000)->nullable();
            $table->text('grille_ing', 2000)->nullable();
            $table->text('science_ing', 2000)->nullable();
            $table->text('grille_science', 2000)->nullable();
            $table->text('nom_chef', 128);
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
        Schema::dropIfExists('presentation');
    }
};

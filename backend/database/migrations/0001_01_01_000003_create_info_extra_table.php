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
        Schema::create('info_extra', function (Blueprint $table) {
            $table->increments('code_info_extra');
            $table->string('nom_pere_user', 128)->nullable();
            $table->string('nom_mere_user', 128)->nullable();
            $table->char('telephone_tuteur_user', 32)->nullable();
            $table->string('email_tuteur_user', 128)->nullable();
            $table->string('telephone_mere', 128)->nullable();
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
        Schema::dropIfExists('info_extra');
    }
};

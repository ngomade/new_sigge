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
        Schema::create('ec_ressource', function (Blueprint $table) {
            $table->char('code_ec', 32);
            $table->foreign('code_ec')->references('code_ec')->on('ec')->onDelete('cascade');

            $table->unsignedInteger('code_res');
            $table->foreign('code_res')->references('code_res')->on('ressource')->onDelete('cascade');
            $table->char('code_pers', 32);
            $table->foreign('code_pers')->references('code_pers')->on('personnel')->onDelete('cascade');
            $table->primary(['code_ec', 'code_res', 'code_pers']);
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
        Schema::dropIfExists('ec_ressource');
    }
};

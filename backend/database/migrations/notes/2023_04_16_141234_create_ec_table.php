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
        Schema::create('ec', function (Blueprint $table) {
            $table->char('code_ec', 32)->primary();
            $table->char('code_ue', 32);
            $table->foreign('code_ue')->references('code_ue')->on('ue')->onDelete('cascade');
            $table->string('intitule_ec', 128);
            $table->smallInteger('credit_ec');
            $table->smallInteger('vh_ec');
            $table->smallInteger('cm_ec');
            $table->smallInteger('td_ec');
            $table->smallInteger('tp_ec');
            $table->smallInteger('tpe_ec');
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
        Schema::dropIfExists('ec');
    }
};

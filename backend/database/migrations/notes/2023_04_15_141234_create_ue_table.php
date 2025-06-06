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
        Schema::create('ue', function (Blueprint $table) {
            $table->char('code_ue', 32)->primary();
            $table->string('code_sem', 10);
             $table->foreign('code_sem')->references('code_sem')->on('semestre')->onDelete('cascade');
            $table->string('intitule_ue', 128);
            $table->text('desc_ue')->nullable();
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
        Schema::dropIfExists('ue');
    }
};

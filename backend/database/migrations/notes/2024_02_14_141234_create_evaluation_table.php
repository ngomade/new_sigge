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
        Schema::create('evaluation', function (Blueprint $table) {
            $table->char('code_ec', 32);
            $table->foreign('code_ec')->references('code_ec')->on('ec')->onDelete('cascade');
            $table->char('code_examen', 32);
            $table->foreign('code_examen')->references('code_examen')->on('examen')->onDelete('cascade');
            $table->char('code_user', 32);
            $table->foreign('code_user')->references('code_user')->on('users')->onDelete('cascade');
            $table->date('date_evaluation');
            $table->char('code_ano', 32)->nullable();
            $table->decimal('note_eval', 10);
            $table->date('date_evalu');
            $table->timestamps();
            $table->primary(['code_ec', 'code_examen', 'code_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation');
    }
};

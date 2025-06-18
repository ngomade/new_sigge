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
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('code_doc');
            $table->char('code_session', 32)->nullable();
            $table->foreign('code_session')->references('code_session')->on('session_examen')->onDelete('cascade');
            $table->string('code_bureau', 128)->nullable();
            $table->foreign('code_bureau')->references('code_bureau')->on('bureau')->onDelete('cascade');
            $table->string('label_doc', 128);
            $table->text('description_doc')->nullable();
            $table->string('type_doc', 128);
            $table->string('nom_fichier', 128);
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
        Schema::dropIfExists('documents');
    }
};

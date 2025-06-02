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
        Schema::create('ecole_element', function (Blueprint $table) {
            $table->id();
            $table->string('code_ecole', 20);
            $table->unsignedBigInteger('code_el');
           

            $table->foreign('code_ecole')->references('code_ecole')->on('ecole')->onDelete('cascade');
            $table->foreign('code_el')->references('code_el')->on('dossier')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecole_element');
    }
};

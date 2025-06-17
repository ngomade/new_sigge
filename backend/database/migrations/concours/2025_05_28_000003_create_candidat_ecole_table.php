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
        Schema::create('candidat_ecoles', function (Blueprint $table) {
            $table->String('ca_code');
            $table->String('code_ecole');
            $table->primary(['ca_code', 'code_ecole']);
            $table->foreign('ca_code')->references('ca_code')->on('candidat')->onDelete('cascade');
            $table->foreign('code_ecole')->references('code_ecole')->on('ecole')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidat_ecoles');
    }
};

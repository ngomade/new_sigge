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
        Schema::create('dossier', function (Blueprint $table) {
            $table->id('code_el')->primary();
            $table->string('label_el');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossier');
    }
};
